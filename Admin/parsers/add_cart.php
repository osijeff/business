<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/business/config/init.php';

$product = sanitizer($_POST['product_id']);
$size = sanitizer($_POST['size']);
$available = sanitizer($_POST['available']);
$quantity = sanitizer($_POST['quantity']);
$item = array();
$item[] = array(
  'id' => $product,
  'size' => $size,
  'quantity' => $quantity,
);

//$domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;
$query = $conn->query("SELECT * FROM products WHERE id = '{$product}'");
$product_query = mysqli_fetch_assoc($query);

$_SESSION['sucess_flash'] = $product_query['title']. ' was added to your cart.';




//check to see if the cart cookie exists
if($cart_id !=''){
  $cartQ = $conn->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
  $cart = mysqli_fetch_assoc($cartQ);
  $previous_items = json_decode($cart['items'],true);
  $item_match = 0;
  $new_items = array();
  foreach($previous_items as $prev_item){
    if($item[0]['id'] == $prev_item['id'] && $item[0]['size'] == $prev_item['size']){
      $prev_item['quantity'] = $prev_item['quantity'] + $item[0]['quantity'];
       if($prev_item['quantity'] > $available){
        $prev_item['quantity'] = $available;
      }
      $item_match = 1;
    }
      $new_items[] = $prev_item;
  }
    if($item_match != 1){
      $new_items = array_merge($item,$previous_items);
    }
    $items_json = json_encode($new_items);
    $cart_expire = date("Y-m-d H:i:s",strtotime("+30 days"));
    $conn->query("UPDATE cart SET items = '{$items_json}', expire_date = '{$cart_expire}' WHERE id = '{$cart_id}'");
    setcookie(CART_COOKIE,' ',0,"/",false);
      if($_SERVER['HTTP_HOST'] === "localhost"){
          setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,"/",false);
        }else{
         setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE);}

}else{// here if it is empty add to database and set cookie
  $items_json = json_encode($item);
  $cart_expire = date("Y-m-d H:i:s",strtotime("+30 days"));
  $conn->query("INSERT INTO cart (items,expire_date) VALUES('{$items_json}','{$cart_expire}')");
    $cart_id = $conn->insert_id;
    //setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain,false);

    if($_SERVER['HTTP_HOST'] === "localhost"){
    //setcookie('name', 'value',false,"/",false);
    setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,"/",false);
  }else{
         setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE);}





}



?>
