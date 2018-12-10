<!--HOW THE MENU LOOK LIKE AT THE STATIC STATE WITHOUT PHP-->
<?php
// here we are creating a variable to store information from the catigories table
//with a condition of only where parent id equal to zero
$sql ="SELECT * FROM catigories WHERE parent =0";
// after making the selection, we can now use our database connection variable pointing to
//query() method to retrive/store our selection which is now in $sql variable using it as an argurement
// into another variable called $parent_query
$parent_query = $conn->query($sql);
?>
<nav class=" navbar fixed-top navbar navbar-expand-lg navbar-light bg-light"style="text-transform:uppercase">
  <a class="navbar-brand" href="index.php">Jeff's boutique</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <!-- next we are going to use the while loop to populate our top level menu
       according to what we now have in $parent_query variable using mysqli_fetch_assoc
       method. we will pass $parent_query variable as argurement. remember when creating
     a while loop, you must also end the while loop at the end of your top level menu like so
     --><//?php endwhile ?>
      <?php while($parent = mysqli_fetch_assoc($parent_query) ) : ?>
      <?php $parent_id = $parent['id'];?>
      <li class="nav-item dropdown">
        <!--top menu item  -->
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $parent['catigory'];?>
        <!-- here that we supose to have static top memu list such as men, women, girls and boys1
        we will just do it like so -->
        <!-- here we are targeting the catigory column from the catigories table.
      the $parent variable here now contain a list of array by the help of mysqli_fetch_assoc.
      using the catigory column as a key-->
        </a>
        <!--now the sub-menu  -->
        <?php
        $sql2 = "SELECT * FROM catigories WHERE parent = '$parent_id'";
        $child_query = $conn->query($sql2);
        ?>

        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <?php while($child = mysqli_fetch_assoc($child_query )) : ?>
          <a class="dropdown-item" href="category.php?cat=<?php echo $child['id'];?>"><?php echo $child['catigory']; ?>

          </a>

        <?php endwhile; ?>
        </div>
      </li>
 <?php endwhile; ?>
   <li><a href="cart.php" class="nav-link"><span><i class="fa fa-shopping-cart"></i></span> My cart</a></li>
    </ul>
  </nav>
