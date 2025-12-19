<?php
require '../config.php';
if (!isRole('admin')) {
    redirect('admin/login.php');
}

require '../layout/index.php';

$count_users = (new DB('users'))->select('COUNT(*) as count')->getOne()['count'];
$count_technicians = (new DB('technicians'))->select('COUNT(*) as count')->getOne()['count'];
$count_orders = (new DB('orders'))->select('COUNT(*) as count')->getOne()['count'];
$earning = (new DB('invoices'))->select('SUM(amount) as earning')->getOne()[0] ?? 0;

$items = (new DB('invoices i'))
    ->join('technicians t', 'i.technician_id=t.id')
    ->where(['status'=>'unpaid'])
    ->select("i.*,CONCAT(t.first_name, ' ',t.last_name) as full_name")
    ->orderBy('i.created_at DESC LIMIT 5')
    ->get();
?>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #2af598 0%, #009efd 100%);
        --info-gradient: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
        --danger-gradient: linear-gradient(135deg, #ff0844 0%, #ffb199 100%);
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
                    <h1 class="h2 mb-1">لوحة التحكم</h1>
                    <p class="mb-0">نظرة عامة على أداء المنصة اليوم</p>
                </div>
                <div>
                    <button id="refreshBtn" class="btn btn-sm btn-white shadow-sm rounded-pill px-3 py-2 text-primary font-weight-bold">
                        <i class="fas fa-sync-alt mr-2"></i> تحديث البيانات
                    </button>
                </div>
            </div>

            <!-- Content Row -->
            <div class="row">

                <!-- Users Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card dashboard-card card-bg-primary h-100 py-4 px-3">
                        <div class="card-body p-0 d-flex flex-column justify-content-between position-relative" style="min-height: 120px;">
                            <div>
                                <div class="stat-label mb-2">المستخدمين</div>
                                <div class="stat-value"><?= $count_users ?></div>
                            </div>
                            <i class="fas fa-users card-icon"></i>
                        </div>
                    </div>
                </div>

                <!-- Technicians Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card dashboard-card card-bg-info h-100 py-4 px-3">
                        <div class="card-body p-0 d-flex flex-column justify-content-between position-relative" style="min-height: 120px;">
                            <div>
                                <div class="stat-label mb-2">الفنيين</div>
                                <div class="stat-value"><?= $count_technicians ?></div>
                            </div>
                            <i class="fas fa-user-cog card-icon"></i>
                        </div>
                    </div>
                </div>

                <!-- Orders Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card dashboard-card card-bg-success h-100 py-4 px-3">
                        <div class="card-body p-0 d-flex flex-column justify-content-between position-relative" style="min-height: 120px;">
                            <div>
                                <div class="stat-label mb-2">الطلبات</div>
                                <div class="stat-value"><?= $count_orders ?></div>
                            </div>
                            <i class="fas fa-clipboard-list card-icon"></i>
                        </div>
                    </div>
                </div>

                <!-- Earnings Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card dashboard-card card-bg-danger h-100 py-4 px-3">
                        <div class="card-body p-0 d-flex flex-column justify-content-between position-relative" style="min-height: 120px;">
                            <div>
                                <div class="stat-label mb-2">الإيرادات</div>
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
                            <h5 class="m-0 font-weight-bold text-dark">الفواتير الأخيرة (غير المدفوعة)</h5>
                            <a href="<?= get_path('invoices') ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">عرض الكل</a>
                        </div>
                        <div class="card-body p-0">
                            <?php include ROOT_PATH . '/components/alert.php' ?>

                            <div class="table-responsive">
                                <table class="table custom-table mb-0" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th class="border-0"># الفاتورة</th>
                                        <th class="border-0">الفني</th>
                                        <th class="border-0">رقم الطلب</th>
                                        <th class="border-0">المبلغ</th>
                                        <th class="border-0">الحالة</th>
                                        <th class="border-0">تاريخ الاستحقاق</th>
                                        <th class="border-0 text-left">إجراء</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (empty($items)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">لا توجد فواتير غير مدفوعة حالياً</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td class="font-weight-bold">INV-<?= str_pad($item['id'], 4, '0', STR_PAD_LEFT) ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm ml-2 bg-light rounded-circle p-2 text-primary">
                                                            <i class="fas fa-user"></i>
                                                        </div>
                                                        <span><?= $item['full_name'] ?></span>
                                                    </div>
                                                </td>
                                                <td><span class="badge badge-light border">ORD-<?= $item['order_id'] ?></span></td>
                                                <td class="font-weight-bold text-dark"><?= number_format($item['amount'], 2) ?> ر.س</td>
                                                <td><span class="badge badge-modern badge-soft-warning bg-warning text-white">غير مدفوع</span></td>
                                                <td class="text-muted"><?= date('M d, Y', strtotime($item['due_date'])) ?></td>
                                                <td class="text-left">
                                                    <a href="<?= get_path('invoices/details.php?id='.$item['id']) ?>" class="view-btn" title="عرض التفاصيل">
                                                        <i class="fas fa-arrow-left"></i>
                                                    </a>
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

<?php require ROOT_PATH . "/layout/end.php" ?>

<script>
    $(document).ready(function() {
        $('#refreshBtn').click(function() {
            location.reload();
        });
        
        // Remove default datatable init if it interferes with our custom styling, 
        // or re-init it with cleaner settings if needed.
        // For now, we use a simple table structure.
    });
</script>
