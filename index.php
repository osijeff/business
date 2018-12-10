
<?php   require_once('config/init.php');?>
<?php include "includes/head.php"?>
<?php include "includes/navigation.php"?>
<?php include "includes/herosection.php"?>
<?php include "includes/leftside.php"?>
<?php $sql = "SELECT * FROM products WHERE featured = 1"?>
<!--end of navigation  -->
<?php $featured = $conn->query($sql)?>
<!-- end of div headwrapper -->
    <div class="col-md-8">
      <h2 class="text-center">FEATURE PRODUCTS</h2>
      <div class="row">
        <?php while($product = mysqli_fetch_assoc($featured)) : ?>
          <!-- always remember to use var_dump to see if evrything is okey -->
          <//?php var_dump($product)?>
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
