<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/business/config/init.php';
if(!is_logged_in()){
  login_error_redirect();
}
include 'includes/header.php';
include 'includes/navigation.php';

//Restore Product

if(isset($_GET['restore'])) {

 $idz = sanitizer($_GET['restore']);
 $conn->query("UPDATE `products` SET `deleted`=0 WHERE `id`='$idz'");
 header('Location: Restore.php');

}


?>
<?php

$sqlw = "SELECT * FROM `products` WHERE `deleted`=1";
$p_result = $conn->query($sqlw);


?>
 <h2 class="text-center">Products</h2>

<table class="table table-bordered table-condensed table-striped">
 <thead>
   <th>Restore</th>
   <th>Product</th>
   <th>Price</th>
   <th>Parent ~ Category</th>
   <th>Featured</th>
   <th>Sold</th>
 </thead>
 <tbody>
   <?php while($product = mysqli_fetch_assoc($p_result)) :

  $childID = $product['catigories'];
  $catsql = "SELECT * FROM `catigories` WHERE `id`='$childID'";
  $cat_result = $conn->query($catsql);
  $child = mysqli_fetch_assoc($cat_result);
  $parentID = $child['parent'];
  $p_sql = "SELECT * FROM `catigories` WHERE `id`='$parentID'";
  $presult = $conn->query($p_sql);
  $parent = mysqli_fetch_assoc($presult);
  $catigory = $parent['catigory'].' ~ '.$child['catigory'];

  ?>


   <tr>

    <td>
     <a href="Restore.php?restore=<?php echo $product['id']; ?>" class="btn btn-xs btn-success"><i class="far fa-sync-alt"></i></a>
    </td>
     <!--EDIT OR REMOVE -->

    <td><?php echo $product['title']; ?></td> <!-- TITLE -->
    <td><?php echo money($product['price']); ?></td> <!-- PRICE -->
    <td><?php echo $catigory; ?></td>
    <!-- Categories -->
    <td>
      <a  href="products.php?Featured=<?php echo (($product['Featured'] == 0)?'1':'0');?>&id=<?php echo $product['id'];?>" class="btn btn-xs btn-success">
      <i class="fa <?php echo(($product['Featured']==1)?'fa-minus':'fa-plus');?>"></i></a>
      &nbsp<?php echo(($product['Featured']==1))?'Remove Featured':'Add Featured';?>
    </td>



    <!-- FEATURED PRODUCT -->

    <td>0</td> <!-- SOLD -->

   </tr>
  <?php endwhile; ?>
</tbody>

</table>











<?php
 include 'includes/footer.php';
?>
