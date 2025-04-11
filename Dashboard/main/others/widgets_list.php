<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="../../../images/favicon.ico">

	<title>Lists</title>

	<!-- Vendors Style-->
	<link rel="stylesheet" href="../src/css/vendors_css.css">
	<!-- mine Style-->
	<link rel="stylesheet" href="../src/fontawesome/css/all.min.css">
	<link rel="stylesheet" href="../src/fontawesome/font-awesome.css">

	<!-- Style-->
	<link rel="stylesheet" href="../src/css/style.css">
	<link rel="stylesheet" href="../src/css/skin_color.css">

</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed sidebar-collapse">

	<div class="wrapper">
		<!-- <div id="loader"></div> -->

		<!-- header -->
		<?php include 'bars/header.php'; ?>

		<!--side bar  -->
		<?php include 'bars/side_bar.php'; ?>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<div class="container-full">
				<!-- Content Header (Page header) -->
				<div class="content-header">
					<div class="d-flex align-items-center">
						<div class="me-auto">
							<h4 class="page-title">List widgets</h4>
							<div class="d-inline-block align-items-center">
								<nav>
									<ol class="breadcrumb">
										<li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
										<li class="breadcrumb-item" aria-current="page">Widgets</li>
										<li class="breadcrumb-item active" aria-current="page">List widgets</li>
									</ol>
								</nav>
							</div>
						</div>

					</div>
				</div>

				<!-- Main content -->
				<section class="content">

					<div class="row">

						<div class="col-12 col-xl-4">
							<div class="box">
								<div class="box-header with-border">
									<h4 class="box-title">Tasks Overview <small class="subtitle">Pending 10 tasks</small></h4>
									<ul class="box-controls pull-right">
										<li class="dropdown">
											<a data-bs-toggle="dropdown" href="#" class="px-10 pt-5"><i class="ti-more-alt"></i></a>
											<div class="dropdown-menu dropdown-menu-end">
												<a class="dropdown-item" href="#"><i class="ti-import"></i> Import</a>
												<a class="dropdown-item" href="#"><i class="ti-export"></i> Export</a>
												<a class="dropdown-item" href="#"><i class="ti-printer"></i> Print</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item" href="#"><i class="ti-settings"></i> Settings</a>
											</div>
										</li>
									</ul>
								</div>
								<div class="box-body">
									<div class="d-flex align-items-center mb-30">
										<div class="me-15 bg-primary-light h-50 w-50 l-h-60 rounded text-center">
											<span class="icon-Library fs-24"><span class="path1"></span><span class="path2"></span></span>
										</div>
										<div class="d-flex flex-column">
											<a href="#" class="text-dark hover-primary mb-1 fs-16">Project Briefing</a>
											<span class="text-fade fs-12">Project Manager</span>
										</div>
									</div>
									<div class="d-flex align-items-center mb-30">
										<div class="me-15 bg-danger-light h-50 w-50 l-h-60 rounded text-center">
											<span class="icon-Write fs-24"><span class="path1"></span><span class="path2"></span></span>
										</div>
										<div class="d-flex flex-column">
											<a href="#" class="text-dark hover-danger mb-1 fs-16">Concept Design</a>
											<span class="text-fade fs-12">Art Director</span>
										</div>
									</div>
									<div class="d-flex align-items-center mb-30">
										<div class="me-15 bg-success-light h-50 w-50 l-h-60 rounded text-center">
											<span class="icon-Group-chat fs-24"><span class="path1"></span><span class="path2"></span></span>
										</div>
										<div class="d-flex flex-column">
											<a href="#" class="text-dark hover-success mb-1 fs-16">Functional Logics</a>
											<span class="text-fade fs-12">Sales Manager</span>
										</div>
									</div>
									<div class="d-flex align-items-center mb-30">
										<div class="me-15 bg-info-light h-50 w-50 l-h-60 rounded text-center">
											<span class="icon-Attachment1 fs-24"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></span>
										</div>
										<div class="d-flex flex-column">
											<a href="#" class="text-dark hover-info mb-1 fs-16">Development</a>
											<span class="text-fade fs-12">Creative Head</span>
										</div>
									</div>
									<div class="d-flex align-items-center">
										<div class="me-15 bg-warning-light h-50 w-50 l-h-60 rounded text-center">
											<span class="icon-Shield-user fs-24"></span>
										</div>
										<div class="d-flex flex-column">
											<a href="#" class="text-dark hover-warning mb-1 fs-16">Testing</a>
											<span class="text-fade fs-12">QA Managers</span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-12 col-xl-4">
							<div class="box">
								<div class="box-header with-border">
									<h4 class="box-title">People</h4>
									<ul class="box-controls pull-right">
										<li class="dropdown">
											<a data-bs-toggle="dropdown" href="#" class="px-10 pt-5"><i class="ti-more-alt"></i></a>
											<div class="dropdown-menu dropdown-menu-end">
												<a class="dropdown-item" href="#"><i class="ti-import"></i> Import</a>
												<a class="dropdown-item" href="#"><i class="ti-export"></i> Export</a>
												<a class="dropdown-item" href="#"><i class="ti-printer"></i> Print</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item" href="#"><i class="ti-settings"></i> Settings</a>
											</div>
										</li>
									</ul>
								</div>
								<div class="box-body">
									<div class="d-flex align-items-center mb-30">
										<div class="me-15">
											<img src="../../../images/avatar/avatar-1.png" class="bg-primary-light avatar avatar-lg rounded-circle" alt="" />
										</div>
										<div class="d-flex flex-column flex-grow-1">
											<a href="#" class="text-dark hover-primary mb-1 fs-16">Sophia</a>
											<span class="text-mute fs-12">Project Manager</span>
										</div>
										<div>
											<a href="#" class="btn btn-sm btn-primary-light">Contact</a>
										</div>
									</div>
									<div class="d-flex align-items-center mb-30">
										<div class="me-15">
											<img src="../../../images/avatar/avatar-2.png" class="bg-primary-light avatar avatar-lg rounded-circle" alt="" />
										</div>
										<div class="d-flex flex-column flex-grow-1">
											<a href="#" class="text-dark hover-danger mb-1 fs-16">Mason</a>
											<span class="text-mute fs-12">Art Director</span>
										</div>
										<div>
											<a href="#" class="btn btn-sm btn-primary-light">Contact</a>
										</div>
									</div>
									<div class="d-flex align-items-center mb-30">
										<div class="me-15">
											<img src="../../../images/avatar/avatar-3.png" class="bg-primary-light avatar avatar-lg rounded-circle" alt="" />
										</div>
										<div class="d-flex flex-column flex-grow-1">
											<a href="#" class="text-dark hover-success mb-1 fs-16">Emily</a>
											<span class="text-mute fs-12">Sales Manager</span>
										</div>
										<div>
											<a href="#" class="btn btn-sm btn-primary-light">Contact</a>
										</div>
									</div>
									<div class="d-flex align-items-center mb-30">
										<div class="me-15">
											<img src="../../../images/avatar/avatar-4.png" class="bg-primary-light avatar avatar-lg rounded-circle" alt="" />
										</div>
										<div class="d-flex flex-column flex-grow-1">
											<a href="#" class="text-dark hover-info mb-1 fs-16">Daniel</a>
											<span class="text-mute fs-12">Creative Head</span>
										</div>
										<div>
											<a href="#" class="btn btn-sm btn-primary-light">Contact</a>
										</div>
									</div>
									<div class="d-flex align-items-center">
										<div class="me-15">
											<img src="../../../images/avatar/avatar-5.png" class="bg-primary-light avatar avatar-lg rounded-circle" alt="" />
										</div>
										<div class="d-flex flex-column flex-grow-1">
											<a href="#" class="text-dark hover-warning mb-1 fs-16">Natalie</a>
											<span class="text-mute fs-12">QA Managers</span>
										</div>
										<div>
											<a href="#" class="btn btn-sm btn-primary-light">Contact</a>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-12 col-xl-4">
							<div class="box">
								<div class="box-header with-border">
									<h4 class="box-title">Authors</h4>
									<ul class="box-controls pull-right d-md-flex d-none">
										<li class="dropdown">
											<button class="btn btn-primary dropdown-toggle px-10 " data-bs-toggle="dropdown" href="#">Create</button>
											<div class="dropdown-menu dropdown-menu-end">
												<a class="dropdown-item" href="#"><i class="ti-import"></i> Import</a>
												<a class="dropdown-item" href="#"><i class="ti-export"></i> Export</a>
												<a class="dropdown-item" href="#"><i class="ti-printer"></i> Print</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item" href="#"><i class="ti-settings"></i> Settings</a>
											</div>
										</li>
									</ul>
								</div>
								<div class="box-body">
									<div class="d-flex align-items-center mb-30">
										<div class="me-15">
											<img src="../../../images/avatar/avatar-1.png" class="avatar avatar-lg rounded-circle bg-primary-light" alt="" />
										</div>
										<div class="d-flex flex-column flex-grow-1">
											<a href="#" class="text-dark hover-primary mb-1 fs-16">Sophia</a>
											<span class="text-fade fs-12">Product Manager</span>
										</div>
										<div class="dropdown">
											<a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
											<div class="dropdown-menu dropdown-menu-end">
												<a class="dropdown-item flexbox" href="#">
													<span>Inbox</span>
													<span class="badge badge-pill badge-info">5</span>
												</a>
												<a class="dropdown-item" href="#">Sent</a>
												<a class="dropdown-item" href="#">Spam</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item flexbox" href="#">
													<span>Draft</span>
													<span class="badge badge-pill badge-default">1</span>
												</a>
											</div>
										</div>
									</div>
									<div class="d-flex align-items-center mb-30">
										<div class="me-15">
											<img src="../../../images/avatar/avatar-10.png" class="avatar avatar-lg rounded-circle bg-primary-light" alt="" />
										</div>
										<div class="d-flex flex-column flex-grow-1">
											<a href="#" class="text-dark hover-danger mb-1 fs-16">Mason</a>
											<span class="text-fade fs-12">Product Manager</span>
										</div>
										<div class="dropdown">
											<a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
											<div class="dropdown-menu dropdown-menu-end">
												<a class="dropdown-item flexbox" href="#">
													<span>Inbox</span>
													<span class="badge badge-pill badge-info">5</span>
												</a>
												<a class="dropdown-item" href="#">Sent</a>
												<a class="dropdown-item" href="#">Spam</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item flexbox" href="#">
													<span>Draft</span>
													<span class="badge badge-pill badge-default">1</span>
												</a>
											</div>
										</div>
									</div>
									<div class="d-flex align-items-center mb-30">
										<div class="me-15">
											<img src="../../../images/avatar/avatar-11.png" class="avatar avatar-lg rounded-circle bg-primary-light" alt="" />
										</div>
										<div class="d-flex flex-column flex-grow-1">
											<a href="#" class="text-dark hover-success mb-1 fs-16">Emily</a>
											<span class="text-fade fs-12">Product Manager</span>
										</div>
										<div class="dropdown">
											<a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
											<div class="dropdown-menu dropdown-menu-end">
												<a class="dropdown-item flexbox" href="#">
													<span>Inbox</span>
													<span class="badge badge-pill badge-info">5</span>
												</a>
												<a class="dropdown-item" href="#">Sent</a>
												<a class="dropdown-item" href="#">Spam</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item flexbox" href="#">
													<span>Draft</span>
													<span class="badge badge-pill badge-default">1</span>
												</a>
											</div>
										</div>
									</div>
									<div class="d-flex align-items-center mb-30">
										<div class="me-15">
											<img src="../../../images/avatar/avatar-12.png" class="avatar avatar-lg rounded-circle bg-primary-light" alt="" />
										</div>
										<div class="d-flex flex-column flex-grow-1">
											<a href="#" class="text-dark hover-info mb-1 fs-16">Daniel</a>
											<span class="text-fade fs-12">Product Manager</span>
										</div>
										<div class="dropdown">
											<a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
											<div class="dropdown-menu dropdown-menu-end">
												<a class="dropdown-item flexbox" href="#">
													<span>Inbox</span>
													<span class="badge badge-pill badge-info">5</span>
												</a>
												<a class="dropdown-item" href="#">Sent</a>
												<a class="dropdown-item" href="#">Spam</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item flexbox" href="#">
													<span>Draft</span>
													<span class="badge badge-pill badge-default">1</span>
												</a>
											</div>
										</div>
									</div>
									<div class="d-flex align-items-center">
										<div class="me-15">
											<img src="../../../images/avatar/avatar-15.png" class="avatar avatar-lg rounded-circle bg-primary-light" alt="" />
										</div>
										<div class="d-flex flex-column flex-grow-1">
											<a href="#" class="text-dark hover-warning mb-1 fs-16">Natalie</a>
											<span class="text-fade fs-12">Product Manager</span>
										</div>
										<div class="dropdown">
											<a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
											<div class="dropdown-menu dropdown-menu-end">
												<a class="dropdown-item flexbox" href="#">
													<span>Inbox</span>
													<span class="badge badge-pill badge-info">5</span>
												</a>
												<a class="dropdown-item" href="#">Sent</a>
												<a class="dropdown-item" href="#">Spam</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item flexbox" href="#">
													<span>Draft</span>
													<span class="badge badge-pill badge-default">1</span>
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-12 col-xl-4">
							<div class="box">
								<div class="box-header with-border">
									<h4 class="box-title">Todo</h4>
									<ul class="box-controls pull-right d-md-flex d-none">
										<li class="dropdown">
											<button class="btn btn-primary dropdown-toggle px-10 " data-bs-toggle="dropdown" href="#">Create</button>
											<div class="dropdown-menu dropdown-menu-end">
												<a class="dropdown-item" href="#"><i class="ti-import"></i> Import</a>
												<a class="dropdown-item" href="#"><i class="ti-export"></i> Export</a>
												<a class="dropdown-item" href="#"><i class="ti-printer"></i> Print</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item" href="#"><i class="ti-settings"></i> Settings</a>
											</div>
										</li>
									</ul>
								</div>
								<div class="box-body">
									<div class="d-flex align-items-center mb-25">
										<span class="bullet bullet-bar bg-success align-self-stretch"></span>
										<div class="h-20 mx-20 flex-shrink-0">
											<input type="checkbox" id="md_checkbox_21" class="filled-in chk-col-success">
											<label for="md_checkbox_21" class="h-20 p-10 mb-0"></label>
										</div>

										<div class="d-flex flex-column flex-grow-1">
											<a href="#" class="text-dark hover-success fs-16">
												Create FireStone Logo
											</a>
											<span class="text-fade fs-12">
												Due in 2 Days
											</span>
										</div>
										<div class="dropdown">
											<a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
											<div class="dropdown-menu dropdown-menu-end">
												<h6 class="dropdown-header">Choose Label:</h6>
												<a class="dropdown-item flexbox" href="#">
													<span class="badge badge-primary-light">Customer</span>
												</a>
												<a class="dropdown-item" href="#">
													<span class="badge badge-danger-light">Partner</span>
												</a>
												<a class="dropdown-item" href="#">
													<span class="badge badge-success-light">Suplier</span>
												</a>
												<a class="dropdown-item" href="#">
													<span class="badge badge-info-light">Member</span>
												</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item flexbox" href="#">
													<span>Add New</span>
													<span class="badge badge-pill badge-default">+</span>
												</a>
											</div>
										</div>
									</div>
									<div class="d-flex align-items-center mb-25">
										<span class="bullet bullet-bar bg-primary align-self-stretch"></span>
										<div class="h-20 mx-20 flex-shrink-0">
											<input type="checkbox" id="md_checkbox_22" class="filled-in chk-col-primary">
											<label for="md_checkbox_22" class="h-20 p-10 mb-0"></label>
										</div>

										<div class="d-flex flex-column flex-grow-1">
											<a href="#" class="text-dark hover-primary fs-16">
												Lorem ipsum dolor sit amet
											</a>
											<span class="text-fade fs-12">
												Due in 2 Days
											</span>
										</div>
										<div class="dropdown">
											<a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
											<div class="dropdown-menu dropdown-menu-end">
												<h6 class="dropdown-header">Choose Label:</h6>
												<a class="dropdown-item flexbox" href="#">
													<span class="badge badge-primary-light">Customer</span>
												</a>
												<a class="dropdown-item" href="#">
													<span class="badge badge-danger-light">Partner</span>
												</a>
												<a class="dropdown-item" href="#">
													<span class="badge badge-success-light">Suplier</span>
												</a>
												<a class="dropdown-item" href="#">
													<span class="badge badge-info-light">Member</span>
												</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item flexbox" href="#">
													<span>Add New</span>
													<span class="badge badge-pill badge-default">+</span>
												</a>
											</div>
										</div>
									</div>
									<div class="d-flex align-items-center mb-25">
										<span class="bullet bullet-bar bg-danger align-self-stretch"></span>
										<div class="h-20 mx-20 flex-shrink-0">
											<input type="checkbox" id="md_checkbox_23" class="filled-in chk-col-danger">
											<label for="md_checkbox_23" class="h-20 p-10 mb-0"></label>
										</div>

										<div class="d-flex flex-column flex-grow-1">
											<a href="#" class="text-dark hover-danger fs-16">
												Vivamus consectetur urna
											</a>
											<span class="text-fade fs-12">
												Due in 2 Days
											</span>
										</div>
										<div class="dropdown">
											<a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
											<div class="dropdown-menu dropdown-menu-end">
												<h6 class="dropdown-header">Choose Label:</h6>
												<a class="dropdown-item flexbox" href="#">
													<span class="badge badge-primary-light">Customer</span>
												</a>
												<a class="dropdown-item" href="#">
													<span class="badge badge-danger-light">Partner</span>
												</a>
												<a class="dropdown-item" href="#">
													<span class="badge badge-success-light">Suplier</span>
												</a>
												<a class="dropdown-item" href="#">
													<span class="badge badge-info-light">Member</span>
												</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item flexbox" href="#">
													<span>Add New</span>
													<span class="badge badge-pill badge-default">+</span>
												</a>
											</div>
										</div>
									</div>
									<div class="d-flex align-items-center mb-25">
										<span class="bullet bullet-bar bg-info align-self-stretch"></span>
										<div class="h-20 mx-20 flex-shrink-0">
											<input type="checkbox" id="md_checkbox_24" class="filled-in chk-col-info">
											<label for="md_checkbox_24" class="h-20 p-10 mb-0"></label>
										</div>

										<div class="d-flex flex-column flex-grow-1">
											<a href="#" class="text-dark hover-info fs-16">
												Sed quis augue sed augue
											</a>
											<span class="text-fade fs-12">
												Due in 2 Days
											</span>
										</div>
										<div class="dropdown">
											<a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
											<div class="dropdown-menu dropdown-menu-end">
												<h6 class="dropdown-header">Choose Label:</h6>
												<a class="dropdown-item flexbox" href="#">
													<span class="badge badge-primary-light">Customer</span>
												</a>
												<a class="dropdown-item" href="#">
													<span class="badge badge-danger-light">Partner</span>
												</a>
												<a class="dropdown-item" href="#">
													<span class="badge badge-success-light">Suplier</span>
												</a>
												<a class="dropdown-item" href="#">
													<span class="badge badge-info-light">Member</span>
												</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item flexbox" href="#">
													<span>Add New</span>
													<span class="badge badge-pill badge-default">+</span>
												</a>
											</div>
										</div>
									</div>
									<div class="d-flex align-items-center">
										<span class="bullet bullet-bar bg-warning align-self-stretch"></span>
										<div class="h-20 mx-20 flex-shrink-0">
											<input type="checkbox" id="md_checkbox_25" class="filled-in chk-col-warning">
											<label for="md_checkbox_25" class="h-20 p-10 mb-0"></label>
										</div>

										<div class="d-flex flex-column flex-grow-1">
											<a href="#" class="text-dark hover-warning fs-16">
												Aliquam in magna
											</a>
											<span class="text-fade fs-12">
												Due in 2 Days
											</span>
										</div>
										<div class="dropdown">
											<a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
											<div class="dropdown-menu dropdown-menu-end">
												<h6 class="dropdown-header">Choose Label:</h6>
												<a class="dropdown-item flexbox" href="#">
													<span class="badge badge-primary-light">Customer</span>
												</a>
												<a class="dropdown-item" href="#">
													<span class="badge badge-danger-light">Partner</span>
												</a>
												<a class="dropdown-item" href="#">
													<span class="badge badge-success-light">Suplier</span>
												</a>
												<a class="dropdown-item" href="#">
													<span class="badge badge-info-light">Member</span>
												</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item flexbox" href="#">
													<span>Add New</span>
													<span class="badge badge-pill badge-default">+</span>
												</a>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>

						<div class="col-12 col-xl-4">
							<div class="box">
								<div class="box-header with-border">
									<h4 class="box-title">Trends</h4>
									<ul class="box-controls pull-right">
										<li class="dropdown">
											<a data-bs-toggle="dropdown" href="#" class="px-10 pt-5"><i class="ti-more-alt"></i></a>
											<div class="dropdown-menu dropdown-menu-end">
												<a class="dropdown-item" href="#"><i class="ti-import"></i> Import</a>
												<a class="dropdown-item" href="#"><i class="ti-export"></i> Export</a>
												<a class="dropdown-item" href="#"><i class="ti-printer"></i> Print</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item" href="#"><i class="ti-settings"></i> Settings</a>
											</div>
										</li>
									</ul>
								</div>
								<div class="box-body">
									<div class="d-flex align-items-center mb-30">
										<div class="me-15 bg-lightest h-50 w-50 l-h-50 rounded text-center">
											<img src="../../../images/svg-icon/color-svg/001-glass.svg" class="h-30" alt="">
										</div>
										<div class="d-flex flex-column flex-grow-1 me-2">
											<a href="#" class="text-dark hover-primary mb-1 fs-16">Duis faucibus lorem</a>
											<span class="text-fade fs-12">Pharetra, Nulla , Nec, Aliquet</span>
										</div>
										<span class="badge badge-xl badge-primary-light"><span class="fw-600">+125$</span></span>
									</div>
									<div class="d-flex align-items-center mb-30">
										<div class="me-15 bg-lightest h-50 w-50 l-h-50 rounded text-center">
											<img src="../../../images/svg-icon/color-svg/002-google.svg" class="h-30" alt="">
										</div>
										<div class="d-flex flex-column flex-grow-1 me-2">
											<a href="#" class="text-dark hover-danger mb-1 fs-16">Mauris varius augue</a>
											<span class="text-fade fs-12">Pharetra, Nulla , Nec, Aliquet</span>
										</div>
										<span class="badge badge-xl badge-primary-light"><span class="fw-600">+125$</span></span>
									</div>
									<div class="d-flex align-items-center mb-30">
										<div class="me-15 bg-lightest h-50 w-50 l-h-50 rounded text-center">
											<img src="../../../images/svg-icon/color-svg/003-settings.svg" class="h-30" alt="">
										</div>
										<div class="d-flex flex-column flex-grow-1 me-2">
											<a href="#" class="text-dark hover-success mb-1 fs-16">Aliquam in magna</a>
											<span class="text-fade fs-12">Pharetra, Nulla , Nec, Aliquet</span>
										</div>
										<span class="badge badge-xl badge-primary-light"><span class="fw-600">+125$</span></span>
									</div>
									<div class="d-flex align-items-center mb-30">
										<div class="me-15 bg-lightest h-50 w-50 l-h-50 rounded text-center">
											<img src="../../../images/svg-icon/color-svg/004-dad.svg" class="h-30" alt="">
										</div>
										<div class="d-flex flex-column flex-grow-1 me-2">
											<a href="#" class="text-dark hover-info mb-1 fs-16">Phasellus venenatis nisi</a>
											<span class="text-fade fs-12">Pharetra, Nulla , Nec, Aliquet</span>
										</div>
										<span class="badge badge-xl badge-primary-light"><span class="fw-600">+125$</span></span>
									</div>
									<div class="d-flex align-items-center">
										<div class="me-15 bg-lightest h-50 w-50 l-h-50 rounded text-center">
											<img src="../../../images/svg-icon/color-svg/005-paint-palette.svg" class="h-30" alt="">
										</div>
										<div class="d-flex flex-column flex-grow-1 me-2">
											<a href="#" class="text-dark hover-warning mb-1 fs-16">Vivamus consectetur</a>
											<span class="text-fade fs-12">Pharetra, Nulla , Nec, Aliquet</span>
										</div>
										<span class="badge badge-xl badge-primary-light"><span class="fw-600">+125$</span></span>
									</div>
								</div>
							</div>
						</div>

						<div class="col-12 col-xl-4">
							<div class="box">
								<div class="box-header with-border">
									<h4 class="box-title">Trends</h4>
									<ul class="box-controls pull-right">
										<li class="dropdown">
											<a data-bs-toggle="dropdown" href="#" class="px-10 pt-5"><i class="ti-more-alt"></i></a>
											<div class="dropdown-menu dropdown-menu-end">
												<a class="dropdown-item" href="#"><i class="ti-import"></i> Import</a>
												<a class="dropdown-item" href="#"><i class="ti-export"></i> Export</a>
												<a class="dropdown-item" href="#"><i class="ti-printer"></i> Print</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item" href="#"><i class="ti-settings"></i> Settings</a>
											</div>
										</li>
									</ul>
								</div>
								<div class="box-body">
									<div class="mb-30">
										<div class="d-flex align-items-center">
											<div class="me-15 bg-lightest h-50 w-50 l-h-50 rounded text-center">
												<img src="../../../images/svg-icon/color-svg/001-glass.svg" class="h-30" alt="">
											</div>
											<div class="d-flex flex-column flex-grow-1 me-2">
												<a href="#" class="text-dark hover-primary mb-1 fs-16">Duis faucibus lorem</a>
												<span class="text-fade fs-12">Pharetra, Nulla , Nec, Aliquet</span>
											</div>
										</div>
										<p class="text-muted mb-0 pt-10">
											Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...
										</p>
									</div>
									<div class="mb-30">
										<div class="d-flex align-items-center">
											<div class="me-15 bg-lightest h-50 w-50 l-h-50 rounded text-center">
												<img src="../../../images/svg-icon/color-svg/002-google.svg" class="h-30" alt="">
											</div>
											<div class="d-flex flex-column flex-grow-1 me-2">
												<a href="#" class="text-dark hover-danger mb-1 fs-16">Mauris varius augue</a>
												<span class="text-fade fs-12">Pharetra, Nulla , Nec, Aliquet</span>
											</div>
										</div>
										<p class="text-muted mb-0 pt-10">
											Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...
										</p>
									</div>
									<div>
										<div class="d-flex align-items-center">
											<div class="me-15 bg-lightest h-50 w-50 l-h-50 rounded text-center">
												<img src="../../../images/svg-icon/color-svg/003-settings.svg" class="h-30" alt="">
											</div>
											<div class="d-flex flex-column flex-grow-1 me-2">
												<a href="#" class="text-dark hover-success mb-1 fs-16">Aliquam in magna</a>
												<span class="text-fade fs-12">Pharetra, Nulla , Nec, Aliquet</span>
											</div>
										</div>
										<p class="text-muted mb-0 pt-10">
											Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...
										</p>
									</div>
								</div>
							</div>
						</div>

						<div class="col-12 col-xl-4">
							<div class="box">
								<div class="box-header with-border">
									<h4 class="box-title">Support Tickets</h4>
								</div>
								<div class="box-body p-0">
									<div class="media-list bb-1 bb-dashed border-light">
										<div class="media align-items-center">
											<a class="avatar avatar-lg status-success" href="#">
												<img src="../../../images/avatar/avatar-10.png" class="bg-success-light" alt="...">
											</a>
											<div class="media-body">
												<p class="fs-16">
													<a class="hover-primary" href="#">Theron Trump</a>
												</p>
												<span class="text-fade fs-12">2 day ago</span>
											</div>
											<div class="media-right">
												<span class="badge badge-warning-light">Pending</span>
											</div>
										</div>
										<div class="media pt-0">
											<p class="text-mute">Lorem ipsum dolor sit amet,consectetuer edipiscing elit,sed diam nonummy nibh euismod tinciduntut laoreet doloremagna aliquam erat volutpat.</p>
										</div>
									</div>
									<div class="media-list bb-1 bb-dashed border-light">
										<div class="media align-items-center">
											<a class="avatar avatar-lg status-success" href="#">
												<img src="../../../images/avatar/avatar-3.png" class="bg-success-light" alt="...">
											</a>
											<div class="media-body">
												<p class="fs-16">
													<a class="hover-primary" href="#">Nil Yeager</a>
												</p>
												<span class="text-fade fs-12">5 day ago</span>
											</div>
											<div class="media-right">
												<span class="badge badge-success-light">Open</span>
											</div>
										</div>
										<div class="media pt-0">
											<p class="text-mute">Lorem ipsum dolor sit amet,consectetuer edipiscing elit,sed diam nonummy nibh euismod tinciduntut laoreet doloremagna aliquam erat volutpat.</p>
										</div>
									</div>
									<div class="media-list">
										<div class="media align-items-center">
											<a class="avatar avatar-lg status-success" href="#">
												<img src="../../../images/avatar/avatar-4.png" class="bg-success-light" alt="...">
											</a>
											<div class="media-body">
												<p class="fs-16">
													<a class="hover-primary" href="#">Tyler Mark</a>
												</p>
												<span class="text-fade fs-12">7 day ago</span>
											</div>
											<div class="media-right">
												<span class="badge badge-danger-light">Close</span>
											</div>
										</div>
										<div class="media pt-0">
											<p class="text-mute">Lorem ipsum dolor sit amet,consectetuer edipiscing elit,sed diam nonummy nibh euismod tinciduntut laoreet.</p>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-12 col-xl-4">
							<div class="box">
								<div class="box-header with-border">
									<h4 class="box-title">Branches list</h4>
								</div>
								<div class="box-body p-0">
									<div class="media-list media-list-hover media-list-divided">
										<a class="media media-single" href="#">
											<i class="fs-18 me-0 flag-icon flag-icon-us"></i>
											<span class="title text-mute">USA </span>
											<span class="badge badge-pill badge-secondary-light">125</span>
										</a>

										<a class="media media-single" href="#">
											<i class="fs-18 me-0 flag-icon flag-icon-ba"></i>
											<span class="title text-mute">Bahrain</span>
											<span class="badge badge-pill badge-primary-light">124</span>
										</a>

										<a class="media media-single" href="#">
											<i class="fs-18 me-0 flag-icon flag-icon-ch"></i>
											<span class="title text-mute">China</span>
											<span class="badge badge-pill badge-info-light">425</span>
										</a>

										<a class="media media-single" href="#">
											<i class="fs-18 me-0 flag-icon flag-icon-de"></i>
											<span class="title text-mute">Denmark</span>
											<span class="badge badge-pill badge-success-light">321</span>
										</a>

										<a class="media media-single" href="#">
											<i class="fs-18 me-0 flag-icon flag-icon-fr"></i>
											<span class="title text-mute">France</span>
											<span class="badge badge-pill badge-danger-light">159</span>
										</a>

										<a class="media media-single" href="#">
											<i class="fs-18 me-0 flag-icon flag-icon-ga"></i>
											<span class="title text-mute">Greece</span>
											<span class="badge badge-pill badge-warning-light">452</span>
										</a>

										<a class="media media-single" href="#">
											<i class="fs-18 me-0 flag-icon flag-icon-us"></i>
											<span class="title text-mute">USA</span>
											<span class="badge badge-pill badge-secondary-light">125</span>
										</a>

									</div>
								</div>
							</div>
						</div>


						<div class="col-12 col-xl-4">
							<div class="box">
								<div class="box-header with-border">
									<h4 class="box-title">Download Files</h4>
									<div class="dropdown pull-right">
										<h6 class="dropdown-toggle mb-0" data-bs-toggle="dropdown">Today</h6>
										<div class="dropdown-menu">
											<a class="dropdown-item active" href="#">Today</a>
											<a class="dropdown-item" href="#">Yesterday</a>
											<a class="dropdown-item" href="#">Last week</a>
											<a class="dropdown-item" href="#">Last month</a>
										</div>
									</div>
								</div>
								<div class="box-body pt-0">
									<div class="media-list media-list-divided">
										<div class="media media-single px-0">
											<div class="ms-0 me-15 bg-success-light h-50 w-50 l-h-50 rounded text-center">
												<span class="fs-24 text-success"><i class="fa fa-file-pdf-o"></i></span>
											</div>
											<span class="title fw-500 fs-16 text-mute">Deeveloper Manual</span>
											<a class="fs-18 text-gray hover-info" href="#"><i class="fa fa-download"></i></a>
										</div>

										<div class="media media-single px-0">
											<div class="ms-0 me-15 bg-primary-light h-50 w-50 l-h-50 rounded text-center">
												<span class="fs-24 text-primary"><i class="fa fa-file-text"></i></span>
											</div>
											<span class="title fw-500 fs-16 text-mute">Documentation</span>
											<a class="fs-18 text-gray hover-info" href="#"><i class="fa fa-download"></i></a>
										</div>

										<div class="media media-single px-0">
											<div class="ms-0 me-15 bg-warning-light h-50 w-50 l-h-50 rounded text-center">
												<span class="fs-24 text-warning"><i class="fa fa-file-excel-o"></i></span>
											</div>
											<span class="title fw-500 fs-16 text-mute">Download Excel version</span>
											<a class="fs-18 text-gray hover-info" href="#"><i class="fa fa-download"></i></a>
										</div>

										<div class="media media-single px-0">
											<div class="ms-0 me-15 bg-danger-light h-50 w-50 l-h-50 rounded text-center">
												<span class="fs-24 text-danger"><i class="fa fa-file-zip-o"></i></span>
											</div>
											<span class="title fw-500 fs-16 text-mute">Download Ziped version</span>
											<a class="fs-18 text-gray hover-info" href="#"><i class="fa fa-download"></i></a>
										</div>

										<div class="media media-single px-0">
											<div class="ms-0 me-15 bg-info-light h-50 w-50 l-h-50 rounded text-center">
												<span class="fs-24 text-info"><i class="fa fa-file-word-o"></i></span>
											</div>
											<span class="title fw-500 fs-16 text-mute">Download Word version</span>
											<a class="fs-18 text-gray hover-info" href="#"><i class="fa fa-download"></i></a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- /.row -->

				</section>
				<!-- /.content -->
			</div>
		</div>
		<!-- /.content-wrapper -->

		<!-- footer -->
		<?php include 'bars/footer.php'; ?>

		<!-- Side panel -->
		<!-- Control Sidebar -->
		<aside class="control-sidebar">

			<div class="rpanel-title"><span class="pull-right btn btn-circle btn-danger" data-toggle="control-sidebar"><i class="ion ion-close text-white"></i></span> </div> <!-- Create the tabs -->
			<ul class="nav nav-tabs control-sidebar-tabs">
				<li class="nav-item"><a href="#control-sidebar-home-tab" data-bs-toggle="tab"><i class="mdi mdi-message-text"></i></a></li>
				<li class="nav-item"><a href="#control-sidebar-settings-tab" data-bs-toggle="tab"><i class="mdi mdi-playlist-check"></i></a></li>
			</ul>
			<!-- Tab panes -->
			<div class="tab-content">
				<!-- Home tab content -->
				<div class="tab-pane" id="control-sidebar-home-tab">
					<div class="flexbox">
						<a href="javascript:void(0)" class="text-grey">
							<i class="ti-more"></i>
						</a>
						<p>Users</p>
						<a href="javascript:void(0)" class="text-end text-grey"><i class="ti-plus"></i></a>
					</div>
					<div class="lookup lookup-sm lookup-right d-none d-lg-block">
						<input type="text" name="s" placeholder="Search" class="w-p100">
					</div>
					<div class="media-list media-list-hover mt-20">
						<div class="media py-10 px-0">
							<a class="avatar avatar-lg status-success" href="#">
								<img src="../../../images/avatar/1.jpg" alt="...">
							</a>
							<div class="media-body">
								<p class="fs-16">
									<a class="hover-primary" href="#"><strong>Tyler</strong></a>
								</p>
								<p>Praesent tristique diam...</p>
								<span>Just now</span>
							</div>
						</div>

						<div class="media py-10 px-0">
							<a class="avatar avatar-lg status-danger" href="#">
								<img src="../../../images/avatar/2.jpg" alt="...">
							</a>
							<div class="media-body">
								<p class="fs-16">
									<a class="hover-primary" href="#"><strong>Luke</strong></a>
								</p>
								<p>Cras tempor diam ...</p>
								<span>33 min ago</span>
							</div>
						</div>

						<div class="media py-10 px-0">
							<a class="avatar avatar-lg status-warning" href="#">
								<img src="../../../images/avatar/3.jpg" alt="...">
							</a>
							<div class="media-body">
								<p class="fs-16">
									<a class="hover-primary" href="#"><strong>Evan</strong></a>
								</p>
								<p>In posuere tortor vel...</p>
								<span>42 min ago</span>
							</div>
						</div>

						<div class="media py-10 px-0">
							<a class="avatar avatar-lg status-primary" href="#">
								<img src="../../../images/avatar/4.jpg" alt="...">
							</a>
							<div class="media-body">
								<p class="fs-16">
									<a class="hover-primary" href="#"><strong>Evan</strong></a>
								</p>
								<p>In posuere tortor vel...</p>
								<span>42 min ago</span>
							</div>
						</div>

						<div class="media py-10 px-0">
							<a class="avatar avatar-lg status-success" href="#">
								<img src="../../../images/avatar/1.jpg" alt="...">
							</a>
							<div class="media-body">
								<p class="fs-16">
									<a class="hover-primary" href="#"><strong>Tyler</strong></a>
								</p>
								<p>Praesent tristique diam...</p>
								<span>Just now</span>
							</div>
						</div>

						<div class="media py-10 px-0">
							<a class="avatar avatar-lg status-danger" href="#">
								<img src="../../../images/avatar/2.jpg" alt="...">
							</a>
							<div class="media-body">
								<p class="fs-16">
									<a class="hover-primary" href="#"><strong>Luke</strong></a>
								</p>
								<p>Cras tempor diam ...</p>
								<span>33 min ago</span>
							</div>
						</div>

						<div class="media py-10 px-0">
							<a class="avatar avatar-lg status-warning" href="#">
								<img src="../../../images/avatar/3.jpg" alt="...">
							</a>
							<div class="media-body">
								<p class="fs-16">
									<a class="hover-primary" href="#"><strong>Evan</strong></a>
								</p>
								<p>In posuere tortor vel...</p>
								<span>42 min ago</span>
							</div>
						</div>

						<div class="media py-10 px-0">
							<a class="avatar avatar-lg status-primary" href="#">
								<img src="../../../images/avatar/4.jpg" alt="...">
							</a>
							<div class="media-body">
								<p class="fs-16">
									<a class="hover-primary" href="#"><strong>Evan</strong></a>
								</p>
								<p>In posuere tortor vel...</p>
								<span>42 min ago</span>
							</div>
						</div>

					</div>

				</div>
				<!-- /.tab-pane -->
				<!-- Settings tab content -->
				<div class="tab-pane" id="control-sidebar-settings-tab">
					<div class="flexbox">
						<a href="javascript:void(0)" class="text-grey">
							<i class="ti-more"></i>
						</a>
						<p>Todo List</p>
						<a href="javascript:void(0)" class="text-end text-grey"><i class="ti-plus"></i></a>
					</div>
					<ul class="todo-list mt-20">
						<li class="py-15 px-5 by-1">
							<!-- checkbox -->
							<input type="checkbox" id="basic_checkbox_1" class="filled-in">
							<label for="basic_checkbox_1" class="mb-0 h-15"></label>
							<!-- todo text -->
							<span class="text-line">Nulla vitae purus</span>
							<!-- Emphasis label -->
							<small class="badge bg-danger"><i class="fa fa-clock-o"></i> 2 mins</small>
							<!-- General tools such as edit or delete-->
							<div class="tools">
								<i class="fa fa-edit"></i>
								<i class="fa fa-trash-o"></i>
							</div>
						</li>
						<li class="py-15 px-5">
							<!-- checkbox -->
							<input type="checkbox" id="basic_checkbox_2" class="filled-in">
							<label for="basic_checkbox_2" class="mb-0 h-15"></label>
							<span class="text-line">Phasellus interdum</span>
							<small class="badge bg-info"><i class="fa fa-clock-o"></i> 4 hours</small>
							<div class="tools">
								<i class="fa fa-edit"></i>
								<i class="fa fa-trash-o"></i>
							</div>
						</li>
						<li class="py-15 px-5 by-1">
							<!-- checkbox -->
							<input type="checkbox" id="basic_checkbox_3" class="filled-in">
							<label for="basic_checkbox_3" class="mb-0 h-15"></label>
							<span class="text-line">Quisque sodales</span>
							<small class="badge bg-warning"><i class="fa fa-clock-o"></i> 1 day</small>
							<div class="tools">
								<i class="fa fa-edit"></i>
								<i class="fa fa-trash-o"></i>
							</div>
						</li>
						<li class="py-15 px-5">
							<!-- checkbox -->
							<input type="checkbox" id="basic_checkbox_4" class="filled-in">
							<label for="basic_checkbox_4" class="mb-0 h-15"></label>
							<span class="text-line">Proin nec mi porta</span>
							<small class="badge bg-success"><i class="fa fa-clock-o"></i> 3 days</small>
							<div class="tools">
								<i class="fa fa-edit"></i>
								<i class="fa fa-trash-o"></i>
							</div>
						</li>
						<li class="py-15 px-5 by-1">
							<!-- checkbox -->
							<input type="checkbox" id="basic_checkbox_5" class="filled-in">
							<label for="basic_checkbox_5" class="mb-0 h-15"></label>
							<span class="text-line">Maecenas scelerisque</span>
							<small class="badge bg-primary"><i class="fa fa-clock-o"></i> 1 week</small>
							<div class="tools">
								<i class="fa fa-edit"></i>
								<i class="fa fa-trash-o"></i>
							</div>
						</li>
						<li class="py-15 px-5">
							<!-- checkbox -->
							<input type="checkbox" id="basic_checkbox_6" class="filled-in">
							<label for="basic_checkbox_6" class="mb-0 h-15"></label>
							<span class="text-line">Vivamus nec orci</span>
							<small class="badge bg-info"><i class="fa fa-clock-o"></i> 1 month</small>
							<div class="tools">
								<i class="fa fa-edit"></i>
								<i class="fa fa-trash-o"></i>
							</div>
						</li>
						<li class="py-15 px-5 by-1">
							<!-- checkbox -->
							<input type="checkbox" id="basic_checkbox_7" class="filled-in">
							<label for="basic_checkbox_7" class="mb-0 h-15"></label>
							<!-- todo text -->
							<span class="text-line">Nulla vitae purus</span>
							<!-- Emphasis label -->
							<small class="badge bg-danger"><i class="fa fa-clock-o"></i> 2 mins</small>
							<!-- General tools such as edit or delete-->
							<div class="tools">
								<i class="fa fa-edit"></i>
								<i class="fa fa-trash-o"></i>
							</div>
						</li>
						<li class="py-15 px-5">
							<!-- checkbox -->
							<input type="checkbox" id="basic_checkbox_8" class="filled-in">
							<label for="basic_checkbox_8" class="mb-0 h-15"></label>
							<span class="text-line">Phasellus interdum</span>
							<small class="badge bg-info"><i class="fa fa-clock-o"></i> 4 hours</small>
							<div class="tools">
								<i class="fa fa-edit"></i>
								<i class="fa fa-trash-o"></i>
							</div>
						</li>
						<li class="py-15 px-5 by-1">
							<!-- checkbox -->
							<input type="checkbox" id="basic_checkbox_9" class="filled-in">
							<label for="basic_checkbox_9" class="mb-0 h-15"></label>
							<span class="text-line">Quisque sodales</span>
							<small class="badge bg-warning"><i class="fa fa-clock-o"></i> 1 day</small>
							<div class="tools">
								<i class="fa fa-edit"></i>
								<i class="fa fa-trash-o"></i>
							</div>
						</li>
						<li class="py-15 px-5">
							<!-- checkbox -->
							<input type="checkbox" id="basic_checkbox_10" class="filled-in">
							<label for="basic_checkbox_10" class="mb-0 h-15"></label>
							<span class="text-line">Proin nec mi porta</span>
							<small class="badge bg-success"><i class="fa fa-clock-o"></i> 3 days</small>
							<div class="tools">
								<i class="fa fa-edit"></i>
								<i class="fa fa-trash-o"></i>
							</div>
						</li>
					</ul>
				</div>
				<!-- /.tab-pane -->
			</div>
		</aside>
		<!-- /.control-sidebar -->

		<!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
		<div class="control-sidebar-bg"></div>


	</div>
	<!-- ./wrapper -->
	<!-- footer -->
	<?php include 'bars/chatbot_overlay.php'; ?>

	<!-- Page Content overlay -->


	<!-- Vendor JS -->
	<script src="../src/js/vendors.min.js"></script>
	<script src="../src/js/pages/chat-popup.js"></script>
	<script src="../../../assets/icons/feather-icons/feather.min.js"></script>

	<!-- Lion Admin App -->
	<script src="../src/js/demo.js"></script>
	<script src="../src/js/template.js"></script>

	<script src="../src/js/pages/list.js"></script>

</body>

</html>