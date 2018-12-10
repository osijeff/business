<?php

require_once 'config/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
include 'includes/headerpartial.php';
if($cart_id !=''){
  $cartQ = $conn->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
  $result = mysqli_fetch_assoc($cartQ);
  $items = json_decode($result['items'],true);
  $i = 1;
  $sub_total = 0;
  $item_count=0;
}
?>


<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
    <h4 class="text-uppercase text-center">My shopping Cart</h4>
    <hr>
    <?php
    if($cart_id == ''):?>
    <section class="bg-denger">
      <p class="text-center text-danger"> Your shopping Cart is empty</p>
    </section>

    <?php else:?>
      <table class="table table-bordered table-condensed table-striped">
        <thead class="success">
          <th>#</th>
          <th>Item</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Size</th>
          <th>Sub Total</th>
        </thead>
        <tbody>
          <?php

          foreach((array)$items as $item){
            $product_id = $item['id'];
            $productQ = $conn->query("SELECT * FROM products WHERE id = '{$product_id}'");
            $product = mysqli_fetch_assoc($productQ);
            $sArray = explode(',',$product['sizes']);
            foreach ($sArray as $sizeString){
              $s = explode(':',$sizeString);
              if($s[0] == $item['size']){
                $available = $s[1];
              }
            }
          
          ?>
          <tr>
            <td><?php echo $i;?></td>
            <td><?php echo $product['title'];?></td>
            <td><?php echo money($product['price']);?></td>
            <td>
              <button class="btn btn-xs btn-default" onclick="update_cart('removeone','<?php echo $product['id'];?>','<?php echo $item['size'];?>');">-</button>
              <?php echo $item['quantity'];?>
              <?php if($item['quantity'] < $available): ?>
                <button class="btn btn-xs btn-default" onclick="update_cart('addone','<?php echo $product['id'];?>','<?php echo $item['size'];?>');">+</button>
              <?php else:?>
                <span class="text-danger">Max</span>
              <?php endif;?>
            </td>
            <td><?php echo $item['size'];?></td>
            <td><?php echo money($item['quantity'] * $product['price']);?></td>
          </tr>
          <?php
          $i++;
          $item_count += $item['quantity'];
          $sub_total += ($product['price'] * $item['quantity']);

         }
        $tax = TAXRATE * $sub_total;
        $tax = number_format($tax,2);
        $grand_total = $tax + $sub_total;
           ?>

        </tbody>
      </table>
      <table class="table table-bordered table-condensed text-right">
        <legend>Totals</legend>
         <thead class="totals-table-header">
           <th>Total Items</th>
           <th>Sub Total</th>
           <th>Tax</th>
           <th>Grand Total</th>
         </thead>
         <tbody>
           <tr>
             <td><?php echo $item_count;?></td>
             <td><?php echo money($sub_total);?></td>
             <td><?php echo money($tax);?></td>
             <td class="bg-success"><?php echo money($grand_total)?></td>
           </tr>
         </tbody>
      </table>
      <!-- Button trigger modal. check out button -->
<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#checkoutModal">
<span><i class="fa fa-shopping-cart"></i></span> Check Out >>
</button>

<!-- Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog " aria-labelledby="checkoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="checkoutModalLabel">Shipping Address</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-left" >
      <form class="" action="thankyou.php" method="post" id="payment-form">
        <span class="bg-danger"id="payment-errors"></span>
        <input type="hidden" name="tax" value="<?php echo $tax;?>">
        <input type="hidden" name="sub_total" value="<?php echo $sub_total;?>">
        <input type="hidden" name="grand_total" value="<?php echo $grand_total;?>">
        <input type="hidden" name="cart_id" value="<?php echo $cart_id;?>">
        <input type="hidden" name="description" value="<?php echo $item_count.'item'.(($item_count>1)?'s':'').'from jeff Boutigue.';?>">
        <div id="step1" style="display: block;">
          <div class="row">
           <div class="form-group col-md-6">
            <label for="full_name">Full Name:</label>
            <input type="text" name="full_name" value="" id="full_name" class="form-control">
           </div>
           <div class="form-group col-md-6">
            <label for="Email">Email:</label>
            <input type="email" name="email" value="" id="email" class="form-control">
             <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
          </div>
          </div>
          <div class="row">
          <div class="form-group col-md-6">
            <label for="street">Stree Address:</label>
            <input type="text" name="street" value="" id="street" class="form-control" data-stripe="address_line1">
          </div>
          <div class="form-group col-md-6">
            <label for="street2">Street Address 2:</label>
            <input type="text" name="street2" value="" id="street2" class="form-control" data-stripe="address_line2">
          </div>
          </div>
          <div class="row">
          <div class="form-group col-md-6">
            <label for="city">City:</label>
            <input type="text" name="city" value="" id="city" class="form-control" data-stripe="address_city">
          </div>
          <div class="form-group col-md-6">
            <label for="state">State:</label>
            <input type="text" name="state" value="" id="state"  class="form-control" data-stripe="address_state">
          </div>
          </div>
          <div class="row">
          <div class="form-group col-md-6">
            <label for="zip_code">Zip Code:</label>
            <input type="text" name="zip_code" value="" id="zip_code" class="form-control" data-stripe="address_zip">
          </div>
          <div class="form-group col-md-6">
            <label for="country">Country:</label>
            <input type="text" name="country" value="" id="country" class="form-control" data-stripe="address_country">
          </div>
          </div>
        </div>
        <!-- form for credit card details -->
        <div id="step2" style="display:none;">
          <div class="row">

          <div class="form-group col-md-3">
            <label for="name">Name On card:</label>
            <input type="text"  id="name" class="form-control" data-stripe="name">
          </div>

          <div class="form-group col-md-3">
            <label for="nmuber">Card Number:</label>
            <input type="text"  id="number" class="form-control" data-stripe="number">
          </div>
          <div class="form-group col-md-3">
            <label for="cvc">CVC:</label>
            <input type="text"  id="cvc" class="form-control" data-stripe="cvc">
          </div>
          </div>
          <div class="row">
          <div class="form-group col-md-3">
            <label for="exp-month">Expire Month:</label>
            <select  id="exp-month" class="form-control" data-stripe="exp_month">
              <option value=""></option>
              <?php for($i=1; $i < 13; $i++):?>
                <option value="<?php echo $i;?>"><?php echo $i;?></option>
              <?php endfor;?>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="exp-year">Expire Year:</label>
            <select   id="exp-year" class="form-control" data-stripe="exp_year">
              <option value=""></option>
              <?php $yr = date("Y");?>
              <?php for($i=0; $i<11; $i++):?>
                <option value="<?php echo $yr + $i;?>"><?php echo $yr + $i;?></option>
              <?php endfor;?>
            </select>
          </div>
        </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button"   class="btn btn-primary" onclick="check_address();" id="next_button">Next >></button>
        <button type="button" class="btn btn-primary" onclick="back_address();" id="back_button" style="display:none"> << Back</button>
        <button type="submit" class="btn btn-primary" id="checkout_button" style="display:none">Check Out</button>  </form>
      </div>
    </div>
  </div>
</div>
    <?php endif;?>
  </div>


</div>
</div>

<script>
function back_address(){
  jQuery('#payment-errors').html("");
  jQuery('#step1').css("display","block");
  jQuery('#step2').css("display","none");
  jQuery('#next_button').css("display","inline");
  jQuery('#back_button').css("display","none");
  jQuery('#checkout_button').css("display","none");
  jQuery('#checkoutModalLabel').html("Shipping address")
}
  function check_address(){
    var data = {
      'full_name':jQuery('#full_name').val(),
      'email' : jQuery('#email').val(),
      'street' : jQuery('#street').val(),
      'street2' : jQuery('#street2').val(),
      'city' : jQuery('#city').val(),
      'state' : jQuery('#state').val(),
      'country' : jQuery('#country').val(),
      'zip_code' : jQuery('#zip_code').val(),
    };
    jQuery.ajax({
      url : '/business/admin/parsers/check_address.php',
      method : 'POST',
      data : data,
      success : function(resp){
        // if(resp == 1){
        //   jQuery('#payment-errors').html(resp);
        // }
        if(resp == true){
          //alert('pass');
        jQuery('#payment-errors').html("");
        jQuery('#step1').css("display","none");
        jQuery('#step2').css("display","block");
        jQuery('#next_button').css("display","none");
        jQuery('#back_button').css("display","inline-block");
        jQuery('#checkout_button').css("display","inline-block");
        jQuery('#checkoutModalLabel').html("Enter Your Card Details");

        }
      },
      error : function(){
        alert("something went wrong");}
        });
  }
  Stripe.setPublishableKey('<?php echo STRIPE_PUBLIC;?>');
  function stripeResponseHandler(status, response){
    var $form = $('#payment-form');

    if(response.error){
      //show the error on the form
      $form.find('#payment-errors').text(response.error.message);
      $form.fine('button').prop('disable', false);
    }else{
      // response contains id and card, which contains additional card details
      var token = response.id;
      // insert the token into the form so it gets submitted to the server
      $form.append($('<input type="hidden" name="stripeToken">').val(token));
      // and submit
      $form.get(0).submit();

    }
  };



    jQuery(function($){
      $('#payment-form').submit(function(event){
        var $form = $(this);
        //Disable the submit button to prevent repeat click
        $form.find('button').prop('disable', true);
        Stripe.card.createToken($form, stripeResponseHandler);
        //prevent the form from submitting with the default action
        return false;
      });
    });
</script>





<?php
include 'includes/footer.php';
?>
