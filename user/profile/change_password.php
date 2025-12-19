<?php
require '../../config.php';
if (!isRole('user')) {
    redirect('user/login.php');
}

require '../../layout/index.php';

?>
<?php

?>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <?php require ROOT_PATH . '/layout/navbar.php'; ?>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <!--                    <h1 class="h3 mb-0 text-gray-800">لوحة التحكم</h1>-->
                    <!--                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> تولبد تقرير</a>-->
                </div>

                <?php
                if (!isset($_GET['id'])) :
                    echo '<div class="alert alert-danger">لا يوجد بيانات بهذا الرقم</div>';
                else:
                ?>
                    <div class="card shadow">
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-bottom-right">تغيير كلمة المرور</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">

                            </div>
                        </div>
                        <div class="card-body">
                            <form class="form needs-validation" action="operations.php" method="post" enctype="multipart/form-data"
                                  novalidate>
                                <input type="hidden" name="id" value="<?=$_GET['id']?>">
                                <div class="form-body row">
                                    <?php
                                    if (isset($_SESSION['error'])) {
                                        echo '<div class="alert mb-2 alert-danger" role="alert">'.$_SESSION['error'].'</div>';
                                        unset($_SESSION['error']);
                                    }
                                    ?>


                                    <div class="col-md-4 m-auto">
                                        <div class="col-md-12  mb-3">
                                            <input name="old_password" type="password" class="form-control text-center" id="old_password"
                                                   value="<?= old('old_password') ?>" placeholder="كلمة المرور الحالية" required>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <input name="new_password" type="password" class="form-control text-center" id="newPassword"
                                                   value="<?= old('new_password') ?>" placeholder="كلمة المرور الجديدة" required>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <input name="confirm_password" type="password" class="form-control text-center" id="confirmPassword"
                                                   value="<?= old('confirm_password') ?>" placeholder="تأكيد كلمة المرور" required>
                                            <div class="invalid-feedback">كلمة المرور غير متطابقة</div>
                                        </div>

                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary m-auto w-50" name="change">تغيير</button>

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                <?php endif;?>


            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <?php require ROOT_PATH . '/layout/footer.php' ?>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

<?php require ROOT_PATH . "/layout/end.php" ?>

