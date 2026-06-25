<?php
require "../config.php";

header("Content-Type: application/json");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode([
        "status" => false,
        "message" => "Invalid video id"
    ]);
    exit;
}

$id = intval($_GET['id']);

// Count increment
$update = mysqli_query(
    $conn,
    "UPDATE sub_category 
     SET video_count = video_count + 1 
     WHERE id = $id"
);

if (!$update) {
    echo json_encode([
        "status" => false,
        "message" => mysqli_error($conn)
    ]);
    exit;
}

// Latest count & URL
$result = mysqli_query(
    $conn,
    "SELECT id, video_url, video_count 
     FROM sub_category 
     WHERE id = $id"
);

$data = mysqli_fetch_assoc($result);

echo json_encode([
    "status" => true,
    "message" => "Video count updated",
    "data" => $data
]);