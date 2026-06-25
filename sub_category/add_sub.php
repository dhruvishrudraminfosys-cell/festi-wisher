<?php
ob_start();

require "../config.php";
require "../auth.php";

$message = "";

/* Fetch categories */
$cat_sql = "SELECT id, name FROM categories ORDER BY id DESC";
$cat_result = mysqli_query($conn, $cat_sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $category_id = intval($_POST['category_id']);
    $date = date('Y-m-d');

    $video_frame = "";
    $video_url = "";
    $thumbnail = "";

    if ($category_id == 0) {
        $message = "<div class='error-msg'>Please select category.</div>";
    } else {

        /* =========================
           IMAGE 1 (video_frame)
        ========================= */
        if (!empty($_FILES['video_frame']['name'])) {

            $uploadDir = "../uploads/sub_categories/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $video_frame = time() . "_frame_" . basename($_FILES['video_frame']['name']);
            $target = $uploadDir . $video_frame;

            $ext = strtolower(pathinfo($video_frame, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($ext, $allowed)) {
                $message = "<div class='error-msg'>Invalid image for video_frame</div>";
            } else {
                move_uploaded_file($_FILES['video_frame']['tmp_name'], $target);
            }
        }

        /* =========================
           VIDEO UPLOAD (video_url)
        ========================= */
        if (!empty($_FILES['video_url']['name'])) {

            $uploadDir = "../uploads/sub_categories/videos/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $video_url = time() . "_video_" . basename($_FILES['video_url']['name']);
            $target = $uploadDir . $video_url;

            $ext = strtolower(pathinfo($video_url, PATHINFO_EXTENSION));
            $allowedVideo = ['mp4', 'webm', 'avi', 'mov'];

            if (!in_array($ext, $allowedVideo)) {
                $message = "<div class='error-msg'>Invalid video format</div>";
            } else {
                move_uploaded_file($_FILES['video_url']['tmp_name'], $target);
            }
        }

        /* =========================
           THUMBNAIL IMAGE
        ========================= */
        if (!empty($_FILES['video_thumbnail_image']['name'])) {

            $uploadDir = "../uploads/sub_categories/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $thumbnail = time() . "_thumb_" . basename($_FILES['video_thumbnail_image']['name']);
            $target = $uploadDir . $thumbnail;

            $ext = strtolower(pathinfo($thumbnail, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($ext, $allowed)) {
                $message = "<div class='error-msg'>Invalid thumbnail image</div>";
            } else {
                move_uploaded_file($_FILES['video_thumbnail_image']['tmp_name'], $target);
            }
        }

        /* =========================
           INSERT INTO DB
        ========================= */
        if (empty($message)) {

            $sql = "INSERT INTO sub_category 
                    (category_id, video_frame, video_url, video_thumbnail_image, video_count, created_at)
                    VALUES 
                    ('$category_id', '$video_frame', '$video_url', '$thumbnail', 0, '$date')";

            if (mysqli_query($conn, $sql)) {

                /* ✅ FIXED REDIRECT PATH */
               header("Location: subcategories.php?msg=added");
                exit();

            } else {
                $message = "<div class='error-msg'>" . mysqli_error($conn) . "</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Sub Category</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/add_category.css">
</head>

<body>

<?php include "../sidebar.php"; ?>

<div class="content">

    <div class="topbar">
        Welcome,
        <span class="username"><?php echo htmlspecialchars($_SESSION['user']); ?></span>
    </div>

    <div class="card">

        <div class="card-header">
            <h2><i class="fa-solid fa-layer-group"></i> Add Sub Category</h2>
            <p>Upload image, video and thumbnail</p>
        </div>

        <form method="POST" enctype="multipart/form-data">

            <!-- CATEGORY -->
            <div class="form-group">
                <label>Select Category</label>
                <select name="category_id" class="form-control" required>
                    <option value="0">-- Select Category --</option>
                    <?php while ($row = mysqli_fetch_assoc($cat_result)) { ?>
                        <option value="<?php echo $row['id']; ?>">
                            <?php echo $row['name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- IMAGE 1 -->
            <div class="form-group">
                <label>Video Frame (Image)</label>
                <input type="file" name="video_frame" class="form-control" accept="image/*">
            </div>

            <!-- VIDEO -->
            <div class="form-group">
                <label>Video Upload</label>
                <input type="file" name="video_url" class="form-control" accept="video/*">
            </div>

            <!-- THUMBNAIL -->
            <div class="form-group">
                <label>Thumbnail Image</label>
                <input type="file" name="video_thumbnail_image" class="form-control" accept="image/*">
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-save">
                    <i class="fa-solid fa-floppy-disk"></i> Save Sub Category
                </button>

                <a href="sub_categories.php" class="btn btn-reset">
                    <i class="fa fa-times"></i> Back
                </a>
            </div>

        </form>

        <?php if (!empty($message)) echo $message; ?>

    </div>

</div>

</body>
</html>

<?php ob_end_flush(); ?>