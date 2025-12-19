<?php
require '../../config.php';
if (!isRole('user')) {
    redirect('user/login.php');
}
?>

<?php require '../../layout/index.php'; ?>
<?php
//if (!isset($_GET['id'])) die('not found');
//$item = (new DB('orders'))->getBy('id',$_GET['id']);
$item = false;
if (isset($_GET['id'])) {
    $db=(new DB('orders p'));
    $item = $db
        ->join('technicians t', 'p.technician_id=t.id')
        ->join('devices d', 'p.device_id=d.id','left')
        ->select("p.*,d.name as device_name,CONCAT(t.first_name, ' ',t.last_name) as full_name")
        ->where(['p.user_id' => $_SESSION['id']])
        ->where("p.id", $_GET['id'])
        ->getOne();

    if ($item !== false) {
        if (isset($_GET['is_read'])) $db->update(['is_read'=>1],$item['id']);
        $rating = (new DB('ratings'))
            ->getBy([
                'order_id' => $_GET['id'],
                'technician_id' => $item['technician_id'],
                'user_id' => $item['user_id']]);
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
            </div>
            <?php
            if ($item === false):
                echo '<div class="alert alert-danger">لا يوجد طلب بهذا الرقم</div>';
            else:?>
                <div class="card mb-5 shadow">
                    <div class="card-header">
                        <h4 class="card-title" id="from-actions-bottom-right">
                            بيانات الطلب <span><?= $item['id'] ?></span>
                        </h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">

                        </div>
                    </div>

                    <div class="card-body row">
                        <div class="col-md-12 mb-3">
                            <?php if (!empty($item['image'])): ?>
                                <img src="<?= assets($item['image']) ?>" class="card--top" height="300" width="100%" >
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">

                            <div class="col-md-12 d-flex mb-3">
                                <span class="me-2">اسم الفني : </span>
                                <p class=" card-text"><?= $item['full_name'] ?></p>
                            </div>
                            <?php if ($item['post_id']!=null):

                                $post=(new DB('posts'))->getBy('id',$item['post_id']);
                                ?>
                                <div class="col-md-12 d-flex mb-3">
                                    <span class="me-2">المنشور : </span>
                                    <a href="<?=get_path('posts/details.php?id='.$item['post_id'])?>" class="text-decoration-none"><?= $post['title'] ?></a>
                                </div>

                            <?php endif;?>
                            <div class="col-md-12 d-flex mb-3">
                                <span class="me-2">اسم الجهاز : </span>
                                <p class=" card-text"><?= $item['device_name']??'<span class="text-danger">تم حذف الجهاز</span>' ?></p>
                            </div>

                            <div class="col-md-12 d-flex mb-3">
                                <span class="me-2">الحالة : </span>
                                <p class=" card-text"><?= status($item['status']) ?></p>
                            </div>

                            <?php if (in_array($item['status'], ['canceled', 'rejected'])) : ?>
                                <div class="col-md-12 d-flex mb-3 ">
                                    <span class="me-2">سبب الرفض : </span>
                                    <p class=" card-text text-danger"><?= $item['reason'] ?></p>
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
                        <div class="col-md-12">
                            <p class="card-text float-end"><small class="text-muted">اخر
                                    تحديث <?= $item['updated_at'] ?></small></p>
                        </div>
                    </div>


                    <?php if ($item['status'] !== 'completed'): ?>
                        <div class="card-footer">
                            <div class="d-flex justify-content-around flex-nowrap">
                                <?php if ($item['status']==='accepted'): ?>
                                    <form action="operations.php" method="post" id="formCancel<?= $item['id'] ?>">
                                        <input type="hidden" name="cancel" value="<?= $item['id'] ?>"/>
                                        <button class="btn btn-outline-danger btnCancel" type="button"  data-form="formCancel<?= $item['id'] ?>"
                                                data-title="هل تريد إلغاء الطلب">
                                            إلغاء
                                            <i class="bx bx-trash"></i></button>
                                    </form>
                                <?php else: ?>
                                <div><a href="edit.php?id=<?= $item['id'] ?>" class="btn btn-outline-primary">تعديل <i class="bx bx-edit"></i></a></div>
                                    <form action="operations.php" method="post" id="formDelete<?= $item['id'] ?>">
                                    <input type="hidden" name="delete" value="<?= $item['id'] ?>"/>
                                    <button class="btn btn-outline-danger btnDelete" type="button"  data-form="formDelete<?= $item['id'] ?>"
                                            data-title="هل تريد حذف الطلب">
                                        حذف<i class="bx bx-trash"></i>
                                    </button>
                                </form>
                                <?php  endif;?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (in_array($item['status'], ['accepted', 'completed'])): ?>
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">التقييم</h1>
                    </div>

                    <div class="card shadow ">
                        <div class="card-body row">
                            <form action="operations.php" method="post">
                                <input type="hidden" name="id" value="<?=  $rating['id']??0 ?>">
                                <input type="hidden" name="order_id" value="<?= $item['id'] ?>">
                                <input type="hidden" name="technician_id" value="<?= $item['technician_id'] ?>">
                                <input type="hidden" name="user_id" value="<?= $_SESSION['id'] ?>">
                                <div class="col-md-12">

                                    <div class="col-md-12 d-flex mb-3">
                                        <div class="ratings">
                                            <?php for ($i = 1; $i <= 5; $i++) {
                                                $cls = (old('rating', $rating['rate']??1) >= $i) ? 'rating-color' : '';
                                                echo "<label class='rating  $cls' data-val='$i'>&#9733;</label>";
                                            } ?>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                    <textarea name="review" type="text" class="form-control" id="review"
                                              placeholder="المراجعة"><?=old('review',$rating['review']??'')?></textarea>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <button type="submit" name="rate" id="rating"
                                                value="<?=$rating['rate']??1?>"
                                                class="btn btn-outline-primary float-end">
                                            تقييم
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                <?php endif; ?>
            <?php endif; ?>
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
<script>
    $('.rating').on('click', function () {
        // console.log($(this).attr('for'))
        let n = $(this).data('val')
        $('.rating').removeClass('rating-color');
        $('#rating').val(n)
        const labels = $('.ratings').children();
        for (let i = 0; i < n; i++) {
            $(labels[i]).addClass('rating-color')
        }
    });
</script>