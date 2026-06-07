<?php
$pageTitle = 'About Us';
require_once 'includes/config.php';
require_once 'includes/header.php';

$aboutContent = getSetting('about_content');
$companyName = getSetting('company_name');
?>
<div class="toast-container"></div>

<!-- Hero Banner -->
<div class="page-hero">
    <div class="page-hero-bg"></div>
    <div class="container position-relative" style="z-index:2">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8" data-aos="fade-up">
                <span class="hero-badge d-inline-flex mb-3">
                    <i class="fas fa-info-circle me-2"></i> Who We Are
                </span>
                <h1 class="page-hero-title">About <span class="text-accent">Us</span></h1>
                <p class="page-hero-desc">Learn more about MMI ENGINEERING — your trusted partner in home services</p>
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
                    <div class="about-image-main rounded-4 overflow-hidden shadow-lg">
                        <img src="/MMI/assets/images/pexels-whidegroup-26583385.jpg" alt="About MMI ENGINEERING" class="img-fluid w-100" style="min-height: 400px; object-fit: cover;">
                    </div>
                    <div class="about-image-badge">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-calendar-check"></i>
                            <div>
                                <strong class="d-block">17+ Years</strong>
                                <small>Of Excellence</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <span class="section-badge">About Company</span>
                <h2 class="section-heading mb-4"><?php echo htmlspecialchars($companyName); ?></h2>
                <div class="about-content">
                    <?php
                    $paragraphs = explode("\n\n", $aboutContent);
                    foreach ($paragraphs as $para) {
                        echo '<p class="text-muted mb-3">' . nl2br(htmlspecialchars(trim($para))) . '</p>';
                    }
                    ?>
                </div>
                <div class="row g-3 mt-4">
                    <div class="col-6">
                        <div class="about-stat-card">
                            <h3 class="fw-bold text-accent mb-0">17+</h3>
                            <small>Years Experience</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="about-stat-card">
                            <h3 class="fw-bold text-accent mb-0">1000+</h3>
                            <small>Projects Done</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="about-stat-card">
                            <h3 class="fw-bold text-accent mb-0">600+</h3>
                            <small>Happy Clients</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="about-stat-card">
                            <h3 class="fw-bold text-accent mb-0">24/7</h3>
                            <small>Support</small>
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
            <p class="section-desc">Our core values shape every service we deliver</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="why-card">
                    <div class="why-card-icon"><i class="fas fa-bullseye"></i></div>
                    <h5>Our Mission</h5>
                    <p>To provide exceptional home services that exceed expectations through quality workmanship and customer care.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="why-card">
                    <div class="why-card-icon"><i class="fas fa-eye"></i></div>
                    <h5>Our Vision</h5>
                    <p>To be the most trusted home service company in Singapore, known for reliability and excellence.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="why-card">
                    <div class="why-card-icon"><i class="fas fa-handshake"></i></div>
                    <h5>Our Promise</h5>
                    <p>Every job is backed by our satisfaction guarantee — we don't rest until you're happy.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="page-section cta-section" style="background: linear-gradient(160deg, #0a1628, #0f1f36);">
    <div class="container text-center" data-aos="fade-up">
        <h2 class="text-white fw-bold mb-3">Ready to Get Started?</h2>
        <p class="text-white-50 mb-4" style="max-width: 500px; margin-left: auto; margin-right: auto;">Book a service today and experience the MMI ENGINEERING difference.</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="/MMI/services.php" class="btn btn-accent btn-lg rounded-pill px-5">
                <i class="fas fa-tools me-2"></i>Our Services
            </a>
            <a href="/MMI/contact.php" class="btn btn-outline-light btn-lg rounded-pill px-5">
                <i class="fas fa-envelope me-2"></i>Contact Us
            </a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
