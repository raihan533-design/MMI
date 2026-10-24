<?php
require_once __DIR__ . '/_layout.php';
adminRequire();

function countOf($conn, $table, $where = '')
{
    $r = mysqli_query($conn, "SELECT COUNT(*) c FROM $table $where");
    return $r ? (int)mysqli_fetch_assoc($r)['c'] : 0;
}
$stats = [
    ['Products',   countOf($conn, 'products'),   'fa-box-open'],
    ['Categories', countOf($conn, 'categories'), 'fa-tags'],
    ['Unread messages', countOf($conn, 'contacts', 'WHERE is_read = 0'), 'fa-inbox'],
    ['FAQs',       countOf($conn, 'faqs'),       'fa-circle-question'],
];
$recent = mysqli_query($conn, "SELECT * FROM contacts ORDER BY id DESC LIMIT 5");

adminHeader('Dashboard', 'dashboard.php');
?>
<div class="row g-3 mb-4">
    <?php foreach ($stats as $s): ?>
    <div class="col-6 col-xl-3">
        <div class="card-a stat-box">
            <div class="ic"><i class="fas <?php echo $s[2]; ?>"></i></div>
            <div><h3><?php echo $s[1]; ?></h3><small class="text-muted"><?php echo e($s[0]); ?></small></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card-a p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Latest messages</h5>
                <a href="<?php echo url('admin/messages.php'); ?>" class="small">View all</a>
            </div>
            <?php if ($recent && mysqli_num_rows($recent) > 0): while ($m = mysqli_fetch_assoc($recent)): ?>
            <div class="d-flex gap-3 py-2 border-bottom">
                <i class="fas fa-circle mt-2" style="font-size:8px;color:<?php echo $m['is_read'] ? '#cfd2ea' : '#1D19F2'; ?>"></i>
                <div class="flex-grow-1">
                    <strong><?php echo e($m['name']); ?></strong> <small class="text-muted">&middot; <?php echo e($m['email']); ?></small>
                    <div class="text-muted small"><?php echo e(mb_substr($m['message'], 0, 90)); ?></div>
                </div>
                <small class="text-muted"><?php echo e(date('d M', strtotime($m['created_at']))); ?></small>
            </div>
            <?php endwhile; else: ?>
            <p class="text-muted mb-0">No messages yet.</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card-a p-4">
            <h5 class="fw-bold mb-3">Quick actions</h5>
            <div class="d-grid gap-2">
                <a class="btn btn-accent" href="<?php echo url('admin/products.php?action=new'); ?>"><i class="fas fa-plus me-2"></i>Add product</a>
                <a class="btn btn-outline-primary" href="<?php echo url('admin/categories.php'); ?>"><i class="fas fa-tags me-2"></i>Manage categories</a>
                <a class="btn btn-outline-primary" href="<?php echo url('admin/settings.php'); ?>"><i class="fas fa-gear me-2"></i>Shop settings &amp; WhatsApp number</a>
            </div>
        </div>
    </div>
</div>
<?php adminFooter(); ?>
