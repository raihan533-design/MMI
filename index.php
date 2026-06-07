<?php
$pageTitle = 'Home';
require_once 'includes/config.php';
require_once 'includes/header.php';

$heroTitle = getSetting('hero_title');
$heroSubtitle = getSetting('hero_subtitle');
$heroTagline = getSetting('hero_tagline');
$companyName = getSetting('company_name');
?>
<div class="toast-container"></div>

<!-- ==================== HERO SECTION ==================== -->
<section class="hero-premium" id="hero">
    <div class="hero-bg">
        <div class="hero-overlay"></div>
        <div class="hero-particles">
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
        </div>
    </div>
    <div class="container hero-content">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center" data-aos="fade-up" data-aos-duration="1000">
                <div class="hero-badge">
                    <i class="fas fa-shield-alt me-2"></i>
                    Trusted Home Service Provider
                </div>
                <h1 class="hero-title">
                    Expert Home Services in <span class="text-accent">Singapore</span>
                </h1>
                <p class="hero-subtitle">
                    Professional plumbing, electrical, AC repair, painting, cleaning & home maintenance. Reliable service by certified experts.
                </p>

                <!-- Search -->
                <div class="hero-search-wrapper" data-aos="fade-up" data-aos-delay="200">
                    <div class="hero-search-inner">
                        <div class="hero-search-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text" id="heroSearch" class="hero-search-input" placeholder="Search services..." autocomplete="off">
                        <button class="hero-search-btn">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Category Chips -->
                <div class="hero-categories" data-aos="fade-up" data-aos-delay="300">
                    <span class="hero-cat-label">Popular categories:</span>
                    <div class="hero-chips">
                        <a href="/MMI/services.php" class="hero-chip">
                            <i class="fas fa-wrench"></i>
                            <span>Plumbing</span>
                        </a>
                        <a href="/MMI/services.php" class="hero-chip">
                            <i class="fas fa-bolt"></i>
                            <span>Electrical</span>
                        </a>
                        <a href="/MMI/services.php" class="hero-chip">
                            <i class="fas fa-snowflake"></i>
                            <span>AC Repair</span>
                        </a>
                        <a href="/MMI/services.php" class="hero-chip">
                            <i class="fas fa-paint-roller"></i>
                            <span>Painting</span>
                        </a>
                        <a href="/MMI/services.php" class="hero-chip">
                            <i class="fas fa-broom"></i>
                            <span>Cleaning</span>
                        </a>
                        <a href="/MMI/services.php" class="hero-chip">
                            <i class="fas fa-tools"></i>
                            <span>Maintenance</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-scroll-indicator">
        <span>Scroll Down</span>
        <div class="scroll-mouse">
            <div class="scroll-dot"></div>
        </div>
    </div>
</section>

<!-- ==================== STATS SECTION ==================== -->
<section class="stats-section">
    <div class="container">
        <div class="row g-3">
            <div class="col-md-3 col-6">
                <div class="stat-item" data-aos="fade-up">
                    <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-number"><span class="counter" data-target="17">0</span>+</div>
                    <div class="stat-label">Years Experience</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-icon"><i class="fas fa-smile"></i></div>
                    <div class="stat-number"><span class="counter" data-target="500">0</span>+</div>
                    <div class="stat-label">Happy Clients</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-icon"><i class="fas fa-tools"></i></div>
                    <div class="stat-number"><span class="counter" data-target="1000">0</span>+</div>
                    <div class="stat-label">Services</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-icon"><i class="fas fa-star"></i></div>
                    <div class="stat-number"><span class="counter" data-target="4.9">0</span></div>
                    <div class="stat-label">Avg Rating</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== SERVICE CATEGORIES ==================== -->
<section class="service-categories-section">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <span class="section-badge">Our Services</span>
            <h2 class="section-heading">All Home Services</h2>
            <p class="section-desc">Quality services delivered by verified professionals</p>
        </div>

        <div class="categories-grid">
            <div class="row g-4">
                <div class="col-xl-2 col-lg-4 col-md-4 col-6" data-aos="fade-up" data-aos-delay="50">
                    <a href="/MMI/services.php" class="category-card">
                        <div class="category-card-icon">
                            <i class="fas fa-wrench"></i>
                        </div>
                        <h5 class="category-card-title">Plumbing Services</h5>
                        <p class="category-card-desc">Pipe repair & fittings</p>
                        <span class="category-card-arrow"><i class="fas fa-arrow-right"></i></span>
                    </a>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-4 col-6" data-aos="fade-up" data-aos-delay="100">
                    <a href="/MMI/services.php" class="category-card">
                        <div class="category-card-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h5 class="category-card-title">Electrical Services</h5>
                        <p class="category-card-desc">Wiring & fixing</p>
                        <span class="category-card-arrow"><i class="fas fa-arrow-right"></i></span>
                    </a>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-4 col-6" data-aos="fade-up" data-aos-delay="150">
                    <a href="/MMI/services.php" class="category-card">
                        <div class="category-card-icon">
                            <i class="fas fa-snowflake"></i>
                        </div>
                        <h5 class="category-card-title">AC & Appliance</h5>
                        <p class="category-card-desc">AC service & repair</p>
                        <span class="category-card-arrow"><i class="fas fa-arrow-right"></i></span>
                    </a>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-4 col-6" data-aos="fade-up" data-aos-delay="200">
                    <a href="/MMI/services.php" class="category-card">
                        <div class="category-card-icon">
                            <i class="fas fa-paint-roller"></i>
                        </div>
                        <h5 class="category-card-title">Painting Services</h5>
                        <p class="category-card-desc">Home & office painting</p>
                        <span class="category-card-arrow"><i class="fas fa-arrow-right"></i></span>
                    </a>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-4 col-6" data-aos="fade-up" data-aos-delay="250">
                    <a href="/MMI/services.php" class="category-card">
                        <div class="category-card-icon">
                            <i class="fas fa-broom"></i>
                        </div>
                        <h5 class="category-card-title">Cleaning Services</h5>
                        <p class="category-card-desc">Deep cleaning</p>
                        <span class="category-card-arrow"><i class="fas fa-arrow-right"></i></span>
                    </a>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-4 col-6" data-aos="fade-up" data-aos-delay="300">
                    <a href="/MMI/services.php" class="category-card">
                        <div class="category-card-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <h5 class="category-card-title">Home Maintenance</h5>
                        <p class="category-card-desc">General repairs</p>
                        <span class="category-card-arrow"><i class="fas fa-arrow-right"></i></span>
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center mt-5" data-aos="fade-up">
            <a href="/MMI/services.php" class="btn btn-accent btn-lg rounded-pill px-5">
                View All Services
                <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- ==================== WHY CHOOSE US ==================== -->
<section class="why-choose-section">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <span class="section-badge">Why Choose Us</span>
            <h2 class="section-heading">Why MMI ENGINEERING?</h2>
            <p class="section-desc">We prioritize quality and customer satisfaction in every service</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="why-card">
                    <div class="why-card-icon"><i class="fas fa-user-check"></i></div>
                    <h5>Verified Professionals</h5>
                    <p>All technicians are background-checked and trained</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="why-card">
                    <div class="why-card-icon"><i class="fas fa-shield-alt"></i></div>
                    <h5>Service Guarantee</h5>
                    <p>100% satisfaction guaranteed on every service</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="why-card">
                    <div class="why-card-icon"><i class="fas fa-tag"></i></div>
                    <h5>Affordable Pricing</h5>
                    <p>Quality services at competitive, transparent prices</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="why-card">
                    <div class="why-card-icon"><i class="fas fa-clock"></i></div>
                    <h5>On-Time Service</h5>
                    <p>Punctual arrival and timely service completion</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== HOW IT WORKS ==================== -->
<section class="how-it-works-section">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <span class="section-badge">How It Works</span>
            <h2 class="section-heading">Get Service in 3 Easy Steps</h2>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <div class="step-icon"><i class="fas fa-search"></i></div>
                    <h5>Choose a Service</h5>
                    <p>Browse and select the service you need</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <div class="step-icon"><i class="fas fa-calendar-check"></i></div>
                    <h5>Book a Slot</h5>
                    <p>Pick a convenient time slot</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-icon"><i class="fas fa-check-circle"></i></div>
                    <h5>Get It Done</h5>
                    <p>Our expert arrives and completes the job</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== FAQ ==================== -->
<?php
$faqQuery = mysqli_query($conn, "SELECT * FROM faqs ORDER BY sort_order ASC LIMIT 5");
?>
<section class="faq-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-header text-center" data-aos="fade-up">
                    <span class="section-badge">FAQ</span>
                    <h2 class="section-heading">Frequently Asked Questions</h2>
                </div>
                <?php if (mysqli_num_rows($faqQuery) > 0): ?>
                <div class="accordion premium-accordion" id="homeFaq">
                    <?php $j = 0; while ($faq = mysqli_fetch_assoc($faqQuery)): $j++; ?>
                    <div class="accordion-item" data-aos="fade-up" data-aos-delay="<?php echo $j * 50; ?>">
                        <h2 class="accordion-header">
                            <button class="accordion-button <?php echo $j > 1 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#hfaq<?php echo $faq['id']; ?>">
                                <span class="faq-icon"><i class="fas fa-question"></i></span>
                                <?php echo htmlspecialchars($faq['question']); ?>
                            </button>
                        </h2>
                        <div id="hfaq<?php echo $faq['id']; ?>" class="accordion-collapse collapse <?php echo $j === 1 ? 'show' : ''; ?>">
                            <div class="accordion-body">
                                <?php echo nl2br(htmlspecialchars($faq['answer'])); ?>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                    <p class="text-muted">FAQs coming soon.</p>
                </div>
                <?php endif; ?>
                <div class="text-center mt-4" data-aos="fade-up">
                    <a href="/MMI/faq.php" class="btn btn-outline-accent rounded-pill px-4">
                        View All FAQs
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
