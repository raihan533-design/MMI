<?php
$siteName = getSetting('company_name', 'kiuixshop');
$footerEmail = getSetting('company_email', '');
$footerPhone = getSetting('company_phone', '');
$footerAddress = getSetting('company_address', '');
$social = [
    'facebook_url'  => 'fa-facebook-f',
    'instagram_url' => 'fa-instagram',
    'tiktok_url'    => 'fa-tiktok',
    'youtube_url'   => 'fa-youtube',
];
?>
<footer class="site-footer">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <a href="<?php echo url('index.php'); ?>" class="d-inline-flex align-items-center mb-3">
                    <img src="<?php echo url('assets/images/logo-white.png'); ?>" alt="kiuix" height="36">
                    <span class="brand-shop brand-shop-footer">shop</span>
                </a>
                <p class="footer-text">Quality products, honest prices and friendly support. Browse the collection and order in minutes.</p>
                <div class="footer-social">
                    <?php foreach ($social as $key => $icon): $link = getSetting($key, ''); if ($link): ?>
                    <a href="<?php echo e($link); ?>" target="_blank" rel="noopener" aria-label="<?php echo e(str_replace('_url', '', $key)); ?>"><i class="fab <?php echo $icon; ?>"></i></a>
                    <?php endif; endforeach; ?>
                    <a href="<?php echo e(waLink()); ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-6">
                <h6 class="footer-heading">Explore</h6>
                <ul class="footer-links">
                    <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
                    <li><a href="<?php echo url('products.php'); ?>">Products</a></li>
                    <li><a href="<?php echo url('about.php'); ?>">About Us</a></li>
                    <li><a href="<?php echo url('faq.php'); ?>">FAQ</a></li>
                    <li><a href="<?php echo url('contact.php'); ?>">Contact</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-6">
                <h6 class="footer-heading">Shop</h6>
                <ul class="footer-links">
                    <?php
                    $footCats = mysqli_query($conn, "SELECT name, slug FROM categories ORDER BY sort_order ASC, name ASC LIMIT 5");
                    if ($footCats) while ($fc = mysqli_fetch_assoc($footCats)): ?>
                    <li><a href="<?php echo url('products.php?category=' . urlencode($fc['slug'])); ?>"><?php echo e($fc['name']); ?></a></li>
                    <?php endwhile; ?>
                </ul>
            </div>
            <div class="col-lg-3">
                <h6 class="footer-heading">Get in touch</h6>
                <ul class="footer-contact">
                    <?php if ($footerAddress): ?><li><i class="fas fa-location-dot"></i><span><?php echo e($footerAddress); ?></span></li><?php endif; ?>
                    <?php if ($footerPhone): ?><li><i class="fas fa-phone"></i><span><?php echo e($footerPhone); ?></span></li><?php endif; ?>
                    <?php if ($footerEmail): ?><li><i class="fas fa-envelope"></i><span><?php echo e($footerEmail); ?></span></li><?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; <?php echo date('Y'); ?> <?php echo e($siteName); ?>. All rights reserved.</span>
            <a href="<?php echo url('admin/login'); ?>" class="footer-admin-link">Admin</a>
        </div>
    </div>
</footer>

<a href="<?php echo e(waLink('Hello ' . $siteName . ', I need some help.')); ?>" target="_blank" rel="noopener" class="whatsapp-float" aria-label="Chat on WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>window.KIUIX = { base: <?php echo json_encode(BASE_URL); ?> };</script>
<script src="<?php echo url('assets/js/main.js'); ?>?v=1"></script>
</body>
</html>
