<?php
$pageTitle = 'Contact Us';
require_once 'includes/config.php';
require_once 'includes/mailer.php';
require_once 'includes/header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $name, $email, $message);
        if (mysqli_stmt_execute($stmt)) {
            $to = 'mmiengpte@gmail.com';
            $subject = "New Contact Message from $name";
            $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
            sendMail($to, $subject, $body, $email);
            $success = 'Thank you for your message! We will get back to you soon.';
            $name = $email = $message = '';
        } else {
            $error = 'Something went wrong. Please try again.';
        }
        mysqli_stmt_close($stmt);
    }
}
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
                <p class="page-hero-desc">Have a question or need a quote? We'd love to hear from you</p>
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
                    <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm">
                    <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <form method="POST" action="" class="premium-form">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Your Name</label>
                            <input type="text" class="form-control premium-input" name="name" placeholder="Enter your name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Your Email</label>
                            <input type="email" class="form-control premium-input" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Message</label>
                            <textarea class="form-control premium-input" name="message" placeholder="Write your message here..." rows="5" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
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
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="ci-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <small class="text-white-50">Location</small>
                            <p class="mb-0 fw-semibold text-white">Singapore</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="ci-icon"><i class="fas fa-phone-alt"></i></div>
                        <div>
                            <small class="text-white-50">Phone</small>
                            <p class="mb-0 fw-semibold text-white">+65 8587 8190</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="ci-icon"><i class="fas fa-envelope"></i></div>
                        <div>
                            <small class="text-white-50">Email</small>
                            <p class="mb-0 fw-semibold text-white">mmiengpte@gmail.com</p>
                        </div>
                    </div>
                    <hr class="border-white-10 my-4">
                    <div class="mb-4">
                        <h6 class="fw-bold text-white mb-3">Working Hours</h6>
                        <div class="d-flex justify-content-between text-white-50">
                            <span>Mon - Sun</span>
                            <span class="text-accent fw-semibold">8:00 AM - 12:00 PM</span>
                        </div>
                    </div>
                    <a href="https://wa.me/6585878190" target="_blank" class="btn btn-success rounded-pill w-100 btn-lg mt-3">
                        <i class="fab fa-whatsapp me-2"></i>Chat on WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
