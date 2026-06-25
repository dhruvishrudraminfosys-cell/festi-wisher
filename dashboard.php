<?php
require "config.php";
require "auth.php";
$currentPage = basename($_SERVER['PHP_SELF']);

// Total Categories
$catQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM categories");
$catData = mysqli_fetch_assoc($catQuery);
$totalCategories = $catData['total'];

// Total Sub Categories
$subQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM sub_category");
$subData = mysqli_fetch_assoc($subQuery);
$totalSubCategories = $subData['total'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<style>
    .dashboard-cards {
        display: flex;
        gap: 25px;
        margin-top: 25px;
        flex-wrap: wrap;
    }

    .stat-card {
        flex: 1;
        min-width: 280px;
        background: #fff;
        border-radius: 18px;
        padding: 25px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        transition: 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-card .icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: #fff;
    }

    .category-card .icon {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
    }

    .subcategory-card .icon {
        background: linear-gradient(135deg, #06b6d4, #0ea5e9);
    }

    .stat-card h2 {
        margin: 0;
        font-size: 34px;
        font-weight: 600;
        color: #111827;
    }

    .stat-card p {
        margin-top: 5px;
        color: #6b7280;
        font-size: 15px;
    }

    @media(max-width:768px) {
        .dashboard-cards {
            flex-direction: column;
        }
    }
</style>

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
            <div class="dashboard-cards">

                <div class="stat-card category-card">
                    <div class="icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="info">
                        <h2><?php echo $totalCategories; ?></h2>
                        <p>Total Categories</p>
                    </div>
                </div>

                <div class="stat-card subcategory-card">
                    <div class="icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <div class="info">
                        <h2><?php echo $totalSubCategories; ?></h2>
                        <p>Total Sub Categories</p>
                    </div>
                </div>

            </div>
        </div>

    </div>

</body>

</html>