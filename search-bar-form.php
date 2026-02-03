<?php
    $search = $_GET['search'] ?? '';
    $sortBy = $_GET['sort_by'] ?? '';
?>
<form method="get" class="row g-2 justify-content-center">
    <div class="col-md-8 col-sm-8">
        <input type="text" name="search" class="form-control border-secondary" placeholder="Search product, brand, or specification..." value="<?= htmlspecialchars($search) ?>">
    </div>
    <div class="d-flex gap-2 col-md-2 col-sm-4">
        <select name="sort_by" class="form-select border-secondary" onchange="this.form.submit()">
            <option value="">Sort</option>
            <option value="price_asc" <?= $sortBy=='price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
            <option value="price_desc" <?= $sortBy=='price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
        </select>
        <button class="btn btn-primary w-100" type="submit">Search</button>
    </div>
</form>
