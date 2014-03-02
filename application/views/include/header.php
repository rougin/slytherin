<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta content="IE=edge" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<meta content="" name="description">
	<meta content="" name="author">
	<link href="<?php echo URL::base('public/ico/favicon.ico'); ?>" rel="shortcut icon">
	<title>Slytherin PHP Documentation</title>
	<link href="<?php echo URL::base('public/css/bootstrap.min.css'); ?>" rel="stylesheet">
	<link href="<?php echo URL::base('public/css/dashboard.css'); ?>" rel="stylesheet">
</head>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="<?php echo URL::base(''); ?>">Slytherin PHP</a>
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-3 col-md-2 sidebar">
				<ul class="nav nav-sidebar">
					<li <?php echo $urls['about']; ?>><a href="<?php echo URL::base('documentation/about'); ?>">What is Slytherin PHP?</a></li>
					<li <?php echo $urls['installation']; ?>><a href="<?php echo URL::base('documentation/installation'); ?>">Installation</a></li>
					<li <?php echo $urls['mvc']; ?>><a href="<?php echo URL::base('documentation/mvc'); ?>">Model-View-Controller</a></li>
					<!-- <li <?php echo $urls['quickstart']; ?>><a href="<?php echo URL::base('documentation/quickstart'); ?>">Quickstart</a></li> -->
				</ul>
			</div>
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">