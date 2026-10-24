<?php
require_once __DIR__ . '/../includes/config.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: ' . url('admin/dashboard.php'));
    exit;
}

$error = '';
$_SESSION['login_fails'] = $_SESSION['login_fails'] ?? 0;
$_SESSION['login_lock_until'] = $_SESSION['login_lock_until'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (time() < $_SESSION['login_lock_until']) {
        $error = 'Too many attempts. Please wait a minute and try again.';
    } elseif (!csrfValid()) {
        $error = 'Session expired. Please try again.';
    } else {
        $username = sanitize($_POST['username'] ?? '');
        $password = (string)($_POST['password'] ?? '');
        $stmt = mysqli_prepare($conn, "SELECT id, username, password FROM users WHERE username = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = (int)$user['id'];
            $_SESSION['admin_name'] = $user['username'];
            $_SESSION['login_fails'] = 0;
            header('Location: ' . url('admin/dashboard.php'));
            exit;
        }
        $_SESSION['login_fails']++;
        if ($_SESSION['login_fails'] >= 5) {
            $_SESSION['login_lock_until'] = time() + 60;
            $_SESSION['login_fails'] = 0;
        }
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | kiuixshop</title>
    <meta name="robots" content="noindex,nofollow">
    <link rel="icon" href="<?php echo url('assets/images/favicon.png'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family:'Inter',sans-serif; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:20px;
               background:linear-gradient(135deg,#1D19F2,#0A0F3D); }
        .login-card { background:#fff; border-radius:24px; padding:40px 34px; width:100%; max-width:410px; box-shadow:0 30px 70px rgba(0,0,0,.3); }
        .login-card img { height:38px; }
        .form-control { border-radius:12px; padding:.75rem 1rem; border:2px solid #E3E6F6; }
        .form-control:focus { border-color:#1D19F2; box-shadow:0 0 0 .2rem rgba(29,25,242,.15); }
        .btn-login { background:#1D19F2; color:#fff; border-radius:12px; padding:.8rem; font-weight:600; border:none; }
        .btn-login:hover { background:#120FC4; color:#fff; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-end gap-1"><img src="<?php echo url('assets/images/logo.png'); ?>" alt="kiuix"><strong style="color:#1D19F2;line-height:1;padding-bottom:4px">shop</strong></div>
            <p class="text-muted mt-2 mb-0">Admin sign in</p>
        </div>
        <?php if ($error): ?><div class="alert alert-danger py-2"><?php echo e($error); ?></div><?php endif; ?>
        <form method="POST" autocomplete="off">
            <?php echo csrfField(); ?>
            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <input type="text" name="username" class="form-control" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-login w-100" type="submit"><i class="fas fa-right-to-bracket me-2"></i>Sign In</button>
        </form>
        <div class="text-center mt-3"><a href="<?php echo url('index.php'); ?>" class="small text-muted text-decoration-none">&larr; Back to site</a></div>
    </div>
</body>
</html>
