<?php
function display_errors($errors){
  $display = '<ul class="bg-danger">';
  foreach($errors as $error){
    $display .= '<li class="text-warning"> '.$error.'</li>';
  }
  $display .= '</ul>';
  return $display;
}
 ?>
<?php
function sanitizer($dirty){
  return htmlentities($dirty, ENT_QUOTES, "UTF-8");
}
?>
<?php
function money($number){
return '$'.number_format($number,2);
}
?>
<?php
  function login($user_id){
   $_SESSION['SBUser'] = $user_id;
   global $conn;
    $date = date("Y-m-d H:i:s");//date format
   $conn->query("UPDATE users SET last_login = '$date' WHERE id = '$user_id'");
   $_SESSION['sucess_flash'] = 'You are now logged in';
    header('Location:index.php');
  }
 function is_logged_in(){
   if(isset($_SESSION['SBUser']) && $_SESSION['SBUser'] > 0){
     return true;
   }
   return false;
 }
 function login_error_redirect($url = 'login.php'){
  $_SESSION['error_flash'] = ' You must be logged in to access that page';
   header('Location: '.$url);
}
function permission_error_redirect($url = 'login.php'){
    $_SESSION['error_flash'] = 'ACCESS DENIELD!';
   header('Location: '.$url);
 }
 function has_permission($permission = 'admin'){
   global $user_data;
   $permissions = explode(',', $user_data['permissions']);
  if(in_array($permission,$permissions, true )){
     return true;
   }
    return false;
 }
 function pretty_date($date){
   return date("M d, Y h:i A",strtotime($date));
 }
 function get_catigory($child_id){
   global $conn;
   $id = sanitizer($child_id);
   $sql = "SELECT p.id AS 'pid',  p.catigory AS 'parent', c.id AS 'cid', c.catigory AS 'child'
    FROM catigories c
    INNER JOIN catigories p
    ON c.parent = p.id
    WHERE c.id = '$id'";
    $query = $conn->query($sql);
    $catigory = mysqli_fetch_assoc($query);
    return $catigory;
 }

 function sizesToArray($string){
   //call on explode method to separate the comma
   $sizesArray = explode(',', $string);
   // create an ampty array
   $returnArray = array();

   // use foreach loop to loop through the size
   foreach ($sizesArray as $size) {
      // call on explode method to saparate the colon from the size
     $s = explode(':',$size);
     $returnArray[] = array('size' =>$s[0], 'quantity' => $s[1]);
   }
   return $returnArray;
 }

 function sizesToString($sizes){
   $sizeString = '';
   foreach ($sizes as $size) {
     $sizeString .= $size['size'].':'.$size['quantity'].',';
   }
   //$strimmed = rtrim($sizeString, ',');
   $trimmed = rtrim($sizeString, ",");
   return $trimmed;
 }
?>
