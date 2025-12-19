<?php
require '../config.php';
if (!isRole('technician')) {
    redirect('technician/login.php');
}

require '../layout/index.php';
$count_orders_complete = (new DB('orders'))->select('COUNT(*) as count')
    ->where(['technician_id' => $_SESSION['id'], 'status' => 'completed'])->getOne()['count'];

$count_orders_canceled = (new DB('orders'))->select('COUNT(*) as count')
    ->where(['technician_id' => $_SESSION['id'], 'status' => 'canceled'])->getOne()['count'];

$count_posts = (new DB('posts'))->select('COUNT(*) as count')
    ->where(['technician_id' => $_SESSION['id']])->getOne()['count'];

$earning = (new DB('invoices'))->select('SUM(amount) as earning')->where('technician_id', $_SESSION['id'])->getOne()[0] ?? 0;

$items = (new DB('orders o'))
    ->join('devices d', 'o.device_id=d.id')
    ->join('users t', 'o.user_id=t.id')
    ->where(['o.technician_id'=> $_SESSION['id'],'status'=>'new'])
    ->select("o.*,d.name,CONCAT(t.first_name, ' ',t.last_name) as full_name,t.phone_number as phone_number")
    ->get();

?>
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #2af598 0%, #009efd 100%);
        --info-gradient: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
        --danger-gradient: linear-gradient(135deg, #ff0844 0%, #ffb199 100%);
        --warning-gradient: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
        --card-shadow: 0 10px 20px rgba(0,0,0,0.05);
        --hover-transform: translateY(-5px);
    }

    .dashboard-card {
        border: none;
        border-radius: 15px;
        color: white;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: var(--card-shadow);
        position: relative;
        z-index: 1;
    }

    .dashboard-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 60%);
        opacity: 0;
        transform: scale(0.5);
        transition: opacity 0.5s, transform 0.5s;
        z-index: -1;
    }

    .dashboard-card:hover {
        transform: var(--hover-transform);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }

    .dashboard-card:hover::before {
        opacity: 1;
        transform: scale(1);
    }

    .card-bg-primary { background: var(--primary-gradient); }
    .card-bg-success { background: var(--success-gradient); }
    .card-bg-info { background: var(--info-gradient); }
    .card-bg-danger { background: var(--danger-gradient); }
    .card-bg-warning { background: var(--warning-gradient); }

    .card-icon {
        opacity: 0.8;
        font-size: 3rem;
        position: absolute;
        left: 20px;
        bottom: 20px;
        transform: rotate(-15deg);
        transition: all 0.3s;
    }

    .dashboard-card:hover .card-icon {
        transform: rotate(0deg) scale(1.1);
        opacity: 1;
    }

    .stat-value { font-size: 2.5rem; font-weight: 700; line-height: 1.2; }
    .stat-label { font-size: 1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9; }

    .table-card {
        border: none;
        border-radius: 15px;
        box-shadow: var(--card-shadow);
    }
    
    .table-card .card-header {
        background: white;
        border-bottom: 1px solid #f0f0f0;
        border-radius: 15px 15px 0 0;
        padding: 1.5rem;
    }

    .custom-table th {
        border-top: none;
        font-weight: 600;
        color: #8898aa;
        background: #f8f9fe;
        text-transform: uppercase;
        font-size: 0.85rem;
    }
    
    .custom-table td {
        vertical-align: middle;
        font-size: 0.95rem;
        color: #525f7f;
        border-top: 1px solid #f0f0f0;
    }

    .custom-table tr:hover td {
        background-color: #f6f9fc;
    }
    
    .badge-modern {
        padding: 0.5em 1em;
        border-radius: 50px;
        font-weight: 500;
        font-size: 0.85rem;
    }
    
    .view-btn {
        background: #f0f3f6;
        color: #5e72e4;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        text-decoration: none !important;
    }
    
    .view-btn:hover {
        background: #5e72e4;
        color: white;
        transform: scale(1.1);
    }

    .action-btn-group .btn {
        border-radius: 20px;
        font-size: 0.8rem;
        padding: 0.4rem 1rem;
    }

    .welcome-section h1 { font-family: 'Rubik', sans-serif; font-weight: 700; color: #32325d; }
    .welcome-section p { color: #8898aa; }

</style>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column bg-light">

    <!-- Main Content -->
    <div id="content">

        <!-- Topbar -->
        <?php require ROOT_PATH . '/layout/navbar.php'; ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid pb-5">

            <!-- Page Heading -->
            <div class="welcome-section mb-5 mt-4 d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="h2 mb-1">مرحباً بك، فني الصيانة</h1>
                    <p class="mb-0">تابع طلباتك وإنجازاتك من هنا</p>
                </div>
                 <div>
                    <button class="btn btn-sm btn-white shadow-sm rounded-pill px-3 py-2 text-primary font-weight-bold" onclick="location.reload()">
                        <i class="fas fa-sync-alt mr-2"></i> تحديث
                    </button>
                </div>
            </div>

            <!-- Content Row -->
            <div class="row">

                <!-- Posts Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card dashboard-card card-bg-info h-100 py-4 px-3">
                        <div class="card-body p-0 d-flex flex-column justify-content-between position-relative" style="min-height: 120px;">
                            <div>
                                <div class="stat-label mb-2">المنشورات</div>
                                <div class="stat-value"><?= $count_posts ?></div>
                            </div>
                            <i class="fas fa-calendar card-icon"></i>
                        </div>
                    </div>
                </div>

                <!-- Completed Orders Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card dashboard-card card-bg-primary h-100 py-4 px-3">
                        <div class="card-body p-0 d-flex flex-column justify-content-between position-relative" style="min-height: 120px;">
                            <div>
                                <div class="stat-label mb-2">طلبات مكتملة</div>
                                <div class="stat-value"><?= $count_orders_complete ?></div>
                            </div>
                            <i class="fas fa-check-circle card-icon"></i>
                        </div>
                    </div>
                </div>

                <!-- Canceled Orders Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card dashboard-card card-bg-danger h-100 py-4 px-3">
                        <div class="card-body p-0 d-flex flex-column justify-content-between position-relative" style="min-height: 120px;">
                            <div>
                                <div class="stat-label mb-2">طلبات ملغية</div>
                                <div class="stat-value"><?= $count_orders_canceled ?></div>
                            </div>
                            <i class="fas fa-times-circle card-icon"></i>
                        </div>
                    </div>
                </div>

                <!-- Earnings Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                     <div class="card dashboard-card card-bg-success h-100 py-4 px-3">
                        <div class="card-body p-0 d-flex flex-column justify-content-between position-relative" style="min-height: 120px;">
                            <div>
                                <div class="stat-label mb-2">الأرباح</div>
                                <div class="stat-value"><?= number_format($earning, 2) ?> <span style="font-size: 1rem">رس</span></div>
                            </div>
                            <i class="fas fa-dollar-sign card-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Row -->
            <div class="row mt-4">
                <div class="col-12">
                     <div class="card table-card">
                        <div class="card-header d-flex flex-row align-items-center justify-content-between">
                            <h5 class="m-0 font-weight-bold text-dark">الطلبات الجديدة</h5>
                             <a href="<?= get_path('orders') ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">عرض الكل</a>
                        </div>
                        <div class="card-body p-0">
                            <?php include ROOT_PATH . '/components/alert.php' ?>

                            <div class="table-responsive">
                                <table class="table custom-table mb-0" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th class="border-0">رقم الطلب</th>
                                        <th class="border-0">المستخدم</th>
                                        <th class="border-0">الجهاز</th>
                                        <th class="border-0">الوصف</th>
                                        <th class="border-0">التاريخ</th>
                                        <th class="border-0 text-left">إجراءات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (empty($items)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">لا توجد طلبات جديدة حالياً</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td class="font-weight-bold">ORD-<?= str_pad($item['id'], 5, '0', STR_PAD_LEFT) ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                         <div class="avatar-sm ml-2 bg-light rounded-circle p-2 text-primary">
                                                            <i class="fas fa-user"></i>
                                                        </div>
                                                        <span><?= $item['full_name'] ?></span>
                                                    </div>
                                                </td>
                                                <td><span class="badge badge-light border"><?= $item['name'] ?></span></td>
                                                <td style="max-width: 200px;">
                                                    <div class="text-truncate" title="<?= $item['description'] ?>"><?= $item['description'] ?></div>
                                                </td>
                                                <td class="text-muted"><?= date('M d, Y', strtotime($item['updated_at'])) ?></td>
                                                <td class="text-left">
                                                    <div class="action-btn-group">
                                                        <a href="<?= get_path('orders/details.php?id='.$item['id']) ?>" class="btn btn-primary btn-sm shadow-sm">
                                                            <i class="fas fa-eye mr-1"></i> تفاصيل
                                                        </a>
                                                        <a href="https://api.whatsapp.com/send?phone=<?= $item['phone_number'] ?>" target="_blank" class="btn btn-success btn-sm shadow-sm">
                                                            <i class="fab fa-whatsapp mr-1"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
<script>
    const columns = [4, 3, 2, 1, 0];
</script>
<?php require ROOT_PATH . "/layout/end.php" ?>