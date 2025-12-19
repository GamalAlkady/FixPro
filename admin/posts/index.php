<?php
require '../../config.php';
if (!isRole('admin')) {
    redirect('admin/login.php');
}
?>
<?php require '../../layout/index.php'; ?>
<?php
$items = (new DB('posts p'))
    ->join('technicians t', 'p.technician_id=t.id')
    ->select("p.*,CONCAT(t.first_name, ' ',t.last_name) as full_name")
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
                    <h1 class="h3 mb-0 text-gray-800">المنشورات</h1>
                    <!--                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> تولبد تقرير</a>-->
                </div>

          <div class="row ">
              <?php
              foreach ($items as $item) {
                  echo ' <div class="col-md-4 p-2"><div class="card mb2">
                       <div class="card-header">'.$item['full_name'].'</div>
                  <div class="card-body" style="max-height: 455px">
                      <img src="'.assets($item['image']).'" class="card-img-top mb-5" alt="..." height="300">
                      <h5 class="card-title fw-bold"><a href="details.php?id='.$item['id'].'">'.$item['title'].'</a></h5>
                      <p class="card-text text-truncate">'.$item['description'].'</p>
                  </div>
        
              </div></div>';
              }
              ?>

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
<?php require '../../components/confirmation.php' ?>
