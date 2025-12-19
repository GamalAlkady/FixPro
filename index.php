<?php require 'config.php' ?>
<?php
$orders_count = (new DB('orders'))->select('COUNT(*) as count')->where('status', 'completed')->getOne()['count'];
$users_count = (new DB('users'))->select('COUNT(*) as count')->getOne()['count'];
$technicians = (new DB('technicians'))->getAll();

$ratings = (new DB('ratings r'))
    ->join('technicians t', 'r.technician_id=t.id')
    ->select('t.first_name,t.last_name,t.image_home, SUM(r.rate) AS sum_rating')
    ->groupBy('technician_id')
    ->orderBy('sum_rating desc LIMIT 4')
    ->get();
//var_dump($ratings);die();
$posts = (new DB('posts'))->orderBy('created_at LIMIT 3')->get();

//var_dump($posts);die();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فيكس برو</title>


    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery-js/1.4.0/css/lightgallery.min.css">


    <link rel="stylesheet" href="<?= assets('css/bootstrap.rtl.min.css') ?>">

    <link href="<?= assets('css/sb-admin-2.css') ?>" rel="stylesheet" type="text/css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="<?= assets('css/style.css') ?>">

    <style>
        body{
            background-image: url(<?= assets('images/bg-fixpro-1.png') ?>);
        }
        /*.fun-fact{*/
        /*    !*background-color: rgba(110,213,209,.5);*!*/
        /*}*/
    </style>
</head>
<body>

<!-- header section starts  -->

<header class="header">

    <div class="contact-info">
        <p> المملكة العربية السعودية , الرياض<i class="fas fa-map"></i></p>
        <p> fixpro@gmail.com <i class="fas fa-envelope"></i></p>
        <?php if (!isset($_SESSION['role'])): ?>
        <p><a href="<?= BASE_PATH( 'login.php') ?>" style="color: white">تسجيل الدخول</a> <i class="fas fa-user-circle"></i></p>
        <?php else: ?>
        <p>
            <i class="fas fa-fw fa-tachometer-alt mx-3"></i>
            <a href="<?= get_path( 'index.php') ?>" style="color: white">لوحة التحكم</a> </p>
        <?php endif;?>
    </div>

    <nav class="navbar">

        <a href="#" class="logo"> <img src="<?= assets('images/logo/logo.svg') ?>" alt="" width="30px">
            <span>فيكس برو</span> </a>

        <div class="links">
            <a href="#home">الرئيسية</a>
            <a href="#about">عنا</a>
            <a href="#services">الخدمات</a>
            <a href="#gallery">المعرض</a>
            <a href="#team">الفنيين</a>
            <a href="#reviews">التقييمات</a>
            <a href="#contact">التواصل</a>
        </div>

        <div id="menu-btn" class="fa fa-bars"></div>

    </nav>

</header>

<!-- header section ends -->

<!-- home section starts  -->

<section class="home pt-5 mt-5" id="home">

    <div class="image pe-5">
        <img src="assets/images/fixpro-home.png" alt="">
    </div>

    <div class="content">
        <h3>لماذا إصلاحها بنفسك؟ اترك الأمر للمحترفين.</h3>
        <p>نقدم لك حلولاً شاملة لجميع مشاكل أجهزتك الإلكترونية. فريقنا من الخبراء المتخصصين مجهز بأحدث الأدوات التقنية
            لإصلاح جميع أنواع الأجهزة، بدءًا من اللابتوبات
            وحتى الشاشات. سواء كنت تواجه مشكلة في نظام التشغيل، أو تلف في الشاشة، أو بطء في الأداء، فنحن نقدم لك الحل
            الأمثل.</p>
        <?php if (!isset($_SESSION['role'])): ?>
        <a href="login.php" class="btn btn-primary w-25 btn-xl fs-2">ابدأ الان</a>
        <?php else:?>
        <a href="<?= get_path( 'index.php') ?>" class="btn">لوحة التحكم</a>
        <?php endif;?>

    </div>

</section>

<!-- home section ends -->

<!-- fun fact section starts  -->

<section class="fun-fact shadow">

    <div class="box">
        <img src="assets/images/fun-fact-icon-1.svg" alt="">
        <div class="info">
            <h3><?= $orders_count ?></h3>
            <p>الإصلاحات المنجزة</p>
        </div>
    </div>

    <div class="box">
        <img src="assets/images/fun-fact-icon-3.svg" alt="">
        <div class="info">
            <h3><?= $users_count ?></h3>
            <p>عدد المستخدمين</p>
        </div>
    </div>

    <div class="box">
        <img src="assets/images/fun-fact-icon-4.svg" alt="">
        <div class="info">
            <h3><?= count($technicians) ?></h3>
            <p>عدد الفنيين</p>
        </div>
    </div>
</section>

<!-- fun fact section ends -->

<!-- about section starts  -->

<section class="about" id="about">

    <div class="content">
        <h3>إصلاح احترافي لأجهزتك جودة لا تضاهى</h3>
        <p><b>قطع غيار أصلية:</b> نستخدم فقط قطع غيار أصلية لضمان أداء الجهاز بشكل مثالي.</p>
        <p><b>فنيون مهرة:</b> فريقنا من الفنيين المدربين بخبرة واسعة قادر على التعامل مع جميع أنواع الأعطال.</p>
        <p><b>أحدث التقنيات:</b> نستخدم أحدث الأدوات والمعدات لضمان إصلاح دقيق وسريع.</p>
        <!--        <a href="#services" class="btn">read more</a>-->
    </div>

    <div class="image">
        <img src="assets/images/computer%20repair.webp" alt="" height="300px">
    </div>

</section>

<!-- about section ends -->

<!-- services section starts  -->

<section class="services" id="services">

    <h1 class="heading"><span>خدماتنا</span></h1>

    <div class="box-container">

        <div class="box">
            <img src="assets/images/Diagnosis-of-breakdowns.webp" alt="">
            <h3>تشخيص الأعطال</h3>
            <p>تشخيص دقيق وسريع للمشكلة.</p>
        </div>

        <div class="box">
            <img src="assets/images/fix-device.png" alt="">
            <h3>إصلاح الشاشات المكسورة</h3>
            <p>استبدال الشاشات التالفة بأخرى جديدة.</p>
        </div>

        <div class="box">
            <img src="assets/images/fix-keypord.png" alt="">
            <h3>إصلاح لوحات المفاتيح</h3>
            <p>إصلاح أو استبدال لوحات المفاتيح التالفة.</p>
        </div>


    </div>

</section>

<!-- services section ends -->


<section class="gallery" id="gallery">

    <h1 class="heading"><span>المعرض</span></h1>

    <div class="gallery-container">
        <?php foreach ($posts as $post): ?>
            <a class="box" href="<?= assets($post['image']) ?>"><img src="<?= assets($post['image']) ?>" alt=""></a>
        <?php endforeach; ?>
    </div>

</section>

<!-- gallery section ends -->

<!-- facilities section starts  -->

<section class="facilities">

    <h1 class="heading"> لماذا <span>تختارنا؟</span></h1>

    <div class="box-container">

        <div class="box">
            <img src="assets/images/why-choose-icon-1.svg" alt="">
            <h3> الدعم 24/7</h3>
            <p> نوفر دعم على مدار 24 ساعة سبع أيام في الأسبوع</p>
        </div>

        <div class="box">
            <img src="assets/images/why-choose-icon-2.svg" alt="">
            <h3>الخبرة</h3>
            <p>لدينا فريق من الفنيين المدربين على أعلى مستوى.</p>
        </div>

        <div class="box">
            <img src="assets/images/why-choose-icon-3.svg" alt="">
            <h3>السرعة</h3>
            <p>ننفذ الإصلاحات في أسرع وقت ممكن.</p>
        </div>

    </div>

</section>

<!-- facilities section ends -->

<!-- team section starts  -->

<section class="team" id="team">

    <h1 class="heading"> افضل <span>الفنيين</span></h1>

    <div class="box-container ">

        <?php foreach ($ratings as $rating): ?>
            <div class="box ">
                <img src="<?= assets($rating['image_home']) ?>" alt="">
                <div class="content">
                    <h3><?= $rating['first_name'] . ' ' . $rating['last_name'] ?></h3>
                    <!--                    <p>electronic expert</p>-->
                    <div class="share">
                        <a href="#" class="fab fa-facebook-f"></a>
                        <a href="#" class="fab fa-twitter"></a>
                        <a href="#" class="fab fa-instagram"></a>
                        <a href="#" class="fab fa-linkedin"></a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>





    </div>

</section>

<!-- team section ends -->

<!-- reviews section starts  -->

<section class="reviews" id="reviews">

    <h1 class="heading"> تقييمات <span>العملاء</span></h1>

    <div class="box-container">

        <?php
        $clients = (new DB('web_ratings w'))->join('users u', 'u.id=w.user_id')->where('rate != 0')
            ->select('w.rate,w.review,CONCAT(u.first_name," ",u.last_name) as full_name,u.image')->orderBy('rate desc')->get();

        if ($clients != false):
            foreach ($clients as $client):
                ?>
                <div class="box">
                    <div class="star">
                        <?php for ($i=0;$i<5;$i++){
                            echo '<i class="fas fa-star" '.($i>=$client['rate']?'style="color: #b1bbc3"':'').'></i>';
                        }?>
                    </div>
                    <div class="text">
                        <i class="fas fa-quote-right"></i>
                        <p><?=$client['review'] ?></p>
                    </div>
                    <div class="user">
                        <img src="<?=assets($client['image'])?>" alt="">
                        <h3><?=$client['full_name']?></h3>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

</section>

<!-- reviews section ends -->

<!-- contact section starts  -->

<section class="contact" id="contact">

    <h1 class="heading"><span>موقعنا</span></h1>

    <div class="row">

        <iframe class="map" style="height: 300px"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15076.89592087332!2d72.8319697277345!3d19.14167056419224!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7b63aceef0c69%3A0x2aa80cf2287dfa3b!2sJogeshwari%20West%2C%20Mumbai%2C%20Maharashtra%20400047!5e0!3m2!1sen!2sin!4v1641269162899!5m2!1sen!2sin"
                allowfullscreen="" loading="lazy"></iframe>
    </div>

</section>

<!-- contact section ends -->

<!-- footer section starts  -->

<section class="footer">

    <div class="box-container">

        <div class="box">
            <h3>روابط سريعة</h3>
            <a class="link" href="#home"> <i class="fas fa-angle-right"></i> الرئيسية</a>
            <a class="link" href="#about"> <i class="fas fa-angle-right"></i> عنا</a>
            <a class="link" href="#services"> <i class="fas fa-angle-right"></i> الخدمات</a>
            <a class="link" href="#gallery"> <i class="fas fa-angle-right"></i> المعرض</a>
            <a class="link" href="#team"> <i class="fas fa-angle-right"></i> الفنيين</a>
            <a class="link" href="#reviews"> <i class="fas fa-angle-right"></i> التقييمات</a>
            <a class="link" href="#contact"> <i class="fas fa-angle-right"></i> التواصل</a>
        </div>

        <div class="box">
            <h3>الساعات المفتوحة</h3>
            <p><span>الجمعة :</span> مغلق </p>
            <p><span>السبت :</span> 8:00ص
                <m>الى</m>
                12:00م
            </p>
            <p><span>الاحد :</span> 8:00ص
                <m>الى</m>
                12:00
            </p>
            <p><span>الاثنين :</span> 8:00ص
                <m>الى</m>
                12:00
            </p>
            <p><span>الثلاثاء :</span> 8:00ص
                <m>الى</m>
                12:00
            </p>
            <p><span>الربوع :</span> 8:00ص
                <m>الى</m>
                12:00
            </p>
            <p><span>الخميس :</span> 8:00ص
                <m>الى</m>
                10:00
            </p>
        </div>

        <div class="box">
            <h3>معلومات التواصل</h3>
            <!--            <a href="#" class="link"> <i class="fas fa-phone"></i> +123-456-7890 </a>-->
            <b class="link"> <i class="fas fa-phone"></i> +111-222-3333 </b>
            <b class="link"> <i class="fas fa-envelope"></i> fixpro@gmail.com </b>
            <b class="link"> <i class="fas fa-map"></i> المملكة العربية السُّعُودية, تبوك </b>
        </div>

    </div>

    <div class="credit"> created by <span>FixPro</span> | all rights reserved</div>

</section>

<!-- footer section ends -->


<script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery-js/1.4.0/js/lightgallery.min.js"></script>

<script>
    lightGallery(document.querySelector('.gallery .gallery-container'));

    let menu = document.querySelector('#menu-btn');
    let navbarLinks = document.querySelector('.header .navbar .links');

    menu.onclick = () => {
        menu.classList.toggle('fa-times');
        navbarLinks.classList.toggle('active');
    }

    window.onscroll = () => {
        menu.classList.remove('fa-times');
        navbarLinks.classList.remove('active');

        if (window.scrollY > 60) {
            document.querySelector('.header .navbar').classList.add('active');
        } else {
            document.querySelector('.header .navbar').classList.remove('active');
        }
    }

</script>

</body>
</html>

