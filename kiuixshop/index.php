<?php
$pageTitle = 'Home';
require_once 'includes/config.php';
require_once 'includes/header.php';

$companyName   = getSetting('company_name', 'kiuixshop');
$heroTitle     = getSetting('hero_title', 'Shop Smart. Live Better.');
$heroSubtitle  = getSetting('hero_subtitle', 'Quality products at honest prices, delivered to your door. Browse the collection and order in minutes.');
$heroTagline   = getSetting('hero_tagline', 'Your trusted online shop');

// Make the last word of the hero title stand out
$titleWords = explode(' ', trim($heroTitle));
$lastWord = array_pop($titleWords);
$titleStart = implode(' ', $titleWords);

$categories = mysqli_query($conn, "SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id) AS product_count FROM categories c ORDER BY c.sort_order ASC, c.name ASC");
$featured = mysqli_query($conn, "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC LIMIT 8");
$heroChips = mysqli_query($conn, "SELECT name, slug, icon FROM categories ORDER BY sort_order ASC, name ASC LIMIT 6");
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
                    <i class="fas fa-bag-shopping me-2"></i>
                    <?php echo e($heroTagline); ?>
                </div>
                <h1 class="hero-title">
                    <?php echo e($titleStart); ?> <span class="text-accent"><?php echo e($lastWord); ?></span>
                </h1>
                <p class="hero-subtitle"><?php echo e($heroSubtitle); ?></p>

                <!-- Search -->
                <div class="hero-search-wrapper" data-aos="fade-up" data-aos-delay="200">
                    <div class="hero-search-inner">
                        <div class="hero-search-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text" id="heroSearch" class="hero-search-input" placeholder="Search products..." autocomplete="off">
                        <button class="hero-search-btn" type="button" aria-label="Search">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Category Chips -->
                <?php if ($heroChips && mysqli_num_rows($heroChips) > 0): ?>
                <div class="hero-categories" data-aos="fade-up" data-aos-delay="300">
                    <span class="hero-cat-label">Popular categories:</span>
                    <div class="hero-chips">
                        <?php while ($chip = mysqli_fetch_assoc($heroChips)): ?>
                        <a href="<?php echo url('products.php?category=' . urlencode($chip['slug'])); ?>" class="hero-chip">
                            <i class="<?php echo e($chip['icon'] ?: 'fas fa-tag'); ?>"></i>
                            <span><?php echo e($chip['name']); ?></span>
                        </a>
                        <?php endwhile; ?>
                    </div>
                </div>
                <?php endif; ?>
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

<!-- ==================== TRUST STRIP ==================== -->
<section class="stats-section">
    <div class="container">
        <div class="row g-3">
            <div class="col-md-3 col-6">
                <div class="stat-item" data-aos="fade-up">
                    <div class="stat-icon"><i class="fas fa-gem"></i></div>
                    <div class="stat-title">Quality Products</div>
                    <div class="stat-label">Carefully selected items</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-icon"><i class="fas fa-truck-fast"></i></div>
                    <div class="stat-title">Fast Delivery</div>
                    <div class="stat-label">Quick &amp; reliable shipping</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-icon"><i class="fas fa-lock"></i></div>
                    <div class="stat-title">Safe Ordering</div>
                    <div class="stat-label">Simple &amp; secure checkout</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-icon"><i class="fas fa-headset"></i></div>
                    <div class="stat-title">Friendly Support</div>
                    <div class="stat-label">We reply on WhatsApp</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== PRODUCT CATEGORIES ==================== -->
<section class="service-categories-section">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <span class="section-badge">Shop by Category</span>
            <h2 class="section-heading">Browse Our Categories</h2>
            <p class="section-desc">Find exactly what you are looking for</p>
        </div>

        <?php if ($categories && mysqli_num_rows($categories) > 0): ?>
        <div class="categories-grid">
            <div class="row g-4 justify-content-center">
                <?php $d = 0; while ($cat = mysqli_fetch_assoc($categories)): $d += 50; ?>
                <div class="col-xl-2 col-lg-4 col-md-4 col-6" data-aos="fade-up" data-aos-delay="<?php echo min($d, 400); ?>">
                    <a href="<?php echo url('products.php?category=' . urlencode($cat['slug'])); ?>" class="category-card">
                        <div class="category-card-icon">
                            <i class="<?php echo e($cat['icon'] ?: 'fas fa-tag'); ?>"></i>
                        </div>
                        <h5 class="category-card-title"><?php echo e($cat['name']); ?></h5>
                        <p class="category-card-desc"><?php echo (int)$cat['product_count']; ?> product<?php echo (int)$cat['product_count'] === 1 ? '' : 's'; ?></p>
                        <span class="category-card-arrow"><i class="fas fa-arrow-right"></i></span>
                    </a>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php else: ?>
        <p class="text-center text-muted">Categories coming soon.</p>
        <?php endif; ?>

        <div class="text-center mt-5" data-aos="fade-up">
            <a href="<?php echo url('products.php'); ?>" class="btn btn-accent btn-lg rounded-pill px-5">
                View All Products
                <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- ==================== FEATURED PRODUCTS ==================== -->
<?php if ($featured && mysqli_num_rows($featured) > 0): ?>
<section class="featured-section">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <span class="section-badge">New Arrivals</span>
            <h2 class="section-heading">Latest Products</h2>
            <p class="section-desc">Fresh in the shop &mdash; grab them while they last</p>
        </div>
        <div class="row g-4">
            <?php while ($product = mysqli_fetch_assoc($featured)):
                $hasImg = $product['image'] && file_exists(ROOT_PATH . '/' . $product['image']);
            ?>
            <div class="col-xl-3 col-lg-4 col-md-6" data-aos="fade-up">
                <div class="product-card">
                    <div class="product-card-img">
                        <?php if ($hasImg): ?>
                        <img src="<?php echo url($product['image']); ?>" alt="<?php echo e($product['name']); ?>" loading="lazy">
                        <?php else: ?>
                        <div class="product-card-img-placeholder"><i class="fas fa-box-open"></i></div>
                        <?php endif; ?>
                        <?php if ($product['category_name']): ?><span class="product-cat-badge"><?php echo e($product['category_name']); ?></span><?php endif; ?>
                    </div>
                    <div class="product-card-body">
                        <h5 class="product-card-title"><?php echo e($product['name']); ?></h5>
                        <?php $desc = trim((string)$product['description']); if ($desc !== ''): ?>
                        <p class="product-card-text"><?php echo e(mb_substr($desc, 0, 90)) . (mb_strlen($desc) > 90 ? '...' : ''); ?></p>
                        <?php endif; ?>
                        <div class="product-price"><?php echo formatPrice($product['price']); ?></div>
                        <a href="<?php echo e(waLink('Hello ' . $companyName . ', I want to order: ' . $product['name'] . ' (' . strip_tags(formatPrice($product['price'])) . ')')); ?>" target="_blank" rel="noopener" class="btn-order">
                            <i class="fab fa-whatsapp me-2"></i>Order Now
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ==================== WHY CHOOSE US ==================== -->
<section class="why-choose-section" style="background: var(--bg-alt);">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <span class="section-badge">Why Choose Us</span>
            <h2 class="section-heading">Why <?php echo e($companyName); ?>?</h2>
            <p class="section-desc">We put quality and customer satisfaction first on every order</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="why-card">
                    <div class="why-card-icon"><i class="fas fa-circle-check"></i></div>
                    <h5>Trusted Quality</h5>
                    <p>Every product is checked before it reaches you</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="why-card">
                    <div class="why-card-icon"><i class="fas fa-shield-halved"></i></div>
                    <h5>Buyer Protection</h5>
                    <p>Questions or problems? We make it right</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="why-card">
                    <div class="why-card-icon"><i class="fas fa-tag"></i></div>
                    <h5>Fair Pricing</h5>
                    <p>Clear, competitive prices with no hidden surprises</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="why-card">
                    <div class="why-card-icon"><i class="fas fa-bolt"></i></div>
                    <h5>Quick Response</h5>
                    <p>Fast replies and speedy order handling</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== HOW IT WORKS ==================== -->
<section class="how-it-works-section" style="background: var(--bg);">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <span class="section-badge">How It Works</span>
            <h2 class="section-heading">Order in 3 Easy Steps</h2>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <div class="step-icon"><i class="fas fa-magnifying-glass"></i></div>
                    <h5>Choose a Product</h5>
                    <p>Browse the shop and pick what you like</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <div class="step-icon"><i class="fab fa-whatsapp"></i></div>
                    <h5>Place Your Order</h5>
                    <p>Tap Order Now and confirm details on WhatsApp</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-icon"><i class="fas fa-box-open"></i></div>
                    <h5>Get It Delivered</h5>
                    <p>We pack it and send it straight to you</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== FAQ ==================== -->
<?php
$faqQuery = mysqli_query($conn, "SELECT * FROM faqs ORDER BY sort_order ASC, id ASC LIMIT 5");
?>
<section class="faq-section" style="background: var(--bg-alt);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-header text-center" data-aos="fade-up">
                    <span class="section-badge">FAQ</span>
                    <h2 class="section-heading">Frequently Asked Questions</h2>
                </div>
                <?php if ($faqQuery && mysqli_num_rows($faqQuery) > 0): ?>
                <div class="accordion premium-accordion" id="homeFaq">
                    <?php $j = 0; while ($faq = mysqli_fetch_assoc($faqQuery)): $j++; ?>
                    <div class="accordion-item" data-aos="fade-up" data-aos-delay="<?php echo $j * 50; ?>">
                        <h2 class="accordion-header">
                            <button class="accordion-button <?php echo $j > 1 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#hfaq<?php echo (int)$faq['id']; ?>">
                                <span class="faq-icon"><i class="fas fa-question"></i></span>
                                <?php echo e($faq['question']); ?>
                            </button>
                        </h2>
                        <div id="hfaq<?php echo (int)$faq['id']; ?>" class="accordion-collapse collapse <?php echo $j === 1 ? 'show' : ''; ?>">
                            <div class="accordion-body">
                                <?php echo nl2br(e($faq['answer'])); ?>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-circle-question fa-3x text-muted mb-3"></i>
                    <p class="text-muted">FAQs coming soon.</p>
                </div>
                <?php endif; ?>
                <div class="text-center mt-4" data-aos="fade-up">
                    <a href="<?php echo url('faq.php'); ?>" class="btn btn-outline-accent rounded-pill px-4">
                        View All FAQs
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
