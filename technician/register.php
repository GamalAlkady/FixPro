<?php
require '../config.php';
if (isRole('technician')) {
    redirect('technician/index.php');
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>فيكس برو</title>

    <!-- Custom fonts for this template-->

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet">

    <link rel="stylesheet" href="<?= assets('css/bootstrap.rtl.min.css') ?>">
    <link href="<?= assets('css/sb-admin-2.css') ?>" rel="stylesheet" type="text/css">

</head>

<body class="bg-gradient-primary">

<div class="container">
    <div class="row justify-content-center">

        <div class="card o-hidden border-0 shadow-lg my-5 col-md-9">
            <div class="card-body ">
                <!-- Nested Row within Card Body -->
                <div class="p-2">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">إنشاء حساب!</h1>
                    </div>
                    <form class="form needs-validation " action="operations.php" method="post"
                          enctype="multipart/form-data" novalidate>
                        <?php
                        if (isset($_SESSION['error'])) {
                            echo '<div class="alert mb-2 alert-danger" role="alert">' . $_SESSION['error'] . '</div>';
                            unset($_SESSION['error']);
                        }
                        ?>
                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="text" class="form-control form-control-user" name="first_name"
                                       id="exampleFirstName"
                                       placeholder="الاسم الأول" value="<?= old('first_name') ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-user" name="last_name"
                                       id="exampleLastName"
                                       placeholder="الاسم الأخير" value="<?= old('last_name') ?>" required>
                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="custom-file col-md-12">
                                <input type="file" name="image" class="custom-file-input" id="formFile">
                                <label class="custom-file-label" for="formFile" id="formFileLabel" data-browse="تصفح">صورة
                                    الملف الشخصي</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <input type="text" name="address" class="form-control form-control-user" id="address" placeholder="العنوان">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input name="phone_number" type="number" class="form-control" id="phone_number"
                                       value="<?= old('phone_number') ?>" placeholder="رقم الهاتف" required>
                            </div>
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="email" class="form-control form-control-user" name="email"
                                       id="exampleInputEmail"
                                       placeholder="الايميل" value="<?= old('email') ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="password" class="form-control form-control-user"
                                       id="newPassword" placeholder="كلمة المرور" name="password"
                                       value="<?= old('password') ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <input type="password" class="form-control form-control-user"
                                       id="confirmPassword" placeholder="تأكيد كلمة المرور"
                                       value="<?= old('password') ?>" required>
                                <div class="invalid-feedback">كلمة المرور غير متطابقة</div>

                            </div>
                        </div>

                        <button type="submit" name="register" class="btn btn-primary btn-user btn-block">
                            تسجيل الحساب
                        </button>

                    </form>
                    <hr>
                    <div class="text-center">
                        <a class="small" href="login.php">لديك حساب بالفعل ؟ قم بتسجيل الدخول!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../assets/js/sb-admin-2.min.js"></script>


<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function () {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
        $('#formFile').on('change', function () {
            if ($(this).val() != '')
                $("#formFileLabel").text($(this).val());
        })
    })()
</script>

</body>

</html>