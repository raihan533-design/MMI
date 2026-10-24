<?php
$siteName = getSetting('company_name', 'kiuixshop');
$currentPage = basename($_SERVER['SCRIPT_NAME']);
$fullTitle = (isset($pageTitle) && $pageTitle !== 'Home') ? $pageTitle . ' | ' . $siteName : $siteName . ' | ' . getSetting('hero_tagline', 'Shop quality products online');
$navItems = [
    'index.php'    => ['Home', 'fa-house'],
    'products.php' => ['Products', 'fa-bag-shopping'],
    'about.php'    => ['About', 'fa-circle-info'],
    'faq.php'      => ['FAQ', 'fa-circle-question'],
    'contact.php'  => ['Contact', 'fa-envelope'],
];
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($fullTitle); ?></title>
    <meta name="description" content="<?php echo e(getSetting('hero_subtitle', '')); ?>">
    <meta name="theme-color" content="#1D19F2">
    <link rel="icon" type="image/png" href="<?php echo url('assets/images/favicon.png'); ?>">
    <script>
        // Apply saved theme before paint to avoid a flash
        try { var t = localStorage.getItem('kiuix-theme'); if (t) document.documentElement.setAttribute('data-theme', t); } catch (e) {}
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="<?php echo url('assets/css/style.css'); ?>?v=1" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg site-navbar sticky-top" id="siteNavbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo url('index.php'); ?>" aria-label="<?php echo e($siteName); ?> home">
            <img src="<?php echo url('assets/images/logo.png'); ?>" alt="kiuix" class="brand-logo brand-logo-light" height="34">
            <img src="<?php echo url('assets/images/logo-white.png'); ?>" alt="kiuix" class="brand-logo brand-logo-dark" height="34">
            <span class="brand-shop">shop</span>
        </a>
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <?php foreach ($navItems as $file => $item): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage === $file ? 'active' : ''; ?>" href="<?php echo url($file); ?>">
                        <?php echo e($item[0]); ?>
                    </a>
                </li>
                <?php endforeach; ?>
                <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                    <button id="themeToggle" class="theme-toggle-btn" type="button" aria-label="Toggle dark mode">
                        <i class="fas fa-sun"></i>
                        <i class="fas fa-moon"></i>
                    </button>
                </li>
                <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                    <a class="btn btn-accent rounded-pill px-4" href="<?php echo url('products.php'); ?>">
                        <i class="fas fa-bag-shopping me-2"></i>Shop Now
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
