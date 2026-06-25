<?php
require "../config.php";
require "../auth.php";

$currentPage = basename($_SERVER['PHP_SELF']);

$msg = "";
$type = "";

if (isset($_GET['msg'])) {
    if ($_GET['msg'] == "added") {
        $msg = "Sub Category added successfully!";
        $type = "success";
    } elseif ($_GET['msg'] == "updated") {
        $msg = "Sub Category updated successfully!";
        $type = "success";
    } elseif ($_GET['msg'] == "deleted") {
        $msg = "Sub Category deleted successfully!";
        $type = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sub Category Management</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/css/bootstrap.min.css">

    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/category.css">
</head>

<body>

    <?php include "../sidebar.php"; ?>

    <div class="content">

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="welcome">
                Welcome,
                <span class="username">
                    <?php echo htmlspecialchars($_SESSION['user']); ?>
                </span>
            </div>
        </div>

        <!-- ALERT -->
        <?php if (!empty($msg)) { ?>
            <div class="alert alert-<?php echo $type; ?> alert-dismissible fade show" role="alert"
                style="margin-bottom:15px; padding:10px; border-radius:8px;
                background-color: <?php echo $type == 'success' ? '#d1e7dd' : ($type == 'info' ? '#cff4fc' : '#f8d7da'); ?> !important;
                color: <?php echo $type == 'success' ? '#0f5132' : ($type == 'info' ? '#055160' : '#842029'); ?> !important;
                border-color: <?php echo $type == 'success' ? '#badbcc' : ($type == 'info' ? '#b6effb' : '#f5c2c7'); ?> !important;">
                <?php echo $msg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>


        <div class="card">

            <div class="card-header">
                <div class="card-title">
                    <h2>Sub Category Management</h2>
                    <p>Manage video sub categories</p>
                </div>

                <a href="add_sub.php" class="btn-add">
                    <i class="fa fa-plus"></i> Add Sub Category
                </a>
            </div>

            <div class="table-responsive">

                <table id="subCategoryTable" class="display">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category</th>
                            <th>Thumbnail</th>
                            <th>Video URL</th>
                            <th>Video Frame</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $sql = "SELECT sc.*, c.name AS category_name
                        FROM sub_category sc
                        LEFT JOIN categories c ON c.id = sc.category_id
                        ORDER BY sc.id DESC";

                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>

                                <tr>
                                    <td><?php echo $row['id']; ?></td>

                                    <td><?php echo htmlspecialchars($row['category_name']); ?></td>

                                    <td>
                                        <?php if (!empty($row['video_thumbnail_image'])) { ?>
                                            <img src="../uploads/sub_categories/<?php echo $row['video_thumbnail_image']; ?>"
                                                width="60" height="60" style="border-radius:8px; object-fit:cover;">
                                        <?php } else { ?>
                                            No Image
                                        <?php } ?>
                                    </td>

                                    <td>
                                        <?php if (!empty($row['video_url'])) { ?>
                                            <a href="../uploads/sub_categories/videos/<?php echo $row['video_url']; ?>" target="_blank">
                                                Open Video
                                            </a>
                                        <?php } else { ?>
                                            No Video
                                        <?php } ?>
                                    </td>

                                   

                                     <td>
                                        <?php if (!empty($row['video_frame'])) { ?>
                                            <img src="../uploads/sub_categories/<?php echo $row['video_frame']; ?>"
                                                width="60" height="60" style="border-radius:8px; object-fit:cover;">
                                        <?php } else { ?>
                                            No Image
                                        <?php } ?>
                                    </td>

                                    <td>
                                        <?php echo date("d-m-Y", strtotime($row['created_at'])); ?>
                                    </td>

                                    <td>
                                        <a href="edit_sub.php?id=<?php echo $row['id']; ?>" class="btn-edit">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <a href="del_sub.php?id=<?php echo $row['id']; ?>" class="btn-delete"
                                            onclick="return confirm('Delete this sub category?')">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                            <?php }
                        } ?>

                    </tbody>
                </table>

            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#subCategoryTable').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                responsive: true,
                ordering: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search sub categories...",
                }
            });
        });

        // auto hide alert
        setTimeout(function () {
            let alertBox = document.querySelector('.alert');
            if (alertBox) alertBox.remove();
        }, 3000);
    </script>

</body>

</html>