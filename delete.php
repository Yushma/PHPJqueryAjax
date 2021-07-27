<?php
include("db_connection.php");//db connection file included
/*************************single user record deletion code**************** */
if(isset($_GET['id'])){
    $id=$_GET['id'];
 
$sql_delete = "DELETE FROM user where id=".$id;
$conn->query($sql_delete);
}
/********************************Bulk user deletion code***********************/
if($_POST['bulkid']){
  $sql_delete = "DELETE FROM user WHERE id IN (".$_POST['bulkid'].")";
  $conn->query($sql_delete); 
}
?>