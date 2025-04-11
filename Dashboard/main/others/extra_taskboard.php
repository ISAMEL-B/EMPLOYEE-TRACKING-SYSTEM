<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="../../../images/favicon.ico">

	<title>Dashboard </title>

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

		<?php include 'bars/header.php'; ?>

		<!-- Left side column. contains the logo and sidebar -->
		<?php include 'bars/side_bar.php'; ?>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<div class="container-full">
				<!-- Content Header (Page header) -->
				<div class="content-header">
					<div class="d-flex align-items-center">
						<div class="me-auto">
							<h4 class="page-title">Task Board</h4>
							<div class="d-inline-block align-items-center">
								<nav>
									<ol class="breadcrumb">
										<li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
										<li class="breadcrumb-item" aria-current="page">Extra</li>
										<li class="breadcrumb-item active" aria-current="page">Task Board</li>
									</ol>
								</nav>
							</div>
						</div>

					</div>
				</div>

				<!-- Main content -->
				<section class="content">
					<div class="row">
						<div class="col-12 col-lg-6 col-xl-4">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title">Upcoming</h5>

									<div class="card-actions float-end">
										<div class="dropdown show">
											<a href="#" data-bs-toggle="dropdown" data-bs-display="static"><i class="align-middle" data-feather="more-horizontal"></i></a>

											<div class="dropdown-menu dropdown-menu-end">
												<a class="dropdown-item" href="#">Action</a>
												<a class="dropdown-item" href="#">Another action</a>
												<a class="dropdown-item" href="#">Something else here</a>
											</div>
										</div>
									</div>
								</div>
								<div class="card-body p-3">
								<div id="tasks-upcoming" class="tasks">
									<div class="card mb-3 bg-gray-100 cursor-grab border no-shadow">
										<div class="card-body p-3">
											<div class="float-end me-2">
												<div class="form-check">
													<input class="form-check-input" type="checkbox" value="" id="task1">
													<label class="form-check-label ps-20" for="task1"></label>
												</div>
											</div>
											<p>Review employee performance metrics for Q1 and update the HR scorecard.</p>
											<div class="float-end d-flex">
												<img src="../../../images/avatar/avatar-1.png" width="32" height="32" class="bg-light rounded-circle" alt="Avatar">
												<img src="../../../images/avatar/avatar-2.png" width="32" height="32" class="mx-2 bg-light rounded-circle" alt="Avatar">
												<img src="../../../images/avatar/avatar-3.png" width="32" height="32" class="bg-light rounded-circle" alt="Avatar">
											</div>
											<a class="btn btn-primary-light btn-sm" href="#">View</a>
										</div>
									</div>
									<div class="card mb-3 bg-gray-100 cursor-grab border no-shadow">
										<div class="card-body p-3">
											<div class="float-end me-2">
												<div class="form-check">
													<input class="form-check-input" type="checkbox" value="" id="task2">
													<label class="form-check-label ps-20" for="task2"></label>
												</div>
											</div>
											<p>Complete employee training reports and integrate feedback into the HRM system.</p>
											<div class="float-end d-flex">
												<img src="../../../images/avatar/avatar-10.png" width="32" height="32" class="bg-light rounded-circle" alt="Avatar">
												<img src="../../../images/avatar/avatar-8.png" width="32" height="32" class="mx-2 bg-light rounded-circle" alt="Avatar">
												<img src="../../../images/avatar/avatar-9.png" width="32" height="32" class="bg-light rounded-circle" alt="Avatar">
											</div>
											<a class="btn btn-primary-light btn-sm" href="#">View</a>
										</div>
									</div>
									<div class="card mb-3 bg-gray-100 cursor-grab border no-shadow">
										<div class="card-body p-3">
											<div class="float-end me-2">
												<div class="form-check">
													<input class="form-check-input" type="checkbox" value="" id="task3">
													<label class="form-check-label ps-20" for="task3"></label>
												</div>
											</div>
											<p>Update recruitment data for new hires and analyze department staffing needs.</p>
											<div class="float-end d-flex">
												<img src="../../../images/avatar/avatar-11.png" width="32" height="32" class="bg-light rounded-circle" alt="Avatar">
												<img src="../../../images/avatar/avatar-16.png" width="32" height="32" class="mx-2 bg-light rounded-circle" alt="Avatar">
												<img src="../../../images/avatar/avatar-15.png" width="32" height="32" class="bg-light rounded-circle" alt="Avatar">
											</div>
											<a class="btn btn-primary-light btn-sm" href="#">View</a>
										</div>
									</div>
								</div>

								<div class="d-grid">
									<a href="#" class="btn btn-primary">Add new task</a>
								</div>
							</div>
							</div>
						</div>
						<div class="col-12 col-lg-6 col-xl-4">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title">In Progress</h5>
									<div class="card-actions float-end">
										<div class="dropdown show">
											<a href="#" data-bs-toggle="dropdown" data-bs-display="static"><i class="align-middle" data-feather="more-horizontal"></i></a>
											<div class="dropdown-menu dropdown-menu-end">
												<a class="dropdown-item" href="#">Action</a>
												<a class="dropdown-item" href="#">Another action</a>
												<a class="dropdown-item" href="#">Something else here</a>
											</div>
										</div>
									</div>
								</div>
								<div class="card-body">

								<div id="tasks-progress" class="tasks">
								<div class="card mb-3 bg-gray-100 cursor-grab border no-shadow">
									<div class="card-body p-3">
										<div class="float-end me-2">
											<div class="form-check">
												<input class="form-check-input" type="checkbox" value="" id="task6">
												<label class="form-check-label ps-20" for="task6"></label>
											</div>
										</div>
										<p>Compile staff attendance data and update HR analytics for performance tracking.</p>
										<div class="float-end d-flex">
											<img src="../../../images/avatar/avatar-15.png" width="32" height="32" class="bg-light rounded-circle" alt="Avatar">
											<img src="../../../images/avatar/avatar-3.png" width="32" height="32" class="mx-2 bg-light rounded-circle" alt="Avatar">
											<img src="../../../images/avatar/avatar-4.png" width="32" height="32" class="bg-light rounded-circle" alt="Avatar">
										</div>
										<a class="btn btn-primary-light btn-sm" href="#">View</a>
									</div>
								</div>
								<div class="card mb-3 bg-gray-100 cursor-grab border no-shadow">
									<div class="card-body p-3">
										<div class="float-end me-2">
											<div class="form-check">
												<input class="form-check-input" type="checkbox" value="" id="task7">
												<label class="form-check-label ps-20" for="task7"></label>
											</div>
										</div>
										<p>Assess department productivity reports and implement strategies for improvement.</p>
										<div class="float-end d-flex">
											<img src="../../../images/avatar/avatar-16.png" width="32" height="32" class="bg-light rounded-circle" alt="Avatar">
											<img src="../../../images/avatar/avatar-7.png" width="32" height="32" class="mx-2 bg-light rounded-circle" alt="Avatar">
											<img src="../../../images/avatar/avatar-9.png" width="32" height="32" class="bg-light rounded-circle" alt="Avatar">
										</div>
										<a class="btn btn-primary-light btn-sm" href="#">View</a>
									</div>
								</div>
								<div class="card mb-3 bg-gray-100 cursor-grab border no-shadow">
									<div class="card-body p-3">
										<div class="float-end me-2">
											<div class="form-check">
												<input class="form-check-input" type="checkbox" value="" id="task8">
												<label class="form-check-label ps-20" for="task8"></label>
											</div>
										</div>
										<p>Analyze employee retention rates and develop strategies for workforce stability.</p>
										<div class="float-end d-flex">
											<img src="../../../images/avatar/avatar-2.png" width="32" height="32" class="bg-light rounded-circle" alt="Avatar">
											<img src="../../../images/avatar/avatar-10.png" width="32" height="32" class="mx-2 bg-light rounded-circle" alt="Avatar">
											<img src="../../../images/avatar/avatar-16.png" width="32" height="32" class="bg-light rounded-circle" alt="Avatar">
										</div>
										<a class="btn btn-primary-light btn-sm" href="#">View</a>
									</div>
								</div>
							</div>

									<div class="d-grid">
										<a href="#" class="btn btn-primary">Add new task</a>
									</div>

								</div>
							</div>
						</div>
						<div class="col-12 col-lg-6 col-xl-4">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title">Completed</h5>
									<div class="card-actions float-end">
										<div class="dropdown show">
											<a href="#" data-bs-toggle="dropdown" data-bs-display="static"><i class="align-middle" data-feather="more-horizontal"></i></a>

											<div class="dropdown-menu dropdown-menu-end">
												<a class="dropdown-item" href="#">Action</a>
												<a class="dropdown-item" href="#">Another action</a>
												<a class="dropdown-item" href="#">Something else here</a>
											</div>
										</div>
									</div>
								</div>
								<div class="card-body">

								<div id="tasks-completed" class="tasks">
									<div class="card mb-3 bg-gray-100 cursor-grab border no-shadow">
										<div class="card-body p-3">
											<div class="float-end me-2">
												<div class="form-check">
													<input class="form-check-input" type="checkbox" value="" id="task9">
													<label class="form-check-label ps-20" for="task9"></label>
												</div>
											</div>
											<p>HRM of MUST - Score Card: Employee performance evaluation and competency tracking.</p>
											<div class="float-end d-flex">
												<img src="../../../images/avatar/avatar-4.png" width="32" height="32" class="bg-light rounded-circle" alt="Avatar">
												<img src="../../../images/avatar/avatar-11.png" width="32" height="32" class="mx-2 bg-light rounded-circle" alt="Avatar">
												<img src="../../../images/avatar/avatar-15.png" width="32" height="32" class="bg-light rounded-circle" alt="Avatar">
											</div>
											<a class="btn btn-primary-light btn-sm" href="#">View</a>
										</div>
									</div>

									<div class="card mb-3 bg-gray-100 cursor-grab border no-shadow">
										<div class="card-body p-3">
											<div class="float-end me-2">
												<div class="form-check">
													<input class="form-check-input" type="checkbox" value="" id="task10">
													<label class="form-check-label ps-20" for="task10"></label>
												</div>
											</div>
											<p>HRM of MUST - Score Card: Staff attendance and punctuality tracking.</p>
											<div class="float-end d-flex">
												<img src="../../../images/avatar/avatar-7.png" width="32" height="32" class="bg-light rounded-circle" alt="Avatar">
												<img src="../../../images/avatar/avatar-8.png" width="32" height="32" class="mx-2 bg-light rounded-circle" alt="Avatar">
												<img src="../../../images/avatar/avatar-5.png" width="32" height="32" class="bg-light rounded-circle" alt="Avatar">
											</div>
											<a class="btn btn-primary-light btn-sm" href="#">View</a>
										</div>
									</div>

									<div class="card mb-3 bg-gray-100 cursor-grab border no-shadow">
										<div class="card-body p-3">
											<div class="float-end me-2">
												<div class="form-check">
													<input class="form-check-input" type="checkbox" value="" id="task11">
													<label class="form-check-label ps-20" for="task11"></label>
												</div>
											</div>
											<p>HRM of MUST - Score Card: Employee training and skills development records.</p>
											<div class="float-end d-flex">
												<img src="../../../images/avatar/avatar-8.png" width="32" height="32" class="bg-light rounded-circle" alt="Avatar">
												<img src="../../../images/avatar/avatar-9.png" width="32" height="32" class="mx-2 bg-light rounded-circle" alt="Avatar">
												<img src="../../../images/avatar/avatar-7.png" width="32" height="32" class="bg-light rounded-circle" alt="Avatar">
											</div>
											<a class="btn btn-primary-light btn-sm" href="#">View</a>
										</div>
									</div>

									<div class="card mb-3 bg-gray-100 cursor-grab border no-shadow">
										<div class="card-body p-3">
											<div class="float-end me-2">
												<div class="form-check">
													<input class="form-check-input" type="checkbox" value="" id="task12">
													<label class="form-check-label ps-20" for="task12"></label>
												</div>
											</div>
											<p>HRM of MUST - Score Card: Employee feedback and engagement monitoring.</p>
											<div class="float-end d-flex">
												<img src="../../../images/avatar/avatar-9.png" width="32" height="32" class="bg-light rounded-circle" alt="Avatar">
												<img src="../../../images/avatar/avatar-13.png" width="32" height="32" class="mx-2 bg-light rounded-circle" alt="Avatar">
												<img src="../../../images/avatar/avatar-15.png" width="32" height="32" class="bg-light rounded-circle" alt="Avatar">
											</div>
											<a class="btn btn-primary-light btn-sm" href="#">View</a>
										</div>
									</div>

									<div class="card mb-3 bg-gray-100 cursor-grab border no-shadow">
										<div class="card-body p-3">
											<div class="float-end me-2">
												<div class="form-check">
													<input class="form-check-input" type="checkbox" value="" id="task13">
													<label class="form-check-label ps-20" for="task13"></label>
												</div>
											</div>
											<p>HRM of MUST - Score Card: Employee satisfaction and productivity analysis.</p>
											<div class="float-end d-flex">
												<img src="../../../images/avatar/avatar-4.png" width="32" height="32" class="bg-light rounded-circle" alt="Avatar">
												<img src="../../../images/avatar/avatar-12.png" width="32" height="32" class="mx-2 bg-light rounded-circle" alt="Avatar">
												<img src="../../../images/avatar/avatar-10.png" width="32" height="32" class="bg-light rounded-circle" alt="Avatar">
											</div>
											<a class="btn btn-primary-light btn-sm" href="#">View</a>
										</div>
									</div>
								</div>

									<div class="d-grid">
										<a href="#" class="btn btn-primary">Add new task</a>
									</div>

								</div>
							</div>
						</div>
					</div>
				</section>
				<!-- /.content -->
			</div>
		</div>
		<!-- /.content-wrapper -->

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



	<?php include 'bars/chatbot_overlay.php'; ?>

	<!-- Page Content overlay -->


	<!-- Vendor JS -->
	<script src="../src/js/vendors.min.js"></script>
	<script src="../src/js/pages/chat-popup.js"></script>
	<script src="../../../assets/icons/feather-icons/feather.min.js"></script>

	<script src="../../../assets/vendor_components/dragula-master/dist/dragula.js"></script>

	<!-- Lion Admin App -->
	<script src="../src/js/demo.js"></script>
	<script src="../src/js/template.js"></script>

	<script src="../src/js/pages/extra_taskboard.js"></script>


</body>

</html>