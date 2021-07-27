<?php
include("db_connection.php");// db connection file included

if(!isset($_GET['catid'])){
    if($_POST){
  //****************image upload coding starts********************** */    
    $target_dir = SITE_ROOT."/upload/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    if(isset($_POST["submit"])) {
      $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
      if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
      } else {
        echo "File is not an image.";
        $uploadOk = 0;
      }
    }
    if (file_exists($target_file)) {
      echo "Sorry, file already exists.";
      $uploadOk = 0;
    }
     // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
      echo "Sorry, your file is too large.";
      $uploadOk = 0;
    }
     if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
      echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
      $uploadOk = 0;
    }
    if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";
 
    } else {
    
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
      } else {
        echo "Sorry, there was an error uploading your file.";
      }
    }
//****************image upload coding end**************************************/
/************************User details update code starts****************************** */
if(isset($_POST['id'])&& ($_POST['name']!='')&& ($_POST['contact_no']!='')){
    foreach($_POST['hobby'] as $value)
    {
       $hobbys[]=$value;
    }
    if(isset($_POST['category'])){
        $sql_insert = "UPDATE user SET
        name = '".$_POST['name']."',
        contact_no = '".$_POST['contact_no']."',
       
        category_id = '".$_POST['category']."',
        hobby_name = '".$hobby."' WHERE id = '".$_POST['id']."'";
    }else{
        $hobby = implode(",",$hobbys);
        $sql_insert = "UPDATE user SET
        name = '".$_POST['name']."',
        contact_no = '".$_POST['contact_no']."',
        
        category_id = '".$_POST['category']."',
        hobby_name = '".$hobby."' WHERE id = '".$_POST['id']."'";
    }
/*********************************User update code ends here*************************/
}else{
    /********************************user data insertion code starts here************/
     foreach($_POST['hobby'] as $value)
    {
    $hobbys[]=$value;
    }
    $hobby = implode(",",$hobbys);

    $sql_insert = "INSERT INTO user 
    (name,contact_no,pic,category_id,hobby_name) 
    VALUES('".$_POST['name']."',
    '".$_POST['contact_no']."',
    '".$_FILES['fileToUpload']['name']."',
    '".$_POST['category']."',
    '".$hobby."')";
  }
  $conn->query($sql_insert);
}

}
/*********************************user data insertion ends here*************************/
/**********************category dropdown for inline edit starts**********************/
if(isset($_GET['catid']) && (!isset($_POST['id']))){  
    $sql_category = "SELECT category_id FROM user where id=".$_GET['catid'];
    $res_categoryres = $conn->query($sql_category);
    $res = $res_categoryres->fetch_assoc();
    $sql_category = "SELECT * FROM category";
    $res_category = $conn->query($sql_category);
    $drop = '<select name="Category" id="category">';
    $drop .="\n";
    foreach($res_category as $value){
       $drop .="\n";
       if($res['category_id']==$value['id']){
        
        $drop .= '<option value="'. $value['id'].'"selected="selected">'.$value['category_name'].'</option>';
       }else{
       $drop .= '<option value="'. $value['id'].'">'.$value['category_name'].'</option>';
       }
    }
    echo $drop;

}
?>