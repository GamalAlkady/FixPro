<?php
require '../../config.php';
if (!isRole('user')) {
    redirect('user/login.php');
}

require '../../layout/index.php';
$item = [];
if (isset($_GET['id'])) {
    $item = (new DB('orders'))->getBy('id', $_GET['id']);
    if ($item != []) {
        $technicians = (new DB('technicians'))->getAll();
        $devices = (new DB('devices'))->getAll();
    }
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
            <?php
            if ($item === []) {
                echo '<div class="alert alert-danger">لا يوجد طلب بهذا الرقم</div>';
                die();
            }
            ?>
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <!--                    <h1 class="h3 mb-0 text-gray-800">لوحة التحكم</h1>-->
                <!--                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> تولبد تقرير</a>-->
            </div>

            <div class="card shadow">
                <div class="card-header">
                    <h4 class="card-title" id="from-actions-bottom-right">تعديل الطلب</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">

                    </div>
                </div>
                <div class="card-body">
                    <form class="form needs-validation" action="operations.php" method="post"
                          enctype="multipart/form-data"
                          novalidate>
                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                        <div class="form-body col-md-6 row m-auto">
                            <?php include ROOT_PATH . '/components/alert.php' ?>

                            <?php if ($item['status'] == 'rejected') echo '<input type="hidden" name="old_technician_id" value="' . $item['technician_id'] . '" />' ?>
                            <div class="row mb-3">
                                <label for="technician_id" class="col-sm-2 col-form-label">الفني</label>
                                <div class="form-group col-sm-10">
                                    <select class="selectpicker border" name="technician_id" id="technician_id"
                                            data-style="dir-rtl text-start btn-select" data-live-search="true"
                                            data-width="100%" required >
                                        <option selected disabled value="">اختيار فني</option>
                                        <?php
                                        foreach ($technicians as $technician) {
                                            echo '<option value="' . $technician['id'] . '" ' . ($technician['id'] == $item['technician_id'] ? 'selected' : '') . '>' . $technician['first_name'] . ' ' . $technician['last_name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <label for="device_id" class="col-sm-2 col-form-label">الجهاز</label>
                                <div class="col-sm-10">
                                    <select class="selectpicker border" name="device_id" id="device_id"
                                            data-style="dir-rtl text-start btn-select" data-live-search="true"
                                            data-width="100%" required>
                                        <option selected disabled value="">اختيار جهاز</option>
                                        <?php
                                        foreach ($devices as $device) {
                                            echo '<option value="' . $device['id'] . '"  ' . ($device['id'] == $item['device_id'] ? 'selected' : '') . ' >' . $device['name'] . ' ' . $device['location'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="formFile" class="col-sm-2 form-label">الصورة</label>
                                <div class="col-md-10">
                                    <input type="file" name="image" class="custom-file-input" id="formFile">
                                    <input type="hidden" name="old_image"
                                           class="custom-file-input" id="oldImage">
                                    <label class="custom-file-label" for="validatedCustomFile" id="formFileLabel"
                                           data-browse="تصفح">  <?= basename(old('old_imag', !empty($item['image'])?$item['image']:'اختيار ملف')) ?></label>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="description" class="col-form-label">الوصف</label>
                                <textarea name="description" type="text"
                                          class="form-control"><?= old('description', $item['description']) ?></textarea>
                            </div>


                        </div>
                        <div class="form-actions d-flex justify-content-between flex-row-reverse    ">
                            <button onclick="history.back()" type="button" class="btn text-white btn-danger mr-1">
                                إلغاء <i class="ft-x"></i>
                            </button>
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
    (function () {
        'use strict'

        $('#formFile').on('change', function () {
            if ($(this).val() != '')
                $("#formFileLabel").text($(this).val());
        })

    })();
</script>
