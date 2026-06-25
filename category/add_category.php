<?php
ob_start();

require "../config.php";
require "../auth.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = mysqli_real_escape_string($conn, trim($_POST['category_name']));
    $date = !empty($_POST['date']) ? $_POST['date'] : date('Y-m-d');

    $image = "";

    // Validate category name
    if (empty($name)) {
        $message = "<div class='error-msg'>Category name is required.</div>";
    } else {

        // Image Upload
        if (isset($_FILES['category_image']) && $_FILES['category_image']['error'] == 0) {

            $uploadDir = "../uploads/categories/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $image = time() . "_" . basename($_FILES['category_image']['name']);
            $targetFile = $uploadDir . $image;

            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));

            if (in_array($ext, $allowed)) {
                if (!move_uploaded_file($_FILES['category_image']['tmp_name'], $targetFile)) {
                    $message = "<div class='error-msg'>Failed to upload image.</div>";
                }
            } else {
                $message = "<div class='error-msg'>Only JPG, JPEG, PNG, GIF and WEBP images are allowed.</div>";
            }
        }

        // Insert only if no upload error message
        if (empty($message)) {

            $sql = "INSERT INTO categories (name, image, date)
                    VALUES ('$name', '$image', '$date')";

            if (mysqli_query($conn, $sql)) {

                // IMPORTANT: same folder redirect (NO ../)
                header("Location: categories.php?msg=added");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
                <?php echo htmlspecialchars($_SESSION['user']); ?>
            </span>
        </div>
    </div>

    <div class="card">

        <div class="card-header">
            <h2><i class="fa-solid fa-folder-plus"></i> Add Category</h2>
            <p>Create a new category.</p>
        </div>

        <form method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="category_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Category Image</label>
                <input type="file" name="category_image" class="form-control" accept="image/*">
            </div>

            <div class="form-group">
                <label>Date</label>
                <input type="date" name="date" class="form-control">
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-save">
                    <i class="fa-solid fa-floppy-disk"></i> Save Category
                </button>

                <a href="categories.php" class="btn btn-reset">
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