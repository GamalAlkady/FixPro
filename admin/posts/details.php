<?php
require '../../config.php';
if (!isRole('admin')) {
    redirect('admin/login.php');
}
?>

<?php require '../../layout/index.php'; ?>
<?php
$item = [];
if (isset($_GET['id'])) {
    $items = (new DB('posts p'))
        ->join('technicians t', 'p.technician_id=t.id')
        ->select("p.*,CONCAT(t.first_name, ' ',t.last_name) as full_name")
        ->where("p.id", $_GET['id'])
        ->get();
    if (count($items) > 0) $item = $items[0];
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
                    echo '<div class="alert alert-danger">لا يوجد منشور بهذا الرقم</div>';
                    die();
                }
                ?>
                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="card-title" id="from-actions-bottom-right">الفني <?=$item['full_name']?></h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">

                        </div>
                    </div>
                    <img src="<?= assets($item['image']) ?>" class="card-img-top" height="400">

                    <div class="card-body">
                        <h5 class="card-title fw-bold"><?= $item['title'] ?></h5>
                        <p class="card-text"><?= $item['description'] ?></p>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between flex-nowrap">
                            <div>
<!--                                <a href="../orders/add.php?technician_id=--><?//=$item['technician_id']?><!--" class="btn btn-primary">طلب صيانة</a>                            </div>-->
                            <p class="card-text float-end"><small class="text-muted">اخر تحديث <?= $item['created_at'] ?></small></p>
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