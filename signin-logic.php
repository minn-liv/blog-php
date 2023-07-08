<?php
require 'config/database.php';

if (isset($_POST['submit'])) {
    // Get form data
    $username_email = filter_var($_POST['username_email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!$username_email) {
        $_SESSION['signin'] = "Username or Email required";
    } elseif (!$password) {
        $_SESSION['signin'] = "Password Required";
    } else {
        // Fetch user from database
        $fetch_user_query = "SELECT * FROM users WHERE username='$username_email' OR email='$username_email'";
        $fetch_user_result = mysqli_query($connection, $fetch_user_query);

        if (mysqli_num_rows($fetch_user_result) == 1) {
            // Convert the record into assoc array
            $user_record = mysqli_fetch_assoc($fetch_user_result);
            $db_password = $user_record['password'];
            // compare form password with database password
            if (password_verify($password, $db_password)) {
                // Set session for access control
                $_SESSION['user-id'] = $user_record['id'];
                // Set session if user is an admin
                if ($user_record['is_admin'] == 1) {
                    $_SESSION['user_is_admin'] = true;
                }

                header('location: ' . ROOT_URL . 'admin/');
            } else {
                $_SESSION['signin'] = "Please check you input";
            }
        } else {
            $_SESSION['signin'] = "User not found";
        }
    }

    // If any problem, redirect back to signin page with login data
    if (isset($_SESSION['signin'])) {
        $_SESSION['singin-data'] = $_POST;
        header('location: ' . ROOT_URL . 'signin.php');
        die();
    }
} else {
    header('location: ' . ROOT_URL . 'signin.php');
    die();
}
