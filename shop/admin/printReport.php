<?ob_start();?>
<?php 
    require '../db.php';
    require ("../libraries/fpdf/fpdf.php");
  
    date_default_timezone_set('Asia/Bangkok');
    // $start_date = '2023-01-01 00:00:00';
    // $end_date = date('Y-m-d 23:59:59');
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    $print_date = date('Y-m-d H:i:s');

    $query =    "SELECT pl.ProductID , p.ProductName , p.PricePerUnit , p.Cost , SUM(Qty) as total_Qty 
    FROM productlist pl , product p 
    WHERE pl.ProductID = p.ProductID 
        AND OrderID IN 
            (SELECT OrderID FROM `order` 
                    WHERE Status != 'Canceled' AND Date BETWEEN '$start_date' AND '$end_date') 
    GROUP BY ProductID 
    ORDER BY ProductID";
    
    $statement = $pdo->prepare($query);
    $statement->execute();
    if ($statement->rowCount() > 0) {
        $productLists = $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    $query =    "SELECT * FROM `order` 
                WHERE Status != 'Canceled' AND Date BETWEEN '$start_date' AND '$end_date' ORDER BY Date";
    $statement = $pdo->prepare($query);
    $statement->execute();
    if($statement->rowCount() > 0) {
        $orders = $statement->fetchAll(PDO::FETCH_ASSOC);
        $cntOrder = count($orders);
    }





  

    class PDF extends FPDF
    {
        function Header(){
        $this->AddFont('THSarabunNew','','THSarabunNew.php');

        //Display Company Info
        $this->SetFont('THSarabunNew','',28);
        $this->cell(0,10,iconv('UTF-8','cp874','รายงานสรุปยอดขาย'),0,1,'C');
        
        //Display Horizontal line
        $this->Line(10,48,200,48);
        }
        
        function body($start_date, $end_date, $print_date, $productLists, $cntOrder){
        $this->AddFont('THSarabunNew','','THSarabunNew.php');
        $this->SetFont('THSarabunNew','',14);
        $this->cell(0,10,iconv('UTF-8','cp874','ตั้งแต่วันที่ : ' . $start_date . ' ถึงวันที่ : ' . $end_date),0,1,'C');
        $this->SetFont('THSarabunNew', '', 14);
        $this->cell(0,10,iconv('UTF-8','cp874','จำนวน Order : ' . $cntOrder . ' Order'),0,1,'C');
        //Display INVOICE text
        $this->SetY(40);
        $this->SetX(-60);
        $this->SetFont('THSarabunNew','',14);
        $this->Cell(50,10,iconv('UTF-8','cp874','พิมพ์เมื่อวันที่ : ' . $print_date),0,1);


        
        //Display Table headings
        $this->SetY(65);
        $this->SetX(10);
        $this->SetFont('THSarabunNew','',14);

        $this->SetFont('THSarabunNew','',12);
        $this->Cell(20,9,"PRODUCT ID",1,0);
        $this->Cell(50,9,"PRODUCT NAME",1,0);
        $this->Cell(25,9,"SELLING PRICE",1,0,"C");
        $this->Cell(25,9,"COST PRICE",1,0,"C");
        $this->Cell(20,9,"QTY",1,0,"C");
        $this->Cell(25,9,"SUB TOTAL",1,0,"C");
        $this->Cell(25,9,"PROFIT",1,1,"C");
        $this->SetFont('THSarabunNew','',12);
        
        //Display table product rows
        $all_qty = 0;
        $all_total = 0.00;
        $all_pofit = 0.00;
        foreach($productLists as $row){
            $total = $row["PricePerUnit"] * $row["total_Qty"];
            $profit = $total - ($row["Cost"] * $row["total_Qty"]);
            $this->Cell(20,9,$row["ProductID"],"LR",0);
            $this->Cell(50,9,$row["ProductName"],"LR",0);
            $this->Cell(25,9,number_format($row["PricePerUnit"], 2),"R",0,"R");
            $this->Cell(25,9,number_format($row["Cost"], 2),"R",0,"R");
            $this->Cell(20,9,$row["total_Qty"],"R",0,"C");
            $this->Cell(25,9,number_format($total, 2),"R",0,"R");
            $this->Cell(25,9,number_format($profit, 2),"R",1,"R");
            $all_qty += $row["total_Qty"];
            $all_total += $total;
            $all_pofit += $profit;
        }
        //Display table empty rows
        for($i = 0 ; $i < 3 ; $i++)
        {
            $this->Cell(20,9,"","LR",0);
            $this->Cell(50,9,"","LR",0);
            $this->Cell(25,9,"","R",0,"R");
            $this->Cell(25,9,"","R",0,"R");
            $this->Cell(20,9,"","R",0,"C");
            $this->Cell(25,9,"","R",0,"R");
            $this->Cell(25,9,"","R",1,"R");
        }
        //Display table total row
        $this->SetFont('THSarabunNew','',12);
        $this->Cell(120,9,"TOTAL",1,0,"R");
        $this->Cell(20,9,$all_qty,1,0,"C");
        $this->Cell(25,9,number_format($all_total, 2),1,0,"R");
        $this->Cell(25,9,number_format($all_pofit, 2),1,1,"R");
        
        //Display amount in words
        $this->SetY(225);
        $this->SetX(10);
        $this->SetFont('THSarabunNew','',12);
        //$this->Cell(0,9,"Amount in Words ",0,1);
        //$this->SetFont('THSarabunNew','',12);
        //$this->Cell(0,9,$info["words"],0,1);
        
        }
        function Footer(){
        $this->AddFont('THSarabunNew','','THSarabunNew.php');
        //set footer position
        $this->SetY(-50);
        $this->SetFont('THSarabunNew','',12);
        //$this->Cell(0,10,"for ABC COMPUTERS",0,1,"R");
        $this->Ln(15);
        $this->SetFont('THSarabunNew','',12);
        //$this->Cell(0,10,"Authorized Signature",0,1,"R");
        $this->SetFont('THSarabunNew','',10);
        
        //Display Footer Text
        //$this->Cell(0,10,"This is a computer generated invoice",0,1,"C");
        
        }
        
    }
    //Create A4 Page with Portrait 
    $pdf=new PDF("P","mm","A4");
    $pdf->AddPage();
    $pdf->body($start_date, $end_date, $print_date, $productLists, $cntOrder);
    $pdf->Output();
?>