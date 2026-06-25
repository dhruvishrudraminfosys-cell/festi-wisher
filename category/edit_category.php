<?php
ob_start();
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
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = trim($_POST['name']);
    $name = mysqli_real_escape_string($conn, $name);

    if (empty($name)) {
        $error = "Category name is required.";
    } else {

        $imageName = $category['image'];

        // Upload image
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0) {

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $fileType = mime_content_type($_FILES['image']['tmp_name']);

            if (!in_array($fileType, $allowedTypes)) {
                $error = "Only JPG, PNG, GIF, WEBP allowed.";
            } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                $error = "Max size 2MB allowed.";
            } else {

                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $newImageName = uniqid('cat_') . '.' . $ext;

                // ABSOLUTE PATH (important fix)
                $uploadDir = "../uploads/categories/";

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $uploadPath = $uploadDir . $newImageName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {

                    // delete old image
                    if (!empty($category['image']) && file_exists($uploadDir . $category['image'])) {
                        unlink($uploadDir . $category['image']);
                    }

                    $imageName = $newImageName;

                } else {
                    $error = "Image upload failed.";
                }
            }
        }

        // UPDATE
        if (empty($error)) {

            $updateSql = "UPDATE categories SET name='$name', image='$imageName' WHERE id=$id";

            if (mysqli_query($conn, $updateSql)) {
                header("Location: " . $baseUrl . "category/categories.php?msg=updated");
                exit;
            } else {
                $error = "DB Error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Category</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/css/bootstrap.min.css">

    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/add_category.css">
</head>

<body>

<?php include "../sidebar.php"; ?>

<div class="content">

    <div class="topbar">
        <div class="welcome">
            Welcome,
            <span class="username">
                <?= htmlspecialchars($_SESSION['user']) ?>
            </span>
        </div>
    </div>

    <div class="card">

        <div class="card-header">
            <h2>Edit Category</h2>
            <p>Update category details below.</p>
        </div>

        <?php if (!empty($error)) { ?>
            <div class="error-msg">
                <i class="fa fa-circle-exclamation"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php } ?>

        <form method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Category Name *</label>
                <input type="text" name="name" class="form-control"
                    value="<?= htmlspecialchars($category['name']) ?>" required>
            </div>

            <?php if (!empty($category['image'])) { ?>
                <div class="form-group">
                    <label>Current Image</label><br>
                    <img src="../uploads/categories/<?= htmlspecialchars($category['image']) ?>"
                        width="100" height="100" style="border-radius:10px;">
                </div>
            <?php } ?>

            <div class="form-group">
                <label>Replace Image (optional)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-save">
                    <i class="fa fa-save"></i> Update
                </button>

                <a href="<?= $baseUrl ?>category/categories.php" class="btn btn-reset">
                    Cancel
                </a>
            </div>

        </form>

    </div>
</div>

</body>
</html>