

      <nav class=" navbar fixed-top navbar navbar-expand-lg navbar-light bg-light"style="text-transform:uppercase">
<a class="navbar-brand" href="/business/admin/index.php">Jeff's boutique Admin</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <a class="nav-link" href="brands.php"><i class="fa fa-home" style="font-size:15px"></i>brands</a>
              <a class="nav-link" href="catigories.php"><i class="fa fa-envelope" style="font-size:15px"></i> Catigories</a>
              <a class="nav-link" href="Products.php"><i class="fa fa-envelope" style="font-size:15px"></i> Products</a>
              <a class="nav-link" href="restore.php"><i class="fa fa-envelope" style="font-size:15px"></i> Archived</a>
              <?php if(has_permission('admin')):?>
               <a class="nav-link pull-right" href="users.php"><i class="fa fa-envelope" style="font-size:15px"></i> Users</a>
             <?php endif;?>
           </ul>
              <!--top menu item  -->
              <ul class="nav navbar-nav navbar-right">
                <li class="dropdown" style="list-style:none">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user-circle-o" style="font-size:24px"></i> Hello <?php echo $user_data['last'];?>
                    <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu" style="text-transform:lowercase">
                      <li><a href="change_password.php">Change Password</a></li>
                      <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                  </li>
              </ul>
  </nav>
