<?php
require '../../config.php';
if (!isRole('admin')) {
    redirect('admin/login.php');
}

require '../../layout/index.php';
?>

<?php
$db = new DB('devices');
$item = null;
if (isset($_GET['id'])) {
    $item = $db->getBy('id',$_GET['id']);
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

                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h4 class="card-title" id="from-actions-bottom-right">تعديل الجهاز</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">

                        </div>
                    </div>
                    <div class="card-body shadow">
                        <form class="form needs-validation" action="operations.php" method="post"
                              enctype="multipart/form-data"
                              novalidate>
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <div class="form-body row">
                                <?php
                                if (isset($_SESSION['error'])) {
                                    echo '<div class="alert mb-2 alert-danger" role="alert">' . $_SESSION['error'] . '</div>';
                                    unset($_SESSION['error']);
                                }
                                ?>

                                <div class="col-md-6 mb-3">
                                    <label for="validationCustom01" class="form-label">اسم الجهاز</label>
                                    <input name="name" type="text" class="form-control" id="validationCustom01"
                                           value="<?= old('name',$item['name']) ?>"
                                           required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="validationCustom02" class="form-label">نوع الجهاز</label>
                                    <input name="type" type="text" class="form-control" id="validationCustom02"
                                           value="<?= old('type',$item['type']) ?>"
                                           required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="validationCustom03" class="form-label">موقع الجهاز</label>
                                    <input name="location" type="text" class="form-control" id="validationCustom03"
                                           value="<?= old('location',$item['location']) ?>"
                                           required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="validationCustom04" class="form-label">ملاحظات</label>
                                    <textarea name="notes" type="text" class="form-control"
                                              id="validationCustom04"><?= old('notes',$item['notes']) ?>
                            </textarea>
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