<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Atur Password Baru</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
</head>
<body>
<div class="container py-5">
    <div class="row d-flex justify-content-center align-items-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <h4 class="text-center mb-4">Atur Password Baru Anda</h4>
                    <form method="post" action="<?php echo site_url('auth/update_password'); ?>">
                        
                        <input type="hidden" name="token" value="<?php echo $token; ?>">

                        <div class="form-outline mb-4" data-mdb-input-init>
                            <input class="form-control" id="password" name="password" type="password" />
                            <label class="form-label" for="password">Password Baru</label>
                            <?php echo form_error('password', '<small class="text-danger">', '</small>'); ?>
                        </div>

                        <div class="form-outline mb-4" data-mdb-input-init>
                            <input class="form-control" id="confirm_password" name="confirm_password" type="password" />
                            <label class="form-label" for="confirm_password">Konfirmasi Password Baru</label>
                            <?php echo form_error('confirm_password', '<small class="text-danger">', '</small>'); ?>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-block mb-4" data-mdb-button-init data-mdb-ripple-init>
                                Simpan Password
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>