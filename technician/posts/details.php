<?php
require '../../config.php';
if (!isRole('technician')) {
    redirect('technician/login.php');
}

require '../../layout/index.php';

?>

<?php
$db = new DB('posts');
$item = null;
if (isset($_GET['id'])) {
    $item = $db->getBy(['id'=>$_GET['id'],'technician_id'=>$_SESSION['id']]);
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
                    echo '<div class="alert alert-danger">لا يوجد منشور بهذا الرقم</div>';
                else:?>
                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="card-title" id="from-actions-bottom-right"><?= $item['title'] ?></h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">

                        </div>
                    </div>
                    <img src="<?= assets($item['image']) ?>" class="card-img-top m-auto" style="width: 90%;height: 500px;">

                    <div class="card-body p-3">
                        <pre class="card-text"><?= $item['description'] ?></pre>
                        <p class="card-text float-end"><small class="text-muted">اخر تحديث <?= $item['created_at'] ?></small>
                        </p>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-around flex-nowrap">
                            <div>
                                <a href="edit.php?id=<?=$item['id']?>" class="btn btn-outline-primary">تعديل <i class="bx bx-edit"></i></a>
                            </div>
                            <form action="operations.php" method="post" id="formDelete<?=$item['id']?>">
                                <input type="hidden" name="delete" value="<?=$item['id']?>"/>
                                <button type="button" class="btn btn-outline-danger btnDelete" data-form="formDelete<?=$item['id']?>"  data-title="هل تريد حذف المنشور">
                                    حذف <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endif;?>
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
