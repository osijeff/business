<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/business/config/init.php';
if(!is_logged_in()){
  login_error_redirect();
}
include 'includes/header.php';
$hashed = $user_data['password'];
$old_password = ((isset($_POST['old_password']))?sanitizer($_POST['old_password']):'');
$old_password = trim($old_password);
$password = ((isset($_POST['password']))?sanitizer($_POST['password']):'');
$password = trim($password);
$confirm = ((isset($_POST['confirm']))?sanitizer($_POST['confirm']):'');
$confirm = trim($confirm);
$new_hashed = password_hash($password, PASSWORD_DEFAULT);
$user_id = $user_data['id'];
$errors = array();
?>
<style>
body{
  background-image:url("/business/images/headerlogo/background.png");
  background-size: 100vw 100vh;
  background-attachment: fixed;
}
#change_password-form{
  background:#004D40;
  color: white;
  padding: 2em;
  margin: 0 auto;
  width: 50%;
  height: 80%;
  border:2px solid #000;
  border-radius: 10px;
  box-shadow: 5px 5px 10px grey;
  margin-bottom: 15em;
}
</style>
<div id="change_password-form">
  <div>

    <?php
    if($_POST){
      //form validation
      if (empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])){
          $errors[] = 'You must fill out all fields.';
      }

      //password validation
      // password is less than 6 charsacters
      if (strlen($password) < 6) {
          $errors[] = 'password must be at least 6 character';
        }

  //if new password matches confirm
  if($password != $confirm){
    $errors[] = 'Oops! The new password and confirm new password does not match';
  }
        // verify password
      if(!password_verify($old_password, $hashed)) {
            $errors[] = 'Oops! your old password does not match our records, please try again';
      }
      //check for errors
      if (!empty($errors)) {
      echo display_errors($errors);
    }else {
          //change password
          $conn->query("UPDATE users SET password = '$new_hashed' WHERE id = '$user_id'");
          $_SESSION['sucess_flash'] = 'Your password has been update';
          header('Location: index.php');
      }
    }

    ?>
  </div>
  <h2 class="text-center">Change Password</h2><hr>
  <form action="change_password.php" method="post">
    <div class="form-group">
      <label for="old_password">Old Password:</label>
      <input type="password" name="old_password" id="email" class="form-control" value="<?php echo $old_password;?>">
    </div>
    <div class="form-group">
      <label for="password">New Password:</label>
      <input type="password" name="password" id="password" class="form-control" value="<?php echo $old_password;?>">
    </div>
    <div class="form-group">
      <label for="confirm">Confirm new Password:</label>
      <input type="password" name="confirm" id="confirm" class="form-control" value="<?php echo $confirm;?>">
    </div>
    <div class="form-group">
      <a href="index.php" class="btn btn-default">Cancel</a>
      <input type="submit" name="" value="login" class="btn btn-primary">
    </div>
  </form>
  <p class="text-right"><a href="/business/index.php" alt="home">visit site</a> </p>
  </div>


<?php
 include 'includes/footer.php';
?>
