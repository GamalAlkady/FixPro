<?php
require '../../config.php';
if (!isRole('admin')) {
    redirect('admin/login.php');
}
require '../../layout/index.php';
?>
<?php
//if (!isset($_GET['id'])) die('not found');
//$item = (new DB('orders'))->getBy('id',$_GET['id']);
$item = [];
if (isset($_GET['id'])) {
    $item = (new DB('orders o'))
        ->join('users u', 'o.user_id=u.id')
        ->join('devices d', 'o.device_id=d.id')
        ->join('technicians t', 'o.technician_id=t.id')
        ->where('o.id',$_GET['id'])
        ->select("o.*,d.name as device_name,CONCAT(u.first_name, ' ',u.last_name) as user_name,CONCAT(t.first_name, ' ',t.last_name) as technician_name")
        ->getOne();
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
            </div>
            <?php
            if ($item === []) {
                echo '<div class="alert alert-danger">لا يوجد طلب بهذا الرقم</div>';
            } else {
                ?>
                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="card-title" id="from-actions-bottom-right">
                            بيانات الطلب <span><?= $item['id'] ?></span>
                        </h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">

                        </div>
                    </div>

                    <div class="card-body row">

                        <div class="col-md-6">
                            <div class="col-md-12 d-flex mb-3">
                                <span class="me-2">اسم المستخدم : </span>
                                <p class=" card-text"><?= $item['user_name'] ?></p>
                            </div>

                            <div class="col-md-12 d-flex mb-3">
                                <span class="me-2">اسم الفني : </span>
                                <p class=" card-text"><?= $item['technician_name'] ?></p>
                            </div>

                            <div class="col-md-12 d-flex mb-3">
                                <span class="me-2">اسم الجهاز : </span>
                                <p class=" card-text"><?= $item['device_name'] ?></p>
                            </div>

                            <div class="col-md-12 d-flex mb-3">
                                <span class="me-2">المشكلة : </span>
                                <p class=" card-text"><?= $item['description'] ?></p>
                            </div>

                            <div class="col-md-12 d-flex mb-3">
                                <span class="me-2">الحالة : </span>
                                <p class=" card-text"><?= status($item['status']) ?></p>
                            </div>

                            <?php if (in_array($item['status'], ['canceled', 'rejected'])) : ?>
                                <div class="col-md-12 d-flex mb-3 text-danger">
                                    <!--                                <span class="me-2">سبب الرفض </span>-->
                                    <p class=" card-text"><?= $item['reason'] ?></p>
                                </div>
                            <?php endif; ?>


                        </div>

                        <div class="col-md-6">

                            <?php if (!empty($item['image'])): ?>
                                <img src="<?= assets($item['image']) ?>" class="card--top" height="200" width="100%">
                            <?php endif; ?>
                        </div>

                        <p class="card-text float-end"><small class="text-muted">اخر
                                تحديث <?= $item['updated_at'] ?></small></p>


                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-around flex-nowrap">
                            <form action="operations.php" method="post"
                                  class="formRejected d-flex justify-content-around w-100">
                                <input type="hidden" name="id" value="<?= $item['id'] ?>"/>

                                <?php if ($item['status'] == 'accepted') { ?>
                                    <button class="btn btn-outline-success" type="submit" name="accept"
                                            value="completed">
                                        اكتمال
                                    </button>
                                    <button class="btn btn-outline-danger btn-reject" data-id="<?= $item['id'] ?>"
                                            data-title="سبب الإلغاء" name="reject" value="canceled" type="button">الغاء
                                    </button>

                                <?php } elseif ($item['status'] == 'new' or $item['status'] == 'modify') { ?>
                                    <button class="btn btn-outline-success" type="submit" name="accept"
                                            value="accepted">
                                        قبول
                                    </button>
                                    <button class="btn btn-outline-danger btn-reject" data-id="<?= $item['id'] ?>"
                                            data-title="سبب الرفض" name="reject" value="rejected" type="button">رفض
                                    </button>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <?php require ROOT_PATH . '/layout/footer.php' ?>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->
</div>

<div class="modal fade" id="reject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">السبب</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form needs-validation" id="formReject" action="operations.php" method="post"
                      enctype="multipart/form-data"
                      novalidate>
                    <input type="hidden" name="id" id="rejectId">
                    <input type="hidden" name="status" id="rejectStatus">
                    <div class="">
                        <label for="validationCustom01" class="form-label"> السبب</label>
                        <input name="reason" type="text" class="form-control" id="validationCustom01" required>
                    </div>

                    <div class="modal-footer d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary btnYes" name="reject">موافق</button>
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">الغاء</button>
                        <!--                <a class="btn btn-primary" href="login.html">نعم</a>-->
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<?php require ROOT_PATH . "/layout/end.php" ?>


<script>
    (function () {
        "use strict";
        const myModal = new bootstrap.Modal(document.getElementById('reject'), {keyboard: false});
        let formId = null;
        $(".btn-reject").on('click', function (event) {
            $(".modal-title").text($(this).data('title'))
            $("#rejectId").val($(this).data('id'))
            $("#rejectStatus").val($(this).val())
            myModal.show()
        })

    })();

</script>