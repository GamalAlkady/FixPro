<?php
require '../config.php';

$is_verify=0;

if (!isset($_SESSION['verify_role'])) {
    redirect('login.php');
    die();
}
if (!isset($_SESSION['time_verify']) or (time() - $_SESSION['time_verify'] > (5 * 60))) {
    $result = sendEmail($_SESSION['email'],$_SESSION['name']);
    if ($result!==true){
        $_SESSION['error']=$result;
    }
}
elseif (isset($_GET['token'])){
   $res =  (new DB($_SESSION['verify_role'].'s'))->update(['email_verification'=>1],$_SESSION['id']);
   if ($res===false){
       $_SESSION['error']=$res;
   }else{
    $is_verify=1;
    unset($_SESSION['verify_role']);
       $_SESSION['role']='technician';
       redirect('index.php');
       die();
   }
}
/**/
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Register</title>

    <!-- Custom fonts for this template-->

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet">

    <link rel="stylesheet" href="<?= assets('css/bootstrap.rtl.min.css') ?>">
    <link href="<?= assets('css/sb-admin-2.css') ?>" rel="stylesheet" type="text/css">

</head>

<body class="bg-gradient-primary">

<div class="container-fluid d-flex justify-content-center">
    <?php if ($is_verify===1): ?>
    <div class="modal show" tabindex="-1" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                </div>
                <div class="modal-body text-center ">
                    <span class="sr-onl">تحقق...</span>
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="card o-hidden border-0 shadow-lg my-5 col-md-9">
        <div class="card-body ">
            <?php include ROOT_PATH . '/components/alert.php' ?>
            <!-- Nested Row within Card Body -->
            <div class="p-2">
                <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">
                        لكي تتمكن من استخدام الموقع يجب ان يتم التحقق من بريدك الإلكتروني اولاً
                    </h1>
                </div>

                <div class="form-group row">
                    <div class="col-sm-12 mb-3 mb-sm-0 text-center">
                        لقد ارسلنا رابط التحقق الى <?= $_SESSION['email'] ?>
                        <p class="text-info mt-2">يرجى التحقق من بريدك الإلكتروني والضغط على زر تأكيد البريد
                            الإلكتروني.</p>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <?php endif;?>
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

<?php if ($is_verify===1): ?>
<script>
    $('#myModal').modal('show')
    // $('#myModal .modal-body').append('<div style="" id="loadingDiv"><div class="loader">Loading...</div></div>');
    $(window).on('load', function(){
        setTimeout(removeLoader, 2000); //wait for page load PLUS two seconds.
    });
    function removeLoader(){
        $('#myModal').modal('hide')
        window.location.href='index.php';
        // $( "#loadingDiv" ).fadeOut(500, function() {
        //     // fadeOut complete. Remove the loading div
        //     $( "#loadingDiv" ).remove(); //makes page more lightweight
        // });
    }
</script>
<?php endif;?>
</body>

</html>