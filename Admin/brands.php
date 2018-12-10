<?php
require_once '../config/init.php';
if(!is_logged_in()){
  login_error_redirect();
}
include 'includes/header.php';
include 'includes/navigation.php';
?>
<!--  get brands from database-->
<?php
 $sql = "SELECT * FROM brand ORDER BY brand";
 $results = $conn->query($sql);
 ?>
 <?php
 $errors = array();
 //delete brands
 if( isset($_GET['delete']) && !empty($_GET['delete'])){
   $delete_id = (int)$_GET['delete'];
   $delete_id = sanitizer($delete_id);
   $sql = "DELETE FROM brand WHERE id = '$delete_id'";
   $conn->query($sql);
   header('location: brands.php');
 }
 //edit brand name
 if( isset($_GET['edit']) && !empty($_GET['edit'])){
      $edit_id = (int) $_GET['edit'];
      $edit_id = sanitizer($edit_id);
       $sql2 = "SELECT * FROM  brand WHERE id =' $edit_id'";
       $edit_result = $conn->query($sql2);
       $ebrand = mysqli_fetch_assoc( $edit_result);
 }
 //if add forms is submitted
if(isset($_POST['add_submit'])){
  $brand = sanitizer($_POST['brand']);
  //check if brand is blank
   if($_POST['brand'] === ''){
     $errors[] .= 'You Must Enter a Brand';
   }
   //check if bramd exist in database
   $sql ="SELECT * FROM brand WHERE brand = '$brand'";
   if(isset($_GET['edit'])){
     $sql = "SELECT * FROM brand WHERE = '$brand' AND is != '$edit_id'";
   }
   $result = $conn->query($sql);
   $count = mysqli_num_rows($result);
    if($count > 0){
      $errors[] .= $brand.' brand already exits Please Choose Another Brand name...';
    }
   // display errors
   // if note empty
   if(!empty($errors)){
     echo display_errors($errors);
   }  else {
     //add brand to database
     $sql ="INSERT INTO brand (brand) VALUES ('$brand')";
     if(isset($_GET['edit'])){
       $sql = "UPDATE brand SET brand = '$brand' WHERE id = '$edit_id'";
     }
     $conn->query($sql);
     header('location: brands.php');
    }
}
 ?>

 <?php
  //get the edit brand value to appear on the input box when clicked on edit icon
   $brand_value = '';
  if(isset($_GET['edit'])){
   $brand_value = $ebrand['brand'];
 } else {
   if(isset($_POST['brand'])){
     $brand_value = sanitizer( $_POST['brand']);
   }
 }
 ?>
 <style>
   .brandform{
     /* width: 70px; */
    background-color: blue;
    height: 5em;
    padding-top: 25px;
    color: white;

   }
 </style>
<h2 style="text-align: center; margin-top:.5em;">BRANDS</h2>
<hr>
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-2">.col-sm-4</div>
    <div class="col-sm-8">
      <form class="form-inline" action="brands.php<?php echo ((isset($_GET['edit']))?'?edit=' .$edit_id: '');?>" method="post">
        <div class="form-group">
          <label for="brand" ><?php echo((isset($_GET['edit']))?'Edit':'Add A ');?> Brand </label>
           <input type="text" name="brand" id="brand" class="form-control" placeholder="Insert brand"
          value="<?php echo $brand_value;?>">
          <?php if(isset($_GET['edit'])):?>
          <a href="brands.php" class="btn btn-warning"> Cancel</a>
          <?php endif;?>
          <input type="submit" name="add_submit" value="<?php echo((isset($_GET['edit']))?'Edit':'Add A');?> Brand" class="btn btn-success">
        </div>
      </form>
    </div>
    <div class="col-sm-2" style="background-color:lavender;">.col-sm-2</div>
  </div>
</div>

  <hr>
<table class="table table-bordered table-striped table-auto"  style="width:80%; margin:0 auto; text-align:center; background-color:black; color:white">
  <thead class="thead-dark">
    <th scope="col">Edit</th><th scope="col">Brands</th><th scope="col">Delete</th>
  </thead>
  <tbody>
    <?php while($brand = mysqli_fetch_assoc($results)) :?>
    <tr>
      <td><a href="brands.php?edit=<?php echo $brand['id'];?>"><i style="font-size:24px" class="fa">&#xf044;</i></a></td>
      <td><?php echo $brand ['brand'];?></td>
      <td><a href="brands.php?delete=<?php echo $brand['id'];?>"><i style="font-size:24px" class="fa fa-trash" aria-hidden="true"></i></a></td></td>
    </tr>
  <?php endwhile;?>
  </tbody>
</table>









<?php
include 'includes/footer.php';
 ?>
