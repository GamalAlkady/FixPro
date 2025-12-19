<?php
require '../../config.php';
if (!isRole('technician')) {
    redirect('technician/login.php');
}
?>

<?php require '../../layout/index.php'; ?>
<?php
//if (!isset($_GET['id'])) die('not found');
//$item = (new DB('orders'))->getBy('id',$_GET['id']);
$rating = $item = false;
if (isset($_GET['id'])) {
    $item = (new DB('orders p'))
        ->join('users t', 'p.user_id=t.id')
        ->join('devices d', 'p.device_id=d.id')
        ->select("p.*,d.name as device_name,CONCAT(t.first_name, ' ',t.last_name) as full_name")
        ->where("p.id", $_GET['id'])
        ->getOne();
    if ($item !== false) {
        if ($item['is_read'] == 0 and in_array($item['status'], ['new', 'modify'])) (new DB('orders'))->update(['is_read' => 1], $item['id']);
        $rating = (new DB('ratings'))->getBy('order_id', $_GET['id']);
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

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <!--                    <h1 class="h3 mb-0 text-gray-800">لوحة التحكم</h1>-->
                <!--                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> تولبد تقرير</a>-->
            </div>
            <?php
            if ($item === false):
                echo '<div class="alert alert-danger">لا يوجد طلب بهذا الرقم</div>';
            else:?>

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
                        <div class="col-md-12 mb-3 text-center">

                            <?php if (!empty($item['image'])): ?>
                                <img src="<?= assets($item['image']) ?>" class="card--top m-auto" height="300" width="80%">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-12 d-flex mb-3">
                                <span class="me-2">اسم المستخدم : </span>
                                <p class=" card-text"><?= $item['full_name'] ?></p>
                            </div>
                            <?php if ($item['post_id'] != null):

                                $post = (new DB('posts'))->getBy('id', $item['post_id']);
                                ?>
                                <div class="col-md-12 d-flex mb-3">
                                    <span class="me-2">المنشور : </span>
                                    <a href="<?= get_path('posts/details.php?id=' . $item['post_id']) ?>"
                                       class="text-decoration-none"><?= $post['title'] ?></a>
                                </div>

                            <?php endif; ?>

                            <div class="col-md-12 d-flex mb-3">
                                <span class="me-2">اسم الجهاز : </span>
                                <p class=" card-text"><?= $item['device_name'] ?></p>
                            </div>


                            <div class="col-md-12 d-flex mb-3">

                                <span class="me-2">الحالة : </span>
                                <p class=" card-text"><?= status($item['status']) ?></p>
                            </div>

                            <?php if (in_array($item['status'], ['canceled', 'rejected'])) : ?>
                                <div class="col-md-12 d-flex mb-3 text-danger">
                                    <span class="me-2">سبب الرفض </span>
                                    <p class=" card-text">: <?= $item['reason'] ?></p>
                                </div>
                            <?php endif; ?>

                        </div>



                        <div class="col-md-12">
                            <hr>
                            <div class="col-md-12  mb-3 ">
                                <b class="me-2">المشكلة : </b>
                                <p class=" card-text"><?= $item['description'] ?></p>
                            </div>
                        </div>

                        <?php if ($rating !== false and in_array($item['status'], ['accepted', 'completed'])): ?>
                            <div class="col-md-12">
                                <hr>

                                    <b class="me-2">التقييم : </b>
                                <div class="col-md-12 my-2 border ">

                                    <div class="card-text rating fw-bold" style="font-size: 1.5rem">
                                        <?php for ($i = 1; $i <= 5; $i++) {
                                            $cls = ($rating['rate'] >= $i) ? 'rating-color' : '';
                                            echo "<label class='rating  $cls' data-val='$i'>&#9733;</label>";
                                        } ?>
                                    </div>
                                    <div class="col-md-12 d-flex mb-3 ">
                                        <div class="card-text rating  w-100"><?= $rating['review'] ?></div>
                                    </div>
                                </div>


                            </div>

                        <?php endif; ?>
                        <div class="col-md-12">
                            <!--                        <hr>-->
                            <p class="card-text float-end"><small class="text-muted">اخر
                                    تحديث <?= $item['updated_at'] ?></small></p>
                        </div>

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
            <?php endif; ?>
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