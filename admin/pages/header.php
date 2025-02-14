<?php
require_once "../config.php";
session_start();
// echo BASE_PATH . '/login.php';
// if (!isset($_SESSION['admin_id'])) {
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . '/login.php');
}
?>

<?php
// echo BASE_PATH;
spl_autoload_register(function ($class) {
    // Adjust the path to your classes directory
    $file = BASE_PATH . '/classes/' . $class . '.php';

    // Check if the file exists before requiring it
    if (file_exists($file)) {
        require_once $file;
    } else {
        // Optional: Handle the error if the file doesn't exist
        throw new Exception("Class file for {$class} not found.");
    }
});

// require_once BASE_PATH . '/classes/Profile.php';
$profile = new Profile();
$userProfileData = $profile->getProfileDetails($_SESSION['user_id']);

//update profile

$updateProfile = new UpdateProfile();
$updateProfile->updateAdminProfile($_POST, $_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Researcher - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="includes/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="includes/css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css">


    <!-- Add this CSS for size and hover effect -->
    <style>
        .profile-picture {
            width: 350px;
            /* Set initial size */
            height: 350px;
            /* Set initial size */
            object-fit: cover;
            /* Ensure the image covers the container */
            border-radius: 50%;
            /* Keep the circular shape */
            transition: transform 0.3s ease-in-out;
            /* Smooth transition for hover */
        }

        .profile-picture:hover {
            transform: scale(1.2);
            /* Zoom the image on hover */
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
            /* Add shadow effect to make it look like a mirror zoom */
        }
    </style>

</head>

<body id="page-top">

    <div id="wrapper">