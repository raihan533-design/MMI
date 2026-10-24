<?php
require_once __DIR__ . '/_layout.php';
adminRequire();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfValid()) { flash('danger', 'Session expired. Try again.'); redirectTo('messages.php'); }
    $do = $_POST['do'] ?? '';
    $id = (int)($_POST['id'] ?? 0);
    if ($do === 'toggle' && $id) {
        $s = mysqli_prepare($conn, "UPDATE contacts SET is_read = 1 - is_read WHERE id = ?");
        mysqli_stmt_bind_param($s, 'i', $id); mysqli_stmt_execute($s); mysqli_stmt_close($s);
    } elseif ($do === 'delete' && $id) {
        $s = mysqli_prepare($conn, "DELETE FROM contacts WHERE id = ?");
        mysqli_stmt_bind_param($s, 'i', $id); mysqli_stmt_execute($s); mysqli_stmt_close($s);
        flash('success', 'Message deleted.');
    }
    redirectTo('messages.php');
}

$list = mysqli_query($conn, "SELECT * FROM contacts ORDER BY id DESC");
adminHeader('Messages', 'messages.php');
?>
<?php if ($list && mysqli_num_rows($list) > 0): while ($m = mysqli_fetch_assoc($list)): ?>
<div class="card-a p-4 mb-3" style="<?php echo $m['is_read'] ? '' : 'border-left:4px solid #1D19F2'; ?>">
    <div class="d-flex justify-content-between flex-wrap gap-2">
        <div>
            <strong><?php echo e($m['name']); ?></strong>
            <a class="ms-2 small" href="mailto:<?php echo e($m['email']); ?>"><?php echo e($m['email']); ?></a>
            <?php if (!$m['is_read']): ?><span class="badge bg-primary ms-2">New</span><?php endif; ?>
        </div>
        <small class="text-muted"><?php echo e(date('d M Y, h:i A', strtotime($m['created_at']))); ?></small>
    </div>
    <p class="mt-3 mb-3" style="white-space:pre-wrap"><?php echo e($m['message']); ?></p>
    <div class="d-flex gap-2">
        <form method="POST"><?php echo csrfField(); ?><input type="hidden" name="do" value="toggle"><input type="hidden" name="id" value="<?php echo (int)$m['id']; ?>">
            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-envelope-open me-1"></i>Mark <?php echo $m['is_read'] ? 'unread' : 'read'; ?></button></form>
        <a class="btn btn-sm btn-outline-secondary" href="mailto:<?php echo e($m['email']); ?>"><i class="fas fa-reply me-1"></i>Reply</a>
        <form method="POST" onsubmit="return confirm('Delete this message?');"><?php echo csrfField(); ?><input type="hidden" name="do" value="delete"><input type="hidden" name="id" value="<?php echo (int)$m['id']; ?>">
            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash me-1"></i>Delete</button></form>
    </div>
</div>
<?php endwhile; else: ?>
<div class="card-a p-5 text-center text-muted"><i class="fas fa-inbox fa-2x mb-3"></i><p class="mb-0">No messages yet.</p></div>
<?php endif;
adminFooter(); ?>
