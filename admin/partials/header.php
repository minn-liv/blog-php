<?php
require '../partials/header.php';

// Check login status
if (!isset($_SESSION['user-id'])) {
    header('location: ' . ROOT_URL . 'signin.php');
    die();
}
