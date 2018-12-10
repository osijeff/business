<?php
require_once '../config/init.php';
if(!is_logged_in()){
  login_error_redirect();
}

//var_dump(isset($user_data));
  if(!has_permission('admin')){
   permission_error_redirect('index.php');
  }
include 'includes/header.php';
include 'includes/navigation.php';
?>
<style>
body{
  background-image:url("/business/images/headerlogo/background.png");
  background-size: 100vw 100vh;
  background-attachment: fixed;
}
#login-form{
  background:#004D40;
  color: white;
  padding: 2em;
  margin: 4em auto;
  width: 50%;
  height: 80%;
  border:2px solid #000;
  border-radius: 10px;
  box-shadow: 5px 5px 10px grey;
  margin-bottom: 15em;
}
</style>
<?php
if(isset ($_GET['delete'])){
  $delete_id = sanitizer($_GET['delete']);
  $conn->query("DELETE FROM users WHERE id = '$delete_id'");
  $_SESSION['sucess_flash'] = 'User has been deleted';
  header('Location: users.php');
}
if(isset($_GET['add'])){
  $name = ((isset($_POST['name']))?sanitizer($_POST['name']):'');
  $email = ((isset($_POST['email']))?sanitizer($_POST['email']):'');
  $password = ((isset($_POST['password']))?sanitizer($_POST['password']):'');
  $confirm = ((isset($_POST['confirm']))?sanitizer($_POST['confirm']):'');
  $permissions = ((isset($_POST['permissions']))?sanitizer($_POST['permissions']):'');
  $errors = array();
  if($_POST){
    $emailQuery = $conn->query("SELECT * FROM users WHERE email = '$email'");
    $emailCount = mysqli_num_rows($emailQuery);
    if($emailCount != 0){
      $errors[] = 'That email already exists in our database';
    }
    $required = array('name', 'email', 'password','confirm', 'permissions');
    foreach ($required as $f) {
      if(empty($_POST[$f])){
        $errors[]='You must fill out all fields';
        break;
      }
    }
    if(strlen($password) < 8){
      $errors[]= ' Oops! Your password must be at least 8 characters';
    }
    if($password != $confirm){
      $errors[]= 'Your passwords do not match.';
    }
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
      $errors[]='You must provide a valid email';
    }
    if(!empty($errors)){
      echo display_errors($errors);

    }else{
      //add to database
      $hashed = password_hash($password,PASSWORD_DEFAULT);
      $conn->query("INSERT INTO users (full_name,email,password,permissions) VALUES ('$name','$email','$hashed','$permissions')");
      $_SESSION['sucess_flash']='User has been added';
      header('Location: users.php');
    }
  }
?>
<div id="login-form">
<h2 class= "text-center">Add A New user</h2><hr>
<form action="users.php?add=1" method="POST">
  <div class="form-group">
    <label for="name">Full Name:</label>
    <input type="text" name="name" id="name" class="form-control"value="<?php echo $name;?>">
  </div>
  <div class="form-group">
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" class="form-control"value="<?php echo $email;?>">
  </div>
  <div class="form-group">
    <label for="password">password:</label>
    <input type="password" name="password" id="password" class="form-control"value="<?php echo $password;?>">
    </div>
    <div class="form-group">
      <label for="confirm">Confirm Password:</label>
      <input type="password" name="confirm" id="confirm" class="form-control"value="<?php echo $confirm;?>">
    </div>
    <div class="form-group">
    <label for="name">Permissions:</label>
    <select class="form-control" name="permissions">
      <option value=""<?php echo (($permissions == '')?' selected':'');?>></option>
      <option value="editor"><?php echo (($permissions == 'editor')?' selected': '');?>Editor</option>
      <option value="admin,editor"<?php echo (($permissions == 'admin,editor')?'selected':'');?>>Admin</option>
    </select>
  </div>
  <div class="form-group">
    <a href="users.php" class="btn btn-default">Cancel</a>
    <input type="submit" name="" value="Add User" class="btn btn-primary">
  </div>
</form>
</div>
<?php
}else{
//echo $_SESSION['SBUser']; // meant for display user id
//echo $user_data['first'];
//echo $user_data['last'];
$userQuery = $conn->query("SELECT * FROM users ORDER BY full_name");


?>
<div style="background-color:#004D40; color:white; text-align:center">
<h2>Users</h2>
 <a href="users.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add New User</a>
<br><hr>
<table class="table table-bordered table-striped table-condensed">
  <thead><th></th><th></th><th>Name</th><th>Email</th><th>Join date</th><th>Last Login</th><th>Permissions</th></thead>
  <tbody>
    <?php while ($user = mysqli_fetch_assoc($userQuery)): ?>
    <tr>
      <td>
        <?php if ($user['id'] != $user_data['id']):?>
          <a  href="users.php?delete=<?php echo $user['id'];?>" <i class="fa fa-trash" aria-hidden="true"> Delete</i></a>
        <?php endif?>
      </td>
      <td>
        <?php if ($user['id'] != $user_data['id']):?>
        <a href="users.php?edit=<?php echo $user['id'];?>"><i class="fa"> &#xf044; Edit</i></a>
        <?php endif?>
    </td>
      <td><?php echo $user['full_name'];?></td>
      <td><?php echo $user['email'];?></td>
      <td><?php echo pretty_date($user['join_date']); ?></td>
      <td><?php echo (($user['last_login']=='0000-00-00 00:00:00')?'Never login':pretty_date($user['last_login'])) ;?></td>
      <td><?php echo $user['permissions'];?><td>
    </tr>

  <?php endwhile?>
  </tbody>

</table>
<?php }; ?>
</div>
<?php
include 'includes/footer.php';
 ?>
