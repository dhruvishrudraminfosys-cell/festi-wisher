<?php
require "config.php";

$errorEmail = "";
$errorPassword = "";
$errorMain = "";

$email = "";

// LOGIN HANDLER
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // EMAIL VALIDATION
    if (empty($email)) {
        $errorEmail = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorEmail = "Invalid email format";
    }

    // PASSWORD VALIDATION
    if (empty($password)) {
        $errorPassword = "Password is required";
    }

    // PROCESS LOGIN
    if ($errorEmail == "" && $errorPassword == "") {

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user'] = $user['email'];

            // 🔥 IMPORTANT: Redirect to avoid resubmission popup
            header("Location: dashboard.php");
            exit;

        } else {
            $errorMain = "Invalid email or password";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign In</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">

    <style>
        .field-error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 5px;
        }

        .error-box {
            background: #fee2e2;
            color: #b91c1c;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            font-family: 'Poppins', sans-serif;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background: #000;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn:hover {
            background: #222;
        }
    </style>
</head>

<body>

<div class="login-card">

    <div class="title">Sign In</div>
    <div class="subtitle">Access your admin dashboard</div>

    <!-- MAIN ERROR -->
    <?php if ($errorMain != "") { ?>
        <div class="error-box">
            <?php echo $errorMain; ?>
        </div>
    <?php } ?>

    <form method="POST">

        <!-- EMAIL -->
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="Enter your email"
                   value="<?php echo htmlspecialchars($email); ?>">

            <?php if ($errorEmail != "") { ?>
                <div class="field-error"><?php echo $errorEmail; ?></div>
            <?php } ?>
        </div>

        <!-- PASSWORD -->
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password">

            <?php if ($errorPassword != "") { ?>
                <div class="field-error"><?php echo $errorPassword; ?></div>
            <?php } ?>
        </div>

        <button class="btn" type="submit" name="login">Login</button>

    </form>

</div>

</body>
</html>