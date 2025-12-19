<ul class="navbar-nav position-fixed bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <div class="w-100 position-fixed overlay sidebarToggle"></div>
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center bg-white shadow text-dark" href="index.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <img src="<?=assets('images/logo/logo.png')?>" alt="" width="30px">
        </div>
        <div class="sidebar-brand-text mx-3 fw-bold">فيكس برو</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->

    <?php  if (isRole('admin') or isRole('technician')):?>
        <li class="nav-item active">
            <a class="nav-link" href="<?=get_path('')?>">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>لوحة التحكم</span></a>
        </li>
    <?php endif;?>
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
<!--    <div class="sidebar-heading">-->
<!--        Interface-->
<!--    </div>-->

    <!-- Nav Item - Pages Collapse Menu -->
    <?php if (isRole('admin')){ ?>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
           aria-expanded="true" aria-controls="collapseTwo">
            <i class=" bx bx-devices"></i>
            <span>ادارة الاجهزة</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header"></h6>
                <a class="collapse-item" href="<?=get_path('devices')?>">عرض الاجهزة</a>
                <a class="collapse-item" href="<?=get_path('devices/add.php')?>">اضافة جهاز</a>
            </div>
        </div>
    </li>
        <hr class="sidebar-divider">

        <li class="nav-item">
            <a class="nav-link" href="<?=get_path('orders')?>">
                <i class="fas fa-fw fa-file"></i>
                <span>الطلبات</span></a>
        </li>
    <?php }?>

    <?php if (isRole('technician')){ ?>
        <li class="nav-item">
            <a class="nav-link" href="<?=get_path('orders')?>">
                <i class="fas fa-fw fa-file"></i>
                <span>الطلبات</span></a>
        </li>

        <hr class="sidebar-divider">


        <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePost"
           aria-expanded="true" aria-controls="collapsePost">
            <i class="fas fa-fw fa-blog"></i>
            <span>ادارة المنشورات</span>
        </a>
        <div id="collapsePost" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header"></h6>
                <a class="collapse-item" href="<?=get_path('posts')?>">عرض المنشورات</a>
                <a class="collapse-item" href="<?=get_path('posts/add.php')?>">اضافة منشور</a>
            </div>
        </div>
    </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInvoices"
               aria-expanded="true" aria-controls="collapseInvoices">
                <i class="fas fa-fw fa-file-invoice-dollar"></i>
                <span>الفواتير</span>
            </a>
            <div id="collapseInvoices" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header"></h6>
                    <a class="collapse-item" href="<?=get_path('invoices')?>">عرض الفواتير</a>
                    <a class="collapse-item" href="<?=get_path('invoices/add.php')?>">اضافة فاتورة</a>
                </div>
            </div>
        </li>

    <?php } ?>

    <?php if (isRole('user') or isRole('admin')) {?>

        <!-- Nav Item - Charts -->
        <li class="nav-item">
            <a class="nav-link" href="<?=get_path('posts')?>">
                <i class="fas fa-fw fa-chart-bar"></i>
                <span>المنشورات</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?=get_path('invoices')?>">
                <i class="fas fa-fw fa-file-invoice-dollar"></i>
                <span>الفواتير</span></a>
        </li>

    <?php }?>

    <?php if (isRole('user')){?>
        <!-- Divider -->
        <hr class="sidebar-divider">

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOrders"
               aria-expanded="true" aria-controls="collapseOrders">
                <i class="fas fa-fw fa-file"></i>
                <span>الطلبات</span>
            </a>
            <div id="collapseOrders" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header"></h6>
                    <a class="collapse-item" href="<?=get_path('orders')?>">عرض الطلبات</a>
                    <a class="collapse-item" href="<?=get_path('orders/add.php')?>">طلب صيانة</a>
                </div>
            </div>
        </li>


    <?php }?>

    <?php if (isRole('admin')){?>
        <!-- Divider -->


        <hr class="sidebar-divider">

        <!-- Nav Item - Charts -->
        <li class="nav-item">
            <a class="nav-link" href="<?=get_path('reports')?>">
                <i class="fas fa-fw fa-chart-bar"></i>
                <span>التقارير</span></a>
        </li>
    <?php }?>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
<!--    <div class="text-center" style="z-index: 200">-->
<!--        <button class="rounded-circle border-0 sidebarToggle" id="sidebarToggle"></button>-->
<!--    </div>-->



</ul>
