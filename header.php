<?php
  //Include menu options applicable to all pages of the web site
  include("PhpSampleTemplate.php");
  require_once 'Settings.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>
  </title>
  <link href="accets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="accets/css/screen.css" />
  <link rel="stylesheet" type="text/css" href="StyleSheet.css" />
</head>
<body>
<div class="col-lg-12">
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" href="DisplayUsers.php"><b>User Management</b></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="DisplayExtensions.php"><b>Extension Management</b></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="DisplayApplications.php"><b>View Applications</b></a>
  </li>
</ul>