<?ob_start();?>
<?php
    require_once __DIR__ . '/db.php'; 
    include_once __DIR__ . '/navbar.php';
    require_once 'JWT_Handler.php'; 

    if (isset($_COOKIE['JWT'])) {
        $JWT = new JWT_Handler();
        $Username = $JWT->decode()->Username;
        $FName = $JWT->decode()->FName;
        $LName = $JWT->decode()->LName;
    }


    if (isset($_GET['Page'])) {
        $Page = $_GET['Page'];
        
        if ($Page == "Address"){
            $AddressQuery = "SELECT * FROM address WHERE Username = '$Username'";
            $statement = $pdo->prepare($AddressQuery);
            $statement->execute();
            if ($statement->rowCount() > 0) {
                $addressLists = $statement->fetchAll(PDO::FETCH_ASSOC);
            }  
        }
      
    }
   

?>
<head>
    <link rel="stylesheet" href="./css/profile.css">
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css'>
</head>
<div class="container">
    <div class="main-body">
    
          <div class="row gutters-sm">
            <div class="col-md-3 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                    <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150">
                    <div class="mt-3">
                      <h4><?= $FName ?> <?= $LName ?></h4>
                      <p class="text-secondary mb-1"></p>
                    </div>
                  </div>
                </div>
              </div>
              
              
              
              <div class="card mt-3">
                <ul class="list-group list-group-flush">
                    <a href="profile.php"><button class="list-group-item d-flex justify-content-between align-items-center flex-wrap" style="width: 100%;">ช้อมูลส่วนของตัวผู้ใช้</button></a>
                    <a href="profile.php?Page=Address"><button class="list-group-item d-flex justify-content-between align-items-center flex-wrap" style="width: 100%;">ที่อยู่</button></a>
                    <a href="profile.php?Page=OrderHistory"><button class="list-group-item d-flex justify-content-between align-items-center flex-wrap" style="width: 100%;">ประวัติรายการสั่งซื้อ</button></a>
                </ul>
              </div>
            </div>
            
            <?php if(isset($_GET['Page'])): ?>
                <?php if ($Page == "OrderHistory"): ?>
                    <div class="col-md-9 mb-3">
                        <?php include("OrderHistory.php") ?>
                    </div>
                <?php endif ?>


                <?php if ($Page == "Address"): ?>
                  <div class="col-md-9">
                      <div class="card mb-3">
                          <div class="card-body">
                              <div class="row">
                                  <div class="col-sm-2">
                                      <h6 class="mb-0">ที่อยู่</h6>
                                  </div>
                                  <div class="col-sm-9">
                                      <?php if(isset($addressLists)): ?>
                                          <?php foreach($addressLists as $address): ?> 
                                              <div class="address">
                                              <p><?= $address["Name"] ?> <?= $address["Tel"] ?> , <?= $address["Detail"] ?> <?= $address["District"] ?> <?= $address["Amphure"] ?> <?= $address["Province"] ?>   <?= $address["Zip"] ?></p>
                                              </div>
                                              <a href='delete_address.php?AddressID=<?= $address["AddressID"]  ?>' onclick="return confirmDelete()">
                                                  <img src="../images/icon/Junk_Icon.png" style="width: 30px; height:30px">
                                              </a>
                                              <hr>
                                          <?php endforeach; ?>
                                      <?php endif; ?>
                                    
                                  </div>
                                  
                              </div>
                              <center>
                                <a href='profile.php?Page=AddNewAddress'>
                                    <p>เพิ่มที่อยู่ใหม่ <img src="../images/icon/add_icon.png" style="width: 20px; height:20px"> </p>
                                </a>
                              </center>
                          </div>
                      </div>
                  </div>

                <?php endif ?>

                <?php if ($Page == "AddNewAddress"): ?>
                  <?php include("form_address.php") ?>
                <?php endif ?>



                <?php else : ?>
                  <?php
                      $query = "SELECT * FROM customer WHERE Username = '$Username'";
                      $statement = $pdo->prepare($query);
                      $statement->execute();
                      if ($statement->rowCount() > 0) {
                          $userInfos = $statement->fetchAll(PDO::FETCH_ASSOC);
                          $userInfo = $userInfos[0];
                      }  
                  ?>
                  <div class="col-md-9">
                    <div class="card mb-3">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-sm-3">
                            <h6 class="mb-0">Full Name</h6>
                          </div>
                          <div class="col-sm-9 text-secondary">
                              <?= $userInfo['FName'] ?> <?= $userInfo['LName'] ?>
                          </div>
                        </div>
                        <hr>
                        
                        <div class="row">
                          <div class="col-sm-3">
                            <h6 class="mb-0">Email</h6>
                          </div>
                          <div class="col-sm-9 text-secondary">
                              <?= $userInfo['Email'] ?>
                          </div>
                        </div>
                        <hr>
                        
                        <div class="row">
                          <div class="col-sm-3">
                            <h6 class="mb-0">Tel</h6>
                          </div>
                          <div class="col-sm-9 text-secondary">
                          <?= $userInfo['Tel'] ?>
                          </div>
                        </div>
                        <hr>
                        
                        <div class="row">
                          <!-- <div class="col-sm-12">
                            <a class="btn btn-info " target="__blank" href="https://www.bootdey.com/snippets/view/profile-edit-data-and-skills">Edit</a>
                          </div> -->
                        </div>
                      </div>
                    </div>
                  </div>

            <?php endif ?>
            
            
            
          </div>
        </div>
    </div>
    <script>
    function confirmDelete() {
        // แสดง popup confirm และรับค่าที่ผู้ใช้ตอบ
        var confirmation = confirm("คุณต้องการที่จะยกเลิกรายการสั่งซื้อใช่หรือไม่?");
        // หากผู้ใช้กด OK ใน popup confirm จะเปิดลิงก์
        return confirmation;
    }
  </script>
    
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js'></script>