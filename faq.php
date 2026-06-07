<?php
$pageTitle = 'FAQ';
require_once 'includes/config.php';
require_once 'includes/header.php';

$query = mysqli_query($conn, "SELECT * FROM faqs ORDER BY sort_order ASC");
?>
<div class="toast-container"></div>

<!-- Hero Banner -->
<div class="page-hero">
    <div class="page-hero-bg"></div>
    <div class="container position-relative" style="z-index:2">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8" data-aos="fade-up">
                <span class="hero-badge d-inline-flex mb-3">
                    <i class="fas fa-question-circle me-2"></i> Got Questions?
                </span>
                <h1 class="page-hero-title">Frequently Asked <span class="text-accent">Questions</span></h1>
                <p class="page-hero-desc">Find answers to commonly asked questions about our services</p>
            </div>
        </div>
    </div>
    <div class="page-hero-wave">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 40L48 35C96 30 192 20 288 25C384 30 480 50 576 55C672 60 768 50 864 40C960 30 1056 20 1152 18C1248 16 1344 24 1392 28L1440 32V80H1392C1344 80 1248 80 1152 80C1056 80 960 80 864 80C768 80 672 80 576 80C480 80 384 80 288 80C192 80 96 80 48 80H0V40Z" fill="currentColor"/>
        </svg>
    </div>
</div>

<!-- FAQ Content -->
<section class="page-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-header text-center" data-aos="fade-up">
                    <span class="section-badge">FAQ</span>
                    <h2 class="section-heading">We Have Answers</h2>
                    <p class="section-desc">Everything you need to know about our home services</p>
                </div>

                <?php if (mysqli_num_rows($query) > 0): ?>
                <div class="accordion premium-accordion" id="faqAccordion">
                    <?php $i = 0; while ($faq = mysqli_fetch_assoc($query)): $i++; ?>
                    <div class="accordion-item" data-aos="fade-up" data-aos-delay="<?php echo $i * 50; ?>">
                        <h2 class="accordion-header">
                            <button class="accordion-button <?php echo $i > 1 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?php echo $faq['id']; ?>">
                                <span class="faq-icon"><i class="fas fa-question"></i></span>
                                <?php echo htmlspecialchars($faq['question']); ?>
                            </button>
                        </h2>
                        <div id="faq<?php echo $faq['id']; ?>" class="accordion-collapse collapse <?php echo $i === 1 ? 'show' : ''; ?>" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?php echo nl2br(htmlspecialchars($faq['answer'])); ?>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-5" data-aos="fade-up">
                    <div class="empty-state">
                        <i class="fas fa-question-circle fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No FAQs available yet</h4>
                        <p class="text-muted">Check back soon for updates.</p>
                    </div>
                </div>
                <?php endif; ?>

                <div class="text-center mt-5 p-5 bg-light rounded-4 shadow-sm" data-aos="fade-up">
                    <h5 class="fw-bold mb-2">Still Have Questions?</h5>
                    <p class="text-muted mb-4">Feel free to reach out to us directly.</p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="/MMI/contact.php" class="btn btn-accent rounded-pill px-4">
                            <i class="fas fa-envelope me-2"></i>Contact Us
                        </a>
                        <a href="https://wa.me/6585878190" target="_blank" class="btn btn-success rounded-pill px-4">
                            <i class="fab fa-whatsapp me-2"></i>WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
