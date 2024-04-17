<?php 
    require '../db.php';
    require ("../libraries/fpdf/fpdf.php");
  
  
    $OrderID = $_GET['OrderID'];
    $AddressQuery = "SELECT Name ,  Amphure , Province , District , Zip, Tel , Type
    FROM address a, addresslist al
    WHERE a.AddressID = al.AddressID AND al.OrderID = '$OrderID'";
    $statement = $pdo->prepare($AddressQuery);
    $statement->execute();
    if ($statement->rowCount() > 0) {
        $addressLists = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    $ProductQuery = "
    SELECT 
    p.ProductName, 
    pl.Qty, 
    p.PricePerUnit,
    o.Payment,
    osd.Shipper,
    osd.TrackingNumber
    FROM 
        product p 
        INNER JOIN productlist pl ON p.ProductID = pl.ProductID 
        INNER JOIN `order` o ON pl.OrderID = o.OrderID
        LEFT JOIN order_shipping_detail osd ON o.OrderID = osd.OrderID
    WHERE 
        pl.OrderID = '$OrderID'

    ";
    $statement = $pdo->prepare($ProductQuery);
    $statement->execute();
    if ($statement->rowCount() > 0) {
        $productLists = $statement->fetchAll(PDO::FETCH_ASSOC);
    }

  //customer and invoice details
  $infoBuy = [];
  $infoBill = [];
  $infoShipping = [];
  $Payment = null;
  $Shipper = null;
  $TrackingNumber = null;

  
  foreach ($addressLists as $row) {
      if($row['Type'] == "Buy")
          $infoBuy =[
            "Name" => $row['Name'],
            "District" => $row['District'],
            "Amphure" => $row['Amphure'], 
            "Province" => $row['Province'], 
            "Zip" => $row['Zip'], 
            "Tel" => $row['Tel'], 

          ];
      
  
      
      if($row['Type'] == "Bill"){
          $infoBill = [
              "Name" => $row['Name'],
              "District" => $row['District'],
              "Amphure" => $row['Amphure'], 
              "Province" => $row['Province'],
              "Zip" => $row['Zip'],  
              "Tel" => $row['Tel'], 

              
          ];
  
      }
      if($row['Type'] == "Ship"){
          $infoShipping = [
            "Name" => $row['Name'],
            "District" => $row['District'],
            "Amphure" => $row['Amphure'], 
            "Province" => $row['Province'], 
            "Zip" => $row['Zip'], 
            "Tel" => $row['Tel'], 

          ];
  
      }
  }
  
 
    
  //invoice Products
  $products_info = [];
  foreach ($productLists as $list) {
      $price = $list['Qty'] * $list['PricePerUnit'];
      $products_info[] = [
          "name" => $list['ProductName'],
          "price" => number_format($list['PricePerUnit'], 2), // Format เป็นทศนิยมสองตำแหน่ง
          "qty" => $list['Qty'],
          "total" => $price,
          
          
      ];
      if ($Payment === null) {
        $Payment = $list['Payment'];
      }
      if ($Shipper === null) {
        $Shipper = $list['Shipper'];
      }
      if ($TrackingNumber === null) {
        $TrackingNumber = $list['TrackingNumber'];
      }
  }
  
  class PDF extends FPDF
  {
    private $OrderIDD;
    function setOrderID($OrderID) {
      $this->OrderIDD = $OrderID;
  }
    private $Paymentt;
    function setPayment($Payment) {
    $this->Paymentt = $Payment;
  }
  private $Shipperr; // แก้จาก $Shipperr เป็น $Shipper ในตัวแปร private

    function setShipper($Shipper) {
    $this->Shipperr = $Shipper; 
  }
  
  private $TrackingNumberr; 
  function setTrackingNumber($TrackingNumber) {
  $this->TrackingNumberr = $TrackingNumber; 
  }

    function Header(){
        $this->AddFont('THSarabunNew', '', 'THSarabunNew.php');
        $this->AddFont('THSarabunNew', 'B', 'THSarabunNew_b.php');
        
        //Display Company Info
        $this->SetFont('THSarabunNew', 'B', 30); 
        $this->Cell(0, 50, iconv('UTF-8', 'cp874', 'ใบจัดส่ง'), 0, 10, 'C');
        
        
        // $this->SetFont('THSarabunNew', 'B', 14); 
        // $this->SetY(10);
        // $this->SetX(50); // กำหนดตำแหน่ง X เริ่มต้น
        // $this->Cell(0, 10, iconv('UTF-8', 'cp874', 'Admin'), 0, 1, 'L'); // Cell() โดยการใช้ 0 เพื่อให้ FPDF ปรับขนาดข้อความอัตโนมัติ
        // $this->SetFont('THSarabunNew', '', 14); // เปลี่ยนกลับเป็นปกติ
        // $this->SetX(50); // กำหนดตำแหน่ง X เริ่มต้น
        // $this->Cell(0, 7, iconv('UTF-8', 'cp874', 'bangkok'), 0, 1,'L'); // Cell() โดยการใช้ 0 เพื่อให้ FPDF ปรับขนาดข้อความอัตโนมัติ
        // $this->SetX(50); // กำหนดตำแหน่ง X เริ่มต้น
        // $this->Cell(0, 7, iconv('UTF-8', 'cp874', 'Thailand 15240'), 0, 1,'L'); // Cell() โดยการใช้ 0 เพื่อให้ FPDF ปรับขนาดข้อความอัตโนมัติ
        // $this->SetX(50); // กำหนดตำแหน่ง X เริ่มต้น
        // $this->Cell(0, 7, "tel.16161166", 0, 1,'L'); // Cell() โดยการใช้ 0 เพื่อให้ FPDF ปรับขนาดข้อความอัตโนมัติ

        
      
      //Display INVOICE text
      $date = date("d-m-Y");
      $this->SetY(15);
      $this->SetX(-40);
      $this->SetFont('THSarabunNew','',14);
      $this->Cell(27,20, 'Date: ' . $date, 0, 1, 'R');
      $this->SetY(15);
      $this->SetX(-40);
      $this->Cell(50,10,iconv('UTF-8', 'cp874', 'OrderID: ' . $this->OrderIDD), 0, 1); 
      $this->SetX(-40);
      // $this->Cell(60,10,iconv('UTF-8', 'cp874', 'Customer Tax ID: ?'), 0, 1);      
     
      //Display Horizontal line
      $this->Line(0,48,210,48);
    }
    
    function body($infoBill,$infoBuy,$infoShipping,$products_info){
      $this->AddFont('THSarabunNew','','THSarabunNew.php');

    // Buyer
    $this->SetY(55);
    $this->SetX(10);
    $this->SetFont('THSarabunNew', 'B', 14);
    $this->Cell(0, 7, iconv('UTF-8', 'cp874', 'Sender :'), 0,0,'L');
    $this->SetX(25);
    $this->SetFont('THSarabunNew', '', 14);
    $this->Cell(25, 7, iconv('UTF-8', 'cp874', "TECHSHOP"), 0, 1,'L');
    $this->SetX(25);
    $this->Cell(25, 7, iconv('UTF-8', 'cp874', "เเขวง ลาดกระบัง"), 0, 1,'L');
    $this->SetX(25);
    $this->Cell(25, 7, iconv('UTF-8', 'cp874', "เขต ลาดกระบัง"), 0, 0,'L');
    $this->Cell(25, 7, iconv('UTF-8', 'cp874', "จังหวัด กรุงเทพมหานคร"), 0, 1,'L');
    $this->SetX(25);
    $this->Cell(15, 7, iconv('UTF-8', 'cp874', "10520"), 0, 0,'L');
    $this->Cell(25, 7, iconv('UTF-8', 'cp874', "Tel. 123456789"), 0, 1,'L');



     $this->SetY(55);
     $this->SetX(75);
     $this->SetFont('THSarabunNew','B',14);
     $this->Cell(15, 7, iconv('UTF-8', 'cp874', 'Recipient :'), 0, 0);
     $this->SetFont('THSarabunNew','',14);
     $this->SetX(95);
     $this->Cell(15, 7, iconv('UTF-8', 'cp874', "คุณ ".$infoShipping["Name"]), 0, 1,'L');
     $this->SetX(95); // ปรับตำแหน่ง X ใหม่
     $this->Cell(25, 7, iconv('UTF-8', 'cp874', "เเขวง ".$infoShipping["District"]), 0, 0,'L');
     $this->Cell(15, 7, iconv('UTF-8', 'cp874', "เขต ".$infoShipping["Amphure"]), 0, 1,'L');
     $this->SetX(95); // ปรับตำแหน่ง X ใหม่

     $this->Cell(35, 7, iconv('UTF-8', 'cp874', "จังหวัด ".$infoShipping["Province"]), 0, 0,'L');
     $this->Cell(25, 7, iconv('UTF-8', 'cp874', $infoShipping["Zip"]), 0, 1,'L');
     $this->SetX(95); // ปรับตำแหน่ง X ใหม่
     $this->Cell(15, 7, iconv('UTF-8', 'cp874', "Tel : ".$infoShipping["Tel"]), 0, 1,'L');





    // $this->SetY(55);
    // $this->SetX(150);
    // $this->SetFont('THSarabunNew','B',14);
    // $this->Cell(15, 7, iconv('UTF-8', 'cp874', 'Ship to:'), 0, 0);
    // $this->SetFont('THSarabunNew','',14);
    // $this->Cell(15, 7, iconv('UTF-8', 'cp874', $infoShipping["Name"]), 0, 1,'L');
    // $this->SetX(165);
    // $this->Cell(15, 7, iconv('UTF-8', 'cp874', $infoShipping["District"]), 0, 1,'L');
    // $this->SetX(165);
    // $this->Cell(15, 7, iconv('UTF-8', 'cp874', $infoShipping["Amphure"]), 0, 0,'L');
    // $this->Cell(15, 7, iconv('UTF-8', 'cp874', $infoShipping["Province"]), 0, 1,'L');
      
      //Display Invoice no
      $this->SetY(55);
      $this->SetX(-60);
      //$this->Cell(50,7,"Invoice No : ".$info["Province"]);
      
      //Display Invoice date
      $this->SetY(63);
      $this->SetX(-60);
      //$this->Cell(50,7,"Invoice Date : ".$info["Province"]);
      
      //Display Table headings
      $this->SetY(95);
      $this->SetX(10);
      $this->SetFont('THSarabunNew','',12);
      $this->Cell(10,9,"NO.",1,0,"C"); // เพิ่มคอลัมน์ "NO."
      $this->Cell(70,9,"NAME","TRB",0,"C"); // เพิ่มคอลัมน์ "NAME"
      $this->Cell(40,9,"PRICE","TRB",0,"C");
      $this->Cell(30,9,"QTY","TRB",0,"C");
      $this->Cell(40,9,"TOTAL","TRB",1,"C");
      $this->SetFont('THSarabunNew','',12);
      //Display table product rows
      $allPrice = 0.00;
      $no = 1; // เริ่มตัวเลขลำดับที่ 1
      foreach($products_info as $row){
          $this->Cell(10,9,$no++,"LR",0,"C"); // แสดงคอลัมน์ "NO." และเพิ่มค่าเริ่มต้นทีละ 1
          $this->Cell(70,9,$row["name"],"R",0 ); // แสดงคอลัมน์ "NAME"
          $this->Cell(40,9,($row["price"]),"R",0,"R");
          $this->Cell(30,9,$row["qty"],"R",0,"C");
          $this->Cell(40,9,number_format($row["total"], 2),"R",1,"R");
          $allPrice += $row["total"];
      }
      
      //Display table empty rows
      for($i=0;$i<12-count($products_info);$i++)
      {
        $this->Cell(10,9,"","LR",0);
        $this->Cell(70,9,"","R",0);
        $this->Cell(40,9,"","R",0,"R");
        $this->Cell(30,9,"","R",0,"C");
        $this->Cell(40,9,"","R",1,"R");
      }
      //Display table total row
      $SPcost = 50;
      $VAT = ($allPrice * 0.07)+$SPcost;

      $this->SetFont('THSarabunNew','',12);
      $this->Cell(150, 9, "SUBTOTAL", 'LTR', 0, 'R');
      $this->Cell(40, 9, number_format($allPrice,2), 'TR', 1, 'R');
      $this->Cell(150, 9, "VAT 7 %", "LR", 0, 'R');
      $this->Cell(40, 9, number_format($VAT,2), "R", 1, 'R');
      $this->Cell(150, 9, iconv('UTF-8', 'cp874', "Shipping cost"), "LR", 0, 'R');
      $this->Cell(40, 9, number_format($SPcost,2), "R", 1, 'R');
      $this->SetFont('THSarabunNew','B',12);
      $this->Cell(150, 9, "TOTAL", 'LR', 0, 'R');
      $this->Cell(40, 9, number_format(($allPrice + $VAT),2), 'R', 1, 'R');
      $this->Cell(190, 0, '', 1, 1);
      

      
    }
    function Footer() {

     
      $this->AddFont('THSarabunNew','','THSarabunNew.php');
      //set footer position
      $this->SetY(-50);
      $this->SetFont('THSarabunNew','',14);
      $this->Cell(0,10,iconv('UTF-8', 'cp874', "ช่องทางการชำระ"),0,1,"R");
      $this->Cell(0,10,$this->Paymentt,0,1,"R");
      $this->Ln(15);
      $this->SetY(-50);
      $this->SetX(15);
      $this->SetFont('THSarabunNew','',14);
      $this->Cell(0,10,iconv('UTF-8', 'cp874', "บริษัทจัดส่ง : $this->Shipperr"),0,1,"L");
      // $this->Cell(0,10,iconv('UTF-8', 'cp874', "หมายเลขพัสดุ :$this->TrackingNumberr"),0,1,"L");

      
    }
    
  }
  
  //Create A4 Page with Portrait 
  $pdf=new PDF("P","mm","A4");
  $pdf->setOrderID($OrderID);
  $pdf->setPayment($Payment);
  $pdf->setShipper($Shipper);
  $pdf->setTrackingNumber($TrackingNumber);

  $pdf->AddPage();

  $logo = '../images/icon/logo_techshop.png';
  $pdf->Image($logo, 9,10, 25, 10);

  $pdf->body($infoBill,$infoBuy,$infoShipping,$products_info);
  $pdf->Output();
?>