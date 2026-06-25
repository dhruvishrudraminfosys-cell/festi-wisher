<?php
ob_start();

require "../config.php";
require "../auth.php";

$baseUrl = "/festi-wisher/";

/* =========================
   VALIDATE ID
========================= */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: " . $baseUrl . "sub_category/subcategories.php");
    exit;
}

$id = intval($_GET['id']);

/* =========================
   FETCH SUB CATEGORY
========================= */
$sql = "SELECT * FROM sub_category WHERE id = $id LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    header("Location: " . $baseUrl . "sub_category/subcategories.php");
    exit;
}

$data = mysqli_fetch_assoc($result);

/* =========================
   FETCH CATEGORIES
========================= */
$cat_sql = "SELECT id, name FROM categories ORDER BY id DESC";
$cat_result = mysqli_query($conn, $cat_sql);

$message = "";

/* =========================
   UPDATE LOGIC
========================= */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $category_id = intval($_POST['category_id']);

    if ($category_id == 0) {
        $message = "<div class='error-msg'>Please select category.</div>";
    } else {

        $video_frame = $data['video_frame'];
        $video_url = $data['video_url'];
        $thumbnail = $data['video_thumbnail_image'];

        /* =========================
           IMAGE FRAME
        ========================= */
        if (!empty($_FILES['video_frame']['name'])) {

            $uploadDir = "../uploads/sub_categories/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $video_frame = time() . "_frame_" . basename($_FILES['video_frame']['name']);
            move_uploaded_file($_FILES['video_frame']['tmp_name'], $uploadDir . $video_frame);

            if (!empty($data['video_frame']) && file_exists($uploadDir . $data['video_frame'])) {
                unlink($uploadDir . $data['video_frame']);
            }
        }

        /* =========================
           VIDEO
        ========================= */
        if (!empty($_FILES['video_url']['name'])) {

            $uploadDir = "../uploads/sub_categories/videos/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $video_url = time() . "_video_" . basename($_FILES['video_url']['name']);
            move_uploaded_file($_FILES['video_url']['tmp_name'], $uploadDir . $video_url);

            if (!empty($data['video_url']) && file_exists($uploadDir . $data['video_url'])) {
                unlink($uploadDir . $data['video_url']);
            }
        }

        /* =========================
           THUMBNAIL
        ========================= */
        if (!empty($_FILES['video_thumbnail_image']['name'])) {

            $uploadDir = "../uploads/sub_categories/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $thumbnail = time() . "_thumb_" . basename($_FILES['video_thumbnail_image']['name']);
            move_uploaded_file($_FILES['video_thumbnail_image']['tmp_name'], $uploadDir . $thumbnail);

            if (!empty($data['video_thumbnail_image']) && file_exists($uploadDir . $data['video_thumbnail_image'])) {
                unlink($uploadDir . $data['video_thumbnail_image']);
            }
        }

        /* =========================
           UPDATE QUERY
        ========================= */
        if (empty($message)) {

            $update = "UPDATE sub_category SET 
                category_id = '$category_id',
                video_frame = '$video_frame',
                video_url = '$video_url',
                video_thumbnail_image = '$thumbnail'
                WHERE id = $id";

            if (mysqli_query($conn, $update)) {

                header("Location: " . $baseUrl . "sub_category/subcategories.php?msg=updated");
                exit;

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
    <title>Edit Sub Category</title>

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
            <h2><i class="fa-solid fa-pen-to-square"></i> Edit Sub Category</h2>
            <p>Update video, image and thumbnail</p>
        </div>

        <?php if (!empty($message)) echo $message; ?>

        <form method="POST" enctype="multipart/form-data">

            <!-- CATEGORY -->
            <div class="form-group">
                <label>Select Category</label>
                <select name="category_id" class="form-control" required>
                    <option value="0">-- Select Category --</option>
                    <?php while ($row = mysqli_fetch_assoc($cat_result)) { ?>
                        <option value="<?php echo $row['id']; ?>"
                            <?php if ($row['id'] == $data['category_id']) echo "selected"; ?>>
                            <?php echo $row['name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- CURRENT IMAGE -->
            <div class="form-group">
                <label>Current Video Frame</label><br>
                <?php if (!empty($data['video_frame'])) { ?>
                    <img src="../uploads/sub_categories/<?php echo $data['video_frame']; ?>"
                        width="100" style="border-radius:10px;">
                <?php } else { echo "No Image"; } ?>
            </div>

            <!-- FRAME -->
            <div class="form-group">
                <label>Replace Video Frame</label>
                <input type="file" name="video_frame" class="form-control" accept="image/*">
            </div>

            <!-- CURRENT VIDEO -->
            <div class="form-group">
                <label>Current Video</label><br>
                <?php if (!empty($data['video_url'])) { ?>
                    <video width="150" controls>
                        <source src="../uploads/sub_categories/videos/<?php echo $data['video_url']; ?>" type="video/mp4">
                    </video>
                <?php } else { echo "No Video"; } ?>
            </div>

            <!-- VIDEO -->
            <div class="form-group">
                <label>Replace Video</label>
                <input type="file" name="video_url" class="form-control" accept="video/*">
            </div>

            <!-- CURRENT THUMBNAIL -->
            <div class="form-group">
                <label>Current Thumbnail</label><br>
                <?php if (!empty($data['video_thumbnail_image'])) { ?>
                    <img src="../uploads/sub_categories/<?php echo $data['video_thumbnail_image']; ?>"
                        width="100" style="border-radius:10px;">
                <?php } else { echo "No Thumbnail"; } ?>
            </div>

            <!-- THUMBNAIL -->
            <div class="form-group">
                <label>Replace Thumbnail</label>
                <input type="file" name="video_thumbnail_image" class="form-control" accept="image/*">
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-save">
                    <i class="fa fa-save"></i> Update
                </button>

                <a href="subcategories.php" class="btn btn-reset">
                    Cancel
                </a>
            </div>

        </form>

    </div>
</div>

</body>
</html>

<?php ob_end_flush(); ?>