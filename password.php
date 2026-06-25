<?php

$password = "123456789";

// Hash password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Store $hashedPassword in database
echo $hashedPassword;

?>