<?php
require "../config.php";

header("Content-Type: application/json");

$response = [];

$sql = "SELECT * FROM sub_category ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if ($result) {

    $sub_categories = [];

    while ($row = mysqli_fetch_assoc($result)) {

        $sub_categories[] = [
            "id" => (int)$row['id'],
            "category_id" => (int)$row['category_id'],
            "video_frame" => $row['video_frame'],
            "video_url" => $row['video_url'],
            "video_thumbnail_image" => $row['video_thumbnail_image'],
            "video_count" => (int)$row['video_count'],
            "created_at" => $row['created_at'],
            "updated_at" => $row['updated_at']
        ];
    }

    $response = [
        "status" => true,
        "message" => "Sub categories fetched successfully",
        "data" => $sub_categories
    ];

} else {

    $response = [
        "status" => false,
        "message" => mysqli_error($conn)
    ];
}

echo json_encode($response);
?>