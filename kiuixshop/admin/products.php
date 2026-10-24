<?php
require_once __DIR__ . '/_layout.php';
adminRequire();

$cats = [];
$cr = mysqli_query($conn, "SELECT id, name FROM categories ORDER BY sort_order, name");
while ($cr && $row = mysqli_fetch_assoc($cr)) { $cats[] = $row; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfValid()) { flash('danger', 'Session expired. Try again.'); redirectTo('products.php'); }
    $do = $_POST['do'] ?? '';
    $id = (int)($_POST['id'] ?? 0);

    if ($do === 'delete' && $id) {
        $s = mysqli_prepare($conn, "SELECT image FROM products WHERE id = ?");
        mysqli_stmt_bind_param($s, 'i', $id); mysqli_stmt_execute($s);
        $old = mysqli_fetch_assoc(mysqli_stmt_get_result($s));
        mysqli_stmt_close($s);
        $s = mysqli_prepare($conn, "DELETE FROM products WHERE id = ?");
        mysqli_stmt_bind_param($s, 'i', $id); mysqli_stmt_execute($s); mysqli_stmt_close($s);
        if ($old) { deleteImageFile($old['image']); }
        flash('success', 'Product deleted.');
        redirectTo('products.php');
    }

    if ($do === 'save') {
        $name  = sanitize($_POST['name'] ?? '');
        $price = max(0, (float)($_POST['price'] ?? 0));
        $desc  = sanitize($_POST['description'] ?? '');
        $catId = (int)($_POST['category_id'] ?? 0);
        $catId = $catId > 0 ? $catId : null;
        $sort  = (int)($_POST['sort_order'] ?? 0);
        if ($name === '') { flash('danger', 'Product name is required.'); redirectTo('products.php' . ($id ? "?edit=$id" : '?action=new')); }

        $img = handleImageUpload('image');
        if ($img === null) { redirectTo('products.php' . ($id ? "?edit=$id" : '?action=new')); }

        if ($id) {
            if ($img !== '') {
                $s = mysqli_prepare($conn, "SELECT image FROM products WHERE id = ?");
                mysqli_stmt_bind_param($s, 'i', $id); mysqli_stmt_execute($s);
                $old = mysqli_fetch_assoc(mysqli_stmt_get_result($s)); mysqli_stmt_close($s);
                $s = mysqli_prepare($conn, "UPDATE products SET name=?, price=?, description=?, category_id=?, sort_order=?, image=? WHERE id=?");
                mysqli_stmt_bind_param($s, 'sdsiisi', $name, $price, $desc, $catId, $sort, $img, $id);
                if ($old) { deleteImageFile($old['image']); }
            } else {
                $s = mysqli_prepare($conn, "UPDATE products SET name=?, price=?, description=?, category_id=?, sort_order=? WHERE id=?");
                mysqli_stmt_bind_param($s, 'sdsiii', $name, $price, $desc, $catId, $sort, $id);
            }
            mysqli_stmt_execute($s); mysqli_stmt_close($s);
            flash('success', 'Product updated.');
        } else {
            $imgVal = $img !== '' ? $img : null;
            $s = mysqli_prepare($conn, "INSERT INTO products (name, price, description, category_id, sort_order, image) VALUES (?,?,?,?,?,?)");
            mysqli_stmt_bind_param($s, 'sdsiis', $name, $price, $desc, $catId, $sort, $imgVal);
            mysqli_stmt_execute($s); mysqli_stmt_close($s);
            flash('success', 'Product added.');
        }
        redirectTo('products.php');
    }
}

$editing = null;
if (isset($_GET['edit'])) {
    $eid = (int)$_GET['edit'];
    $s = mysqli_prepare($conn, "SELECT * FROM products WHERE id = ?");
    mysqli_stmt_bind_param($s, 'i', $eid); mysqli_stmt_execute($s);
    $editing = mysqli_fetch_assoc(mysqli_stmt_get_result($s)); mysqli_stmt_close($s);
}
$showForm = $editing || (($_GET['action'] ?? '') === 'new');

adminHeader('Products', 'products.php');

if ($showForm): $p = $editing ?: ['id' => 0, 'name' => '', 'price' => '', 'description' => '', 'category_id' => '', 'sort_order' => 0, 'image' => '']; ?>
<div class="card-a p-4" style="max-width:760px">
    <h5 class="fw-bold mb-3"><?php echo $p['id'] ? 'Edit product' : 'Add product'; ?></h5>
    <form method="POST" enctype="multipart/form-data">
        <?php echo csrfField(); ?>
        <input type="hidden" name="do" value="save">
        <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-semibold">Name</label>
                <input class="form-control" name="name" value="<?php echo e($p['name']); ?>" maxlength="200" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Price (<?php echo e(getSetting('currency_symbol', '৳')); ?>)</label>
                <input class="form-control" type="number" step="0.01" min="0" name="price" value="<?php echo e($p['price']); ?>" required>
            </div>
            <div class="col-md-8">
                <label class="form-label fw-semibold">Category</label>
                <select class="form-select" name="category_id">
                    <option value="">Uncategorized</option>
                    <?php foreach ($cats as $c): ?>
                    <option value="<?php echo (int)$c['id']; ?>" <?php echo (int)$p['category_id'] === (int)$c['id'] ? 'selected' : ''; ?>><?php echo e($c['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Sort order</label>
                <input class="form-control" type="number" name="sort_order" value="<?php echo (int)$p['sort_order']; ?>">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Description</label>
                <textarea class="form-control" name="description" rows="4"><?php echo e($p['description']); ?></textarea>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Image <small class="text-muted fw-normal">(JPG / PNG / WEBP, max 4 MB)</small></label>
                <?php if ($p['image'] && is_file(ROOT_PATH . '/' . $p['image'])): ?>
                <div class="mb-2"><img src="<?php echo url($p['image']); ?>" class="thumb" style="width:90px;height:90px" alt=""> <small class="text-muted ms-2">Upload a new file to replace it.</small></div>
                <?php endif; ?>
                <input class="form-control" type="file" name="image" accept="image/jpeg,image/png,image/webp">
            </div>
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-accent px-4" type="submit"><i class="fas fa-floppy-disk me-2"></i>Save</button>
                <a class="btn btn-light" href="<?php echo url('admin/products.php'); ?>">Cancel</a>
            </div>
        </div>
    </form>
</div>
<?php else:
    $list = mysqli_query($conn, "SELECT p.*, c.name AS cat FROM products p LEFT JOIN categories c ON c.id = p.category_id ORDER BY p.sort_order ASC, p.id DESC"); ?>
<div class="d-flex justify-content-end mb-3">
    <a class="btn btn-accent" href="<?php echo url('admin/products.php?action=new'); ?>"><i class="fas fa-plus me-2"></i>Add product</a>
</div>
<div class="card-a p-0 overflow-hidden">
    <div class="table-responsive">
    <table class="table align-middle mb-0">
        <thead class="table-light"><tr><th style="width:70px"></th><th>Name</th><th>Category</th><th>Price</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        <?php if ($list && mysqli_num_rows($list) > 0): while ($r = mysqli_fetch_assoc($list)): ?>
        <tr>
            <td><?php if ($r['image'] && is_file(ROOT_PATH . '/' . $r['image'])): ?><img class="thumb" src="<?php echo url($r['image']); ?>" alt=""><?php else: ?><div class="thumb d-flex align-items-center justify-content-center text-primary"><i class="fas fa-box-open"></i></div><?php endif; ?></td>
            <td class="fw-semibold"><?php echo e($r['name']); ?></td>
            <td><?php echo e($r['cat'] ?: 'Uncategorized'); ?></td>
            <td><?php echo formatPrice($r['price']); ?></td>
            <td class="text-end text-nowrap">
                <a class="btn btn-sm btn-outline-primary" href="<?php echo url('admin/products.php?edit=' . (int)$r['id']); ?>"><i class="fas fa-pen"></i></a>
                <form method="POST" class="d-inline" onsubmit="return confirm('Delete this product?');">
                    <?php echo csrfField(); ?><input type="hidden" name="do" value="delete"><input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                </form>
            </td>
        </tr>
        <?php endwhile; else: ?>
        <tr><td colspan="5" class="text-center text-muted py-4">No products yet. Click "Add product".</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
<?php endif;
adminFooter(); ?>
