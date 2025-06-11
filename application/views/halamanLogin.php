<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login - Users</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
        <link href="<?php echo base_url(); ?>css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <!-- <body class="bg-gray-200">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Halaman Login Program Reservasi Hotel XYZ</h3></div>
                                    <div class="card-body">
                                        <form name="formlogin" id="formlogin" method="post" action="<?php echo base_url('halaman/proseslogin'); ?>">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="Username" name="Username" type="text" placeholder="Username" />
                                                <label>Username</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="Password" name="Password" type="password" placeholder="Password" />
                                                <label>Password</label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                                                <label class="form-check-label" for="inputRememberPassword">Pengingat Password</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small" href="password.html" style="text-decoration:none">Lupa Password?</a>
                                                <a class="btn btn-primary" href="javascript:void(0)" onClick="proseslogin();">Login</a>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small"><a href="register.html" style="text-decoration:none">Program Reservasi Hotel Berbasis Web</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2023</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div> -->

        <div class="container py-5">
        <div class="row d-flex justify-content-center align-items-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card shadow-lg">
            <div class="card-body p-5">
                <h4 class="text-center mb-4">Halaman Login SIDADANG (Sistem Pendataan Pendatang)</h4>
            <form name="formlogin" id="formlogin" method="post" action="<?php echo base_url('halaman/proseslogin'); ?>">
                

                <div class="form-outline mb-4" data-mdb-input-init>
                    <input class="form-control" id="nik" name="nik" type="text" placeholder="NIK" />
                    <label class="form-label" for="nik">NIK</label>
                </div>



                <div class="form-outline mb-4" data-mdb-input-init>
                    <input class="form-control" id="password" name="password" type="password" placeholder="Password" />
                    <label class="form-label" for="password">Password</label>
                </div>


                <div class="row mb-4">
                    <div class="col-md-6 d-flex align-items-center">
                        
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="form2Example31" checked />
                        <label class="form-check-label ms-2" for="form2Example31">Ingat Saya</label>
                    </div>
                    </div>

                    <div class="col-md-6 text-md-end">
                    <!-- Simple link -->
                    <a href="#!">Lupa Password?</a>
                    </div>
                </div>

                <!-- Submit button -->
                <div class="text-center">
                    <button type="button" class="btn btn-primary btn-block mb-4" data-mdb-button-init data-mdb-ripple-init href="javascript:void(0)" onClick="proseslogin();">
                    Sign in
                    </button>
                </div>

                <!-- Register buttons -->
                <div class="text-center">
                    <p>Belum memiliki akun? <a href="<?php echo site_url('Register'); ?>">Register</a>
                    </p>
                    <!-- <p>or sign up with:</p>
                    <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-link btn-floating mx-2" data-mdb-button-init data-mdb-ripple-init>
                        <i class="fab fa-facebook-f"></i>
                    </button>

                    <button type="button" class="btn btn-link btn-floating mx-2" data-mdb-button-init data-mdb-ripple-init>
                        <i class="fab fa-google"></i>
                    </button> -->
                    </div>
                </div>
            </form>
            </div>
            </div>
        </div>
        </div>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo base_url(); ?>js/scripts.js"></script>
       	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script language="javascript">
        	function proseslogin()
			{
				var nik = $('#nik').val();
                if (nik == "") {
                    alert("NIK masih kosong");
                    $('#nik').focus();
                    return false;
                }	
				
				var password=$('#password').val();
				if(password=="")
				{
					alert("password masih kosong");
					$('#password').focus();
					return false;
				}	
				$('#formlogin').submit();
			}
        </script>

    <?php
        $pesan = $this->session->flashdata('pesan');
        $alert_type = $this->session->flashdata('alert_type'); // Ambil jenis alert

        if (!empty($pesan)) { 
    ?>
        <div class="alert alert-<?php echo ($alert_type == 'success') ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php echo $pesan; ?>
        </div>
    <?php
        }
    ?>


    </body>
</html>
