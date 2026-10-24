<?php
require_once __DIR__ . '/_layout.php';
adminRequire();

$fields = [
    'company_name'     => ['Shop name', 'text'],
    'hero_tagline'     => ['Home badge text', 'text'],
    'hero_title'       => ['Home headline', 'text'],
    'hero_subtitle'    => ['Home sub-headline', 'textarea'],
    'about_content'    => ['About us text (blank line = new paragraph)', 'textarea'],
    'whatsapp_number'  => ['WhatsApp number (with country code, digits only, e.g. 8801XXXXXXXXX)', 'text'],
    'company_phone'    => ['Phone (shown on site)', 'text'],
    'company_email'    => ['Email (contact form messages are sent here)', 'text'],
    'company_address'  => ['Address', 'text'],
    'working_hours'    => ['Working hours', 'text'],
    'currency_symbol'  => ['Currency symbol', 'text'],
    'facebook_url'     => ['Facebook URL', 'text'],
    'instagram_url'    => ['Instagram URL', 'text'],
    'tiktok_url'       => ['TikTok URL', 'text'],
    'youtube_url'      => ['YouTube URL', 'text'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfValid()) { flash('danger', 'Session expired. Try again.'); redirectTo('settings.php'); }
    $do = $_POST['do'] ?? '';

    if ($do === 'settings') {
        foreach ($fields as $key => $meta) {
            $val = sanitize($_POST[$key] ?? '');
            if (substr($key, -4) === '_url' && $val !== '' && !preg_match('#^https?://#i', $val)) { $val = ''; }
            if ($key === 'whatsapp_number') { $val = preg_replace('/\D+/', '', $val); }
            $s = mysqli_prepare($conn, "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
            mysqli_stmt_bind_param($s, 'ss', $key, $val); mysqli_stmt_execute($s); mysqli_stmt_close($s);
        }
        flash('success', 'Settings saved.');
    } elseif ($do === 'password') {
        $cur = (string)($_POST['current'] ?? '');
        $new = (string)($_POST['new'] ?? '');
        $uid = (int)$_SESSION['admin_id'];
        $s = mysqli_prepare($conn, "SELECT password FROM users WHERE id = ?");
        mysqli_stmt_bind_param($s, 'i', $uid); mysqli_stmt_execute($s);
        $u = mysqli_fetch_assoc(mysqli_stmt_get_result($s)); mysqli_stmt_close($s);
        if (!$u || !password_verify($cur, $u['password'])) {
            flash('danger', 'Current password is wrong.');
        } elseif (strlen($new) < 8) {
            flash('danger', 'New password must be at least 8 characters.');
        } else {
            $hash = password_hash($new, PASSWORD_BCRYPT);
            $s = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE id = ?");
            mysqli_stmt_bind_param($s, 'si', $hash, $uid); mysqli_stmt_execute($s); mysqli_stmt_close($s);
            flash('success', 'Password changed.');
        }
    }
    redirectTo('settings.php');
}

adminHeader('Settings', 'settings.php');
?>
<div class="row g-3">
    <div class="col-xl-8">
        <div class="card-a p-4">
            <h5 class="fw-bold mb-3">Shop settings</h5>
            <form method="POST">
                <?php echo csrfField(); ?><input type="hidden" name="do" value="settings">
                <div class="row g-3">
                    <?php foreach ($fields as $key => $meta): ?>
                    <div class="<?php echo $meta[1] === 'textarea' ? 'col-12' : 'col-md-6'; ?>">
                        <label class="form-label fw-semibold small"><?php echo e($meta[0]); ?></label>
                        <?php if ($meta[1] === 'textarea'): ?>
                        <textarea class="form-control" name="<?php echo e($key); ?>" rows="4"><?php echo e(getSetting($key, '')); ?></textarea>
                        <?php else: ?>
                        <input class="form-control" name="<?php echo e($key); ?>" value="<?php echo e(getSetting($key, '')); ?>">
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                    <div class="col-12"><button class="btn btn-accent px-4" type="submit"><i class="fas fa-floppy-disk me-2"></i>Save settings</button></div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card-a p-4">
            <h5 class="fw-bold mb-3">Change password</h5>
            <form method="POST" autocomplete="off">
                <?php echo csrfField(); ?><input type="hidden" name="do" value="password">
                <div class="mb-3"><label class="form-label fw-semibold small">Current password</label><input class="form-control" type="password" name="current" required></div>
                <div class="mb-3"><label class="form-label fw-semibold small">New password (min 8)</label><input class="form-control" type="password" name="new" minlength="8" required></div>
                <button class="btn btn-accent w-100" type="submit">Update password</button>
            </form>
        </div>
    </div>
</div>
<?php adminFooter(); ?>
