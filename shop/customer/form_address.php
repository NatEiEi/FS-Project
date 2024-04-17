<?php 
    include('db_thailand.php');
    $sql = "SELECT * FROM provinces";
    $query = mysqli_query($conn, $sql); 
    require_once __DIR__ . '/db.php'; 

    // $Name = "";
    // $Tel = "";
    // $Detail = "";
	// $Amphure = "";
	// $Province = "";
    // $District = "";
    // $Zip = "";
    
    // if (isset($AddressID)) {
    //     $AddressID = $_GET['AddressID'];
    //     $query = "SELECT * FROM address WHERE AddressID='$AddressID'";
    //     $statement = $pdo->prepare($query);
    //     $statement->execute();
    //     if($statement->rowCount() > 0) {
    //         $addressList = $statement->fetchAll(PDO::FETCH_ASSOC);
    //         $address = $addressList[0];
    //         $Name = $address['Name'];
    //         $Tel = $address['Tel'];
    //         $Detail = $address['Detail'];
    //         $Amphure = $address['Amphure'];
    //         $Province = $address['Province'];
    //         $District = $address['District'];
    //         $Zip = $address['Zip'];
    //     }
    // }
?>
                  <link rel="stylesheet" href="css/address_form.css">
                  <div class="col-md-9">
                      <div class="card mb-3">
                          <div class="card-body">

                  <p>เพิ่มที่อยู่ผู้ซื้อ</p>
                  <label>ข้อมูลติดต่อ</label>
                  <form action="save_address.php" method="GET">
                  <input class="form-group" type="text" placeholder="ชื่อ นามสกุล" name="Name"><br>
                  <input class="form-group" type="text" placeholder="หมายเลขโทรศัพท์" name="Tel" maxlength="10"><br>
                  <input class="form-group" type="text" placeholder="บ้านเลขที่ , ซอย , หมู่ , ถนน , แขวง/ตำบล" name="Detail" size="40" >
                  
                  <label for="province">จังหวัด</label>
                  <select name="Province" id="province" class="form-control" >
                      <option value="">เลือกจังหวัด</option>
                        <?php while($result = mysqli_fetch_assoc($query)): ?>
                          <option value="<?=$result['id']?>"><?=$result['name_th']?></option>
                      <?php endwhile; ?>
                  </select>

                  <label for="amphure">อำเภอ</label>
                  <select name="Amphure" id="amphure" class="form-control" >
                      <option value="">เลือกอำเภอ</option>
                  </select>
                    
                  <label for="district">ตำบล</label>
                  <select name="District" id="district" class="form-control" >
                      <option value="">เลือกตำบล</option>
                  </select>

                  <label for="zip">รหัสไปรษณีย์</label>
                  <select name="Zip" id="zip" class="form-control" >
                      <option value="">เลือกรหัสไปรษณีย์</option>
                  </select>
                  <button type="submit" class="btn_submit_address">เพิ่มที่อยู่ใหม่</button>
                  </form>

                  </div>
                      </div>
                  </div>
                  <script src="js/jquery.min.js"></script>
                  <script src="js/script.js"></script>
                  