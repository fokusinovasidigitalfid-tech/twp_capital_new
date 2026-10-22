<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Capital Place | Log in</title>

        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="shortcut icon" href="<?php echo e(url('/tenant/img/logoweb/iconweb.ico')); ?>">
        <!-- CSS -->
        <link href="<?php echo e(asset('tenant/public/lainnya/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('tenant/public/lainnya/plugins/font-awesome-4.4.0/css/font-awesome.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('tenant/public/AssetsLogin/css/AdminLTE.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('tenant/public/AssetsLogin/css/square/blue.css')); ?>" rel="stylesheet">

        <style>
            html, body {
                height: 100%;
                margin: 0;
            }

            .login-page {
                position: relative;
                overflow: hidden;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }

            /* Background blur */
            .login-page::before {
                content: "";
                position: absolute;
                inset: -20px;
                background: url("<?php echo e(asset('tenant/public/lainnya/img/Background_new.jpeg')); ?>") no-repeat center center;
                background-size: cover;
                filter: blur(8px);
                transform: scale(1.1);
                z-index: 0;
            }

            .login-page > * {
                position: relative;
                z-index: 1;
            }

            /* Container */
            .login-box {
                width: 100%;
                max-width: 420px;
            }

            /* Card */
            .login-box-body {
                padding: 30px 25px;
                border-radius: 12px;
            }

            /* Title */
            .login-title {
                font-size: 30px;
                color: #bda870;
                text-align: center;
                margin-bottom: 5px;
				font-family: "Century Gothic", CenturyGothic, AppleGothic, sans-serif;
            }

            .login-subtitle {
                font-size: 20px;
                color: #bda870;
                text-align: center;
                margin-bottom: 20px;
				font-family: "Century Gothic", CenturyGothic, AppleGothic, sans-serif;
            }

            /* Form spacing */
            .form-group {
                margin-bottom: 18px;
            }

            /* Mobile */
            @media (max-width: 480px) {
                .login-title {
                    font-size: 24px;
					font-family: "Century Gothic", CenturyGothic, AppleGothic, sans-serif;
                }

                .login-subtitle {
                    font-size: 18px;
					font-family: "Century Gothic", CenturyGothic, AppleGothic, sans-serif;
                }

                .login-box-body {
                    padding: 20px 15px;
                }
            }
            select.form-control {
                height: 45px;
                font-size: 16px;
            }
        </style>
    </head>

<body class="login-page">
    <div class="login-box">

        

        <div class="login-box-body">
            <p class="login-title">CAPITAL PLACE</p>
            <p class="login-subtitle">Log In</p>

            <?php if(session('alert')): ?>
                <div class="alert alert-danger"><?php echo e(session('alert')); ?></div>
            <?php endif; ?>

            
            <form action="<?php echo e(url('/login')); ?>" method="POST" id="formlogin">
                <?php echo e(csrf_field()); ?>

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" class="form-control input-lg" name="email" id="email"
                        value="<?php echo e(old('email')); ?>" placeholder="Enter your email address" required autofocus>
                </div>
                <div class="form-group" id="bsn-group" style="display:none;">
                    <label class="form-label" for="bsn">Login As</label>
                    <select id="bsn" name="bsn" class="form-control"></select>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div style="position:relative;">
                        <input type="password" class="form-control input-lg" id="password" name="password"
                            placeholder="Enter your password" style="padding-right:45px;" required>
                        <span id="togglePassword"
                            style="position:absolute;right:15px;top:50%;transform:translateY(-50%);cursor:pointer;color:#000;">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-lg btn-primary btn-block">Log In</button>
                </div>
            </form>

        </div>
    </div>
    <script>
        document.getElementById('togglePassword').onclick = function () {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');

            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        };
    </script>
    <!-- JS -->
    <script src="<?php echo e(asset('tenant/public/lainnya/plugins/jQuery/jQuery-2.1.4.min.js')); ?>"></script>
    <script src="<?php echo e(asset('tenant/public/AssetsLogin/js/bootstrap.min.js')); ?>"></script>
    <script>
        // "Login As" hanya muncul kalau email punya lebih dari satu akses (admin dan/atau beberapa company)
        $(function () {
            var oldBsn = <?php echo json_encode(old('bsn'), 15, 512) ?>;
            function loadAccess() {
                var email = $.trim($('#email').val());
                if (!email) { $('#bsn-group').hide(); return; }
                $.getJSON(<?php echo json_encode(url('/login/businesses'), 15, 512) ?>, { email: email }, function (res) {
                    var opts = [];
                    if (res.admin) { opts.push({ id: 'admin', name: 'Administrator' }); }
                    $.each(res.tenants, function (i, t) { opts.push({ id: t.id, name: t.name }); });
                    var $sel = $('#bsn').empty();
                    $.each(opts, function (i, o) {
                        $('<option>').val(o.id).text(o.name).prop('selected', String(o.id) === String(oldBsn)).appendTo($sel);
                    });
                    $('#bsn-group').toggle(opts.length > 1);
                });
            }
            $('#email').on('change blur', loadAccess);
            loadAccess();
        });
    </script>
    </body>
</html>
<?php /**PATH D:\xampp\htdocs\twp_capital_new\resources\views\login.blade.php ENDPATH**/ ?>