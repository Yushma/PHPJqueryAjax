<?php 

include("db_connection.php");//database connection done 
$dir = trim('upload\"','\'"');
/*****************************user listing query************************** */
$sql = "SELECT u.id,u.name,u.contact_no,u.pic,u.hobby_name,c.category_name FROM user u
LEFT JOIN category c
ON u.category_id = c.id";
$result = $conn->query($sql);
/****************************category dropdown for form**********************/
$sql_category = "SELECT * FROM category";
$res_category = $conn->query($sql_category);

?>
<!DOCTYPE html>
<html>
<head>
    <style>
        table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://rawgithub.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.css">
   
<script src="table-edits.min.js"></script>
</head>
<body>
<!-----------------------------user listing starts------------------------->
<div id="userListing">
<table style="width:100%;" >
<tr>
  
    <td><a href = "#" id="formButton">Add New</a> | <a href="#" id="bulkDeleteactive" onclick="bulkDelete();">Bulk Delete</a></td>
</tr>
</table>
<table style="width:100%;" >
  <tr>
    <th>Sr No</th>
    <th>Select</th> 
    <th>Name</th>
    <th>Contact No</th>
    <th>Hobby</th> 
    <th>Category</th>
    <th>Profile Pic</th>
    <th>Action</th>
  </tr>
<?php
$i=0;
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
     $i++;
 ?>
  <tr>
    <td><?php echo $i;?></td>
    <td><input type="checkbox" name="bulkCheckbox[]" class="bulkCheck" value="<?php echo $row["id"];?>"></td>
    <td  data-field="name" class="edit_name<?php echo $row["id"];?>"><?php echo $row["name"];?> </td>
    <td data-field="contact_no" class="edit_contact_no<?php echo $row["id"];?>"><?php echo $row["contact_no"];?></td>
    <td data-field="hobby" class="edit_hobby_name<?php echo $row["id"];?>"><?php echo $row["hobby_name"];?></td>
    <td data-field="Category" class="edit_category_name<?php echo $row["id"];?>" ><?php echo $row["category_name"];?></td>
    <td data-field="fileToUpload" class="edit_pic<?php echo $row["id"];?>"><img src="<?php echo $dir.$row["pic"];?>" alt="image" width="40" height="50"></td>
    <td><a class="button button-small edit" id="edit<?php echo $row["id"];?>" onclick="showCategory(<?php echo $row["id"];?>);">Edit</a>
    <a id="save<?php echo $row["id"];?>" class="button button-small save" style="display:none;" >Save</a>|<a href='#'onclick="del(<?php echo $row['id'];?>);">Delete</a></td>
 </tr>
  <?php }
  }
  ?>
</table>
</div>
<!-----------------------------user listing ends------------------------->
<script>
  $('#category').on('change', function() {
    alert("hi");
  alert( this.value );
});
/***********************************inline edit and save**********************/
  $("table tr").editable({
    keyboard: true,
    dblclick: true,
    click: true,
    button: true,
    buttonSelector: ".edit",
    dropdowns: {},
    maintainWidth: true,
    edit: function(values) {
    
    },
    save: function(values) {
    
      
    },
    cancel: function(values) {}
});
$("table tr").editable({
    save: function(values) {
      var id = $(this).data('id');
      $.post('/api/object/' + id, values);
    }
});
/********************** on page load ****************/
$(document).ready(function(){
    $("#userForm").hide();
  
  $("#formButton").click(function() {
            $("#userForm").show();
            $("#userListing").hide();
 });
/*****************************Ajax form submission*********************/
$("#ajaxUserForm").submit(function(event) {
event.preventDefault();
var $form = $(this),
url = $form.attr('action');
var form_data = new FormData(document.getElementById("ajaxUserForm"));

   $.ajax({
           type: "POST",
           url: url,
           data: form_data,
           processData: false,
           contentType: false,
           success: function(data)
           {
            $("#userForm").hide();
            window.location='user_listing_page.php'
           }
         });
  });
});
/**********************cancel button click**************/
function cancel(){
  window.location='user_listing_page.php';
}
/*************Single record deletion code****************************/
function del(id){
  if(confirm("Are you sure you want to delete this Record?")){
            $.ajax({
                type: "GET",
                url: "delete.php",
                data: 'id=' + id,
                success: function(data)
                {
                  window.location='user_listing_page.php'
                 } 
                
            });
        }
}
function showCategory(id){
         $.ajax({
                type: "GET",
                url: "action.php",
                data: 'catid='+id,
                success: function(data)
                {
                 $(".edit_category_name"+id).html(data);
               
                } 
            });  $(".edit_category_name"+id).off("click");
}

/************************Bulk Delete code starts****************************** */
function bulkDelete(){
 check = $(".bulkCheck").is(":checked");
    if(check) {
      if(confirm("Are you sure you want to delete this Record?")){
        var bulkCheckbox = new Array();
        $("input:checked").each(function() {
          bulkCheckbox.push($(this).val());
        });
        
            $.ajax({
                type: "POST",
                url: "delete.php",
                data: 'bulkid=' + bulkCheckbox,
                success: function(data)
                {
                 window.location='user_listing_page.php'
                 } 
                
            });
        }
    } else {
        alert("Checkbox needs to be checked.");
    }
            
}
</script>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>
<!-------------------------User Registration form starts------------------------>
<div id="userForm">
<form action="action.php" method="get" name="ajaxUserForm" id="ajaxUserForm" enctype="multipart/form-data">  
<table style="width:100%;">
  <tr>
    <td>Name</td>
        <td><input type="text" id="name" name="name" value="" required></td>
    </tr>
   <br>
    <tr>
        <td>Contact No</td>
        <td><input type="text" id="contact_no" name="contact_no" value="" onkeypress="return /[0-9,+]/i.test(event.key)" required><br></td>
    </tr>
    <br>
    <tr>
        <td>Hobby</td>
        <td name="hobby">
        <input type="checkbox" id="Programming" name="hobby[]" value="Programming">
        <label for="Programming"> Programming</label>
        <input type="checkbox" id="Games" name="hobby[]" value="Games">
        <label for="Games"> Games</label><br>
        <input type="checkbox" id="Reading" name="hobby[]" value="Reading">
        <label for="Reading">Reading</label>
        <input type="checkbox" id="Photography" name="hobby[]" value="Photography">
        <label for="Photography"> Photography</label><br>
            
    </td>
    </tr>
    <br>
    <tr>
        <td>Category</td>
        <td dropdown>
         
            <select name="category" id="category">
            <option value="">Select Category</option>
        <?php
        
            if ($res_category->num_rows > 0) {
            while($row_category = $res_category->fetch_assoc()) {
        ?>  <option value="<?php echo $row_category['id'];?>"><?php echo $row_category['category_name'];?></option>
           <?php }} ?>
            </select>

        </td>
    </tr>
    <br>
    <tr>
        <td>Profile Pic</td>
        <td>
            <input type="file" name="fileToUpload" id="fileToUpload" value="Upload Image" >
           
    </td>
    </tr>
    <br>
    <tr>
        <td></td>
        <td>
            <button type="submit" value="Submit">Save</button>
            <button type="cancel" value="Cancel" onclick="cancel();">Cancel</button>
    </td>
    </tr>
 
</table>
</form>
</div>
</body>
</html>