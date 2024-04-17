$(function(){
    var provinceObject = $('#province');
    var amphureObject = $('#amphure');
    var districtObject = $('#district');
    var zipObject = $('#zip');

    // on change province
    provinceObject.on('change', function(){
        var provinceId = $(this).val();

        amphureObject.html('<option value="">เลือกอำเภอ</option>');
        districtObject.html('<option value="">เลือกตำบล</option>');

        $.get('./assets/get_amphure.php?province_id=' + provinceId, function(data){
            var result = JSON.parse(data);
            $.each(result, function(index, item){
                amphureObject.append(
                    $('<option></option>').val(item.id).html(item.name_th)
                );
            });
        });
    });

    // on change amphure
    amphureObject.on('change', function(){
        var amphureId = $(this).val();

        districtObject.html('<option value="">เลือกตำบล</option>');
        
        $.get('./assets/get_district.php?amphure_id=' + amphureId, function(data){
            var result = JSON.parse(data);
            $.each(result, function(index, item){
                districtObject.append(
                    $('<option></option>').val(item.id).html(item.name_th)
                );
            });
        });
    });

    districtObject.on('change', function() {
        var districtId = $(this).val();

        zipObject.html('<option value="">เลือกรหัสไปรษณีย์</option>');

        $.get('./assets/get_zip.php?district_id=' + districtId, function(data) {
          var result = JSON.parse(data);
          $.each(result, function(index, item) {
            zipObject.append(
              $('<option></option>').val(item.id).html(item.zip_code)
            );
          });
        });
      });






      var provinceObject1 = $('#province1');
      var amphureObject1 = $('#amphure1');
      var districtObject1 = $('#district1');
      var zipObject1 = $('#zip1');
  
      // on change province
      provinceObject1.on('change', function(){
          var provinceId = $(this).val();
  
          amphureObject1.html('<option value="">เลือกอำเภอ</option>');
          districtObject1.html('<option value="">เลือกตำบล</option>');
  
          $.get('./assets/get_amphure.php?province_id=' + provinceId, function(data){
              var result = JSON.parse(data);
              $.each(result, function(index, item){
                amphureObject1.append(
                      $('<option></option>').val(item.id).html(item.name_th)
                  );
              });
          });
      });
  
      // on change amphure
      amphureObject1.on('change', function(){
          var amphureId = $(this).val();
  
          districtObject1.html('<option value="">เลือกตำบล</option>');
          
          $.get('./assets/get_district.php?amphure_id=' + amphureId, function(data){
              var result = JSON.parse(data);
              $.each(result, function(index, item){
                districtObject1.append(
                      $('<option></option>').val(item.id).html(item.name_th)
                  );
              });
          });
      });
  
      districtObject1.on('change', function() {
          var districtId = $(this).val();
  
          zipObject1.html('<option value="">เลือกรหัสไปรษณีย์</option>');
  
          $.get('./assets/get_zip.php?district_id=' + districtId, function(data) {
            var result = JSON.parse(data);
            $.each(result, function(index, item) {
                zipObject1.append(
                $('<option></option>').val(item.id).html(item.zip_code)
              );
            });
          });
        });



        // ----------------------------------------------------------------
        var provinceObject2 = $('#province2');
        var amphureObject2 = $('#amphure2');
        var districtObject2 = $('#district2');
        var zipObject2 = $('#zip2');
    
        // on change province
        provinceObject2.on('change', function(){
            var provinceId = $(this).val();
    
            amphureObject2.html('<option value="">เลือกอำเภอ</option>');
            districtObject2.html('<option value="">เลือกตำบล</option>');
    
            $.get('./assets/get_amphure.php?province_id=' + provinceId, function(data){
                var result = JSON.parse(data);
                $.each(result, function(index, item){
                    amphureObject2.append(
                        $('<option></option>').val(item.id).html(item.name_th)
                    );
                });
            });
        });
    
        // on change amphure
        amphureObject2.on('change', function(){
            var amphureId = $(this).val();
    
            districtObject2.html('<option value="">เลือกตำบล</option>');
            
            $.get('./assets/get_district.php?amphure_id=' + amphureId, function(data){
                var result = JSON.parse(data);
                $.each(result, function(index, item){
                  districtObject2.append(
                        $('<option></option>').val(item.id).html(item.name_th)
                    );
                });
            });
        });
    
        districtObject2.on('change', function() {
            var districtId = $(this).val();
    
            zipObject2.html('<option value="">เลือกรหัสไปรษณีย์</option>');
    
            $.get('./assets/get_zip.php?district_id=' + districtId, function(data) {
              var result = JSON.parse(data);
              $.each(result, function(index, item) {
                  zipObject2.append(
                  $('<option></option>').val(item.id).html(item.zip_code)
                );
              });
            });
          });
});