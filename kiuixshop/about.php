<?php
$pageTitle = 'About Us';
require_once 'includes/config.php';
require_once 'includes/header.php';

$aboutContent = getSetting('about_content', '');
$companyName  = getSetting('company_name', 'kiuixshop');
?>
<div class="toast-container"></div>

<!-- Hero Banner -->
<div class="page-hero">
    <div class="page-hero-bg"></div>
    <div class="container position-relative" style="z-index:2">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8" data-aos="fade-up">
                <span class="hero-badge d-inline-flex mb-3">
                    <i class="fas fa-circle-info me-2"></i> Who We Are
                </span>
                <h1 class="page-hero-title">About <span class="text-accent">Us</span></h1>
                <p class="page-hero-desc">Learn more about <?php echo e($companyName); ?> &mdash; your trusted online shop</p>
            </div>
        </div>
    </div>
</div>

<!-- About Content -->
<section class="page-section">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="about-image-wrapper">
                    <div class="about-visual">
                        <img src="<?php echo url('assets/images/logo-white.png'); ?>" alt="<?php echo e($companyName); ?>">
                    </div>
                    <div class="about-image-badge">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-heart"></i>
                            <div>
                                <strong class="d-block">Customer First</strong>
                                <small class="text-muted">Always</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <span class="section-badge">About Company</span>
                <h2 class="section-heading mb-4"><?php echo e($companyName); ?></h2>
                <div class="about-content">
                    <?php
                    $paragraphs = preg_split('/\R{2,}/', trim($aboutContent));
                    foreach ($paragraphs as $para) {
                        if (trim($para) === '') continue;
                        echo '<p class="text-muted mb-3">' . nl2br(e(trim($para))) . '</p>';
                    }
                    ?>
                </div>
                <div class="row g-3 mt-4">
                    <div class="col-6">
                        <div class="about-stat-card">
                            <h3 class="fw-bold text-accent mb-0"><i class="fas fa-gem"></i></h3>
                            <small>Quality Products</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="about-stat-card">
                            <h3 class="fw-bold text-accent mb-0"><i class="fas fa-truck-fast"></i></h3>
                            <small>Fast Delivery</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="about-stat-card">
                            <h3 class="fw-bold text-accent mb-0"><i class="fas fa-tag"></i></h3>
                            <small>Fair Prices</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="about-stat-card">
                            <h3 class="fw-bold text-accent mb-0"><i class="fab fa-whatsapp"></i></h3>
                            <small>WhatsApp Support</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Values -->
<section class="page-section bg-light">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <span class="section-badge">Our Values</span>
            <h2 class="section-heading">What Drives Us</h2>
            <p class="section-desc">Our core values shape every order we deliver</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="why-card">
                    <div class="why-card-icon"><i class="fas fa-bullseye"></i></div>
                    <h5>Our Mission</h5>
                    <p>To bring you great products at fair prices, with a shopping experience that is simple, honest and friendly.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="why-card">
                    <div class="why-card-icon"><i class="fas fa-eye"></i></div>
                    <h5>Our Vision</h5>
                    <p>To become a shop customers trust and come back to, known for quality, reliability and care.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="why-card">
                    <div class="why-card-icon"><i class="fas fa-handshake"></i></div>
                    <h5>Our Promise</h5>
                    <p>Every order matters to us. If something is not right, we will work with you until it is.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="page-section cta-section">
    <div class="container text-center" data-aos="fade-up">
        <h2 class="text-white fw-bold mb-3">Ready to Start Shopping?</h2>
        <p class="text-white-50 mb-4" style="max-width: 500px; margin-left: auto; margin-right: auto;">Explore the collection and discover the <?php echo e($companyName); ?> difference.</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="<?php echo url('products.php'); ?>" class="btn btn-white btn-lg rounded-pill px-5">
                <i class="fas fa-bag-shopping me-2"></i>Shop Products
            </a>
            <a href="<?php echo url('contact.php'); ?>" class="btn btn-outline-light btn-lg rounded-pill px-5">
                <i class="fas fa-envelope me-2"></i>Contact Us
            </a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
