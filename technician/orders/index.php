<?php
require '../../config.php';
if (!isRole('technician')) {
    redirect('technician/login.php');
}
require '../../layout/index.php';
?>
<?php
$items = (new DB('orders o'))
    ->join('devices d', 'o.device_id=d.id')
    ->join('users t', 'o.user_id=t.id')
    ->where('o.technician_id',$_SESSION['id'])
    ->select("o.*,d.name,CONCAT(t.first_name, ' ',t.last_name) as full_name,t.phone_number as phone_number")
    ->orderBy('o.updated_at desc')
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

                </div>
                <div class="card-body ">
                    <?php include ROOT_PATH . '/components/alert.php' ?>
                    <div class="table-responsive-stack">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th style="flex-basis: 10%">رقم الطلب</th>
                            <th style="flex-basis: 15%">المستخدم</th>
                            <th style="flex-basis: 20%">الجهاز</th>
                            <th style="flex-basis: 30%">المشكلة</th>
                            <th style="flex-basis: 5%">الحالة</th>
                            <th style="flex-basis: 8%" >التاريخ</th>
                            <th style="flex-basis: 12%" ></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        foreach ($items as $item) {
//                            var_dump($item['status']);
                            echo '<tr>';
                            echo '<td style="flex-basis: 10%"><a href="details.php?id=' . $item['id'] . '">' . $item['id'] . '</a></td>';
                            echo '<td style="flex-basis: 15%"><p>' . $item['full_name'] . '</p></td>';
                            echo '<td style="flex-basis: 20%"><p>' . $item['name'] . '</p></td>';
                            echo '<td style="flex-basis: 30%" class="overflow-hidden"><p class="text-truncate w-truncate">' . $item['description'] . '</p></td>';
                            echo '<td style="flex-basis: 5%">' . status($item['status']) . '</td>';
                            echo '<td style="flex-basis: 8%">' . date('d/m/Y', strtotime($item['created_at'])) . '</td>';


                            echo '  <td style="flex-basis: 12%" >
                                <form action="operations.php" method="post" class="formRejected d-flex justify-content-between flex-column">
                                <input type="hidden" name="id" value="' . $item['id'] . '"/>'
                            ?>
                            <div class="w-100">
                                <a href="https://api.whatsapp.com/send?phone=<?=$item['phone_number']?>" target="_blank" class="btn btn-outline-primary w-100">دردشة فورية</a>
                            </div>

                            <div class="w-100 mt-2 d-flex justify-content-between">
                            <?php if($item['status']=='accepted'){?>
<!--                                <input type="hidden" name="status" value="">-->
                                    <button class="btn btn-outline-success" type="submit" name="accept" value="completed">
                                    اكتمال</button>
                                <button class="btn btn-outline-danger btn-reject" data-id="<?=$item['id']?>" data-title="سبب الإلغاء" name="reject" value="canceled" type="button">الغاء</button>

                                <?php } elseif($item['status']=='new' or $item['status']=='modify'){?>
                                <button class="btn btn-outline-success" type="submit" name="accept" value="accepted">
                                    قبول</button>
                                <button class="btn btn-outline-danger btn-reject" data-id="<?=$item['id']?>" data-title="سبب الرفض" name="reject" value="rejected" type="button">رفض</button>
                                <?php }
                            elseif ($item['status']=='completed') echo '<a href="'.get_path('invoices/add.php?order_id='.$item['id']).'" class="btn btn-outline-info m-auto" style="width: 100px">فوترة</a>'?>
                            </div>

                        <?php
                            echo ' </form> </td></tr>';
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

</script>
