<?php
$pageTitle = 'Contact Us';
require_once 'includes/config.php';
require_once 'includes/mailer.php';

$success = '';
$error = '';
$name = $email = $message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = sanitize($_POST['name'] ?? '');
    $email   = sanitize($_POST['email'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    if (!csrfValid()) {
        $error = 'Your session expired. Please try again.';
    } elseif ($name === '' || $email === '' || $message === '') {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (mb_strlen($message) > 3000 || mb_strlen($name) > 100 || mb_strlen($email) > 100) {
        $error = 'Your message is too long.';
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $name, $email, $message);
        if (mysqli_stmt_execute($stmt)) {
            $to = getSetting('company_email', '');
            if ($to !== '') {
                sendMail($to, "New Contact Message from $name", "Name: $name\nEmail: $email\n\nMessage:\n$message", $email);
            }
            $success = 'Thank you for your message! We will get back to you soon.';
            $name = $email = $message = '';
        } else {
            $error = 'Something went wrong. Please try again.';
        }
        mysqli_stmt_close($stmt);
    }
}

require_once 'includes/header.php';
$companyName  = getSetting('company_name', 'kiuixshop');
$phone        = getSetting('company_phone', '');
$mail         = getSetting('company_email', '');
$address      = getSetting('company_address', '');
$hours        = getSetting('working_hours', '');
?>
<div class="toast-container"></div>

<!-- Hero Banner -->
<div class="page-hero">
    <div class="page-hero-bg"></div>
    <div class="container position-relative" style="z-index:2">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8" data-aos="fade-up">
                <span class="hero-badge d-inline-flex mb-3">
                    <i class="fas fa-envelope me-2"></i> Get In Touch
                </span>
                <h1 class="page-hero-title">Contact <span class="text-accent">Us</span></h1>
                <p class="page-hero-desc">Have a question about a product or an order? We'd love to hear from you</p>
            </div>
        </div>
    </div>
</div>

<!-- Contact Section -->
<section class="page-section">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-7" data-aos="fade-right">
                <span class="section-badge">Send Message</span>
                <h2 class="section-heading mb-4">Drop Us a Line</h2>
                <p class="text-muted mb-4">Fill out the form below and our team will respond within 24 hours.</p>

                <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm">
                    <i class="fas fa-circle-check me-2"></i><?php echo e($success); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm">
                    <i class="fas fa-circle-exclamation me-2"></i><?php echo e($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <form method="POST" action="" class="premium-form">
                    <?php echo csrfField(); ?>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="cname">Your Name</label>
                            <input type="text" id="cname" class="form-control premium-input" name="name" placeholder="Enter your name" value="<?php echo e($name); ?>" maxlength="100" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="cemail">Your Email</label>
                            <input type="email" id="cemail" class="form-control premium-input" name="email" placeholder="Enter your email" value="<?php echo e($email); ?>" maxlength="100" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="cmsg">Message</label>
                            <textarea id="cmsg" class="form-control premium-input" name="message" placeholder="Write your message here..." rows="5" maxlength="3000" required><?php echo e($message); ?></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-accent btn-lg rounded-pill px-5">
                                <i class="fas fa-paper-plane me-2"></i>Send Message
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-5" data-aos="fade-left">
                <div class="contact-info-premium">
                    <?php if ($address): ?>
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="ci-icon"><i class="fas fa-location-dot"></i></div>
                        <div>
                            <small class="text-white-50">Location</small>
                            <p class="mb-0 fw-semibold text-white"><?php echo e($address); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if ($phone): ?>
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="ci-icon"><i class="fas fa-phone"></i></div>
                        <div>
                            <small class="text-white-50">Phone</small>
                            <p class="mb-0 fw-semibold text-white"><?php echo e($phone); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if ($mail): ?>
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="ci-icon"><i class="fas fa-envelope"></i></div>
                        <div>
                            <small class="text-white-50">Email</small>
                            <p class="mb-0 fw-semibold text-white"><?php echo e($mail); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if ($hours): ?>
                    <hr class="border-white-10 my-4">
                    <div class="mb-4">
                        <h6 class="fw-bold text-white mb-3">Working Hours</h6>
                        <div class="d-flex justify-content-between text-white-50">
                            <span>Every day</span>
                            <span class="text-accent fw-semibold"><?php echo e($hours); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    <a href="<?php echo e(waLink('Hello ' . $companyName . ', I have a question.')); ?>" target="_blank" rel="noopener" class="btn btn-success rounded-pill w-100 btn-lg mt-3">
                        <i class="fab fa-whatsapp me-2"></i>Chat on WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
