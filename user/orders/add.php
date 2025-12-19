<?php
require '../../config.php';
if (!isRole('user')) {
    redirect('user/login.php');
}

require '../../layout/index.php';

$technicians = (new DB('technicians'))->getAll();
$devices = (new DB('devices'))->getAll();

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

                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h4 class="card-title" id="from-actions-bottom-right">إضافة طلب</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">

                        </div>
                    </div>
                    <div class="card-body">

                        <form class="form needs-validation row" action="operations.php" method="post"
                              enctype="multipart/form-data"
                              novalidate>
                            <input type="hidden" name="user_id" value="<?=$_SESSION['id']?>">

                            <div class="form-body col-md-6 row m-auto">
                                <?php
                                if (isset($_SESSION['error'])) {
                                    echo '<div class="alert mb-2 alert-danger" role="alert">'.$_SESSION['error'].'</div>';
                                    unset($_SESSION['error']);
                                }
                                ?>

                                <div class="row mb-3">
                                    <label for="technician_id" class="col-sm-2 col-form-label">الفني</label>
                                    <div class="form-group col-sm-10">
                                        <select class="selectpicker border" name="technician_id" id="technician_id" data-style="dir-rtl text-start btn-select" data-live-search="true" data-width="100%"
                                                 required <?=isset($_GET['post_id'])?'disabled':''?>>
                                            <option selected disabled value="">اختيار فني</option>
                                            <?php
                                            foreach ($technicians as $technician) {
                                                echo '<option value="' . $technician['id'] . '" ' . ($technician['id'] == ($_GET['technician_id']??0) ? 'selected' : '') . '>' . $technician['first_name'] . ' ' . $technician['last_name'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="device_id" class="col-sm-2 col-form-label">الجهاز</label>
                                    <div class="col-sm-10">
                                        <select class="selectpicker border" name="device_id" id="device_id" data-style="dir-rtl text-start btn-select" data-live-search="true" data-width="100%" required>
                                            <option selected disabled value="">اختيار جهاز</option>
                                            <?php
                                            foreach ($devices as $device) {
                                                echo '<option value="' . $device['id'] . '">' . $device['name'] . ' ' . $device['location'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="formFile" class="col-sm-2 form-label">الصورة</label>
                                    <div class="col-md-10">
                                        <input type="file" name="image" class="custom-file-input" id="formFile" >
                                        <label class="custom-file-label" for="validatedCustomFile" id="formFileLabel" data-browse="تصفح">  <?= (isset($_SESSION['old']) and isset($_SESSION['old']['image']))?basename($_SESSION['old']['image']):' اختيار ملف' ?></label>
                                    </div>
                                </div>
                                <?php if (isset($_GET['post_id'])):
                                    $post = (new DB('posts'))->getBy('id',$_GET['post_id']);
                                    echo '<input type="hidden" name="technician_id" value="'.$_GET['technician_id'].'">';
                                    echo '<input type="hidden" name="post_id" value="'.$_GET['post_id'].'">';
                                ?>
                                <div class="row mb-3">
                                    <label for="description" class="col-sm-2 col-form-label">المنشور</label>
                                    <input  type="text" disabled class="col-sm-10 form-control"  value="<?=$post['title']?>">
                                </div>

                                <?php endif;?>
                                <div class="row mb-3">
                                    <label for="description" class="col-form-label">الوصف</label>
                                    <textarea name="description" type="text" class="form-control" id="description" required></textarea>
                                </div>

                            </div>
                            <div class="form-actions d-flex justify-content-around flex-row-reverse">
                                <button onclick="history.back()" type="button" class="btn text-white btn-danger mr-1">
                                    إلغاء <i class="ft-x"></i>
                                </button>
                                <button type="submit" class="btn btn-primary align-items-center" name="add">
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

    })();
</script>
