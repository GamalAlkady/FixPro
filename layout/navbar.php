<nav class="navbar navbar-expand fixed-top navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link  rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>



    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">


        <?php
        $orders_notify = [];
        $title = 'الإشعارات';
        if (isRole('technician')) {
            $profile = (new DB($_SESSION['role'].'s'))->getBy('id',$_SESSION['id']);

            $orders_notify = (new DB('orders o'))
                ->join('users u', 'o.user_id=u.id')
                ->select("o.*,CONCAT(u.first_name, ' ',u.last_name) as full_name")
                ->where(['is_read'=>0,'o.technician_id' => $_SESSION['id']])
                ->where("status in('new','modify')")
                ->get();
        } elseif (isRole('user')) {
            $profile = (new DB($_SESSION['role'].'s'))->getBy('id',$_SESSION['id']);
            $orders_notify = (new DB('orders o'))
                ->join('technicians u', 'o.technician_id=u.id')
                ->where(['is_read'=>0,'user_id' => $_SESSION['id']])
                ->where("status in('accepted','rejected','canceled')")
                ->select("o.*,CONCAT(u.first_name, ' ',u.last_name) as full_name")
                ->get();
//            print_r($orders_notify);die();
        }
        ?>
        <!-- Nav Item - Alerts -->
        <?php if (isRole('user') or isRole('technician')): ?>
            <li class="nav-item dropdown no-arrow mx-1">
                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell fa-fw"></i>
                    <!-- Counter - Alerts -->
                    <span class="badge badge-danger badge-counter"><?= count($orders_notify) ?></span>
                </a>
                <!-- Dropdown - Alerts -->
                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                     aria-labelledby="alertsDropdown">
                    <h6 class="dropdown-header">
                        <?php echo $title; ?>
                    </h6>

                    <?php foreach ($orders_notify as $order) { ?>
                        <a class="dropdown-item d-flex align-items-center"
                           href="<?= get_path('orders/details.php?is_read=1&id=' . $order['id']) ?>">
                            <div class="mr-3">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">
                                    <?= $order['full_name'] ?>
                                </div>

                                <div>
                                    <?php
                                    if ($order['status'] == 'new') echo '<span class="font-weight-bold text-primary"> طلب جديد <span class="fw-light text-truncate d-inline-block w-100 ">' . $order['description'] . '</span> </span>';
                                    elseif ($order['status'] == 'modify') echo '<span class="font-weight-bold text-info"> تم تعديل الطلب رقم <span class="fw-light">' . $order['id'] . '</span> </span>';
                                    elseif ($order['status'] == 'accepted') echo '<span class="font-weight-bold text-primary"> تمت الموافقة على الطلب رقم <span class="fw-light">' . $order['id'] . '</span> </span>';
                                    elseif ($order['status'] == 'rejected') echo '<span class="font-weight-bold text-danger"> تم رفض الطلب <span class="fw-light">' . $order['reason'] . '</span> </span>';
                                    elseif ($order['status'] == 'canceled') echo '<span class="font-weight-bold text-danger"> تم الغاء الطلب  <span class="fw-light">' . $order['reason'] . '</span> </span>';
                                    ?>
                                </div>
                            </div>
                        </a>
                    <?php } ?>

                    <!--                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>-->
                </div>
            </li>
        <?php endif; ?>

        <?php if (isRole('admin')):
            $profile = (new DB('admin'))->getBy('id',$_SESSION['id']);

            $invoices_notify = (new DB('invoices i'))
                ->where('is_read', '0')
                ->join('technicians t', 'i.technician_id=t.id')
                ->select("i.*,CONCAT(t.first_name, ' ',t.last_name) as full_name")
                ->get();
            ?>
            <li class="nav-item dropdown no-arrow mx-1">
                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell fa-fw"></i>
                    <!-- Counter - Alerts -->
                    <span class="badge badge-danger badge-counter"><?= count($invoices_notify) ?></span>
                </a>
                <!-- Dropdown - Alerts -->
                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                     aria-labelledby="alertsDropdown">
                    <h6 class="dropdown-header">
                        <?php echo $title; ?>
                    </h6>

                    <?php foreach ($invoices_notify as $invoice) { ?>
                        <a class="dropdown-item d-flex align-items-center"
                           href="<?= get_path('invoices/details.php?id=' . $invoice['id']) ?>">
                            <div class="mr-3">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">
                                    <?= $invoice['full_name'] ?>
                                </div>

                                <div>
                                    <?php
                                   echo '<span class="font-weight-bold text-primary"> فاتورة جديده <span class="fw-light">' . $invoice['notes'] . '</span> </span>';
                                    ?>
                                </div>
                            </div>
                        </a>
                    <?php } ?>

                    <!--                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>-->
                </div>
            </li>
        <?php endif; ?>

        <div class="topbar-divider d-none d-sm-block"></div>


        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $profile['first_name'].' '.$profile['last_name'] ?></span>
                <img class="img-profile rounded-circle"
                     src="<?= assets($profile['image'], 'images/account.png') ?>">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="userDropdown">
                <a class="dropdown-item" href="<?=get_path('profile')?>">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    الملف الشخصي
                </a>


                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    تسجيل الخروج
                </a>
            </div>
        </li>

    </ul>

</nav>
