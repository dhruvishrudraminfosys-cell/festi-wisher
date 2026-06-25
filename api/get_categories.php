<?php
require "../config.php";

header("Content-Type: application/json");

$sql = "SELECT * FROM categories ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

$categories = [];

while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
}

echo json_encode([
    "status" => true,
    "message" => "Category list fetched successfully",
    "data" => $categories
]);
?>