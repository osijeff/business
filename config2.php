<?php
define('BASEURL', $_SERVER['DOCUMENT_ROOT'].'/business/');
define('CART_COOKIE','SBwi72UCKlwiqzz2');
define('CART_COOKIE_EXPIRE',time() + (86400 *30));
define('TAXRATE',0.087); //sales tax rate set to 0 if you are not arn't charging TAX
define('CURRENCY','usd');
define('CHECKOUTMODE', 'TEST');//change TEST to live when you are ready to go live

if(CHECKOUTMODE === 'TEST'){
  define('STRIPE_PRIVATE', 'sk_test_uZoidwuokjKDZOXAGMkhdqDf');
  define('STRIPE_PUBLIC', 'pk_test_ZcGXNRi0VKhsbPZmUueTGOwO');
}
if(CHECKOUTMODE === 'LIVE'){
  define('STRIPE_PRIVATE', '');
  define('STRIPE_PUBLIC', '');
}
?>
