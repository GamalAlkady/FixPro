<?php
require '../../config.php';
if (!isRole('user')) {
    redirect('user/login.php');
}

require '../../layout/index.php';

?>
<?php
$item = false;

if (isset($_GET['id'])) {
    $db = new DB('invoices i');
    $item = $db->join('orders o', 'i.order_id=o.id')
        ->join('users u', 'o.user_id = u.id')
        ->join('technicians t', 'o.technician_id = t.id')
        ->join('devices d', 'o.device_id = d.id')
        ->select("i.*,o.description,d.name,d.location,
        CONCAT(u.first_name, ' ',u.last_name) as full_name,
        CONCAT(t.first_name, ' ',t.last_name) as tech_name,t.address")
        ->where(["i.id"=> $_GET['id'],'o.user_id'=>$_SESSION['id']])
        ->getOne();
    $admin_name = (new DB('admin'))
        ->select("CONCAT(first_name, ' ',last_name) as full_name")
        ->getOne()['full_name'];
//    $db->update(['is_read' => '1'], $_GET['id']);
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
                <h2>الفاتورة</h2>
                <a href="<?=get_path('invoice_pdf.php?id='.$item['id'],false)?>" target="_blank" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> توليد الفاتورة</a>
            </div>
            <?php
            if ($item === false) {
                echo '<div class="alert alert-danger">لا توجد فاتورة بهذا الرقم</div>';
            } else {
                ?>
                <section class="card">
                    <div id="invoice-template" class="card-body">
                        <!-- Invoice Company Details -->
                        <div id="invoice-company-details" class="row">
                            <div class="col-md-6 col-sm-12 text-center text-md-left">
                                <div class="media">
                                    <img src="<?= assets('images/logo/logo.png') ?>" alt="company logo" class=""
                                         width="70px"/>
                                    <div class="media-body">
                                        <ul class="ml-2 px-0 list-unstyled text-start">
                                            <li class="text-bold-800">فيكس برو</li>
                                            <li>لصيانة الأجهزة الإلكترونية</li>
                                            <li>www.fixpro.com</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Invoice Company Details -->
                        <!-- Invoice Customer Details -->
                        <div id="invoice-customer-details" class="row pt-2">
                            <div class="col-md-9 col-sm-12 ">
                                <h2>الفاتورة</h2>
                                <p class="pb-3"># INV-001001</p>
                                <p>  <span>المبلغ المستحق</span> :
                                    <span class="lead text-bold-800"><?= $item['amount'] ?> ريال سعودي</span></p>

                                <p>
                                    <span class="text-muted">تاريخ الفاتورة :</span> <?= date('d/m/Y', strtotime($item['created_at'])) ?>
                                </p>
                                <p><span class="text-muted">الشروط :</span> مستحقة عند الاستلام</p>
                                <p>
                                    <span class="text-muted">تاريخ الاستحقاق :</span> <?= date('d/m/Y', strtotime($item['due_date'])) ?>
                                </p>
                            </div>

                            <!--                            <div class="col-sm-2 bg-danger text-center text-md-left"></div>-->
                            <div class="col-md-3 col-sm-12  ">
                                <div class="mb-5">
                                    <p class="text-muted me-3"> من : </p>
                                    <p class="text-bold-800"> <?= $item['tech_name'] ?></p>
                                    <p class="text-bold-800"> <?= $item['address'] ?></p>
                                </div>

                                <div>
                                    <p class="text-muted me-3"> ل : </p>
                                    <p class="text-bold-800"> <?= $admin_name ?></p>
                                </div>
                            </div>

                        </div>
                        <!--/ Invoice Customer Details -->
                        <!-- Invoice Items Details -->
                        <div id="invoice-items-details" class="pt-2">

                            <div class="row mt-5">

                                <div class="col-md-12 col-sm-12">
                                    <!--                                    <p class="lead">Total due</p>-->
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                            <tr>
                                                <td width="100px">الطلب</td>
                                                <td class=""><?= $item['order_id'] ?>#</td>
                                            </tr>
                                            <tr>
                                                <td>المستخدم</td>
                                                <td class=""><?= $item['full_name'] ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-bold-800">الجهاز</td>
                                                <td class="text-bold-800 "><?= $item['name'] ?></td>
                                            </tr>
                                            <tr>
                                                <td>الموقع</td>
                                                <td class="pink "><?= $item['location'] ?></td>
                                            </tr>
                                            <tr class="bg-grey bg-lighten-4">
                                                <td class="text-bold-800">المشكلة</td>
                                                <td class="text-bold-800 "><?= $item['description'] ?></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- Invoice Footer -->
                        <div id="invoice-footer">
                            <div class="row">


                            </div>
                        </div>
                        <!--/ Invoice Footer -->
                    </div>
                </section>
            <?php } ?>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <?php require ROOT_PATH . '/layout/footer.php' ?>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->
</div>

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
