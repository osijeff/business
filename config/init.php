<?php


// connection credentials
$conn = mysqli_connect('127.0.0.1', 'root', '', 'c&m boutigue');



//check if connected
if (mysqli_connect_errno()){
  echo "you couldnt connet ".mysqli_connect_error();
  die();
}
//starting session
session_start();
?>

<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/business/config2.php';
require_once BASEURL.'helpers/helpers.php';
require  BASEURL.'vendor/autoload.php';
?>
<?php
$cart_id = '';
   if(isset($_COOKIE[CART_COOKIE])){
     $cart_id = sanitizer($_COOKIE[CART_COOKIE]);
  }
  if(isset($_SESSION['SBUser'])){
    $user_id = $_SESSION['SBUser'];
    $query = $conn->query("SELECT * FROM users WHERE id = '$user_id'");
    $user_data = mysqli_fetch_assoc($query);
    $fn = explode(' ',$user_data['full_name']);
    $user_data['first'] = $fn[0];
    $user_data['last'] = $fn[1];
  }
   if(isset($_SESSION['sucess_flash'])){
  echo '<div class="bg-success"><p class="text-warning text-center">'.$_SESSION['sucess_flash'].'</P></div>';
  unset($_SESSION['sucess_flash']);
  }

   if(isset($_SESSION['error_flash'])){
    echo '<div class="bg-danger"><p class="text-warning text-center">'.$_SESSION['error_flash'].'</P></DIV>';
    unset($_SESSION['error_flash']);
 }
 //use this session destroy to remove the previous session

 //SESSION_destroy();
?>
