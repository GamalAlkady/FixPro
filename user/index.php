<?php
require '../config.php';
if (!isRole('user')) {
    redirect('login.php');
}
require '../layout/index.php';

$items = (new DB('posts p'))
    ->join('technicians t', 'p.technician_id=t.id')
    ->select("p.*,CONCAT(t.first_name, ' ',t.last_name) as full_name")
    ->get();

$web_rating=false;
$interval = 0;
if (is_array($items) and count($items)>=3) {
    $web_rating = (new DB('web_ratings'))->getBy('user_id', $_SESSION['id']);
    if ($web_rating !== false and $web_rating['rate'] === 0) {
        $date1 = new DateTime($web_rating['updated_at']);
        $date2 = new DateTime();
        $interval = $date1->diff($date2);
        $interval = $interval->days;
    }
}
?>

<style>
    :root {
        --user-primary: #6c5ce7;
        --user-secondary: #a29bfe;
        --user-bg: #dfe6e9;
    }

    .rating-card {
        background: linear-gradient(135deg, #fff 0%, #fefefe 100%);
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(108, 92, 231, 0.15);
    }
    
    .rating-header {
        background: transparent;
        border-bottom: none;
        padding: 2rem 2rem 1rem;
    }

    .rating-title {
        font-family: 'Rubik', sans-serif;
        font-weight: 700;
        color: #2d3436;
    }

    .rating-stars {
        display: flex;
        justify-content: center;
        gap: 15px;
        font-size: 2.5rem;
        cursor: pointer;
    }
    
    .star-label {
        color: #b2bec3;
        transition: color 0.2s, transform 0.2s;
    }

    .star-label:hover,
    .star-label.rating-color {
        color: #fdcb6e;
        transform: scale(1.1);
    }

    .post-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        background: white;
    }
    
    .post-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(108, 92, 231, 0.2);
    }

    .post-img-wrapper {
        height: 250px;
        overflow: hidden;
        position: relative;
    }

    .post-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }

    .post-card:hover .post-img {
        transform: scale(1.05);
    }

    .post-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
        padding: 20px;
        color: white;
    }
    
    .tech-name {
        font-size: 0.9rem;
        font-weight: 500;
        opacity: 0.9;
    }

    .post-body {
        padding: 1.5rem;
    }

    .post-title {
        font-weight: 700;
        color: #2d3436;
        margin-bottom: 0.5rem;
        display: block;
        text-decoration: none;
        transition: color 0.2s;
    }

    .post-title:hover {
        color: var(--user-primary);
        text-decoration: none;
    }

    .post-desc {
        color: #636e72;
        font-size: 0.95rem;
        line-height: 1.6;
        height: 4.8rem;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    .post-footer {
        background: transparent;
        border-top: 1px solid #f1f2f6;
        padding: 1rem 1.5rem;
    }

    .btn-custom-primary {
        background-color: var(--user-primary);
        color: white;
        border-radius: 50px;
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s;
        border: none;
        width: 100%;
    }

    .btn-custom-primary:hover {
        background-color: #5849be;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(108, 92, 231, 0.4);
    }

    .section-title {
        position: relative;
        padding-bottom: 15px;
        margin-bottom: 30px;
        font-weight: 700;
        color: #2d3436;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 60px;
        height: 4px;
        background: var(--user-primary);
        border-radius: 2px;
    }

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
            <div class="welcome-section mb-5 mt-4">
                <h1 class="h2 mb-1 text-gray-800 font-weight-bold">الرئيسية</h1>
                <p class="text-muted">تصفح آخر العروض والخدمات المتاحة</p>
            </div>

            <?php if ($web_rating === false or $interval>=3): ?>
                <div class="row justify-content-center mb-5">
                    <div class="col-lg-8">
                        <div class="card rating-card">
                            <div class="rating-header text-center">
                                <h3 class="rating-title h4">كيف كانت تجربتك معنا؟</h3>
                                <p class="text-muted mb-0">رأيك يهمنا لنقدم لك الأفضل دائماً</p>
                            </div>
                            <div class="card-body">
                                <form class="form" id="formRating" action="operations.php" method="post" enctype="multipart/form-data" novalidate>
                                    <input type="hidden" name="id" value="<?= $web_rating['id']??0 ?>">
                                    <input type="hidden" name="user_id" value="<?= $_SESSION['id'] ?>">
                                    <input type="hidden" name="rating" id="ratingInput" value="<?= old('rating', 0) ?>">
                                    
                                    <div class="form-group text-center mb-4">
                                        <div class="rating-stars">
                                            <?php for ($i = 1; $i <= 5; $i++) {
                                                $cls = (old('rating', 0) >= $i) ? 'rating-color' : '';
                                                echo "<i class='fas fa-star star-label $cls' data-val='$i'></i>";
                                            } ?>
                                        </div>
                                    </div>

                                    <div class="form-group px-4">
                                        <textarea name="review" class="form-control bg-light border-0 rounded-lg p-3" id="review" rows="3" placeholder="اكتب تعليقك هنا..." required><?= old('review') ?></textarea>
                                    </div>

                                    <div class="form-group px-4 d-flex justify-content-between align-items-center mt-4">
                                        <button class="btn btn-link text-muted" type="submit" name="later">سأقوم بذلك لاحقاً</button>
                                        <button type="submit" name="web_rate" id="submitRating" class="btn btn-custom-primary px-5 w-auto">إرسال التقييم</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <h3 class="section-title h4">أحدث المنشورات</h3>
            
            <div class="row">
                <?php if(empty($items)): ?>
                    <div class="col-12 text-center py-5">
                        <img src="<?= assets('images/empty.svg') ?>" alt="No Posts" style="width: 200px; opacity: 0.6;">
                        <h5 class="mt-3 text-muted">لا توجد منشورات حالياً</h5>
                    </div>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card post-card h-100">
                                <div class="post-img-wrapper">
                                    <img src="<?= assets($item['image']) ?>" class="post-img" alt="<?= $item['title'] ?>">
                                    <div class="post-overlay">
                                        <div class="tech-name"><i class="fas fa-user-check mr-1"></i> <?= $item['full_name'] ?></div>
                                    </div>
                                </div>
                                <div class="card-body post-body">
                                    <h5 class="card-title"><a href="<?= get_path('posts/details.php?id='. $item['id']) ?>" class="post-title"><?= $item['title'] ?></a></h5>
                                    <p class="card-text post-desc"><?= $item['description'] ?></p>
                                </div>
                                <div class="card-footer post-footer">
                                    <a href="<?= get_path('orders/add.php?post_id='.$item['id'].'&technician_id='.$item['technician_id']) ?>" class="btn btn-custom-primary">
                                        <i class="fas fa-tools mr-2"></i> طلب صيانة
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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
    $('.star-label').on('click', function () {
        let n = $(this).data('val');
        $('.star-label').removeClass('rating-color');
        $('#ratingInput').val(n);
        
        $('.star-label').each(function() {
            if ($(this).data('val') <= n) {
                $(this).addClass('rating-color');
            }
        });
    });

    const form = document.getElementById('formRating');
    if(form) {
        $('#submitRating').on('click', function (event) {
            if ($('#ratingInput').val() == 0) {
                 alert('يرجى اختيار عدد النجوم للتقييم');
                 event.preventDefault();
                 return;
            }
            
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    }
</script>