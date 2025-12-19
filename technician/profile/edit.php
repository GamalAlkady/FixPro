<?php
require '../../config.php';
if (!isRole('technician')) {
    redirect('technician/login.php');
}

require '../../layout/index.php';

?>
<?php
$db=new DB('technicians');
$item=[];
if (isset($_GET['id'])){
    $item=$db->getBy('id',$_GET['id']);
}
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
                if ($item===[]) {
                    echo '<div class="alert alert-danger">لا يوجد بيانات بهذا الرقم</div>';
                    die();
                }
                ?>
                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="card-title" id="from-actions-bottom-right">تعديل الملف الشخصي</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">

                        </div>
                    </div>
                    <div class="card-body">
                        <form class="form needs-validation" action="operations.php" method="post" enctype="multipart/form-data"
                              novalidate>
                            <input type="hidden" name="id" value="<?=$item['id']?>">
                            <div class="form-body row">
                                <?php
                                if (isset($_SESSION['error'])) {
                                    echo '<div class="alert mb-2 alert-danger" role="alert">'.$_SESSION['error'].'</div>';
                                    unset($_SESSION['error']);
                                }
                                ?>

                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">الاسم الأول</label>
                                    <input name="first_name" type="text" class="form-control" id="first_name"
                                           value="<?= old('first_name',$item['first_name']) ?>" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">الاسم الاخير</label>
                                    <input name="last_name" type="text" class="form-control" id="last_name"
                                           value="<?= old('last_name',$item['last_name']) ?>" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">الايميل</label>
                                    <input name="email" type="email" class="form-control" id="email"
                                           value="<?= old('email',$item['email']) ?>" disabled>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">كلمة المرور</label>
                                    <input name="password" type="password" class="form-control" id="password"
                                           value="<?= old('password',$item['password']) ?>" disabled>
                                </div>


                                <div class="col-md-6 mb-3">
                                    <label for="formFile" class="col-sm-4 form-label">صورة الملف الشخصي</label>
                                    <div class="col-md-12">
                                        <input type="file" name="image" class="custom-file-input" id="formFile">
                                        <input type="hidden" name="old_image" value="<?=$item['image']?>" class="custom-file-input" id="oldImage" >
                                        <label class="custom-file-label" for="formFile" id="formFileLabel" data-browse="تصفح">  <?= basename(old('old_imag',$item['image'])) ?></label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="formFile2" class="col-sm-4 form-label">صورة الصفحة الرئيسية</label>
                                    <div class="col-md-12">
                                        <input type="file" name="image_home" class="custom-file-input" id="formFile2">
                                        <input type="hidden" name="old_image_home" value="<?=$item['image_home']?>" class="custom-file-input" id="oldImageHome" >
                                        <label class="custom-file-label" for="formFile2" id="formFile2Label" data-browse="تصفح">  <?= basename(old('old_image_home',$item['image_home'])) ?></label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone_number" class="form-label">رقم الهاتف</label>
                                    <input name="phone_number" type="number" class="form-control" id="phone_number"
                                           value="<?= old('phone_number',$item['phone_number']) ?>" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="validationCustom04" class="form-label">العنوان</label>
                                    <textarea name="address" type="text" class="form-control" required><?= old('address',$item['address']) ?></textarea>
                                </div>


                            </div>
                            <div class="form-actions d-flex justify-content-between flex-row-reverse    ">
                                <a href="index.php" class="btn text-white btn-danger mr-1">
                                    إلغاء <i class="ft-x"></i>
                                </a>
                                <button type="submit" class="btn btn-primary align-items-center" name="edit">
                                    حفظ <i class="bx bx-check"></i>
                                </button>
                            </div>
                        </form>
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

<script>
    (function (){
        'use strict'

        $('#formFile').on('change',function (){
            if ($(this).val()!='')
                $("#formFileLabel").text($(this).val());
        })

        $('#formFile2').on('change',function (){
            if ($(this).val()!='')
                $("#formFile2Label").text($(this).val());
        })

    })();
</script>
