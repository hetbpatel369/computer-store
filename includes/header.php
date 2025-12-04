<?php
// Start session if needed
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml"
        href="<?php echo isset($pathPrefix) ? $pathPrefix : ''; ?>img/Media/favicon.svg">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Computer Store'; ?></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo isset($pathPrefix) ? $pathPrefix : ''; ?>css/bootstrap.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo isset($pathPrefix) ? $pathPrefix : ''; ?>css/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>