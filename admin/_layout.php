<?php
/** Shared admin helpers + layout. Include at top of every admin page (except login). */
require_once __DIR__ . '/../includes/config.php';

function adminRequire()
{
    if (empty($_SESSION['admin_id'])) {
        header('Location: ' . url('admin/index.php'));
        exit;
    }
}

function adminHeader($title, $active = '')
{
    global $conn;
    $unread = 0;
    $r = mysqli_query($conn, "SELECT COUNT(*) c FROM contacts WHERE is_read = 0");
    if ($r) { $unread = (int)mysqli_fetch_assoc($r)['c']; }
    $nav = [
        'dashboard.php'  => ['Dashboard', 'fa-gauge'],
        'products.php'   => ['Products', 'fa-box-open'],
        'categories.php' => ['Categories', 'fa-tags'],
        'messages.php'   => ['Messages', 'fa-inbox'],
        'faqs.php'       => ['FAQs', 'fa-circle-question'],
        'settings.php'   => ['Settings', 'fa-gear'],
    ];
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($title); ?> | kiuixshop Admin</title>
    <meta name="robots" content="noindex,nofollow">
    <link rel="icon" href="<?php echo url('assets/images/favicon.png'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --accent:#1D19F2; --accent-dark:#120FC4; --soft:#EEF0FF; }
        body { font-family:'Inter',system-ui,sans-serif; background:#F5F6FF; color:#12163A; }
        .admin-side { width:240px; background:#0A0F3D; min-height:100vh; position:fixed; inset:0 auto 0 0; padding:20px 14px; z-index:100; }
        .admin-side .brand { padding:6px 10px 22px; display:flex; align-items:flex-end; gap:4px; }
        .admin-side .brand img { height:30px; }
        .admin-side .brand span { color:#9EA0FF; font-weight:700; line-height:1; padding-bottom:4px; }
        .admin-side a.nav-i { display:flex; align-items:center; gap:12px; color:#B8BCEB; padding:11px 14px; border-radius:12px; text-decoration:none; margin-bottom:4px; font-weight:500; }
        .admin-side a.nav-i:hover { background:rgba(255,255,255,.07); color:#fff; }
        .admin-side a.nav-i.active { background:var(--accent); color:#fff; }
        .admin-side .badge { margin-left:auto; }
        .admin-main { margin-left:240px; padding:28px; }
        .card-a { background:#fff; border:1px solid #E3E6F6; border-radius:16px; box-shadow:0 8px 24px rgba(29,25,242,.05); }
        .btn-accent { background:var(--accent); color:#fff; border:none; font-weight:600; }
        .btn-accent:hover { background:var(--accent-dark); color:#fff; }
        .stat-box { padding:20px; display:flex; gap:16px; align-items:center; }
        .stat-box .ic { width:52px; height:52px; border-radius:14px; background:var(--soft); color:var(--accent); display:flex; align-items:center; justify-content:center; font-size:1.3rem; }
        .stat-box h3 { margin:0; font-weight:800; }
        .thumb { width:48px; height:48px; border-radius:10px; object-fit:cover; background:var(--soft); }
        .form-control:focus, .form-select:focus { border-color:var(--accent); box-shadow:0 0 0 .2rem rgba(29,25,242,.15); }
        @media (max-width: 991.98px) { .admin-side { position:static; width:100%; min-height:0; display:flex; flex-wrap:wrap; gap:4px; } .admin-side .brand { width:100%; padding-bottom:10px; } .admin-main { margin-left:0; padding:18px; } }
    </style>
</head>
<body>
<aside class="admin-side">
    <div class="brand"><img src="<?php echo url('assets/images/logo-white.png'); ?>" alt="kiuix"><span>shop</span></div>
    <?php foreach ($nav as $file => $n): ?>
    <a class="nav-i <?php echo $active === $file ? 'active' : ''; ?>" href="<?php echo url('admin/' . $file); ?>">
        <i class="fas <?php echo $n[1]; ?>"></i><?php echo $n[0]; ?>
        <?php if ($file === 'messages.php' && $unread > 0): ?><span class="badge rounded-pill bg-danger"><?php echo $unread; ?></span><?php endif; ?>
    </a>
    <?php endforeach; ?>
    <a class="nav-i" href="<?php echo url('index.php'); ?>" target="_blank"><i class="fas fa-arrow-up-right-from-square"></i>View Site</a>
    <a class="nav-i" href="<?php echo url('admin/logout.php'); ?>"><i class="fas fa-right-from-bracket"></i>Logout</a>
</aside>
<main class="admin-main">
    <h3 class="fw-bold mb-4"><?php echo e($title); ?></h3>
    <?php
    if (!empty($_SESSION['flash'])) {
        [$type, $msg] = $_SESSION['flash']; unset($_SESSION['flash']);
        echo '<div class="alert alert-' . e($type) . ' alert-dismissible fade show"><i class="fas fa-circle-info me-2"></i>' . e($msg) . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
}

function adminFooter()
{
    echo '</main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html>';
}

function flash($type, $msg) { $_SESSION['flash'] = [$type, $msg]; }

function redirectTo($file) { header('Location: ' . url('admin/' . $file)); exit; }

/** Validate + store an uploaded product image. Returns relative path, '' if none, or null on error (flash set). */
function handleImageUpload($field)
{
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) { return ''; }
    $f = $_FILES[$field];
    if ($f['error'] !== UPLOAD_ERR_OK) { flash('danger', 'Image upload failed.'); return null; }
    if ($f['size'] > 4 * 1024 * 1024) { flash('danger', 'Image must be under 4 MB.'); return null; }
    $info = @getimagesize($f['tmp_name']);
    $allowed = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp'];
    if (!$info || !isset($allowed[$info[2]])) { flash('danger', 'Only JPG, PNG or WEBP images are allowed.'); return null; }
    $dir = ROOT_PATH . '/uploads';
    if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
    $name = 'p_' . bin2hex(random_bytes(8)) . '.' . $allowed[$info[2]];
    if (!move_uploaded_file($f['tmp_name'], $dir . '/' . $name)) { flash('danger', 'Could not save the image. Check the uploads folder permissions.'); return null; }
    return 'uploads/' . $name;
}

function deleteImageFile($path)
{
    if ($path && strpos($path, 'uploads/') === 0 && strpos($path, '..') === false) {
        $full = ROOT_PATH . '/' . $path;
        if (is_file($full)) { @unlink($full); }
    }
}
