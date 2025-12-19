<?php
require '../../config.php';
if (!isRole('user')) {
    redirect('user/login.php');
}

require '../../layout/index.php';

?>
<?php $items = (new DB('invoices i'))
    ->join('orders o','o.id=i.order_id')
    ->where('o.user_id',$_SESSION['id'])
    ->select('i.*')->get();
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
                <h1 class="h3 mb-0 text-gray-800">الفواتير</h1>
                <!--                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> تولبد تقرير</a>-->
            </div>


            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">بيانات الفواتير</h6>

                    <?php include ROOT_PATH . '/components/alert.php' ?>
                </div>
                <div class="card-body">
                    <div class="">
                        <table class="table table-bordered table-striped table-responsive-stack" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th style="flex-basis: 10%">رقم الفاتورة</th>
                                <th style="flex-basis: 10%">المبلغ</th>
                                <th style="flex-basis: 20%">الملاحظات</th>
                                <th style="flex-basis: 10%">الحالة</th>
                                <th style="flex-basis: 10%">تاريخ الاستحقاق</th>
                                <th style="flex-basis: 10%">التاريخ</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                            foreach ($items as $item) {
                                echo '<tr>';
                                echo '<td style="flex-basis: 10%"><a href="details.php?id=' . $item['id'] . '" class="text-truncate " style="width: 250px">' . $item['id'] . '</a></td>';
                                echo '<td style="flex-basis: 10%" class="overflow-hidden"><p >' . $item['amount'] . ' ريال سعودي </p></td>';
                                echo '<td style="flex-basis: 20%" class="overflow-hidden"><p class="text-truncate w-truncate" style="width: 300px">' . $item['notes'] . '</p></td>';
                                echo '<td style="flex-basis: 10%">' . status($item['status']) . '</td>';
                                echo '<td style="flex-basis: 10%">' . $item['due_date'] . '</td>';
                                echo '<td style="flex-basis: 10%">' . date('d/m/Y', strtotime($item['created_at'])) . '</td>';
                                echo '</tr>';
                            }
                            ?>
                            </tbody>
                        </table>
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
