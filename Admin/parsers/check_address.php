<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/business/config/init.php';
$name = sanitizer($_POST['full_name']);
$email = sanitizer($_POST['email']);
$street = sanitizer($_POST['street']);
$street2= sanitizer($_POST['street2']);
$city = sanitizer($_POST['city']);
$state= sanitizer($_POST['state']);
$zip_code = sanitizer($_POST['zip_code']);
$country = sanitizer($_POST['country']);
$errors = array();
$required = array(
  'full_name' => 'Full Name',
  'email' => 'Email',
  'street' => 'Street',
  'city' => 'City',
  'state' => 'State',
  'zip_code' => 'Zip Code',
  'country' => 'Country',
);
//check if required field are filled out
foreach ($required as $f => $d) {
if(empty($_POST[$f]) || $_POST[$f] == ''){
  $errors[] = $d.' is required.';
  }
}
//check if valid email address
if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
  $errors[] = 'please enter a valid email.';
}

if(!empty($errors)){
  echo display_errors($errors);
}else{
  echo true;
}
