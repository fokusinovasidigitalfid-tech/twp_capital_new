<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Capital Place | Log in</title>

        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="shortcut icon" href="<?php echo e(url('/img/logoweb/iconweb.ico')); ?>">
        <!-- CSS -->
        <link href="<?php echo e(asset('public/lainnya/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('public/lainnya/plugins/font-awesome-4.4.0/css/font-awesome.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('public/AssetsLogin/css/AdminLTE.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('public/AssetsLogin/css/square/blue.css')); ?>" rel="stylesheet">

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
                background: url("<?php echo e(asset('public/lainnya/img/Background_new.jpeg')); ?>") no-repeat center center;
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
                font-size: 32px;
                color: #bda870;
                text-align: center;
                margin-bottom: 5px;
				font-family: "Century Gothic", CenturyGothic, AppleGothic, sans-serif;
            }

            .login-subtitle {
                font-size: 22px;
                color: #bda870;
                text-align: center;
                margin-bottom: 20px;
				font-family: "Century Gothic", CenturyGothic, AppleGothic, sans-serif;
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
        </style>
    </head>

<body class="login-page">
    <div class="login-box">

        <div class="login-box-body">
            <p class="login-title">CAPITAL PLACE</p>
            <p class="login-subtitle">Tenant Log In</p>

            <form action="<?php echo e(url('/based')); ?>" method="POST" id="formlogin" class="needs-validation" novalidate="">
                <?php echo e(csrf_field()); ?>

                <div class="form-group">
                    <div class="form-label-group">
                        <label class="form-label" for="default-01">Email</label>
                    </div>
                    <input type="text" class="form-control form-control-lg <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" id="email" placeholder="Enter your email address" required = "true">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-lg btn-primary btn-block">Go</button>
                </div>
            </form><!-- form -->

        </div>
    </div>

    <!-- JS -->
    <script src="<?php echo e(asset('public/lainnya/plugins/jQuery/jQuery-2.1.4.min.js')); ?>"></script>
    <script src="<?php echo e(asset('public/AssetsLogin/js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('public/AssetsLogin/js/icheck.min.js')); ?>"></script>

    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%'
            });
        });
    </script>

    </body>
</html><?php /**PATH E:\xampp\htdocs\twp\webtenant\resources\views/login/index.blade.php ENDPATH**/ ?>