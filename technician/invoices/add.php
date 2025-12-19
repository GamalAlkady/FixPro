<?php
require '../../config.php';
if (!isRole('technician')) {
    redirect('technician/login.php');
}

require '../../layout/index.php';
$orders = (new DB('orders o'))
    ->join('devices d', 'o.device_id=d.id')
    ->where(['o.technician_id' => $_SESSION['id'], 'status' => 'completed'])
    ->select('o.*,d.name')
    ->get();

$order_id=old('order_id',null);
if (isset($_GET['order_id'])) $order_id=$_GET['order_id'];

//var_dump($_SESSION);
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
                <!--                <h1 class="h3 mb-0 text-gray-800">لوحة التحكم</h1>-->
                <!--                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> تولبد تقرير</a>-->
            </div>

            <div class="card shadow mb-4">
                <div class="card-header">
                    <h4 class="card-title" id="from-actions-bottom-right">إضافة فاتورة</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">

                    </div>
                </div>
                <div class="card-body">

                    <form class="form needs-validation" action="operations.php" method="post"
                          enctype="multipart/form-data"
                          novalidate>
                        <input type="hidden" name="technician_id" value="<?= $_SESSION['id'] ?>">
                        <div class="form-body ">
                            <?php
                            if (isset($_SESSION['error'])) {
                                echo '<div class="alert mb-2 alert-danger" role="alert">' . $_SESSION['error'] . '</div>';
                                unset($_SESSION['error']);
                            }
                            ?>

                            <div class="row mb-4">
                                <label for="device_id" class="col-sm-1 col-form-label"
                                       style="min-width: 130px !important;">الطلب</label>
                                <div class="col-sm-7">
                                    <select class="selectpicker border" name="order_id" id="device_id"
                                            data-style="dir-rtl text-start btn-select" data-live-search="true"
                                            data-width="100%" required>
                                        <option selected disabled value="">اختيار طلب</option>
                                        <?php
                                        foreach ($orders as $order) {
                                            echo '<option value="' . $order['id'] . '" '.($order_id===$order['id']?'selected':null).'>' . $order['id'] . ' | ' . $order['name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <label for="datepicker" class="col-sm-1 col-form-label"
                                       style="min-width: 130px !important;">المبلغ</label>
                                <div class="col-sm-7">
                                    <input name="amount" type="number" class="form-control" id="datepicker"
                                           value="<?=old('amount')?>" required>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <label for="due_date" class="col-sm-1 col-form-label"
                                       style="min-width: 130px !important;">تاريخ الاستحقاق</label>
                                <div class="col-sm-7">
                                    <input name="due_date" type="date" class="form-control" id="due_date" value="<?=old('due_date')?>" required>
                                </div>
                            </div>


                            <div class="row mb-4">

                                <label for="validationCustom04" class="col-sm-1 col-form-label"
                                       style="min-width: 130px !important;">الملاحظة</label>
                                <div class="col-sm-7 mb-3">
                                    <textarea name="notes" type="text" class="form-control"
                                              id="validationCustom04"><?=old('notes')?></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="form-actions d-flex justify-content-between flex-row-reverse">
                            <a href="index.php" class="btn text-white btn-danger mr-1">
                                إلغاء <i class="ft-x"></i>
                            </a>
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
