<?php
require '../../config.php';
if (!isRole('admin')) {
    redirect('admin/login.php');
}

require '../../layout/index.php';
$items = (new DB('orders o'))
    ->join('users u','o.user_id=u.id')
    ->join('technicians t','o.technician_id=t.id')
    ->join('invoices i','i.order_id=o.id')
    ->select("o.id,o.description,o.updated_at,i.id as invoice_id,i.amount,i.due_date,i.status as invoice_status,CONCAT(u.first_name,' ',u.last_name) as user_name,
    CONCAT(t.first_name,' ',t.last_name) as tech_name")
    ->get();
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
            <div class="d-sm-flex  align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">التقارير</h1>
                <button id="filterButton" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> تولبد تقرير</button>
            </div>


            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">بيانات التقارير</h6>

                    <?php include ROOT_PATH . '/components/alert.php' ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-bordered table-responsive-stack table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                           <tr id="th">
                               <th style="flex-basis: 10%">رقم الفاتورة</th>
                               <th style="flex-basis: 10%">الفني</th>
                               <th style="flex-basis: 10%">المستخدم</th>
                               <th style="flex-basis: 10%">حالة الفاتورة</th>
                               <th style="flex-basis: 10%">مبلغ الصيانة</th>
                               <th style="flex-basis: 10%">تاريخ الطلب</th>
                               <th style="flex-basis: 10%">تاريخ الاستحقاق</th>
                           </tr>
<!--                            <tr>-->
<!--                                <th>رقم الفاتورة</th>-->
<!--                                <th>الفني</th>-->
<!--                                <th>المستخدم</th>-->
<!--                                <th>حالة الفاتورة</th>-->
<!--                                <th>مبلغ الصيانة</th>-->
<!--                                <th>تاريخ الطلب</th>-->
<!--                                <th>تاريخ الاستحقاق</th>-->
<!--                            </tr>-->
                            </thead>


                            <tbody>

                            <?php
                            foreach ($items as $item) {
                                echo '<tr>';
                                echo '<td style="flex-basis: 10%">' . $item['invoice_id'] . '</td>';
                                echo '<td style="flex-basis: 10%">' . $item['user_name'] . '</td>';
                                echo '<td style="flex-basis: 10%">' . $item['tech_name'] . '</td>';
                                echo '<td style="flex-basis: 10%" class="badge '.(($item['invoice_status']=='unpaid')?'text-warning':'text-success').' " style="font-size: 1rem">' . (($item['invoice_status']=='unpaid')?"غير مدفوع":"مدفوع") . '</td>';
                                echo '<td style="flex-basis: 10%">' . $item['amount'] . ' ر.س</td>';
                                echo '<td style="flex-basis: 10%">' . date('d/m/Y', strtotime($item['updated_at'])) . '</td>';
                                echo '<td style="flex-basis: 10%">' . $item['due_date'] . '</td>';
                                echo '</tr>';
                            }
                            ?>
                            </tbody>
<!--                            <tfoot>-->
<!--                                <tr>-->
<!--                                    <th>رقم الطلب</th>-->
<!--                                    <th>الفني</th>-->
<!--                                    <th>المستخدم</th>-->
<!--                                    <th>حالة الفاتورة</th>-->
<!--                                    <th>مبلغ الصيانة</th>-->
<!--                                    <th>تاريخ الطلب</th>-->
<!--                                    <th>تاريخ الاستحقاق</th>-->
<!--                                </tr>-->
<!--                            </tfoot>-->
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
<script>

    // $('#dataTable').DataTable({
    //     "scrollX": false,
    //     order: [],
    //     searching: true,
    //     select: true,
    //     paging: false,
    //     info: false,
    //     // rtl:true,
    //     language: {url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/ar.json'},
    // });
    // intDatatable([0,1,2,3,4,5,6]);
</script>