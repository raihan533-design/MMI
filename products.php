<?php
$pageTitle = 'Products';
require_once 'includes/config.php';
require_once 'includes/header.php';

$companyName = getSetting('company_name', 'kiuixshop');
$categories = mysqli_query($conn, "SELECT c.*, (SELECT COUNT(*) FROM products WHERE category_id = c.id) AS product_count FROM categories c ORDER BY c.sort_order ASC, c.name ASC");
$products = mysqli_query($conn, "SELECT p.*, c.name AS category_name, c.slug AS category_slug FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.sort_order ASC, p.id DESC");
$totalProducts = $products ? mysqli_num_rows($products) : 0;
?>
<div class="toast-container"></div>

<div class="products-hero">
    <div class="products-hero-overlay"></div>
    <div class="container position-relative" style="z-index:2">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8" data-aos="fade-up">
                <span class="hero-badge d-inline-flex mb-3">
                    <i class="fas fa-bag-shopping me-2"></i> The Shop
                </span>
                <h1 class="page-hero-title">Our Products</h1>
                <p class="page-hero-desc">Quality products at honest prices &mdash; pick your favourite and order in a tap.</p>
            </div>
        </div>
    </div>
</div>

<section class="products-page-section" id="productsSection">
    <div class="container">

        <!-- Search Bar -->
        <div class="row justify-content-center mb-4 pt-4" data-aos="fade-up">
            <div class="col-lg-8 col-md-10">
                <div class="search-wrapper">
                    <div class="search-icon"><i class="fas fa-search"></i></div>
                    <input type="text" id="productSearch" class="form-control product-search-input" placeholder="Search products..." autocomplete="off">
                    <div class="search-clear" id="searchClear"><i class="fas fa-times"></i></div>
                </div>
            </div>
        </div>

        <!-- Category Filter Chips -->
        <div class="filter-sticky-wrapper" id="filterSticky">
            <div class="category-filter">
                <div class="filter-scroll">
                    <button class="filter-chip active" data-category="all" onclick="filterByCategory('all')">
                        <i class="fas fa-table-cells-large me-2"></i>All Products
                        <span class="filter-count"><?php echo (int)$totalProducts; ?></span>
                    </button>
                    <?php if ($categories) while ($cat = mysqli_fetch_assoc($categories)): ?>
                    <button class="filter-chip" data-category="<?php echo e($cat['slug']); ?>" onclick="filterByCategory('<?php echo e($cat['slug']); ?>')">
                        <i class="<?php echo e($cat['icon'] ?: 'fas fa-tag'); ?> me-2"></i>
                        <?php echo e($cat['name']); ?>
                        <span class="filter-count"><?php echo (int)$cat['product_count']; ?></span>
                    </button>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <div id="filterLoading" class="filter-loading" style="display:none">
            <div class="spinner"></div>
            <span>Filtering products...</span>
        </div>

        <!-- Products Grid -->
        <?php if ($totalProducts > 0): ?>
        <div class="row g-4" id="productGrid" style="margin-top: 10px;">
            <?php while ($product = mysqli_fetch_assoc($products)):
                $catSlug = $product['category_slug'] ?? 'uncategorized';
                $catName = $product['category_name'] ?? 'Uncategorized';
                $hasImg  = $product['image'] && file_exists(ROOT_PATH . '/' . $product['image']);
                $desc    = trim((string)$product['description']);
                $priceText = strip_tags(formatPrice($product['price']));
            ?>
            <div class="col-xl-3 col-lg-4 col-md-6 product-item"
                 data-category="<?php echo e($catSlug); ?>"
                 data-name="<?php echo e(mb_strtolower($product['name'])); ?>"
                 data-desc="<?php echo e(mb_strtolower($desc)); ?>">
                <div class="product-card">
                    <div class="product-card-img">
                        <?php if ($hasImg): ?>
                        <img src="<?php echo url($product['image']); ?>" alt="<?php echo e($product['name']); ?>" loading="lazy">
                        <?php else: ?>
                        <div class="product-card-img-placeholder"><i class="fas fa-box-open"></i></div>
                        <?php endif; ?>
                        <span class="product-cat-badge"><?php echo e($catName); ?></span>
                    </div>
                    <div class="product-card-body">
                        <h5 class="product-card-title"><?php echo e($product['name']); ?></h5>
                        <?php if ($desc !== ''): ?>
                        <p class="product-card-text"><?php echo e(mb_substr($desc, 0, 110)) . (mb_strlen($desc) > 110 ? '...' : ''); ?></p>
                        <?php endif; ?>
                        <div class="product-price"><?php echo formatPrice($product['price']); ?></div>
                        <a href="<?php echo e(waLink('Hello ' . $companyName . ', I want to order: ' . $product['name'] . ' (' . $priceText . '). Please confirm availability.')); ?>" target="_blank" rel="noopener" class="btn-order">
                            <i class="fab fa-whatsapp me-2"></i>Order Now
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <div class="empty-state">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No products yet</h4>
                <p class="text-muted">New products are coming soon. Check back shortly!</p>
            </div>
        </div>
        <?php endif; ?>

        <!-- No Results -->
        <div id="noResults" class="no-results text-center" style="display:none">
            <div class="empty-state">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h4>No products match your search</h4>
                <p class="text-muted">Try a different keyword or category.</p>
                <button class="btn btn-accent rounded-pill px-4 mt-2" onclick="resetFilters()">
                    <i class="fas fa-rotate-left me-2"></i>Reset Filters
                </button>
            </div>
        </div>

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
