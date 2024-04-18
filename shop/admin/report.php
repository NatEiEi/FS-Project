<?ob_start();?>
<?php
    require '../db.php'; 
    include __DIR__ . '/adminLogin.php';

    date_default_timezone_set('Asia/Bangkok');
    // $currentYearBE = date("Y") + 543;
    $currentYearBE = date("Y");
    echo "<p style='text-align: center;'>วันที่ : " .date("d-m") ."-$currentYearBE " . " เวลา : " . date("H:i:s") ."</p>";

    echo '<center>
    <form action="report.php" method="GET">
      <label for="start_date">Start Date:</label>
      <input type="date" id="start_date" name="start_date">
    
      <label for="end_date">End Date:</label>
      <input type="date" id="end_date" name="end_date">
    
      <label for="report_type">Report Type:</label>
      <select id="report_type" name="report_type">
        <option value="product_sales">Product Sales Report</option>
        <option value="customerreport">Customer Report</option>
      </select>
    
      <button type="submit">Submit</button>
    </form>
    </center>';
    
    



    if (empty($_GET['start_date'])) {
        $start_date = '2023-01-01 00:00:00';
    } else {
        $start_date = date('Y-m-d 00:00:00', strtotime($_GET['start_date']));
    }
    if (empty($_GET['end_date'])) {
        $end_date = date('Y-m-d 23:59:59');
    } else {
        $end_date = date('Y-m-d 23:59:59', strtotime($_GET['end_date']));
    }

    echo "<center>Filter Start Date : $start_date - End Date : $end_date</center>";



    if (isset($_GET['report_type'])) {
        $report_type = $_GET['report_type'];
      
        if ($report_type === 'product_sales') {
          $url = "printsaleReport.php?start_date=".$start_date."&end_date=".$end_date;
          header("Location: $url");
      
        } else if ($report_type === 'customerreport') {
          $url = "PrintCusomerReport.php?start_date=".$start_date."&end_date=".$end_date;
          header("Location: $url");

        }
      }

    // $query =    "SELECT p.ProductID , p.ProductName , p.PricePerUnit , p.Cost ,p.QtyStock, IFNULL(SUM(pl.Qty), 0) as total_Qty 
    //                 FROM product p 
    //                 LEFT JOIN productlist pl ON pl.ProductID = p.ProductID 
    //                     AND pl.OrderID IN 
    //                         (SELECT OrderID FROM `order` 
    //                                 WHERE Status != 'Canceled' AND Date BETWEEN '$start_date' AND '$end_date') 
    //                 GROUP BY p.ProductID 
    //                 ORDER BY p.ProductID";
    // $query2 = "SELECT ProductID, ProductName, QtyStock , PricePerUnit , Cost    
    //             FROM product
    //             WHERE QtyStock > 0;";

                        
    
    // $statement = $pdo->prepare($query);
    // $statement->execute();
    // $statement2 = $pdo->prepare($query2);
    // $statement2->execute();
    echo $start_date;
    echo $end_date;

    // if (isset($_GET['product_sales'])) {
    //     echo " dadad";
    //     // Check if there are rows returned from the query
    //     if ($statement->rowCount() > 0) {
    //         // Fetch all products
    //         $products = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    //         // Display product sales report table
    //         echo "<br><br><table border='1' style='width: 60%; margin: 0 auto;'>
    //               <tr>
    //                 <th style='width: 15%;'>Product ID</th>
    //                 <th style='width: 15%;'>Product Name</th>
    //                 <th style='width: 15%;'>Price / Unit</th>
    //                 <th style='width: 15%;'>Cost / Unit</th>
    //                 <th style='width: 10%;'>Qty</th>
    //                 <th style='width: 15%;'>รวม</th>
    //                 <th style='width: 15%;'>กำไร</th>
    //               </tr>";
            
    //         // Initialize variables for total sum and total profit
    //         $TotalSum = 0;
    //         $TotalProfit = 0;
    
    //         // Iterate over each product
    //         foreach ($products as $product) {
    //             if ($product['total_Qty'] > 0) {
    //                 // Calculate total sales and profit for each product
    //                 $Sum = $product['PricePerUnit'] * $product['total_Qty'];
    //                 $profit = $Sum - ($product['Cost'] * $product['total_Qty']);
                    
    //                 // Update total sum and total profit
    //                 $TotalSum += $Sum;
    //                 $TotalProfit += $profit;
                    
    //                 // Display product details in table rows
    //                 echo "<tr>
    //                         <td style='text-align: center;'>{$product['ProductID']}</td>
    //                         <td style='text-align: center;'>{$product['ProductName']}</td>
    //                         <td style='text-align: center;'>{$product['PricePerUnit']}</td>
    //                         <td style='text-align: center;'>{$product['Cost']}</td>
    //                         <td style='text-align: center;'>{$product['total_Qty']}</td>
    //                         <td style='text-align: center;'>{$Sum}</td>
    //                         <td style='text-align: center;'>{$profit}</td>
    //                       </tr>";
    //             }
    //         }
            
    //         // Display total revenue and total profit
    //         echo "</table>";
    //         echo "<p style='text-align: end; padding:0px 20%;'>รายได้รวม $TotalSum บาท</p>";
    //         echo "<p style='text-align: end; padding:0px 20%;'>กำไรรวม $TotalProfit บาท</p>";
    //     } else {
    //         // If no rows returned, display a message
    //         echo "<p style='text-align: center;'>No products sold within the selected period.</p>";
    //     }
    // }else{
       
    // }
    

    
?>

