<?ob_start();?>
<?php
    require_once __DIR__ . '/db.php'; 
    include_once __DIR__ . '/navbar.php';
    require_once("../libraries/qr/PromptPayQR.php");

    $OrderID = $_GET['OrderID'];
    
    $file_name = "qr_" . $OrderID . ".png";
    $qr_image_path = '../images/qr/' . $file_name;

    $query = "SELECT TotalPrice FROM `order` WHERE OrderID='$OrderID'";
    $statement = $pdo->prepare($query);
    $statement->execute();
    if ($statement->rowCount() > 0) {
        $lists = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    $Price = $lists[0]['TotalPrice'];

    function generateQRCode($qr_image_path  , $Price) {

        $PromptPayQR = new PromptPayQR();
        $PromptPayQR->size = 8;
        $PromptPayQR->id = '0873533433';
        $PromptPayQR->amount = $Price;
        $PromptPayQR->generate($qr_image_path);
        return $Price;
    }

    if (file_exists($qr_image_path)) {
    } else {
        $Price = generateQRCode($qr_image_path , $Price);
    }
  
?>

<head>
    <link rel="stylesheet" href="css/tranfer_slip.css">
</head>

<div class="container">
    <div class="left-section">
        <h2>แสกน QR Code PromtPay เพื่อชำระเงิน</h2><br>
        <img src="<?= $qr_image_path ?>" alt="qr" style="width:50%;height:50%;">
    <div>
        <p>รหัสรายการสั่งซื้อ : <?= $OrderID ?></p>
        <p>ช่องทางชำระเงิน : PromptPay</p> 
        <p>ชื่อบัญชี : </p> 
        <p>รหัสพร้อมเพย์ : 087-353-3433</p>
        <p>จำนวนเงินที่ต้องชำระ : <?= number_format($Price, 2);?> บาท</p>
    </div>
    </div>
    <div class="right-section">
    <center>
    <p>อัปโหลดรูปหลักฐานการโอนเงิน : </p>
    <form method="POST" action="transfer_slip_save.php" enctype="multipart/form-data" style='text-align: start; padding:0px 30%;'>
        <input type="hidden" name="OrderID" value=<?= $OrderID ?>><br>
        <img src="../images/icon/upload_img_icon.png" onclick="document.getElementById('imageUpload').click();" style="cursor: pointer;width:50%;height:50%;">
        <input type="file" id="imageUpload" name="imageUpload" onchange="previewImage(event)" required>
        <hr>
        <div id="imagePreview"></div>
        <br>
        <input type="submit" value="ส่งหลักฐานการโอน">
    </form>
    </center>
    </div>
</div>


<script>

function previewImage(event) {
  var reader = new FileReader();
  reader.onload = function(){
    var output = document.getElementById('imagePreview');
    output.innerHTML = "<p>รูปหลักฐานการโอนเงิน : </p> " +  '<img src="' + reader.result + '" style="max-width:100%;max-height:300px;">';
  }
  reader.readAsDataURL(event.target.files[0]);
}



</script>