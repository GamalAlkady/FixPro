-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2025 at 08:18 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fix_pro`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone_number` int(11) NOT NULL,
  `image` text DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `first_name`, `last_name`, `phone_number`, `image`, `email`, `password`) VALUES
(1, 'ادمن', 'ادمن', 452585674, 'uploads/technicians/download.png', 'admin@gmail.com', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `location` varchar(100) NOT NULL,
  `notes` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`id`, `admin_id`, `name`, `type`, `location`, `notes`) VALUES
(1, 1, 'HP Spectre x360	', 'محمول قابل للتحويل	', 'معمل 4', 'شاشة لمس، قلم رقمي، تصميم أنيق\r\n                                                                                    '),
(2, 1, 'Lenovo ThinkPad X1 Carbon	', 'محمول للأعمال	', 'معمل 3', 'متانة عالية، عمر بطارية طويل، لوحة مفاتيح ممتازة\r\n'),
(3, 1, 'Dell', 'مكتبي', 'معمل 3', 'متانة عالية'),
(4, 1, 'Razer Blade 15	', 'محمول للألعاب قوي	', 'معمل 4', 'تصميم أنيق، أداء قوي للألعاب، إضاءة RGB\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` varchar(20) NOT NULL,
  `technician_id` int(11) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `notes` text NOT NULL,
  `due_date` date DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'unpaid',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `technician_id`, `order_id`, `amount`, `notes`, `due_date`, `status`, `is_read`, `created_at`, `updated_at`) VALUES
('INV-00002', 2, 'ORD-00002', '200', '', '2024-11-30', 'unpaid', 0, '2024-11-20 19:59:11', '2024-11-20 19:59:11');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `technician_id` int(11) NOT NULL,
  `device_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `image` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'new',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `reason` text DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `technician_id`, `device_id`, `description`, `image`, `status`, `is_read`, `reason`, `post_id`, `updated_at`, `created_at`) VALUES
('ORD-00002', 1, 2, 2, 'لدي مشكله في جهازي', 'uploads/orders/شاشة الموت الخضراء.png', 'completed', 1, 'لدي ضروف خاصه', 3, '2024-11-20 19:56:24', '2024-11-20 19:41:02'),
('ORD-00003', 1, 1, 1, 'اعاني من نفس المشكله المذكوره في هذا المنشور', '', 'new', 0, NULL, NULL, '2024-11-28 17:30:52', '2024-11-28 16:57:33');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `technician_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `technician_id`, `title`, `description`, `image`, `created_at`) VALUES
(1, 1, 'شاشة الموت الخضراء.', '1- ما هي شاشة الموت الخضراء؟\r\nقلة قليلة من الناس يعرفون عن شاشة الموت الخضراء في Windows 10 أو إصدارات أخرى لأن قلة قليلة من الناس يواجهونها. شاشة الموت الخضراء تشبه ، إن لم تكن هى ذاتها ، شاشة BSOD (شاشة الموت الزرقاء). الاختلاف الوحيد بين GSOD و BSOD هو أن الشاشة الخضراء تحدث فقط إذا كان المستخدم يعمل على برنامج Windows Insider ، في حين أن Blue Screen هو خطأ عام ولكنه نادر يواجهه في نظام Windows العادي. ومع ذلك ، فإن العوامل المسئولة عن التسبب في شاشة الموت الزرقاء مسئولة أيضًا عن التسبب في ظهور شاشة الموت الخضراء على نظام تشغيل Windows 10.\r\n\r\n-: ما الذي يسبب ظهور الشاشة الخضراء؟\r\nتماماً ، مثل شاشة الموت الزرقاء ، فإن الشاشة الخضراء على نظام تشغيل Windows 10 هي نتيجة لفشل خطير فى نظام تشغيل Windows. في معظم الأحيان يكون ذلك بسبب فشل الأجهزة أو البرامج منخفضة المستوى التي تعمل في نواة Windows. إنه \"خطأ إيقاف\" ينتج عنه تعطل Windows الخاص بك. يمكن أن يكون خلل برنامج تشغيل لجهاز النظام الخاص بك أحد الأسباب الرئيسية وراء شاشة الموت الخضراء على نظام Windows الخاص بك.\r\n\r\n3- كيفية إصلاح الشاشة الخضراء في نظام Windows 10؟\r\nكما تعلم ، فإن شاشة الموت الخضراء في نظام تشغيل Windows 10 هى نتيجة إما لفشل في الأجهزة أو عطل فى برنامج. وبالتالي ، هناك عدة طرق سهلة لإصلاح الشاشة الخضراء على جهاز الكمبيوتر الخاص بك. كل ما عليك فعله هو اتباع طريقة واحدة في كل مرة ، والتحقق مما إذا كانت شاشة الموت الخضراء مازالت قائمة أم لا.', 'uploads/posts/شاشة الموت الخضراء.png', '2024-11-20 15:10:51'),
(2, 1, 'بعض الازرار لا تعمل فى كيبورد الخاص بك؟', 'هناك مشاكل حاسوبية تبدو غريبة للوهلة الأولى لمن يواجهها، تعطل بعض الأزرار فى الكيبورد واحدة من بين هذه المشاكل. ففي يومًا ما تقوم بكتابة رسالة لصديقك على الفيسبوك، أو تكتب مقالًا، أو كتابًا على برنامج Word لتكتشف فجأة أن هناك حروف (أزرار) محددّة لا تستجيب للضغط؛ وليست الحروف فقط بل الأزرار التى تقوم بوظائف متعددة مثل Alt و Ctrl و Shift. وبشكل عام، تعطل أي زر فى الكيبورد سيكون مزعج جدًا لأن كل زرار فى لوحة المفاتيح يتم النقر عليه مرة واحدة على الأقل يوميًا أثناء استعمال الحاسوب. ولهذا السبب، قمنا بإعداد هذه المقالة لنقوم بإستعراض كل الطرق التي تؤدي إلى إصلاح مشكلة بعض الازرار لا تعمل فى كيبورد اللاب توب، حيث ينبغي بعد الإنتهاء من قراءة المقالة أن يتم حل مشكلتك.\r\n\r\nالحل الأول: إعادة تشغيل اللاب توب\r\nعملية إعادة تشغيل بسيطة يمكن أن تؤدي إلى إصلاح 50% من المشاكل التي يواجهها المُستخدمون أثناء إستعمال الكمبيوتر، بما في ذلك مشكلة تعطل الكيبورد بالكامل أو بعض الأزرار فقط. لذلك، عندما تكتشف أن هناك حروف لا تُكتب أو لا تستجيب للضغط، قم بفتح قائمة Start ومن ثم اضغط على ايقونة الطاقة Power واختر منها إما Shutdown لإغلاق اللاب توب وإعادة تشغيله يدويًا أو Restart لإعادة تشغيل النظام بشكل تلقائي. ينبغي بعد ذلك أن تعمل كل الأزرار بصورة طبيعية، ففى أثناء إعادة تشغيل الكمبيوتر ونظام التشغيل يتم تحديث العمليات التى تعمل فى الخلفية والمسئولة عن إدارة لوحة المفاتيح، أو على الأقل البرنامج المتسبب فى حدوث المشكلة. إذا قمت بعمل إعادة تشغيل بالفعل ولم تفلح في حل المشكلة، فإليك بعض الحلول الأخرى.\r\n\r\nالحل الثاني: استعمال أداة الصيانة في ويندوز\r\nيشتمل نظام Windows على أدوات لصيانة المشاكل الأكثر شيوعًا وعرضة لمواجهة المُستخدمين أثناء استعمال الأجهزة الخاصة بهم. وكون هناك العديد من القضايا المتعلقة بلوحة المفاتيح مثل أنها تعمل فى أوقات محددّة، أو لا تعمل على الإطلاق، أو بعض الازرار لا تؤدي وظائفها... وما إلى ذلك، فقد تم تضمين أداة لصيانة لوحة المفاتيح ايضًا. لتشغيلها فى ويندوز 10 قم بالانتقال إلى الإعدادات Windows Settings من خلال قائمة إبدأ، ثم انتقل إلى قسم Update & Security ومن القائمة الجانبية اضغط على Troubleshoot. على الجهة اليُمني، قم بالتمرير إلى الأسفل للنقر فوق Keyboard ثم على زر Run the troubleshooter لتظهر لك نافذة توضح مدى تقدم عملية إكتشاف مشاكل الكيبورد وعرض سبب المشكلة وحلها إذا كان ذلك ممكنًا.', 'uploads/posts/الكيبورد.jpg', '2024-11-20 15:37:04'),
(3, 2, 'مشكلة انطفاء الكمبيوتر فجأة … دليل الأسباب والحلول', 'صُممت أجهزة الكمبيوتر لتجعل حياتنا أفضل وأسهل على عدة مستويات ونواحي، ولكن في بعض الحالات قد تؤدي هذه الأجهزة ذاتها إلى الكثير من الضغوط، وخاصة في حالة ظهور المشكلات، مثل مشكلة انطفاء الكمبيوتر فجأة والتي قد تؤدي لضياع بيانات هامة، إهدار الكثير من ساعات العمل، وغيرها من الأزمات التي يمكن تفاديها ببساطة من خلال حل هذه المشكلة حلاً جذرياً.\r\n\r\nأسباب وحلول مشكلة انطفاء الكمبيوتر فجأة\r\nارتفاع درجة حرارة الكمبيوتر\r\nصُممت أجهزة الكمبيوتر الحديثة لتقوم بالغلق أوتوماتيكياً في حالة ارتفاع درجة حرارة أي من مكوناتها الداخلية، وهناك العديد من الأسباب التي تؤدي لارتفاع درجة حرارة المكونات الداخلية وبالتالي حدوث مشكلة انطفاء الكمبيوتر فجأة والتسبب في العديد من المشكلات الأخرى.\r\n\r\nتبدأ درجة حرارة الكمبيوتر في الارتفاع بمجرد استخدامه في أي من المهام الثقيلة، مثل الألعاب عالية الرسوميات وتطبيقات تحرير الفيديو وتصميم الرسوميات، مع وجود مشكلة ما تعيق الكمبيوتر من خفض درجة حرارته أو الحفاظ عليها مستقرة، ومن بين أسباب هذه الإعاقة، عدم عمل أي من مراوح الكمبيوتر كما ينبغي، الأتربة والغبار وتكثفهما على الأجزاء الداخلية للكمبيوتر، وأيضاً جفاف أو زوال المادة الحرارية التي تفصل بين المعالج المركزي وبين المروحة أو جهاز التبريد الخاص به.\r\n\r\nنتحدث بداية عن أجهزة الكمبيوتر المكتبية، ولتتمكن من التأكد من أن سبب مشكلة انطفاء الكمبيوتر فجأة هي نتيجة لارتفاع درجة الحرارة، قم أولاً بالتأكد من أن المروحة الخاصة بمزوّد الطاقة Power Supply تعمل بكفاءة، ويمكنك ذلك من خلال تفحص ظهر الكمبيوتر والتأكد من عملها بسرعة ومرونة مع التأكد من أن صوتها ليس أعلى من المعتاد.\r\n\r\nإن وجدت أن مروحة مزوّد الطاقة تعاني من أية مشكلة أو أن الوحدة نفسها تتسم بدرجة حرارة مرتفعة تصل إلى حد عدم قدرك على لمسها، فعليك أن تقوم بتغير وحدة تزويد الطاقة فوراً ولا تقم بتشغيل الكمبيوتر دون تغييرها، ففي حالة ما لم يقم الكمبيوتر بالغلق مع وجود وحدة تزويد طاقة معيبة، سيؤدي هذا بالضرورة لإحداث ضرر بالغ بالمكونات الداخلية للكمبيوتر.\r\n\r\nفي حالة ما لم تكن هناك مشكلة في المروحة الخاصة بمزوّد الطاقة، قم بفتح خزانة الكمبيوتر Case وتأكد من أن المراوح أو نظام التبريد الخاصة بها تعمل بالكفاءة المعتادة، تأكد أيضاً من أن مروحة بطاقة الرسوميات (كرت الشاشة) تعمل أيضاً بكفاءة، وهوما ينطبق بالطبع على المروحة الخاصة بالمعالج المركزي، ومن بين سبل التأكد من هذا، عدم وجود أي صوت مرتفع للغاية أو غريب عن المعتاد وأيضاً عدم حركة المراوح بالسرعة الكافية، بالطبع عدم عملها على الإطلاق.\r\n\r\nكل المراوح الداخلية يمكن تغييرها، كما يفضل أيضاً نزع المروحة الخاصة بمعالج الرسوميات ثم مسح المادة الحرارية البيضاء الموجودة أعلى المعالج ثم إضافة مادة جديدة، والمادة والمراوح متوافرة في مختلف المتاجر الخاصة بصيانة أجهزة الكمبيوتر، وفي حالة ما لم تكن تعرف كيفية تغيير المادة أو المراوح فمن الأفضل التوجه إلى مراكز الصيانة الاحترافية.', 'uploads/posts/انطفاء الكمبيوتر.png', '2024-11-20 15:41:14');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `technician_id` int(11) NOT NULL,
  `rate` int(11) NOT NULL,
  `review` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `order_id`, `user_id`, `technician_id`, `rate`, `review`) VALUES
(1, 'ORD-00001', 1, 1, 2, 'اسعدني التعامل معك كثيرا'),
(2, 'ORD-00002', 1, 2, 5, 'انت جيد جدا بعملك');

-- --------------------------------------------------------

--
-- Table structure for table `technicians`
--

CREATE TABLE `technicians` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone_number` int(11) NOT NULL,
  `image` text DEFAULT NULL,
  `image_home` text DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `address` text DEFAULT NULL,
  `web_sites` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`web_sites`)),
  `email_verification` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `technicians`
--

INSERT INTO `technicians` (`id`, `first_name`, `last_name`, `phone_number`, `image`, `image_home`, `email`, `password`, `address`, `web_sites`, `email_verification`) VALUES
(1, 'خالد', 'المطيري', 525641578, 'uploads/technicians/pic-1.png', 'uploads/technicians/team-1.jpg', 'gamal333ge@gmail.com', '12345', 'المملكة العربية السعودية ، الرياض', NULL, b'1'),
(2, 'فهد', 'الحربي', 53365894, 'uploads/technicians/pic-3.png', 'uploads/technicians/team-3.jpg', 'fahd@gmail.com', '12345', 'المملكة العربية السعودية، الرياض', NULL, b'0'),
(3, 'ماجد', 'الزهراني', 52234165, 'uploads/technicians/images.png', NULL, 'majed@gmail.com', '12345', NULL, NULL, b'0'),
(4, 'technician', '1', 547483647, NULL, NULL, 'tech@gmail.com', '123', NULL, NULL, b'0');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone_number` int(11) NOT NULL,
  `image` text DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email_verification` bit(2) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `phone_number`, `image`, `web_rating`, `email`, `password`, `email_verification`) VALUES
(1, 'جواهر عمر', 'الحويطي', 443309190, 'uploads/users/images (1).png', NULL, 'gamal333ge@gmail.com', '12345', b'11'),
(2, 'دانا فايز', 'البلوي', 443309151, 'uploads/users/pic-2.png', NULL, 'dana@gmail.com', '12345', b'00'),
(3, 'ريناد احمد', 'غماري', 443309130, 'uploads/users/woman-1132617_1920.jpg', NULL, 'renad@gmail.com', '12345', b'00'),
(4, 'Amera', 'الحربي', 547483647, 'uploads/users/24405558.jpg', NULL, 'gamal333re@gmail.com', '123', b'00'),
(5, 'جمال ', 'abdulqawi', 547483647, 'uploads/users/screenshot 2024-10-06 193833.png', NULL, 'uuu@gmail.com', '123', b'00');

-- --------------------------------------------------------

--
-- Table structure for table `web_ratings`
--

CREATE TABLE `web_ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rate` int(11) NOT NULL DEFAULT 0,
  `review` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `web_ratings`
--

INSERT INTO `web_ratings` (`id`, `user_id`, `rate`, `review`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 'بصراحة، لم أجد مكانًا أفضل لإصلاح جهازي المحمول. الفريق الفني محترف للغاية ويعرفون تمامًا ما يفعلون. تم إصلاح هاتفي بسرعة وكفاءة، وأنا الآن أستطيع استخدامه كما لو كان جديدًا. شكراً جزيلاً لكم على الخدمة الممتازة!', '2024-11-20 20:10:23', '2024-11-20 20:44:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orderFK` (`order_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `technicians`
--
ALTER TABLE `technicians`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `web_ratings`
--
ALTER TABLE `web_ratings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `technicians`
--
ALTER TABLE `technicians`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `web_ratings`
--
ALTER TABLE `web_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `devices`
--
ALTER TABLE `devices`
  ADD CONSTRAINT `devices_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`);

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `orderFK` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
