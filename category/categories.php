<?php
require "../config.php";
require "../auth.php";
$currentPage = basename($_SERVER['PHP_SELF']);

$msg = "";
$type = "";

if (isset($_GET['msg'])) {
    if ($_GET['msg'] == "added") {
        $msg = "Category added successfully!";
        $type = "success";
    } elseif ($_GET['msg'] == "updated") {
        $msg = "Category updated successfully!";
        $type = "success";
    } elseif ($_GET['msg'] == "deleted") {
        $msg = "Category deleted successfully!";
        $type = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management</title>

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

        <!-- CARD -->
        <div class="card">

            <div class="card-header">
                <div class="card-title">
                    <h2>Category Management</h2>
                    <p>Manage categories and monitor status.</p>
                </div>
                <a href="add_category.php" class="btn-add">
                    <i class="fa fa-plus"></i> Add Category
                </a>
            </div>

            <div class="table-responsive">
                <table id="categoryTable" class="display">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $sql = "SELECT * FROM categories ORDER BY id DESC";
                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>

                                    <td>
                                        <?php if (!empty($row['image'])) { ?>
                                            <img src="../uploads/categories/<?php echo $row['image']; ?>" width="60" height="60"
                                                style="border-radius:8px; object-fit:cover;">
                                        <?php } else { ?>
                                            No Image
                                        <?php } ?>
                                    </td>

                                    <td><?php echo htmlspecialchars($row['name']); ?></td>

                                    <td><?php echo date("d-m-Y", strtotime($row['date'])); ?></td>

                                    <td>
                                        <a href="edit_category.php?id=<?php echo $row['id']; ?>" class="btn-edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="delete_category.php?id=<?php echo $row['id']; ?>" class="btn-delete"
                                            onclick="return confirm('Are you sure delete category?')">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
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
            $('#categoryTable').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                responsive: true,
                ordering: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search categories...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    emptyTable: "No Categories Found"
                }
            });
        });

        // AUTO HIDE ALERT after 3 seconds
        setTimeout(function () {
            let alertBox = document.querySelector('.alert');
            if (alertBox) {
                alertBox.classList.remove('show');
                setTimeout(() => alertBox.remove(), 300);
            }
        }, 3000);
    </script>

</body>

</html>