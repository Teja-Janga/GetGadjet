<?php
    $searchCategory = $searchCategory ?? '';
    $search = $_GET['search'] ?? '';
    $sortBy = $_GET['sort_by'] ?? '';
    $query = 'SELECT * FROM products WHERE 1';
    if ($searchCategory) $query .= " AND Category='" . mysqli_real_escape_string($conn, $searchCategory) . "'";
    if ($search) $query .= " AND (Title LIKE '%" . mysqli_real_escape_string($conn, $search) . "%' OR BrandName LIKE '%" . mysqli_real_escape_string($conn, $search) . "%' OR Description LIKE '%" . mysqli_real_escape_string($conn, $search) . "%')";
    switch ($sortBy) {
        case 'price_asc':  $query .= " ORDER BY Price ASC"; break;
        case 'price_desc': $query .= " ORDER BY Price DESC"; break;
    }
    $searchResults = mysqli_query($conn, $query);
?>
