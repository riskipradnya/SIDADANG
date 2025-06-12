<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard SIDADANG</title>

    <link rel="stylesheet" href="<?php echo base_url('assets/vendors/feather/feather.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendors/mdi/css/materialdesignicons.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendors/ti-icons/css/themify-icons.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendors/font-awesome/css/font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendors/typicons/typicons.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendors/simple-line-icons/css/simple-line-icons.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendors/simple-datatables@7.1.2/dist/style.min.css'); ?>">
    
    <link rel="stylesheet" href="<?php echo base_url('assets/vendors/css/vendor.bundle.base.css'); ?>">

    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon.png'); ?>" />

   <style>
      /* Jika .page-body-wrapper yang punya padding/margin horizontal */
      
      .page-body-wrapper {
          padding-left: 0px !important;
          padding-right: 0px !important;
          margin-left: 0px !important;
          margin-right: 0px !important;
          width: 100% !important;
          max-width: none !important;
      }
      
  </style>

</head>
  <body class="with-welcome-text">
    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
      <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
          <div class="me-3">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
              <span class="icon-menu"></span>
            </button>
          </div>
          <div>
            <a class="navbar-brand brand-logo" href="<?php echo base_url('Dashboard/admin'); ?>">
                <img src="<?php echo base_url('assets/images/logo.svg'); ?>" alt="logo" />
            </a>
            <!-- <a class="navbar-brand brand-logo-mini" href="<?php echo base_url('Dashboard/admin'); ?>">
                <img src="<?php echo base_url('assets/images/logo-mini.svg'); ?>" alt="logo" />
            </a> -->
            </div>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-top">
          <ul class="navbar-nav">
            <li class="nav-item fw-semibold d-none d-lg-block ms-0">
            <h1 class="welcome-text">Selamat Datang, <span class="text-black fw-bold">
              <?php 
                echo $this->session->userdata('NamaLengkap') . ' ('; 
                echo $this->session->userdata('Level') . ')';
              ?>
            </span></h1>
              <h3 class="welcome-sub-text">Sistem Pendataan Pendatang (SIDADANG) mempermudah pengelolaan data penduduk pendatang</h3>
            </li>
          </ul>
          <ul class="navbar-nav ms-auto">
            
            <li class="nav-item">
              <form class="search-form" action="#">
                <i class="icon-search"></i>
                <input type="search" class="form-control" placeholder="Search Here" title="Search here">
              </form>
            </li>
            
            <!--<li class="nav-item dropdown">
             
             </li>
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator" id="countDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-bell"></i>
              </a> 
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="countDropdown">
                <a class="dropdown-item py-3">
                  <p class="mb-0 fw-medium float-start">You have 7 unread mails </p>
                  <span class="badge badge-pill badge-primary float-end">View all</span>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <img src="assets/images/faces/face10.jpg" alt="image" class="img-sm profile-pic">
                  </div>
                  <div class="preview-item-content flex-grow py-2">
                    <p class="preview-subject ellipsis fw-medium text-dark">Marian Garner </p>
                    <p class="fw-light small-text mb-0"> The reservasi is cancelled </p>
                  </div>
                </a>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <img src="assets/images/faces/face12.jpg" alt="image" class="img-sm profile-pic">
                  </div>
                  <div class="preview-item-content flex-grow py-2">
                    <p class="preview-subject ellipsis fw-medium text-dark">David Grey </p>
                    <p class="fw-light small-text mb-0"> The reservasi is cancelled </p>
                  </div>
                </a>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <img src="assets/images/faces/face1.jpg" alt="image" class="img-sm profile-pic">
                  </div>
                  <div class="preview-item-content flex-grow py-2">
                    <p class="preview-subject ellipsis fw-medium text-dark">Travis Jenkins </p>
                    <p class="fw-light small-text mb-0"> The reservasi is cancelled </p>
                  </div>
                </a>
              </div>
            </li> -->
            <li class="nav-item dropdown d-none d-lg-block user-dropdown">
              <a class="nav-link mdi mdi-account-circle text-center " id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 24px; ">
                <!-- <img class="img-xs rounded-circle mdi mdi-account-circle"> </a> -->
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                <div class="dropdown-header text-center ">
                <!-- mdi mdi-account-circle -->
                  <img class="img-md rounded-circle">
                  <p class="mb-1 mt-3 fw-semibold">
                    <?php
	                    echo $this->session->userdata('NamaLengkap');
                    ?>                
                  </p>
                  <!-- <p class="fw-light text-muted mb-0">allenmoreno@gmail.com</p> -->
                </div>
                <!-- <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile <span class="badge badge-pill badge-danger">1</span></a>
                <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-message-text-outline text-primary me-2"></i> Messages</a>
                <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-calendar-check-outline text-primary me-2"></i> Activity</a>
                <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-help-circle-outline text-primary me-2"></i> FAQ</a> -->
                <a class="dropdown-item" href="<?php echo base_url('dashboard/logout'); ?>"><i class="dropdown-item-icon mdi mdi-logout text-primary me-2"></i>Logout</a>
              </div>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url('Dashboard/admin'); ?>">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
              </a>
            </li>
            <li class="nav-item nav-category">Master Data</li>

          
                <li class="nav-item">
                  <a class="nav-link" href="<?php echo base_url('Kaling'); ?>">
                    <i class="menu-icon mdi mdi-home-account"></i>
                    <span class="menu-title">Data KALING</span>
                  </a>
                </li>

              

               
           
            <?php
	            // $Level=$this->session->userdata('Level');
	            // if($Level=="Admin")
	            // {
                    
            ?>
              
            <!-- end session -->

                <li class="nav-item">
                  <a class="nav-link" href="<?php echo base_url('Pj'); ?>">
                    <i class="menu-icon mdi mdi-account-supervisor"></i>
                    <span class="menu-title">Data PJ</span>
                  </a>
                </li>
        

                <!-- <li class="nav-item">
                  <a class="nav-link" href="<?php echo base_url('Pendatang'); ?>">
                    <i class="menu-icon mdi mdi-card-account-details-outline"></i>
                    <span class="menu-title">Pendatang</span>
                  </a>
                </li> -->

              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#submenuPendatang" aria-expanded="false" aria-controls="submenuPendatang">
                    <i class="menu-icon mdi mdi-book-open-page-variant-outline"></i>
                    <span class="menu-title">Data Pendatang</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="submenuPendatang">  <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="<?php echo base_url('Pendatang'); ?>">Form Pendatang</a></li>
                        <li class="nav-item"> <a class="nav-link" href="<?php echo base_url('pendatang/daftar'); ?>">Data Pendatang</a></li>
                    </ul>
                </div>
            </li>

            <li class="nav-item nav-category">Document</li>

            <li class="nav-item">
              <a class="nav-link" href="<?= site_url('surat_kedatangan'); ?>">
                <i class="menu-icon mdi mdi-file-document-multiple-outline"></i>
                <span class="menu-title">Surat Pengantar</span>
              </a>
            </li>
            
          </ul>
        </nav>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
                      <?php
						if(empty($konten))
						{
							echo "";	
						}
						else
						{
							echo $konten;	
						}
						?>
                        
                        <?php
						if(empty($table))
						{
							echo "";	
						}
						else
						{
							echo $table;	
						}
					?>
            <div class="row">
              <div class="col-sm-12">
                <div class="home-tab">
                  
                  
                </div>
              </div>
            </div>
          </div>


          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Copyright © 2025. All rights reserved.</span>
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <!-- jQuery and Bootstrap -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>


    <!-- Vendor JS -->
    <script src="<?php echo base_url('assets/vendors/js/vendor.bundle.base.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendors/chart.js/chart.umd.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendors/progressbar.js/progressbar.min.js'); ?>"></script>

    <!-- Injected JS -->
    <script src="<?php echo base_url('assets/js/off-canvas.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/template.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/settings.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/hoverable-collapse.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/todolist.js'); ?>"></script>

    <!-- Custom JS for this page -->
    <script src="<?php echo base_url('assets/js/jquery.cookie.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/js/dashboard.js'); ?>"></script>

    <!-- Additional Plugins -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo base_url('assets/demo/chart-area-demo.js'); ?>"></script>
    <script src="<?php echo base_url('assets/demo/chart-bar-demo.js'); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo base_url('assets/js/datatables-simple-demo.js'); ?>"></script>

    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>


  </body>
</html>