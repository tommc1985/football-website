<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <link href="<?php echo site_url("bootstrap/docs/assets/css/bootstrap.css"); ?>" rel="stylesheet">
    <link href="<?php echo site_url("bootstrap/docs/assets/css/bootstrap-responsive.css"); ?>" rel="stylesheet">
    <link href="<?php echo site_url("assets/css/tables.css"); ?>" rel="stylesheet">
    <link href="<?php echo site_url("assets/admin/css/style.css"); ?>" rel="stylesheet">
</head>
<body>

<?php
$this->load->view('admin/nav_bar'); ?>
<div class="container main-container">

    <?php
    if (isset($message)) {
        echo $message;
    }