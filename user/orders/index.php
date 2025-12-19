<?php
require '../../config.php';
if (!isRole('user')) {
    redirect('user/login.php');
}
require '../../layout/index.php';

$items = (new DB('orders o'))
    ->join('devices d', 'o.device_id=d.id')
    ->join('technicians t', 'o.technician_id=t.id')
    ->select("o.*,d.name,CONCAT(t.first_name, ' ',t.last_name) as full_name")
    ->orderBy('updated_at desc')
    ->where('user_id',$_SESSION['id'])
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
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">الطلبات</h1>
                    <!--                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> تولبد تقرير</a>-->
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">بيانات الطلبات</h6>

                        <?php include ROOT_PATH . '/components/alert.php' ?>
                    </div>
                    <div class="card-body ">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-responsive-stack" id="dataTable" cellspacing="0">
                                <thead>
                                <tr>
                                    <th style="flex-basis: 10%">رقم الطلب</th>
                                    <th style="flex-basis: 10%">الفني</th>
                                    <th style="flex-basis: 20%">الجهاز</th>
                                    <th style="flex-basis: 30%">الوصف</th>
                                    <th style="flex-basis: 10%">الحالة</th>
                                    <th style="flex-basis: 10%">التاريخ</th>
                                    <th style="flex-basis: 10%"></th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                foreach ($items as $item) {
                                    echo '<tr>';
                                    echo '<td style="flex-basis: 10%"><a href="details.php?id=' . $item['id'] . '">' . $item['id'] . '</a></td>';
                                    echo '<td style="flex-basis: 10%"><p>' . $item['full_name'] . '</p></td>';
                                    echo '<td style="flex-basis: 20%"><p>' . $item['name'] . '</p></td>';
                                    echo '<td style="flex-basis: 30%" class="overflow-hidden"><p class="text-truncate w-truncate" >' . $item['description'] . '</p></td>';
                                    echo '<td style="flex-basis: 10%">' . status($item['status']) . '</td>';
                                    echo '<td style="flex-basis: 10%">' . date('d/m/Y', strtotime($item['created_at'])) . '</td>';

                                    ?>

                                    <?php
                                    if ($item['status'] == 'accepted') { ?>
                                        <td style="flex-basis: 10%">
                                            <div class="d-flex justify-content-center flex-nowrap">
                                                <form action="operations.php" method="post"
                                                      id="formCancel<?= $item['id'] ?>">
                                                    <input type="hidden" name="cancel" value="<?= $item['id'] ?>"/>
                                                    <button type="button" class="btn btn-outline-danger align-items-center btnCancel "
                                                            style="width: 100px"
                                                            data-form="formCancel<?= $item['id'] ?>"
                                                            data-title="هل تريد الغاء الطلب">
                                                        الغاء <i class="fa fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        <?php
                                    } elseif ($item['status'] == 'completed') {
                                        echo '<td style="flex-basis: 10%"></td>';
                                    } else { ?>
                                        <td style="flex-basis: 10%">
                                            <div class="d-flex justify-content-around flex-nowrap">
                                                <div><a href="edit.php?id=<?= $item['id'] ?>"
                                                        class="btn text-primary  "><i class="fa fa-edit"></i></a></div>

                                                <form action="operations.php" method="post"
                                                      id="formDelete<?= $item['id'] ?>">
                                                    <input type="hidden" name="delete" value="<?= $item['id'] ?>"/>
                                                    <button type="button" class="btn text-danger  btnDelete"
                                                            data-form="formDelete<?= $item['id'] ?>"
                                                            data-title="هل تريد حذف الطلب">
                                                         <i class="fa fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    <?php }
                                    echo '</tr>';
                                } ?>
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
<!---->
<?php //if ($web_rating === false or $interval>=3): ?>
<!--    <div class="modal  fade" id="web_rating" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"-->
<!--         aria-hidden="false">-->
<!--        <div class="modal-dialog" role="document">-->
<!--            <div class="modal-content">-->
<!--                <div class="modal-header">-->
<!--                    <h5 class="modal-title" id="exampleModalLabel">تقييم الموقع</h5>-->
<!--                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">-->
<!--                        <span aria-hidden="false">×</span>-->
<!--                    </button>-->
<!--                </div>-->
<!--                <div class="modal-body">-->
<!--                    <form class="form" id="formRating" action="operations.php" method="post"-->
<!--                          enctype="multipart/form-data"-->
<!--                          novalidate>-->
<!--                        <input type="hidden" name="id" value="--><?php //echo $web_rating['id']??0 ?><!--">-->
<!--                        <input type="hidden" name="user_id" value="--><?php //echo $_SESSION['id'] ?><!--">-->
<!--                        <div class="col-md-12">-->
<!---->
<!--                            <div class="col-md-12 d-flex mb-3">-->
<!--                                <div class="ratings">-->
<!--                                    --><?php //for ($i = 1; $i <= 5; $i++) {
//                                        $cls = (old('rating', 1) >= $i) ? 'rating-color' : '';
//                                        echo "<label class='rating  $cls' data-val='$i'>&#9733;</label>";
//                                    } ?>
<!--                                </div>-->
<!--                            </div>-->
<!---->
<!---->
<!--                            <div class="col-md-12">-->
<!--                                    <textarea name="review" type="text" class="form-control" id="review"-->
<!--                                              placeholder="المراجعة" required>--><?php //echo old('review') ?><!--</textarea>-->
<!--                            </div>-->
<!--                            <div class="col-md-12 mt-2">-->
<!--                                <button type="submit" name="web_rate" id="rating" class="btn btn-outline-primary ">تقييم</button>-->
<!--                                <button class="btn btn-secondary float-end" type="submit" name="later" >لاحقاً</button>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </form>-->
<!--                </div>-->
<!---->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->

    <script>
        (function () {
            "use strict";
            const myModal = new bootstrap.Modal(document.getElementById('web_rating'), {keyboard: false});
            myModal.show()
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

                const form=document.getElementById('formRating');
            $('#rating').on('click',function (){
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            })
        })();

    </script>

<?php //endif; ?>