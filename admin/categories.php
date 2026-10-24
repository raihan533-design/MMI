<?php
require_once __DIR__ . '/_layout.php';
adminRequire();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfValid()) { flash('danger', 'Session expired. Try again.'); redirectTo('categories.php'); }
    $do = $_POST['do'] ?? '';
    $id = (int)($_POST['id'] ?? 0);

    if ($do === 'delete' && $id) {
        $s = mysqli_prepare($conn, "DELETE FROM categories WHERE id = ?");
        mysqli_stmt_bind_param($s, 'i', $id); mysqli_stmt_execute($s); mysqli_stmt_close($s);
        flash('success', 'Category deleted. Its products are now uncategorized.');
        redirectTo('categories.php');
    }

    if ($do === 'save') {
        $name = sanitize($_POST['name'] ?? '');
        $icon = sanitize($_POST['icon'] ?? '');
        if ($icon !== '' && !preg_match('/^[a-z0-9\- ]{3,60}$/i', $icon)) { $icon = ''; }
        $sort = (int)($_POST['sort_order'] ?? 0);
        if ($name === '') { flash('danger', 'Category name is required.'); redirectTo('categories.php'); }

        $slug = slugify($name);
        if ($id) {
            // keep slug unique, excluding this row
            $base = $slug; $n = 1;
            while (true) {
                $s = mysqli_prepare($conn, "SELECT id FROM categories WHERE slug = ? AND id <> ?");
                mysqli_stmt_bind_param($s, 'si', $slug, $id); mysqli_stmt_execute($s);
                $exists = mysqli_fetch_assoc(mysqli_stmt_get_result($s)); mysqli_stmt_close($s);
                if (!$exists) break;
                $slug = $base . '-' . (++$n);
            }
            $s = mysqli_prepare($conn, "UPDATE categories SET name=?, slug=?, icon=?, sort_order=? WHERE id=?");
            mysqli_stmt_bind_param($s, 'sssii', $name, $slug, $icon, $sort, $id);
        } else {
            $base = $slug; $n = 1;
            while (true) {
                $s = mysqli_prepare($conn, "SELECT id FROM categories WHERE slug = ?");
                mysqli_stmt_bind_param($s, 's', $slug); mysqli_stmt_execute($s);
                $exists = mysqli_fetch_assoc(mysqli_stmt_get_result($s)); mysqli_stmt_close($s);
                if (!$exists) break;
                $slug = $base . '-' . (++$n);
            }
            $s = mysqli_prepare($conn, "INSERT INTO categories (name, slug, icon, sort_order) VALUES (?,?,?,?)");
            mysqli_stmt_bind_param($s, 'sssi', $name, $slug, $icon, $sort);
        }
        mysqli_stmt_execute($s); mysqli_stmt_close($s);
        flash('success', $id ? 'Category updated.' : 'Category added.');
        redirectTo('categories.php');
    }
}

$editing = null;
if (isset($_GET['edit'])) {
    $eid = (int)$_GET['edit'];
    $s = mysqli_prepare($conn, "SELECT * FROM categories WHERE id = ?");
    mysqli_stmt_bind_param($s, 'i', $eid); mysqli_stmt_execute($s);
    $editing = mysqli_fetch_assoc(mysqli_stmt_get_result($s)); mysqli_stmt_close($s);
}
$f = $editing ?: ['id' => 0, 'name' => '', 'icon' => 'fas fa-tag', 'sort_order' => 0];
$list = mysqli_query($conn, "SELECT c.*, (SELECT COUNT(*) FROM products WHERE category_id = c.id) AS n FROM categories c ORDER BY c.sort_order, c.name");

adminHeader('Categories', 'categories.php');
?>
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card-a p-4">
            <h5 class="fw-bold mb-3"><?php echo $f['id'] ? 'Edit category' : 'Add category'; ?></h5>
            <form method="POST">
                <?php echo csrfField(); ?>
                <input type="hidden" name="do" value="save"><input type="hidden" name="id" value="<?php echo (int)$f['id']; ?>">
                <div class="mb-3"><label class="form-label fw-semibold">Name</label><input class="form-control" name="name" value="<?php echo e($f['name']); ?>" maxlength="200" required></div>
                <div class="mb-3"><label class="form-label fw-semibold">Icon class</label><input class="form-control" name="icon" value="<?php echo e($f['icon']); ?>" placeholder="fas fa-tag"><small class="text-muted">Any <a href="https://fontawesome.com/search?o=r&m=free" target="_blank" rel="noopener">Font Awesome</a> free class, e.g. <code>fas fa-shirt</code></small></div>
                <div class="mb-3"><label class="form-label fw-semibold">Sort order</label><input class="form-control" type="number" name="sort_order" value="<?php echo (int)$f['sort_order']; ?>"></div>
                <button class="btn btn-accent w-100" type="submit"><i class="fas fa-floppy-disk me-2"></i>Save</button>
                <?php if ($f['id']): ?><a class="btn btn-light w-100 mt-2" href="<?php echo url('admin/categories.php'); ?>">Cancel</a><?php endif; ?>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card-a p-0 overflow-hidden">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Category</th><th>Products</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                <?php if ($list && mysqli_num_rows($list) > 0): while ($r = mysqli_fetch_assoc($list)): ?>
                <tr>
                    <td><i class="<?php echo e($r['icon'] ?: 'fas fa-tag'); ?> text-primary me-2"></i><span class="fw-semibold"><?php echo e($r['name']); ?></span> <small class="text-muted">/<?php echo e($r['slug']); ?></small></td>
                    <td><?php echo (int)$r['n']; ?></td>
                    <td class="text-end text-nowrap">
                        <a class="btn btn-sm btn-outline-primary" href="<?php echo url('admin/categories.php?edit=' . (int)$r['id']); ?>"><i class="fas fa-pen"></i></a>
                        <form method="POST" class="d-inline" onsubmit="return confirm('Delete this category?');">
                            <?php echo csrfField(); ?><input type="hidden" name="do" value="delete"><input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="3" class="text-center text-muted py-4">No categories yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php adminFooter(); ?>
