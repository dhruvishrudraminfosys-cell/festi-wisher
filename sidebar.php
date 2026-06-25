<!-- sidebar.php -->
<style>
    .sidebar {
        width: 250px;
        height: 100vh;
        background: #1f2937;
        position: fixed;
        top: 0;
        left: 0;
        padding: 20px;
        color: #fff;
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 22px;
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
    }

    .sidebar ul li {
        margin: 15px 0;
    }

    .sidebar ul li a {
        color: #cbd5e1;
        text-decoration: none;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .sidebar ul li a:hover {
        background: #2563eb;
        color: #fff;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .sidebar ul li a.active {
        background: #2563eb;
        color: #fff;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
</style>

<?php
$current = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
    <h2>Admin Panel</h2>

    <ul>
        <li>
            <a href="<?= BASE_URL ?>dashboard.php" class="<?= ($current == 'dashboard.php') ? 'active' : '' ?>">
                <i class="fa fa-home"></i> Dashboard
            </a>
        </li>

        <li>
            <a href="<?= BASE_URL ?>category/categories.php" class="<?= ($current == 'categories.php' || $current == 'add_category.php' ||  $current == 'edit_category.php') ? 'active' : '' ?>">
                <i class="fa fa-list"></i> Categories
            </a>
        </li>

        <li>
            <a href="<?= BASE_URL ?>subcategories.php" class="<?= ($current == 'subcategories.php') ? 'active' : '' ?>">
                <i class="fa fa-layer-group"></i> Sub Categories
            </a>
        </li>

        <li>
            <a href="<?= BASE_URL ?>users.php" class="<?= ($current == 'users.php') ? 'active' : '' ?>">
                <i class="fa fa-users"></i> Users
            </a>
        </li>

        <li>
            <a href="<?= BASE_URL ?>logout.php">
                <i class="fa fa-sign-out-alt"></i> Logout
            </a>
        </li>
    </ul>
</div>