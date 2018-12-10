<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/business/config/init.php';
include 'includes/header.php';
$email = ((isset($_POST['email']))?sanitizer($_POST['email']):'');
$email = trim($email);
$password = ((isset($_POST['password']))?sanitizer($_POST['password']):'');
$password = trim($password);
//$hashed = password_hash($password, PASSWORD_DEFAULT);
$errors = array();
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
  margin: 0 auto;
  width: 50%;
  height: 80%;
  border:2px solid #000;
  border-radius: 10px;
  box-shadow: 5px 5px 10px grey;
  margin-bottom: 15em;
}
</style>
<div id="login-form">
  <div>

    <?php
    if($_POST){
      //form validation
      if (empty($_POST['email']) || empty($_POST['password'])){
          $errors[] = 'You must enter email and password.';
      }
      // validate Email
      if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $errors[] = 'You must enter valide email.';
      };
      //password validation
      // password is less than 6 charsacters
      if (strlen($password) < 6) {
          $errors[] = 'password must be at least 6 character';
        }

      //check if email exist in database
      $query = $conn->query("SELECT * FROM users WHERE email = '$email'");
      $user = mysqli_fetch_assoc($query);
      $user_count= mysqli_num_rows($query);
      if ($user_count < 1) {
      $errors[] = 'Oops! your email is incorrect OR does not exist in our database.';
      }
        // verify password
      if(!password_verify($password,$user['password'])) {
            $errors[] = 'Oops! the password does not match our records, please try again';
      }
      //check for errors
      if (!empty($errors)) {
      echo display_errors($errors);
    }else {
      //log user in
        //echo'user login';

        $user_id = $user['id'];
        login($user_id);
      }
    }

    ?>
  </div>
  <h2 class="text-center">Login</h2><hr>
  <form action="login.php" method="post">
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" name="email" id="email" class="form-control" value="<?php echo $email;?>">
    </div>
    <div class="form-group">
      <label for="password">password:</label>
      <input type="password" name="password" id="password" class="form-control" value="<?php echo $password;?>">
    </div>
    <div class="form-group">
      <input type="submit" name="" value="login" class="btn btn-primary">
    </div>
  </form>
  <p class="text-right"><a href="/business/index.php" alt="home">visit site</a> </p>
</div>

<?php
 include 'includes/footer.php';
?>
