<?php
require '../../config.php';
if (!isRole('admin')) {
    redirect('admin/login.php');
}

require '../../layout/index.php';

$items=(new DB('devices'))->getAll();
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

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">بيانات الجهاز</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped  table-responsive-stack" id="dataTable" cellspacing="0">
                                <thead>
                                <tr>
                                    <th style="flex-basis: 15%">اسم الجهاز</th>
                                    <th style="flex-basis: 10%">نوع الجهاز</th>
                                    <th style="flex-basis: 10%">الموقع</th>
                                    <th style="flex-basis: 20%">الملاحظة</th>
                                    <th style="flex-basis: 10%"></th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                foreach ($items as $item) {
                                    echo '<tr>';
                                    echo '<td style="flex-basis: 15%">' . $item['name'] . '</td>';
                                    echo '<td style="flex-basis: 10%">' . $item['type'] . '</td>';
                                    echo '<td style="flex-basis: 10%">' . $item['location'] . '</td>';
                                    echo '<td style="flex-basis: 20%" class="overflow-hidden"><p class="text-truncate w-truncate">' . $item['notes'] . '</p></td>';

                                    echo '  <td class="" style="padding: 10px;flex-basis: 10%">
                            <div class="d-flex justify-content-around flex-nowrap">
                                <div>
                                    <a href="edit.php?id=' . $item[0] . '" class="btn p-0 font-size text-primary"><i class="bx bx-edit"></i></a>
                                </div>
                                           <form action="operations.php" method="post" id="formDelete' . $item[0] . '">
                                <input type="hidden" name="delete" value="' . $item[0] . '"/>
                                    <button type="button" class="btn text-danger p-0 btnDelete font-size" data-form="formDelete' . $item[0] . '"  data-title="هل تريد حذف الجهاز"><i class="fa fa-trash"></i></button>
                                </form>
                            </div>
                        </td>';
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
