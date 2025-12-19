<?php
require '../../config.php';
if (!isRole('technician')) {
    redirect('technician/login.php');
}

require '../../layout/index.php';

?>
<?php
$item = (new DB('technicians'))->getBy('id',$_SESSION['id']);
$rating=(new DB('ratings'))
    ->select('AVG(rate) as rate')
    ->where('technician_id',$item['id'])
    ->getOne();

$db=new DB('orders');
$orders=$db->select('COUNT(*) as total')->getOne();
$tech_orders=$db->select('status,COUNT(id) as count_order')
    ->where('technician_id',$_SESSION['id'])
    ->where('status in ("completed","canceled","user_canceled")')
    ->groupBy('status')
    ->get();

$total_tech_orders=0;
foreach ($tech_orders as $order){
    $total_tech_orders +=$order['count_order'];
}

if ($total_tech_orders!==0) {
    $total_orders = $orders['total'];
    $percent_tech_orders = $total_tech_orders / $total_orders * 100;

    $percent_tech_status_orders = [];
    foreach ($tech_orders as $order) {
        $percent_tech_status_orders[$order['status']] = $order['count_order'] / $total_tech_orders * 100;
    }
}

$count_posts=(new DB('posts'))->select('COUNT(*) as count')->getOne()['count'];
$count_tech_posts=(new DB('posts'))->select('COUNT(*) as count')->where('technician_id',$_SESSION['id'])->getOne()['count'];

$count_tech_posts=$count_tech_posts/$count_posts*100;
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
                                        <img src="<?=assets($item['image'],'images/account.png')?>" alt="avatar"
                                             class="rounded-circle img-fluid" style="width: 150px;">
                                        <h5 class="my-3"><?=$item['first_name']?></h5>
                                        <p class="text-muted mb-4"><?=$item['address']?></p>
                                        <div class="d-flex justify-content-center mb-2">
                                            <p class="text-muted mb-4">نسبة التقييم: <span><?=$rating['rate']??0?>%</span></p>
                                        </div>

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
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0">العنوان</p>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="text-muted mb-0"><?=$item['address']?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card mb-4 mb-md-0">
                                            <div class="card-body">
                                                <p class="mb-4"><span class="text-primary font-italic me-1">ألإحصائيات</span></p>
                                                <p class="mb-1" style="font-size: .77rem;">المنشورات</p>
                                                <div class="progress rounded" style="height: 5px;">
                                                    <div class="progress-bar" role="progressbar" style="width: <?=$count_tech_posts?>%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>

                                                <p class="mt-4 mb-1" style="font-size: .77rem;">الطلبات</p>
                                                <div class="progress rounded" style="height: 5px;">
                                                    <div class="progress-bar" role="progressbar" style="width: <?=$percent_tech_orders?>%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <p class="mt-4 mb-1" style="font-size: .77rem;">الطلبات المكتملة</p>
                                                <div class="progress rounded" style="height: 5px;">
                                                    <div class="progress-bar" role="progressbar" style="width: <?=$percent_tech_status_orders['completed']??0 ?>%" aria-valuenow="72"
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
