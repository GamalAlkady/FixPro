<?php
require '../../config.php';
if (!isRole('technician')) {
    redirect('technician/login.php');
}

require '../../layout/index.php';

?>
<?php $items = (new DB('posts'))->where('technician_id',$_SESSION['id'])->get(); ?>

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
                    <h1 class="h3 mb-0 text-gray-800">المنشورات</h1>
                    <!--                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> تولبد تقرير</a>-->
                </div>


                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">بيانات المنشورات</h6>

                        <?php include ROOT_PATH.'/components/alert.php' ?>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-responsive-stack" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th style="flex-basis: 20%;">العنوان</th>
                                    <th style="flex-basis: 35%">الوصف</th>
                                    <th style="flex-basis: 10%">الصورة</th>
                                    <th style="flex-basis: 15%">التاريخ</th>
                                    <th style="flex-basis: 10%"></th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                foreach ($items as $item) {
                                    echo '<tr>';
                                    echo '<td  style="flex-basis: 20%;" ><a href="details.php?id=' . $item['id'] . '" >' . $item['title'] . '</a></td>';
                                    echo '<td  style="flex-basis: 35%;" class="w-td"><p class=" text-truncate w-truncate">' . $item['description'] . '</p></td>';
                                    echo '<td style="flex-basis: 10%"><img width="100px" height="50px" src="' . assets($item['image']) . '" /></td>';
                                    echo '<td style="flex-basis: 15%">' . $item['created_at'] . '</td>';


                                    echo '  <td class="" style="padding: 10px;flex-basis: 10%">
                            <div class="d-flex justify-content-around flex-nowrap">
                                <div>
                                    <a href="edit.php?id=' . $item['id'] . '" class="btn text-info p-0"  style="font-size:1.6rem;"><i class="fa fa-edit"></i></a>
                                </div>
                                <form action="operations.php" method="post" id="formDelete' . $item['id'] . '">
                                <input type="hidden" name="delete" value="' . $item['id'] . '"/>
                                    <button type="button" class="btn text-danger p-0 btnDelete" style="font-size:1.6rem;" data-form="formDelete' . $item['id'] . '"  data-title="هل تريد حذف المنشور"><i class="fa fa-trash"></i></button>
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
