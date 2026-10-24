<?php
require_once __DIR__ . '/_layout.php';
adminRequire();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfValid()) { flash('danger', 'Session expired. Try again.'); redirectTo('faqs.php'); }
    $do = $_POST['do'] ?? '';
    $id = (int)($_POST['id'] ?? 0);

    if ($do === 'delete' && $id) {
        $s = mysqli_prepare($conn, "DELETE FROM faqs WHERE id = ?");
        mysqli_stmt_bind_param($s, 'i', $id); mysqli_stmt_execute($s); mysqli_stmt_close($s);
        flash('success', 'FAQ deleted.');
    } elseif ($do === 'save') {
        $q = sanitize($_POST['question'] ?? '');
        $a = sanitize($_POST['answer'] ?? '');
        $sort = (int)($_POST['sort_order'] ?? 0);
        if ($q === '' || $a === '') { flash('danger', 'Question and answer are required.'); redirectTo('faqs.php'); }
        if ($id) {
            $s = mysqli_prepare($conn, "UPDATE faqs SET question=?, answer=?, sort_order=? WHERE id=?");
            mysqli_stmt_bind_param($s, 'ssii', $q, $a, $sort, $id);
        } else {
            $s = mysqli_prepare($conn, "INSERT INTO faqs (question, answer, sort_order) VALUES (?,?,?)");
            mysqli_stmt_bind_param($s, 'ssi', $q, $a, $sort);
        }
        mysqli_stmt_execute($s); mysqli_stmt_close($s);
        flash('success', $id ? 'FAQ updated.' : 'FAQ added.');
    }
    redirectTo('faqs.php');
}

$editing = null;
if (isset($_GET['edit'])) {
    $eid = (int)$_GET['edit'];
    $s = mysqli_prepare($conn, "SELECT * FROM faqs WHERE id = ?");
    mysqli_stmt_bind_param($s, 'i', $eid); mysqli_stmt_execute($s);
    $editing = mysqli_fetch_assoc(mysqli_stmt_get_result($s)); mysqli_stmt_close($s);
}
$f = $editing ?: ['id' => 0, 'question' => '', 'answer' => '', 'sort_order' => 0];
$list = mysqli_query($conn, "SELECT * FROM faqs ORDER BY sort_order, id");
adminHeader('FAQs', 'faqs.php');
?>
<div class="row g-3">
    <div class="col-lg-5">
        <div class="card-a p-4">
            <h5 class="fw-bold mb-3"><?php echo $f['id'] ? 'Edit FAQ' : 'Add FAQ'; ?></h5>
            <form method="POST">
                <?php echo csrfField(); ?>
                <input type="hidden" name="do" value="save"><input type="hidden" name="id" value="<?php echo (int)$f['id']; ?>">
                <div class="mb-3"><label class="form-label fw-semibold">Question</label><input class="form-control" name="question" value="<?php echo e($f['question']); ?>" maxlength="255" required></div>
                <div class="mb-3"><label class="form-label fw-semibold">Answer</label><textarea class="form-control" name="answer" rows="5" required><?php echo e($f['answer']); ?></textarea></div>
                <div class="mb-3"><label class="form-label fw-semibold">Sort order</label><input class="form-control" type="number" name="sort_order" value="<?php echo (int)$f['sort_order']; ?>"></div>
                <button class="btn btn-accent w-100" type="submit"><i class="fas fa-floppy-disk me-2"></i>Save</button>
                <?php if ($f['id']): ?><a class="btn btn-light w-100 mt-2" href="<?php echo url('admin/faqs.php'); ?>">Cancel</a><?php endif; ?>
            </form>
        </div>
    </div>
    <div class="col-lg-7">
        <?php if ($list && mysqli_num_rows($list) > 0): while ($r = mysqli_fetch_assoc($list)): ?>
        <div class="card-a p-3 mb-2">
            <div class="d-flex justify-content-between gap-2">
                <div><strong><?php echo e($r['question']); ?></strong><div class="text-muted small mt-1"><?php echo e(mb_substr($r['answer'], 0, 140)); ?></div></div>
                <div class="text-nowrap">
                    <a class="btn btn-sm btn-outline-primary" href="<?php echo url('admin/faqs.php?edit=' . (int)$r['id']); ?>"><i class="fas fa-pen"></i></a>
                    <form method="POST" class="d-inline" onsubmit="return confirm('Delete this FAQ?');">
                        <?php echo csrfField(); ?><input type="hidden" name="do" value="delete"><input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
        <?php endwhile; else: ?>
        <div class="card-a p-4 text-center text-muted">No FAQs yet.</div>
        <?php endif; ?>
    </div>
</div>
<?php adminFooter(); ?>
