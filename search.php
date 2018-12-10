<?php   require_once('config/init.php');?>
<?php include "includes/head.php"?>
<?php include "includes/navigation.php"?>
<?php include "includes/headerpartial.php"?>
<?php include "includes/leftside.php";

$sql ="SELECT * FROM products";
$cart_id = (($_POST['cat'] != '')?sanitizer($_POST['cat']):'');
if($cat_id == ''){
  $sql .= ' WHERE deleted = 0';
}
else{
  $sql .= "WHERE catigories = '{$cat_id}' AND deleted = 0";
}
$price_sort = (($_POST['price_sort'] != '')?sanitizer($_POST['price_sort']):'');
$min_price = (($_POST['min_price'] != '')?sanitizer($_POST['min_price']):'');
$max_price = (($_POST['max_price'] != '')?sanitizer($_POST['max_price']):'');
$brand = (($_POST['brand'] != '')?sanitizer($_POST['brand']):'');
// $brand = (($_POST['brand'] != '')?sanitizer($_POST['brand']):'');
if($min_price != ''){
  $sql .= " AND price >= '{$min_price}'";
}
if($max_price != ''){
  $sql .= " AND price <= '{$max_price}'";
}
if($brand != ''){
  $sql .= " AND brand = '{$brand}'";
}
if($price_sort = 'low'){
  $sql .= " ORDER BY price";
}
if($price_sort = 'high'){
  $sql .= " ORDER BY price DESC";
}

$productQ = $conn->query($sql);
// end of navigation

$catigory = get_catigory($cat_id);
?>
<!-- end of div headwrapper -->
    <div class="col-md-8">
      <div class="row">
        <?php if($cat_id != ''): ?>
      <h2 class="text-center" style="text-transform:uppercase; color:green"><?php echo $catigory['parent']. ' ' . $catigory['child']; ?></h2>
    <?php else: ?>
    <h2 class="text-center">Jeff Boutigue</h2>
  <?php  endif; ?>
          <?php while($product = mysqli_fetch_assoc($productQ)) : ?>
          <!-- always remember to use var_dump to see if evrything is okey -->
          <//?php var_dump($product);?>
        <div class="col-md-3">
        <h4><?php echo $product['title'];?></h4>
        <img src="<?php echo $product['image']; ?>" alt="levis Jeans" class="img-thumb"/>
        <p class="list-price text-danger">List Price: <s>$<?php echo $product['list_price'];?></s></p>
        <p class="price">Our Price: $<?php echo $product['price'];?></p>
        <button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?php echo $product['id'];?>)">Details</button>
      </div>
    <?php endwhile; ?>

  </div>
</div>
<!--end of feature product  -->
<//?php include "includes/details.php"?>
<?php include "includes/rightsidebar.php"?>
<?php include "includes/footer.php"?>
