<?php
require '../../config.php';
if (!isRole('user')) {
    redirect('user/login.php');
}

require '../../layout/index.php';

?>
<?php
$item = (new DB('users'))->getBy('id', $_SESSION['id']);


$db = new DB('orders');
$orders = $db->select('COUNT(*) as total')->where('user_id', $_SESSION['id'])->getOne();
$tech_orders = $db->select('status,COUNT(id) as count_order')
    ->where('user_id', $_SESSION['id'])
    ->where('status in ("completed","canceled","user_canceled")')
    ->groupBy('status')
    ->get();

$total_tech_orders = 0;
foreach ($tech_orders as $order) {
    $total_tech_orders += $order['count_order'];
}

$total_orders = $orders['total'];
if ($total_tech_orders)
    $percent_tech_orders = $total_tech_orders / $total_orders * 100;

$percent_tech_status_orders = [];
foreach ($tech_orders as $order) {
    $percent_tech_status_orders[$order['status']] = $order['count_order'] / $total_tech_orders * 100;
}

$web_rating = (new DB('web_ratings'))->getBy('user_id', $_SESSION['id']);


//var_dump($count_tech_posts); die();
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
                                <img src="<?= assets($item['image'], 'images/account.png') ?>" alt="avatar"
                                     class="rounded-circle img-fluid" style="width: 150px;">
                                <h5 class="my-3"><?= $item['first_name'] ?></h5>

                                <div class="d-flex flex-column justify-content-end">
                                    <a href="<?= get_path('profile/edit.php?id=' . $_SESSION['id']) ?>"
                                       class="link nav-link text-start">تعديل الملف الشخصي</a>
                                    <a href="<?= get_path('profile/change_password.php?id=' . $_SESSION['id']) ?>"
                                       class="link nav-link text-start">تغيير كلمة المرور</a>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body text-center">
                                <form class="form needs-validation" id="formRating" action="operations.php" method="post" enctype="multipart/form-data" novalidate>
                                    <input type="hidden" name="id" value="<?= $web_rating['id'] ?? 0 ?>">
                                    <h5 class="">تقييم الموقع</h5>
                                    <div class="ratings">
                                        <?php for ($i = 1; $i <= 5; $i++) {
                                            $cls = (($web_rating['rate']==0?1:$web_rating['rate']) >= $i) ? 'rating-color' : '';
                                            echo "<label class='rating $cls' data-val='$i'>&#9733;</label>";
                                        } ?>
                                    </div>
                                    <textarea name="review" type="text" class="form-control" id="review"
                                              placeholder="المراجعة" required><?= old('review',$web_rating['review']) ?></textarea>
                                    <div class="d-flex flex-column justify-content-end">
                                        <button type="submit" name="rate" id="rating"
                                                class="btn btn-outline-primary mt-2">تقييم
                                        </button>
                                    </div>
                                </form>
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
                                        <p class="text-muted mb-0"><?= $item['first_name'] . ' ' . $item['last_name'] ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">الإيميل</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0"><?= $item['email'] ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">الهاتف</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0"><?= $item['phone_number'] ?></p>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-4 mb-md-0">
                                    <div class="card-body">
                                        <p class="mb-4"><span class="text-primary font-italic me-1">ألإحصائيات</span>
                                        </p>

                                        <p class="mb-1" style="font-size: .77rem;">الطلبات</p>
                                        <div class="progress rounded" style="height: 5px;">
                                            <div class="progress-bar" role="progressbar"
                                                 style="width: <?= $percent_tech_orders ?>%" aria-valuenow="80"
                                                 aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="mt-4 mb-1" style="font-size: .77rem;">الطلبات المكتملة</p>
                                        <div class="progress rounded" style="height: 5px;">
                                            <div class="progress-bar" role="progressbar"
                                                 style="width: <?= $percent_tech_status_orders['completed'] ?? 0 ?>%"
                                                 aria-valuenow="72"
                                                 aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>

                                        <p class="mt-4 mb-1" style="font-size: .77rem;">الطلبات الملغية</p>
                                        <div class="progress rounded" style="height: 5px;">
                                            <div class="progress-bar" role="progressbar"
                                                 style="width: <?= $percent_tech_status_orders['user_canceled'] ?? 0 ?>%"
                                                 aria-valuenow="55"
                                                 aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>

                                        <p class="mt-4 mb-1" style="font-size: .77rem;">الطلبات الملغاة من الفني</p>
                                        <div class="progress rounded" style="height: 5px;">
                                            <div class="progress-bar" role="progressbar"
                                                 style="width: <?= $percent_tech_status_orders['canceled'] ?? 0 ?>%"
                                                 aria-valuenow="89"
                                                 aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>

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

<script>
    (function () {
        "use strict";

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
    })();

</script>