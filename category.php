<?php   require_once('config/init.php');?>
<?php include "includes/head.php"?>
<?php include "includes/navigation.php"?>
<?php include "includes/headerpartial.php"?>
<?php include "includes/leftside.php";
if(isset($_GET['cat'])){
  $cat_id = sanitizer($_GET['cat']);
}else{
    $cat_id='';
}

?>
<?php $sql = "SELECT * FROM products WHERE catigories = '$cat_id'"?>
<!--end of navigation  -->
<?php $productQ = $conn->query($sql);
$catigory = get_catigory($cat_id);

?>
<!-- end of div headwrapper -->
    <div class="col-md-8">
      <h2 class="text-center" style="text-transform:uppercase; color:green"><?php echo $catigory['parent']. ' ' . $catigory['child']; ?></h2>
      <div class="row">
        <?php while($product = mysqli_fetch_assoc($productQ)) : ?>
          <!-- always remember to use var_dump to see if evrything is okey -->
          <//?php var_dump($product);?>
        <div class="col-md-3">
        <h4><?php echo $product['title'];?></h4>
        <img src="<?php echo $product['image']?>" alt="levis Jeans" class="img-thumb"/>
        <p class="list-price text-danger">List Price: <s>$<?php echo $product['list_price'];?></s></p>
        <p class="price">Our Price: $<?php echo $product['price'];?></p>
        <button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?php echo $product['id'];?>)">Details</button>
      </div>
    <?php endwhile;?>

  </div>
</div>
<!--end of feature product  -->
<//?php include "includes/details.php"?>
<?php include "includes/rightsidebar.php"?>
<?php include "includes/footer.php"?>
