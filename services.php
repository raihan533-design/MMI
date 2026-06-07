<?php
$pageTitle = 'Our Services';
require_once 'includes/config.php';
require_once 'includes/header.php';

$categories = mysqli_query($conn, "SELECT c.*, (SELECT COUNT(*) FROM services WHERE category_id = c.id) as service_count FROM categories c ORDER BY c.sort_order ASC, c.name ASC");
$services = mysqli_query($conn, "SELECT s.*, c.name as category_name, c.slug as category_slug FROM services s LEFT JOIN categories c ON s.category_id = c.id ORDER BY s.id DESC");
$totalServices = mysqli_num_rows($services);
?>
<div class="toast-container"></div>

<div class="services-hero">
    <div class="services-hero-overlay"></div>
    <div class="container position-relative" style="z-index:2">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8" data-aos="fade-up">
                <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill fw-semibold">
                    <i class="fas fa-tools me-1"></i> What We Offer
                </span>
                <h1 class="display-4 fw-bold text-white mb-3">Our Services</h1>
                <p class="lead text-white-50 mb-0">High-quality home services in Singapore — reliable, affordable, and expertly done.</p>
            </div>
        </div>
    </div>
</div>

<section class="services-page-section" id="servicesSection">
    <div class="container">

        <!-- Dark/Light Mode Toggle -->
        <div class="d-flex justify-content-end mb-3">
            <div class="theme-toggle-wrapper">
                <button id="themeToggle" class="theme-toggle-btn" aria-label="Toggle theme">
                    <i class="fas fa-sun"></i>
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="row justify-content-center mb-4" data-aos="fade-up">
            <div class="col-lg-8 col-md-10">
                <div class="search-wrapper">
                    <div class="search-icon"><i class="fas fa-search"></i></div>
                    <input type="text" id="serviceSearch" class="form-control service-search-input" placeholder="Search services..." autocomplete="off">
                    <div class="search-clear" id="searchClear"><i class="fas fa-times"></i></div>
                </div>
            </div>
        </div>

        <!-- Category Filter Chips -->
        <div class="filter-sticky-wrapper" id="filterSticky">
            <div class="category-filter" data-aos="fade-up">
                <div class="filter-scroll">
                    <button class="filter-chip active" data-category="all" onclick="filterByCategory('all')">
                        <i class="fas fa-th-large me-2"></i>All Services
                        <span class="filter-count"><?php echo $totalServices; ?></span>
                    </button>
                    <?php mysqli_data_seek($categories, 0); while ($cat = mysqli_fetch_assoc($categories)): ?>
                    <button class="filter-chip" data-category="<?php echo htmlspecialchars($cat['slug']); ?>" onclick="filterByCategory('<?php echo htmlspecialchars($cat['slug']); ?>')">
                        <i class="<?php echo htmlspecialchars($cat['icon'] ?: 'fas fa-tag'); ?> me-2"></i>
                        <?php echo htmlspecialchars($cat['name']); ?>
                        <span class="filter-count"><?php echo $cat['service_count']; ?></span>
                    </button>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <!-- Loading Spinner -->
        <div id="filterLoading" class="filter-loading" style="display:none">
            <div class="spinner"></div>
            <span>Filtering services...</span>
        </div>

        <!-- Services Grid -->
        <?php if ($totalServices > 0): ?>
        <div class="row g-4" id="serviceGrid" style="margin-top: 10px;">
            <?php while ($service = mysqli_fetch_assoc($services)): 
                $catSlug = $service['category_slug'] ?? 'uncategorized';
                $catName = $service['category_name'] ?? 'Uncategorized';
            ?>
            <div class="col-xl-3 col-lg-4 col-md-6 service-item" 
                 data-category="<?php echo htmlspecialchars($catSlug, ENT_QUOTES, 'UTF-8'); ?>"
                 data-name="<?php echo htmlspecialchars(strtolower(html_entity_decode($service['name'])), ENT_QUOTES, 'UTF-8'); ?>"
                 data-desc="<?php echo htmlspecialchars(strtolower(html_entity_decode($service['description'] ?? '')), ENT_QUOTES, 'UTF-8'); ?>"
                 data-aos="fade-up" data-aos-delay="100">
                <div class="service-card">
                    <div class="service-card-img">
                        <?php
                        $imgSrc = htmlspecialchars($service['image']);
                        $imgPath = $service['image'] ? str_replace('/', DIRECTORY_SEPARATOR, $_SERVER['DOCUMENT_ROOT'] . $service['image']) : '';
                        $imgExists = $service['image'] && file_exists($imgPath);
                        ?>
                        <?php if ($imgExists): ?>
                        <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars(html_entity_decode($service['name'])); ?>" loading="lazy">
                        <div class="service-card-img-overlay">
                            <i class="fas fa-search-plus"></i>
                        </div>
                        <?php else: ?>
                        <div class="service-card-img-placeholder">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <?php endif; ?>
                        <span class="service-cat-badge"><?php echo htmlspecialchars($catName); ?></span>
                    </div>
                    <div class="service-card-body">
                        <h5 class="service-card-title"><?php echo htmlspecialchars(html_entity_decode($service['name'])); ?></h5>
                        <?php $desc = html_entity_decode($service['description'] ?? ''); if (trim($desc)): ?>
                        <p class="service-card-text"><?php echo htmlspecialchars(mb_substr($desc, 0, 120)) . (mb_strlen($desc) > 120 ? '...' : ''); ?></p>
                        <?php endif; ?>
                        <div class="service-card-footer">
                            <a href="https://wa.me/6585878190?text=<?php echo urlencode("Hello MMI ENGINEERING, I am interested in your service: " . $service['name'] . ". Please provide more details."); ?>" target="_blank" class="btn btn-book">
                                <i class="fab fa-whatsapp me-2"></i>Book Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-5" data-aos="fade-up">
            <div class="empty-state">
                <i class="fas fa-tools fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No services available yet</h4>
                <p class="text-muted">Check back soon for our latest offerings.</p>
            </div>
        </div>
        <?php endif; ?>

        <!-- No Results -->
        <div id="noResults" class="no-results" style="display:none" data-aos="fade-up">
            <div class="empty-state">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h4>No services match your criteria</h4>
                <p class="text-muted">Try adjusting your search or filter selection.</p>
                <button class="btn btn-accent mt-2" onclick="resetFilters()">
                    <i class="fas fa-redo me-2"></i>Reset Filters
                </button>
            </div>
        </div>

    </div>
</section>

<script src="/MMI/assets/js/services.js"></script>

<?php require_once 'includes/footer.php'; ?>
