<link rel="stylesheet" type="text/css" href="js/modal.css">
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/business/config/init.php';
if(!is_logged_in()){
  login_error_redirect();
}
include'includes/header.php';
include'includes/navigation.php';
// delete products
if(isset($_GET['delete'])){
  $id = sanitizer($_GET['delete']);
  $conn->query("UPDATE products SET deleted = 1 WHERE id = '$id'");
  header('Location: products.php');
}
$dbpath = '';
?>

<?php
if(isset($_GET['add']) || isset($_GET['edit'])){
  $brandQuery = $conn->query("SELECT * FROM brand ORDER BY brand");
  $parentQuery = $conn->query("SELECT * FROM catigories WHERE parent = 0 ORDER BY catigory");
  $title = ((isset($_POST['title']) && $_POST['title'] !='')?sanitizer($_POST['title']):'');
  $brand=((isset($_POST['brand']) && !empty($_POST['brand'] ))?sanitizer($_POST['brand']):'');
  $parent=((isset($_POST['parent']) && !empty($_POST['parent'] ))?sanitizer($_POST['parent']):'');
$catigory = ((isset($_POST['child'])) && !empty($_POST['child'])?sanitizer($_POST['child']):'');
$price = ((isset($_POST['price']) && $_POST['price'] !='')?sanitizer($_POST['price']):'');
$list_price = ((isset($_POST['list_price']) && $_POST['list_price'] !='')?sanitizer($_POST['list_price']):'');
$description = ((isset($_POST['description']) && $_POST['description'] !='')?sanitizer($_POST['description']):'');
$sizes = ((isset($_POST['sizes']) && $_POST['sizes'] !='')?sanitizer($_POST['sizes']):'');
$sizes = rtrim($sizes, ',');
$saved_image = '';
?>
<?php
// create some variables

//isset get edit page
if(isset($_GET['edit'])){
  // get edit id
  $edit_id = (int)$_GET['edit'];
  // Get values from the database
  $productResults  = $conn->query("SELECT * FROM products WHERE id =   '$edit_id'");
  $product = mysqli_fetch_assoc($productResults);
  if(isset($_GET['delete_image'])){
    $image_url = $_SERVER['DOCUMENT_ROOT'].$product['image'];echo $image_url;
    unlink($image_url);
    $conn->query("UPDATE products SET image = '' WHERE id = '$edit_id'");
    header('Location: products.php?edit='.$edit_id);
  };
  $catigory = ((isset($_POST['child']) && $_POST['child'] != '')?sanitizer($_POST['child']):$product['catigories']);
  $title =((isset($_POST['title']) && $_POST['title'] != '')?sanitizer($_POST['title']): $product['title']);
  $brand  = ((isset($_POST['brand']) && $_POST['brand'] != '')?sanitizer($_POST['brand']):$product['brand']);
  $parentQ = $conn->query("SELECT * FROM catigories WHERE id ='$catigory'");
  $parentResult = mysqli_fetch_assoc($parentQ);
  $parent  = ((isset($_POST['parent']) && $_POST['parent'] != '')?sanitizer($_POST['parent']):$parentResult['parent']);
  $price =((isset($_POST['price']) && $_POST['price'] != '')?sanitizer($_POST['price']): $product['price']);
  $list_price =((isset($_POST['list_price']))?sanitizer($_POST['list_price']): $product['list_price']);
  $description =((isset($_POST['description']))?sanitizer($_POST['description']): $product['description']);
  $sizes =((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitizer($_POST['sizes']): $product['sizes']);
  $sizes = rtrim($sizes, ',');
  $saved_image = (($product['image'] != '')?$product['image'] : '');
  $dbpath = $saved_image;
}
if(!empty($sizes)){
  $sizeString = sanitizer($sizes);
  $sizeString = rtrim($sizeString, ',');
  $sizesArray= explode(',',$sizeString);
  $sArray = array();   //sizes array
  $qArray = array();   // quantity array
  foreach($sizesArray as $ss){
    $s = explode(':' , $ss);
    $sArray[] = $s[0];
    $qArray[] = $s[1];
  }
} else{$sizesArray=array();}
?>

<!-- build up the sizes array -->
<?php
if($_POST){
  // $title = sanitizer($_POST['title']);
  // $brand = sanitizer($_POST['brand']);
  // $price = sanitizer($_POST['price']);
  // $list_price = sanitizer($_POST['list_price']);
  // $sizes = sanitizer($_POST['sizes']);
  // $sizes = rtrim($sizes,',');
  // $description = sanitizer($_POST['description']);


  $errors= array();
  // if(!empty($_POST['sizes'])){
  //   $sizeString = sanitizer($_POST['sizes']);
  //   $sizeString = rtrim($sizeString, ',');
  //   $sizesArray= explode(',',$sizeString);
  //   $sArray = array();   //sizes array
  //   $qArray = array();   // quantity array
  //   foreach($sizesArray as $ss){
  //     $s = explode(':' , $ss);
  //     $sArray[] = $s[0];
  //     $qArray[] = $s[1];
  //   }
  // } else{$sizesArray=array();}
  $required = array('title', 'brand', 'price', 'parent', 'child', 'sizes' );
  foreach($required as $field) {
    if($_POST[$field]== ''){
      $errors[] = 'All fields with an Astrisk are required.';
      break;
    }
  }

  if($_FILES['photo']['name']!= ''){
     //var_dump($_FILES);
    $photo = $_FILES['photo'];
    $name = $photo['name'];
    $nameArray = explode('.' , $name);
    $fileName =  @$nameArray[0];
    $fileExtention = @$nameArray[1];
    $mime = explode('/' , $photo['type']);
    $mimeType = @$mime[0];
    $mimeExtension = @$mime[1];
    $tempLocation = $photo['tmp_name'];
    $fileSize = $photo['size'];
    $allowed = array('png','jpg','jpeg','gif',);
    $uploadName = md5(rand().microtime()).'.'.$fileExtention;
    $uploadPath = BASEURL.'images/products/'.$uploadName;
    $dbpath = '/business/images/products/'.$uploadName;
    if($mimeType != 'image'){
      $errors[] = 'The file must be an image.';
    }
    // Check if the filetype is allowed,  and inform the user.
    if(!in_array($fileExtention, $allowed )){
      $errors[] = 'The photo must be a png, jpg, jpeg or gif';
    }
    if($fileSize >15000000){
      $errors[] = 'The file size must be under 15MB.';
    }
    // if($fileExtention != $mimeExtension && ($mimeExtension=='jpeg' && $fileExtention != 'jpg' )){
    //   $errors[] = 'File extension does not match the file'.;
    // }

  }
  if(!empty($errors)){
     echo display_errors($errors);
  }else{
    //uplaod file insert into database
    if(!empty($_FILES)){
      move_uploaded_file($tempLocation, $uploadPath);
    }

    $insertsql = "INSERT INTO products(`title`, `price`, `list_price`, `brand`, `catigories`, `sizes`, `image`, `description`) VALUES('$title', '$price', '$list_price', '$brand', '  $catigory', '$sizes', '$dbpath', '$description')";
    if(isset($_GET['edit'])){
      $insertsql = "UPDATE products SET title = '$title', price = '$price', list_price = '$list_price', brand = '$brand', catigories = '$catigory', sizes = '$sizes', image = '$dbpath',  description = '$description' WHERE id = '$edit_id'";
    }
$conn->query($insertsql);
header('Location: products.php');
  }
}
?>
<h2 class="text-center" style="margin-top:1em"><?php echo((isset($_GET['edit']))?'EDIT':'ADD A NEW');?> PRODUCT</h2>
<form action="products.php?<?php echo((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" method="POST" enctype="multipart/form-data">
    <!-- input box for title -->
  <div class="form-group col-md-3">
    <label for="title">Title*</label>
    <input type="text" name="title" class="form-control" id="title" value="<?php echo $title;?>">
  </div>
  <!-- input box for brand -->
  <div class="form-group col-md-3">
    <label for="brands">Brand*</label>
    <select class="form-control" id="brand" name="brand">
      <option value=""<?php echo (($brand === '')?' selected': '');?>></option>
      <?php while($b = mysqli_fetch_assoc($brandQuery)): ?>
      <option value="<?php echo $b['id'];?>"<?php echo (($brand == $b['id'])?' selected':'');?>><?php echo $b['brand'];?></option>
    <?php endwhile;?>
    </select>
  </div>

  <!-- input box for parent -->

  <div class="form-group col-md-3">
    <label for="parent">Parent Catigory*</label>
    <select class="form-control" id="parent" name="parent">
      <option value=""<?php echo (($parent ==='')?' selected': '');?>></option>
      <?php while($p = mysqli_fetch_assoc($parentQuery)) :?>
      <option value="<?php echo $p['id'];?>"<?php echo (($parent == $p['id'])?' selected':'');?>><?php echo $p['catigory'];?></option>
    <?php endwhile;?>
    </select>
  </div>

  <!-- input box for child -->
 <div class="form-group col-md-3">
   <label for="child">child catigory</label>
   <select class="form-control" name="child" id="child">

   </select>
 </div>
  <!-- input box for price -->
  <div class="form-group col-md-3">
  <label for="price">price*</label>
  <input type="text" name="price" id="price" value="<?php echo $price?>" class="form-control">
</div>
  <!-- input box for list_price-->
<div class="form-group col-md-3">
  <label for="list_price">list_price*</label>
  <input type="text" name="list_price" id="list_price" value="<?php echo $list_price;?>" class="form-control">
</div>
<!-- input box for Quantity & size-->
<div class="form-group col-md-3">
  <label>Quantity & Sizes*</label>
  <button class="btn btn-primary" onclick="jQuery('#sizesModal').modal('toggle');return false;">Quantity & Sizes</button>
</div>
<!-- input box for size & quanity preview-->
<div class="form-group col-md-3">
  <label for="sizes ">Sizes & Qty Preview*</label>
  <input type="text" name="sizes" id="sizes" class = "form-control input-lg" value="<?php echo $sizes;?>"readonly>
</div>
<!-- input box for product photo-->
<div class="form-group col-md-6">
  <?php if($saved_image != ''):?>
    <div class="saved-image">
      <img src="<?php echo $saved_image; ?>" alt=" saved image" style="width:200px; height:auto"/><br>
      <a href="products.php?delete_image=1&edit=<?php echo $edit_id;?>" class="text-danger">Delete Image</a>
    </div>
  <?php else:?>
  <label for="photo">Product Photo*</label>
  <input type="file" name="photo"  id="photo" value="" class="form-control">
  <?php endif;?>
</div>
<!-- input box for product Description-->
<div class="form-group col-md-6">
  <label for="description">Product Description*</label>
<textarea class="form-control"name="description" id="Description" rows="8" cols="80"><?Php echo $description;?></textarea>
</div>
<div class=" col-md-2" style="display:flex; flex-direction:colume">
  <a href="products.php" class=" form-control btn btn-warning" style="margin-right: .5em">Cancel</a>
  <input type="submit" name="" value="<?php echo((isset($_GET['edit']))?'Edit':'Add');?> Product" class="form-control  btn btn-success">
</div>

</form>













<!-- Button trigger modal -->
<!-- Modal -->
<div class="modal fade " id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sizesModalLabel">Size & Qantity</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
        <!-- using for loop to loop out size and Quantity input field 12 times -->
        <?php for( $i=1;$i <= 8;$i++):?>
          <div class="form-group col-md-6">
            <label for="size<?php echo $i;?>">Size:</label>
            <input type="text" name="size<?php echo $i;?>" id="size<?php echo $i;?>" value="<?php ((!empty($sArray[$i-1]))?$sArray[$i-1]:'');?>" class="form-control"></button>
          </div>
          <div class="form-group col-md-6">
            <label for="qty<?php echo $i;?>">Quanty:</label>
            <input type="number" name="qty<?php echo $i;?>" id="qty<?php echo $i;?>" value="<?php ((!empty($qArray[$i-1]))?$qArray[$i-1]:'');?>" min="0" class="form-control">
          </div>
        <?php endfor;?>
    </div>
  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateSizes(); jQuery('#sizesModal').modal('toggle');return false;">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-- end of modal -->


<?php
}else{

?>
<?php
$sql = "SELECT * FROM Products WHERE deleted = 0";
$presults = $conn->query($sql);
?>
<?php
// code to execute featured products
if(isset($_GET['Featured'])){
  $id = (int) $_GET['id'];
  $Featured = (int) $_GET['Featured'];
  $Featured_sql = "UPDATE products SET Featured = '$Featured' WHERE id = '$id'";
  $conn->query($Featured_sql);
  header('Location: products.php');
}?>

<h2 class="text-center">Products</h2>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-6"></div>
    <div class="col-md-6 text-right" ><a href="products.php?add=1" class="btn btn-success" id ="add_product_btn">Add product</a></div>
  </div>
</div>

<hr>
<table class="table table-bordered table-condensed table-striped">
  <thead>
    <th>Edit/delete</th>
    <th>Product</th>
    <th>Price</th>
    <th>Catigory</th>
    <th>Featured</th>
    <th>Sold</th>
  </thead>
  <tbody>
  <?php
  // while loop
   while($product = mysqli_fetch_assoc($presults)) :
    $child_ID = $product['catigories'];
    $catSql = "SELECT * FROM catigories WHERE id = '$child_ID'";
    $result = $conn->query($catSql);
    $child = mysqli_fetch_assoc($result);
    $parent_ID =   $child['parent'];
    $parent_Sql ="SELECT * FROM catigories WHERE id = '$parent_ID'";
    $p_result = $conn->query($parent_Sql);
    $parent = mysqli_fetch_assoc($p_result);
    $catigory = $parent['catigory'].'~' .$child['catigory'];
    ?>
    <tr>
      <td><a href="Products.php?edit=<?php echo $product['id'];?>" class="btn btn-xs btn-success"><i  class="fa">&#xf044;</i></a>
      <a href="Products.php?delete=<?php echo $product['id'];?>" class="btn btn-xs btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></a>
      </td>
      <td><?php echo $product['title'];?></td>
      <td><?php echo money($product['price']);?></td>
      <td><?php echo $catigory;?></td>

      <td><a  href="products.php?Featured=<?php echo (($product['Featured'] == 0)?'1':'0');?>&id=<?php echo $product['id'];?>" class="btn btn-xs btn-success">
      <i class="fa <?php echo(($product['Featured']==1)?'fa-minus':'fa-plus');?>"></i></a>
      &nbsp<?php echo(($product['Featured']==1))?'featured product':'';?>
      </td>

      <td>0</td>
    </tr>

  <?php endwhile;?>
  </tbody>
</table>



<?php }
include'includes/footer.php';
?>
<script>
  jQuery('document').ready(function(){
    get_child_options('<?php echo $catigory;?>');
   //updateSizes();
  });
</script>
