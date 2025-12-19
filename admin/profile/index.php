<?php
require '../../config.php';
if (!isRole('admin')) {
    redirect('admin/login.php');
}

require '../../layout/index.php';

?>
<?php
$item = (new DB('admin'))->getBy('id',$_SESSION['id']);

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
                    <h1 class="h3 mb-0 text-gray-800">الملف الشخصي</h1>
                </div>


                    <div class="card shadow">

                        <div class="card-body row">
                            <?php include ROOT_PATH . '/components/alert.php' ?>

                            <div class="col-lg-4">
                                <div class="card mb-4">
                                    <div class="card-body text-center">
                                        <img src="<?=assets($item['image'],'images/account.png')?>" alt="avatar"
                                             class="rounded-circle img-fluid" style="width: 150px;">
                                        <h5 class="my-3"><?=$item['first_name']?></h5>


                                        <div class="d-flex flex-column justify-content-end">
                                            <a href="<?=get_path('profile/edit.php?id='.$_SESSION['id'])?>" class="link nav-link text-start">تعديل الملف الشخصي</a>
                                            <a href="<?=get_path('profile/change_password.php?id='.$_SESSION['id'])?>" class="link nav-link text-start">تغيير كلمة المرور</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-8">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0">الاسم الكامل</p>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="text-muted mb-0"><?=$item['first_name'].' '.$item['last_name']?></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0">الإيميل</p>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="text-muted mb-0"><?=$item['email']?></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0">الهاتف</p>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="text-muted mb-0"><?=$item['phone_number']?></p>
                                            </div>
                                        </div>


                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
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
<?php require_once '../../components/confirmation.php' ?>
