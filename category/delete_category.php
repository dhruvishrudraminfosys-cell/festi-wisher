<?php
require "../config.php";
require "../auth.php";

$baseUrl = "/festi-wisher/";

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: " . $baseUrl . "category/categories.php");
    exit;
}

$id = intval($_GET['id']);

// Fetch category
$sql = "SELECT * FROM categories WHERE id = $id LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    header("Location: " . $baseUrl . "category/categories.php");
    exit;
}

$category = mysqli_fetch_assoc($result);

// Delete from DB
$deleteSql = "DELETE FROM categories WHERE id = $id";

if (mysqli_query($conn, $deleteSql)) {

    // Delete image safely
    if (!empty($category['image'])) {

        $imagePath = "../uploads/categories/" . $category['image'];

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    header("Location: " . $baseUrl . "category/categories.php?msg=deleted");
    exit;

} else {
    header("Location: " . $baseUrl . "category/categories.php?msg=error");
    exit;
}
?>