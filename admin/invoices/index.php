<?php
require '../../config.php';
if (!isRole('admin')) {
    redirect('admin/login.php');
}

require '../../layout/index.php';

?>
<?php $items = (new DB('invoices'))->GetAll(); ?>

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
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-responsive-stack" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th style="flex-basis: 10%">رقم الفاتورة</th>
                                <th style="flex-basis: 10%">المبلغ</th>
                                <th style="flex-basis: 15%">الملاحظات</th>
                                <th style="flex-basis: 10%">الحالة</th>
                                <th style="flex-basis: 10%">تاريخ الاستحقاق</th>
                                <th style="flex-basis: 10%">التاريخ</th>
                                <th style="flex-basis: 5%"></th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                            foreach ($items as $item) {
                                echo '<tr>';
                                echo '<td style="flex-basis: 10%"><a href="details.php?id=' . $item['id'] . '" >' . $item['id'] . '</a></td>';
                                echo '<td style="flex-basis: 10%"><p >' . $item['amount'] . ' ريال سعودي</p></td>';
                                echo '<td style="flex-basis: 15%" class="overflow-hidden"><p class="text-truncate w-truncate" >' . $item['notes'] . '</p></td>';
                                echo '<td style="flex-basis: 10%">' . status($item['status']) . '</td>';
                                echo '<td style="flex-basis: 10%">' . $item['due_date'] . '</td>';
                                echo '<td style="flex-basis: 10%">' . date('d/m/Y', strtotime($item['created_at'])) . '</td>';


                                    echo '<td class="" style="padding: 10px;flex-basis: 5%">';
                                if ($item['status'] == 'paid') { ?>
                                        <div class="d-flex justify-content-center flex-nowrap">
                                            <form action="operations.php" method="post" id="formDelete<?=$item['id']?>">
                                                <input type="hidden" name="delete" value="<?=$item['id']?>"/>
                                                <button type="button" class="btn text-danger p-0 btnDelete font-size" data-form="formDelete<?=$item['id']?>"  data-title="هل تريد حذف الفاتورة"><i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                <?php }
                                    echo  '</td>';
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
