<?php
require "config.php";
require "auth.php";
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>

<?php include "sidebar.php"; ?>

<div class="content">

    <div class="topbar">
        <div class="welcome">
            Welcome, <span class="username">
                <?php echo $_SESSION['user']; ?>
            </span>
        </div>
    </div>

    <div class="card">
        <h2>Dashboard Overview</h2>
        <p>This is your professional admin panel. You can manage categories and subcategories.</p>
    </div>

</div>

</body>
</html>