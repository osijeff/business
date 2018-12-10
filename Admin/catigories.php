

<!-- ----------------------------------------------------------------------------------- -->
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/business/config/init.php';
if(!is_logged_in()){
  login_error_redirect();
}
include'includes/header.php';
include'includes/navigation.php';
// select statment for the parent catigories list
$sql ="SELECT * FROM catigories WHERE parent = 0";
$result = $conn->query($sql);
$errors = array();
$category = '';
$post_parent = '';
?>

<!--Edit functionality  -->
<?php
//edit catigory
  if(isset($_GET['edit']) && !empty($_GET['edit'])){
    $edit_id = (int)$_GET['edit'];
    $edit_id = sanitizer($edit_id);
    $edit_sql = "SELECT * FROM catigories WHERE id = '$edit_id'";
    $edit_result = $conn->query($edit_sql);
    $edit_catigory = mysqli_fetch_assoc($edit_result);
  }
?>
<!--check if catigory value is set  -->
<?php
$catigory_value='';
$parent_value = 0;
if(isset($_GET['edit'])){
  $catigory_value = $edit_catigory['catigory'];
  $parent_value = $edit_catigory['parent'];
}else{
  if(isset($_POST)){
    $catigory_value = $category;
    $parent_value = $post_parent;
  }
}
?>


<!--  process the form -->
<?php
if(isset($_GET) && !empty($_POST)){
  $post_parent = sanitizer($_POST['parent']);
  $catigory =sanitizer($_POST['catigory']);
  $sqlform = "SELECT * FROM catigories WHERE catigory = '$catigory' AND parent = '$post_parent'";
  if(isset($_GET['edit'])){
    $id = $edit_catigory['id'];
      $sqlform = "SELECT * FROM catigories WHERE catigory = '$catigory' AND parent = '$post_parent' AND id != '$id'";
  }
  $fresult = $conn->query($sqlform);
  $count = mysqli_num_rows($fresult);
  // if catigory is blank
  if($catigory  == ''){
    $errors[] .= 'The catigory Can not be left blank.';
  }
  // if exist in the database
  if($count > 0){
    $errors[] .= $catigory. ' already exists. Please choose a new catigory.';
  }
  // display Errors or update database
  if(!empty($errors)){
    // display Errors
    $display = display_errors($errors);
     // echo $display;
     $display = display_errors($errors);?>
     <script>
       jQuery('document').ready(function(){
         jQuery('#errors').html('<?php echo $display;?>');
       });
     </script>
   <?php }else{
  // update database;
  $updatesql = "INSERT INTO catigories (catigory, parent) VALUES ('$catigory', '$post_parent')";
  if(isset($_GET['edit'])){
    $updatesql = "UPDATE catigories SET catigory = '$catigory', parent = '$post_parent' WHERE id = '$edit_id'";
  }
  $conn->query($updatesql);
  header('Location: catigories.php');
  }
}
?>

<!-- adding functionality to delete and edite -->
<?php
// delete functionality to delete catigory
if(isset($_GET['delete']) && !empty($_GET['delete'])){
  $delete_id = (int)$_GET['delete'];
  $delete_id = sanitizer($delete_id);
  $sql = "SELECT * FROM catigories WHERE id = '$delete_id'";
  $result = $conn->query($sql);
  $delete_catigory = mysqli_fetch_assoc($result);
  if($delete_catigory['parent'] == 0){
    $sql = "DELETE FROM catigories WHERE parent = '$delete_id'";
    $conn->query($sql);
  }
  $delete_sql ="DELETE FROM catigories WHERE id ='$delete_id'";
  $conn->query($delete_sql);
  header('Location: catigories.php');
}
?>


<h2 class="text-center">Catigories</h2>
<hr>
<div class="row">
  <!-- catigory form -->
  <div class="col-md-6">
    <legend><?php echo ((isset($_GET['edit']))?'Edit':'Add A');?> Catigory</legend><hr>
    <div id="errors"></div>
    <form action="catigories.php<?php echo ((isset($_GET['edit']))?'?edit='.$edit_id:'');?>"  method="post" class="form">
      <div class="form-group">
        <lebel for="parent">Parent</lebel>
        <select name="parent" id="parent" class="form-control">
          <option value="0"<?php echo (($parent_value == 0)?'selected="selected"':'');?>>parent</option>
           <?php while($parent = mysqli_fetch_assoc($result)) :?>
             <option value="<?php echo $parent['id'];?>"<?php echo (($parent_value == $parent['id'])?' selected="selected"':'');?>><?php echo $parent['catigory'];?></option>
           <?php endwhile;?>
        </select>
      </div>
      <div class="form-group">
        <label for="catigory">Catigory</label>
        <input type="text" value="<?php echo $catigory_value;?>" class="form-control" name="catigory" id="catigory">
      </div>
      <div class="form-group">
        <input type="submit" name="" value="<?php echo ((isset($_GET['edit']))?'Edit':'Add ');?> Catigory" class="btn btn-success">
      </div>
    </form>
  </div>
  <div class="col-md-6">
    <!-- catigory table  -->
    <table class="table table-bordered">
      <thead>
        <th>catigory</th><th>parent</th><th></th>
      </thead>
      <tbody>
        <!-- create an array for the parent list and use while loop to print them -->
        <?php
        // select statment for the parent catigories list
        $sql ="SELECT * FROM catigories WHERE parent = 0";
        $result = $conn->query($sql);

         while ($parent = mysqli_fetch_assoc($result)) :
          // select statment for the child list
          $parent_id = (int) $parent['id'];
          $sql2 = "SELECT * FROM catigories WHERE parent = '$parent_id'";
          $cresult = $conn->query($sql2);
          ?>
        <tr class="bg-warning">
          <td><?php  echo $parent['catigory'];?></td>
          <td>parent</td>
          <td><a href="catigories.php?edit=<?php  echo $parent['id'];?>" class="btn btn-xs btn-primary"><i  class="fa">&#xf044;</i></a>
          <a href="catigories.php?delete=<?php  echo $parent['id'];?>" class="btn btn-xs btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></td>
          </tr>
          <!-- create an array for the child list and use while loop to print them and its nested inside the parent -->
          <?php while($child = mysqli_fetch_assoc($cresult)): ?>
            <tr class="bg-info">
              <td><?php  echo $child['catigory'];?></td>
              <td><?php  echo $parent['catigory'];?></td>
              <td><a href="catigories.php?edit=<?php  echo $child['id'];?>" class="btn btn-xs btn-primary"><i class="fa">&#xf044;</i></a>
              <a href="catigories.php?delete=<?php  echo $child['id'];?>" class="btn btn-xs btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></td>
          <?php endwhile;?>
          <?php endwhile;?>

        </tr>
      </tbody>

    </table>
  </div>

</div>
<?php
include'includes/footer.php';
?>
