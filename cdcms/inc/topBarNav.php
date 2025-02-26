<style>
  .user-img{
        position: absolute;
        height: 27px;
        width: 27px;
        object-fit: cover;
        left: -7%;
        top: -12%;
  }
  .btn-rounded{
        border-radius: 50px;
  }
</style>
<!-- Navbar -->
      <style>
        #login-nav{
          position:fixed !important;
          top: 0 !important;
          z-index: 1037;
          padding: 1em 1.5em !important;
        }
        #top-Nav{
          top: 4em;
        }
        .text-sm .layout-navbar-fixed .wrapper .main-header ~ .content-wrapper, .layout-navbar-fixed .wrapper .main-header.text-sm ~ .content-wrapper {
          margin-top: calc(3.6) !important;
          padding-top: calc(5em) !important;
      }
      </style>
      <nav class="bg-navy w-100 px-2 py-1 position-fixed top-0" id="login-nav">
        <div class="d-flex justify-content-between w-100">
          <div>
            <span class="mr-2  text-white"><i class="fa fa-phone mr-1"></i> <?= $_settings->info('contact') ?></span>
          </div>
          <div>
            <div class="d-flex align-items-center">
                <?php if(isset($_SESSION['login_id'])): ?>
                    <span class="text-white me-2">Hello, <?php echo $_SESSION['login_firstname']; ?>!</span>
                    <a href="./classes/Login.php?f=logout" class="text-white ms-2">Logout</a>
                <?php else: ?>
                    <a href="./?page=parent_login" class="btn btn-outline-light me-2">Parent Login</a>
                    <a href="./?page=parent_registration" class="btn btn-outline-light me-2">Parent Registration</a>
                    <a href="./admin/login.php" class="text-white ms-2">Admin login</a>
                <?php endif; ?>
            </div>
          </div>
        </div>
      </nav>
      <nav class="main-header navbar navbar-expand navbar-light border-0 navbar-light text-sm" id='top-Nav'>
        
        <div class="container">
          <a href="./" class="navbar-brand">
            <img src="<?php echo validate_image($_settings->info('logo'))?>" alt="Site Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span><?= $_settings->info('short_name') ?></span>
          </a>

          <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse order-3" id="navbarCollapse">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
              <li class="nav-item">
                <a href="./" class="nav-link <?= isset($page) && $page =='home' ? "active" : "" ?>">Home</a>
              </li>
              <li class="nav-item">
                <a href="./?page=about" class="nav-link <?= isset($page) && $page =='about' ? "active" : "" ?>">About Us</a>
              </li>
              <li class="nav-item">
                <a href="./?page=programs" class="nav-link <?= isset($page) && $page =='programs' ? "active" : "" ?>">Programs</a>
              </li>
              <li class="nav-item">
                <a href="./?page=babysitters" class="nav-link <?= isset($page) && $page =='babysitters' ? "active" : "" ?>">Babysitters</a>
              </li>
              <li class="nav-item">
                <a href="./?page=enrollment" class="nav-link <?= isset($page) && $page =='enrollment' ? "active" : "" ?>">Enrollment</a>
              </li>
              
              <!-- <li class="nav-item">
                <a href="#" class="nav-link">Contact</a>
              </li> -->
              <?php if($_settings->userdata('id') > 0): ?>
              <li class="nav-item">
                <a href="./?page=profile" class="nav-link <?= isset($page) && $page =='profile' ? "active" : "" ?>">Profile</a>
              </li>
              <li class="nav-item">
                <a href="./?page=submit-archive" class="nav-link <?= isset($page) && $page =='submit-archive' ? "active" : "" ?>">Submit Thesis/Capstone</a>
              </li>
              <?php endif; ?>
            </ul>

            
          </div>
          <!-- Right navbar links -->
          <div class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
          </div>
        </div>
      </nav>
      <!-- /.navbar -->
      <script>
        $(function(){
          
        })
      </script>