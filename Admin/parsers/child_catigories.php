<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/business/config/init.php';
$parentID = (int) $_POST['parentID'];
$selected = sanitizer($_POST['selected']);
$childQuery = $conn->query("SELECT * FROM catigories WHERE parent = '$parentID'ORDER BY catigory ");
 ob_start();?>
 <option value = ""><option>
   <?php while($child = mysqli_fetch_assoc($childQuery)):?>
     <option value="<?php echo $child['id'];?>"<?php echo (($selected == $child['id'])?' selected':'');?>><?php echo $child['catigory'];?></option>
   <?php endwhile?>
   <?php echo ob_get_clean();?>
