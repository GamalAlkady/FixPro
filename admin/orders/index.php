<?php
require '../../config.php';
if (!isRole('admin')) {
    redirect('admin/login.php');
}
require '../../layout/index.php';
?>
<?php
$items = (new DB('orders o'))
    ->join('devices d', 'o.device_id=d.id')
    ->join('users u', 'o.user_id=u.id')
    ->join('technicians t', 'o.technician_id=t.id')
    ->select("o.*,d.name,CONCAT(u.first_name, ' ',u.last_name) as user_name,CONCAT(t.first_name, ' ',t.last_name) as technician_name")
    ->get();

//echo json_encode($items);
//die();
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
                <h1 class="h3 mb-0 text-gray-800">الطلبات</h1>
                <!--                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> تولبد تقرير</a>-->
            </div>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">بيانات الطلبات</h6>
<!--                    <button class="btn btn-primary" id="filterButton">export</button>-->
                </div>
                <div class="card-body ">
                    <?php include ROOT_PATH . '/components/alert.php' ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-responsive-stack table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th  style="flex-basis: 10%">رقم الطلب</th>
                                <th  style="flex-basis: 15%">المستخدم</th>
                                <th  style="flex-basis: 10%">الفني</th>
                                <th  style="flex-basis: 15%">الجهاز</th>
                                <th  style="flex-basis: 30%">الوصف</th>
                                <th  style="flex-basis: 5%">الحالة</th>
                                <th  style="flex-basis: 10%">التاريخ</th>
                                <th  style="flex-basis: 5%"></th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                            foreach ($items as $item) {
//                            var_dump($item['status']);
                                echo '<tr>';
                                echo '<td style="flex-basis: 10%"><a href="details.php?id=' . $item['id'] . '">' . $item['id'] . '</a></td>';
                                echo '<td style="flex-basis: 15%">' . $item['user_name'] . '</td>';
                                echo '<td style="flex-basis: 10%">' . $item['technician_name'] . '</td>';
                                echo '<td style="flex-basis: 15%">' . $item['name'] . '</td>';
                                echo '<td style="flex-basis: 30%" class="overflow-hidden"><p class="text-truncate w-truncate">' . $item['description'] . '</p></td>';
                                echo '<td style="flex-basis: 5%">' . status($item['status']) . '</td>';
                                echo '<td style="flex-basis: 10%">' . date('d/m/Y',strtotime($item['updated_at'])) . '</td>';


                                echo '  <td style="flex-basis: 5%" >';
                                if ($item['status'] == 'completed') {
                                    echo '<form action="operations.php" method="post" id="formDelete' . $item['id'] . '">
                                <input type="hidden" name="delete" value="' . $item['id'] . '"/>
                                    <button type="button" class="btn text-danger p-0 btnDelete font-size" data-form="formDelete' . $item['id'] . '"  data-title="هل تريد حذف الطلب"><i class="fa fa-trash"></i></button>
                                </form>';
                                    echo ' </form> </td></tr>';
                                }else echo '';
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

    // intDatatable([0,1,2,3,4,5,6]);
</script>
