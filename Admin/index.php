<?php
require_once '../config/init.php';
if(!is_logged_in()){
    header('Location: login.php');

}

//var_dump(isset($user_data));

include 'includes/header.php';
include 'includes/navigation.php';
//echo $_SESSION['SBUser']; // meant for display user id
//echo $user_data['first'];
//echo $user_data['last'];



?>
<h2>Administrator Home</h2>
<?php
include 'includes/footer.php';
 ?>
