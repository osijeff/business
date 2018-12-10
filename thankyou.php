
<?php
require_once 'config/init.php';

// Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey(STRIPE_PRIVATE);

// Token is created using Checkout or Elements!
// Get the payment token ID submitted by the form:
$token = $_POST['stripeToken'];
// Get the rest of the post data
$full_name = sanitizer($_POST['full_name']);
$email = sanitizer($_POST['email']);
$street = sanitizer($_POST['street']);
$street2 = sanitizer($_POST['street2']);
$city = sanitizer($_POST['city']);
$state = sanitizer($_POST['state']);
$zip_code = sanitizer($_POST['zip_code']);
$country = sanitizer($_POST['country']);
$tax = sanitizer($_POST['tax']);
$sub_total = sanitizer($_POST['sub_total']);
$grand_total = sanitizer($_POST['grand_total']);
$cart_id = sanitizer($_POST['cart_id']);
$description = sanitizer($_POST['description']);
$charge_amount = number_format($grand_total,2) * 100;
$metadata = array(
  "cart_id" => $cart_id,
  "tax" => $tax,
  "sub_total" => $sub_total,
);


$charge = \Stripe\Charge::create([
    'amount' => $charge_amount,
    'currency' => 'CURRENCY',
    'description' => '$description',
    'source' => $token,
    'receipt_email' => $email,  // not working on text modal_errors
    'metadata' => $metadata,
]
);


//adjust inventory
$itemQ = $conn->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
$iresults = mysqli_fetch_assoc($itemQ);
$items = json_decode($iresults['items'],true);
foreach ($items as $item) {
  $newSizes = array();
  $item_id = $item['id'];
  $productQ = $conn->query("SELECT sizes FROM products WHERE id = '{$item_id}'");
  $product = mysqli_fetch_assoc($productQ);
  $sizes = sizesToArray($product['sizes']);
  foreach ($sizes as $size) {
    if($size['size'] == $item['size']){
      $q = $size['quantity'] - $item['quantity'];
    $newSizes[]  = array('size' =>$size['size'] ,'quantity' =>$q );}else {
      $newSizes[]  = array('size' =>$size['size'] , 'quantity' =>$size['quantity']);
    }
  }
  $sizeString = sizesToString($newSizes);
  $conn->query("UPDATE products SET sizes = '{$sizeString}' WHERE id = '{$item_id}'");
}
//update cart
$conn->query("UPDATE cart SET paid = 1 WHERE id ='{$cart_id}'");
$conn->query("INSERT INTO transactions
(charge_id,cart_id,full_name,email,street,street2,city,state,zip_code,country,sub_total,tax,grand_total,description,txn_type)
VALUES('$charge->id','$cart_id','$full_name','$email','$street','$street2','$city','$state','$zip_code','$country','$sub_total','$tax','$grand_total','$description','$charge->object')");
$domain = (($_SERVER['HTTP_HOST'] != 'localhost' )?','.$_SERVER['HTTP_HOST']:false);
setcookie(CART_COOKIE,' ',1,"/",false);
include 'includes/head.php';
include 'includes/navigation.php';
include 'includes/headerpartial.php';
?>
<h1 class="text-center text-success">Thank you!</h1>
<p> your card has been successfully charged <?php echo money($grand_total);?>. your have been emailed a reciept. please
check your spam folder if it is not in you inbox. additionally you can print this page as a reciept.</p>
<p> Your reciept number is: <strong><?php echo $cart_id;?></strong></p>
<p> Your order will be shipped to the addresss below.</p>
<address>
  <?php echo $full_name;?> <br>
  <?php echo $street;?> <br>
  <?=(($street2 != '')?$street2.'<br>':'');?>
  <?php echo $city. ', '.$state.','.$zip_code;?> <br>
  <?php echo $country;?> <br>
</address>


<?php
include 'includes/footer.php';

?>
