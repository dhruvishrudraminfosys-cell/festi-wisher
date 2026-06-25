<?php
require "../config.php";
require "../auth.php";

$baseUrl = "/festi-wisher/";

/* =========================
   VALIDATE ID
========================= */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: " . $baseUrl . "sub_category/subcategories.php?msg=invalid");
    exit;
}

$id = intval($_GET['id']);

/* =========================
   FETCH DATA FIRST
========================= */
$sql = "SELECT * FROM sub_category WHERE id = $id LIMIT 1";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: " . $baseUrl . "sub_category/subcategories.php?msg=notfound");
    exit;
}

$row = mysqli_fetch_assoc($result);

/* =========================
   DELETE FROM DATABASE
========================= */
$deleteSql = "DELETE FROM sub_category WHERE id = $id";

if (mysqli_query($conn, $deleteSql)) {

    /* =========================
       DELETE VIDEO FRAME IMAGE
    ========================= */
    $imagePath = "../uploads/sub_categories/" . $row['video_frame'];
    if (!empty($row['video_frame']) && file_exists($imagePath)) {
        unlink($imagePath);
    }

    /* =========================
       DELETE VIDEO FILE
    ========================= */
    $videoPath = "../uploads/sub_categories/videos/" . $row['video_url'];
    if (!empty($row['video_url']) && file_exists($videoPath)) {
        unlink($videoPath);
    }

    /* =========================
       DELETE THUMBNAIL IMAGE
    ========================= */
    $thumbPath = "../uploads/sub_categories/" . $row['video_thumbnail_image'];
    if (!empty($row['video_thumbnail_image']) && file_exists($thumbPath)) {
        unlink($thumbPath);
    }

    header("Location: " . $baseUrl . "sub_category/subcategories.php?msg=deleted");
    exit;

} else {
    header("Location: " . $baseUrl . "sub_category/subcategories.php?msg=error");
    exit;
}
?>