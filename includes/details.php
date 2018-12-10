<?php require_once'../config/init.php'
?>

<?php
$id = $_POST['id'];
$id = (int)$id;
$sql = "SELECT * FROM products WHERE id = '$id'";
$result = $conn->query($sql);
$product = mysqli_fetch_assoc($result);
$brand_id = $product ['brand'];
$sql = "SELECT brand FROM brand WHERE id = '$brand_id'";
$brand_query = $conn->query($sql);
$brand = mysqli_fetch_assoc($brand_query);
$sizestring = $product['sizes'];
$size_array = explode(',', $sizestring );
?>
<!--Details light Box  -->
<?php ob_start();?>
<div class="modal fade details-1 "  id="details-modal" tabindex="-1" role="dialog" aria-labelledby="data">
 <div class="modal-dialog modal-lg" >
   <!-- modal content here -->
 <div class="modal-content Dmodal" >
     <div class="modal-header">
       <h4 class="modal-title text-center"><?php echo $product['title']?></h4>
       <button type="button" class="close" onclick="close_model()" aria-label="close"><span aria-hidden="true">&times;</span></button>
     </div>
     <!-- modal body section -->
    <div class="modal-body">
       <div class="cotainer-fluid">

         <span id="modal_errors" class="bg-danger"></span>
         <div class="row">

           <div class="col-md-6">
             <img src="<?php echo $product['image']?>" alt="<?php echo $product['title']?>" class="details img-responsive">
           </div>
           <div class="col-md-6">
             <h4>details</h4>
             <p><?php echo nl2br($product['description']);?></p>
             <hr>
             <p>price:$<?php echo $product['price'];?></p>
             <p>Brand:<?php echo $brand['brand'];?></p>
             <form action="add_cart.php" method="post" id="add_product_form">
               <input type="hidden" name="product_id" value="<?php echo $id;?>">
               <input type="hidden" name="available" id="available" value="">
               <div class="form-group">
                 <div class="col-xs-3">
                   <lebel for="quantity">Quantity</lebel>
                   <input type="number" name="quantity" class="form-control" id="quantity" min="0">
                 </div>

               </div>
               <div class="form-group">
                 <label for="size">Size</label>
                 <select name="size" id="size" class="form-control">
                   <option value=""></option>
                   <?php foreach ($size_array as $string) {
                     $string_array = explode(':', $string);
                     $size = $string_array[0];
                     $available = $string_array[1];
                     if($available > 0){
                      echo '<option value=" '.$size.'" data-available="'.$available.'">'.$size.' ('.$available.' Available )</option>';
                   }
                 }?>
                 </select>
               </div>
             </form>
           </div>
         </div>
       </div>
       <!-- modaL FOOTER -->
        <div class="modal-footer">
         <button type="button" class="btn btn-defaut" onclick="close_model()">close</button>
         <button onclick="add_to_cart();return false;" class="btn btn-warning"> <span><i class="fa fa-shopping-cart"></i></span> Add to cart</button>
       </div>
     </div>
   </div>
 </div>
</div>
<script>

jQuery('#size').change(function(){
  var available = jQuery('#size option:selected').data("available");
  jQuery('#available').val(available);
});
// function for closing modal
function close_model(){
  jQuery('#details-modal').modal('hide');
  setTimeout(function(){
    jQuery('#details-modal').remove();
    jQuery('.model-backdrop').remove();
  },500);
}


</script>
<?php echo ob_get_clean();?>
