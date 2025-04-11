<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../images/favicon.ico">

    <title>Dashboard  Profile </title>
  
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

  		<!-- Left side column. contains the logo and sidebar -->
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
					<h4 class="page-title">Profile</h4>
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
								<li class="breadcrumb-item" aria-current="page">Extra</li>
								<li class="breadcrumb-item active" aria-current="page">Profile</li>
							</ol>
						</nav>
					</div>
				</div>
				
			</div>
		</div>

		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-xl-4 col-lg-5">
					<div class="card text-center">
						<div class="card-body">
							<img src="../../../images/avatar/avatar-13.png" class="bg-light w-100 h-100 rounded-circle avatar-lg img-thumbnail" alt="profile-image">

							<h4 class="mb-0 mt-2">Isamel K</h4>
							<p class="text-muted fs-14">Project Manager</p>

							<button type="button" class="btn btn-primary btn-sm mb-2">Follow</button>
							<button type="button" class="btn btn-light btn-sm mb-2">Message</button>

							<div class="text-start mt-3">
								<p class="header-title mb-2"><strong>About Me :</strong></p>
								<p class="text-muted mb-3">
									I'm B. Isamel, actively Managing comprehensive Human Resource Management System to track employee performance at MUST. Integrates a dynamic scorecard to evaluate key performance indicators (KPIs), ensuring data-driven decision-making and efficient workforce management.
								</p>								
								<p class="text-muted mb-2 "><strong class="text-dark">Full Name :</strong> <span class="ms-2">Johen M. Doe</span></p>

								<p class="text-muted mb-2 "><strong class="text-dark">Mobile :</strong><span class="ms-2">(123)123 1234</span></p>

								<p class="text-muted mb-2 "><strong class="text-dark">Email :</strong> <span class="ms-2 ">user@email.domain</span></p>

								<p class="text-muted mb-1 "><strong class="text-dark">Location :</strong> <span class="ms-2">USA</span></p>
							</div>

							<ul class="social-list list-inline mt-3 mb-0">
								<li class="list-inline-item">
									<a href="javascript: void(0);" class="waves-effect waves-circle btn btn-social-icon btn-circle btn-facebook-light"><i class="fa fa-facebook"></i></a>
								</li>
								<li class="list-inline-item">
									<a href="javascript: void(0);" class="waves-effect waves-circle btn btn-social-icon btn-circle btn-twitter-light"><i class="fa fa-twitter"></i></a>
								</li>
								<li class="list-inline-item">
									<a href="javascript: void(0);" class="waves-effect waves-circle btn btn-social-icon btn-circle btn-google-light"><i class="fa fa-google"></i></a>
								</li>
								<li class="list-inline-item">
									<a href="javascript: void(0);" class="waves-effect waves-circle btn btn-social-icon btn-circle btn-instagram-light"><i class="fa fa-instagram"></i></a>
								</li>
							</ul>
						</div> <!-- end card-body -->
					</div> <!-- end card -->

					<!-- Messages-->
					<div class="card">
						<div class="card-body">
							<div class="dropdown float-end">
								<a href="#" class="dropdown-toggle no-caret" data-bs-toggle="dropdown" aria-expanded="false">
									<i class="mdi mdi-dots-vertical"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<!-- item-->
									<a href="javascript:void(0);" class="dropdown-item">Settings</a>
									<!-- item-->
									<a href="javascript:void(0);" class="dropdown-item">Action</a>
								</div>
							</div>
							<h4 class="header-title mb-3">Contact</h4>

							<div>
								<div class="d-flex align-items-center mb-30">
									<div class="me-15">
										<img src="../../../images/avatar/avatar-1.png" class="bg-primary-light avatar avatar-lg rounded-circle" alt="">
									</div>
									<div class="d-flex flex-column flex-grow-1">
										<a href="#" class="text-dark hover-primary mb-1 fs-16">Praise</a>
										<span class="text-mute fs-12">Project Manager</span>
									</div>
									<div>
										<a href="#" class="btn btn-sm btn-primary-light">Reply</a>
									</div>
								</div>
								<div class="d-flex align-items-center mb-30">
									<div class="me-15">
										<img src="../../../images/avatar/avatar-2.png" class="bg-primary-light avatar avatar-lg rounded-circle" alt="">
									</div>
									<div class="d-flex flex-column flex-grow-1">
										<a href="#" class="text-dark hover-danger mb-1 fs-16">Felix</a>
										<span class="text-mute fs-12">Art Director</span>
									</div>
									<div>
										<a href="#" class="btn btn-sm btn-primary-light">Reply</a>
									</div>
								</div>
								<div class="d-flex align-items-center mb-30">
									<div class="me-15">
										<img src="../../../images/avatar/avatar-3.png" class="bg-primary-light avatar avatar-lg rounded-circle" alt="">
									</div>
									<div class="d-flex flex-column flex-grow-1">
										<a href="#" class="text-dark hover-success mb-1 fs-16">Achiever</a>
										<span class="text-mute fs-12">Sales Manager</span>
									</div>
									<div>
										<a href="#" class="btn btn-sm btn-primary-light">Reply</a>
									</div>
								</div>
								<div class="d-flex align-items-center mb-30">
									<div class="me-15">
										<img src="../../../images/avatar/avatar-4.png" class="bg-primary-light avatar avatar-lg rounded-circle" alt="">
									</div>
									<div class="d-flex flex-column flex-grow-1">
										<a href="#" class="text-dark hover-info mb-1 fs-16">Daniel</a>
										<span class="text-mute fs-12">Creative Head</span>
									</div>
									<div>
										<a href="#" class="btn btn-sm btn-primary-light">Reply</a>
									</div>
								</div>
								<div class="d-flex align-items-center">
									<div class="me-15">
										<img src="../../../images/avatar/avatar-5.png" class="bg-primary-light avatar avatar-lg rounded-circle" alt="">
									</div>
									<div class="d-flex flex-column flex-grow-1">
										<a href="#" class="text-dark hover-warning mb-1 fs-16">Natalie</a>
										<span class="text-mute fs-12">QA Managers</span>
									</div>
									<div>
										<a href="#" class="btn btn-sm btn-primary-light">Reply</a>
									</div>
								</div>
							</div>
						</div> <!-- end card-body-->
					</div> <!-- end card-->

				</div> <!-- end col-->

				<div class="col-xl-8 col-lg-7">
					<div class="card">
						<div class="card-body">
							<ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
								<li class="nav-item">
									<a href="#aboutme" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
										About
									</a>
								</li>
								<li class="nav-item">
									<a href="#timelinest" data-bs-toggle="tab" aria-expanded="true" class="nav-link rounded-0 active">
										Timeline
									</a>
								</li>
								<li class="nav-item">
									<a href="#settings" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
										Settings
									</a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane" id="aboutme">

									<h5 class="text-uppercase"><i class="mdi mdi-briefcase me-1"></i>
										Experience</h5>

									<div class="timeline-alt pb-0">
										<div class="timeline-item">
											<i class="mdi mdi-circle bg-info-light text-info timeline-icon"></i>
											<div class="timeline-item-info">
												<h5 class="fs-14 mt-0 mb-1">HRM - Info</h5>
												<p>Dummy.com <span class="ms-2 fs-12">Year: 2015 - 18</span></p>
												<p class="text-muted mt-2 mb-0 pb-3">
													The HRM of MUST - Score Card provides a comprehensive tracking system for employee performance, 
													attendance, and competency development. It enables easy assessment of key metrics, progress monitoring, 
													and informed decision-making to enhance workforce efficiency.
												</p>

											</div>
										</div>

										<div class="timeline-item">
											<i class="mdi mdi-circle bg-primary-light text-primary timeline-icon"></i>
											<div class="timeline-item-info">
												<h5 class="fs-14 mt-0 mb-1">Grants Officer</h5>
												<p>Dummy Inc. <span class="ms-2 fs-12">Year: 2012 - 15</span></p>
												<p class="text-muted mt-2 mb-0 pb-3">If several languages coalesce, the grammar
													of the resulting language is more simple and regular than that of
													the individual languages. The new common language will be more
													simple hence HRM system.</p>

											</div>
										</div>

										<div class="timeline-item">
											<i class="mdi mdi-circle bg-info-light text-info timeline-icon"></i>
											<div class="timeline-item-info">
												<h5 class="fs-14 mt-0 mb-1">Content create</h5>
												<p>Dummy pllc <span class="ms-2 fs-12">Year: 2010 - 12</span></p>
												<p class="text-muted mt-2 mb-0 pb-2">The European languages are members of
													the same family. Their separate existence is a myth. For science
													Education, sport.</p>
											</div>
										</div>

									</div>
									<!-- end timeline -->        

									<h5 class="mb-3 mt-4 text-uppercase"><i class="mdi mdi-cards-variant me-1"></i>
										Projects</h5>
									<div class="table-responsive">
										<table class="table text-fade table-borderless table-nowrap mb-0">
											<thead class="table-light">
												<tr>
													<th>#</th>
													<th>Clients</th>
													<th>Project Name</th>
													<th>Start Date</th>
													<th>Due Date</th>
													<th>Status</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>1</td>
													<td><img src="../../../images/avatar/avatar-1.png" alt="table-user" class="bg-light me-2 rounded-circle" height="24"> Nil Yeager</td>
													<td>Community Service</td>
													<td>01/01/2015</td>
													<td>10/15/2018</td>
													<td><span class="badge badge-info-light">Work in Progress</span></td>
												</tr>
												<tr>
													<td>2</td>
													<td><img src="../../../images/avatar/avatar-2.png" alt="table-user" class="bg-light me-2 rounded-circle" height="24"> Mical doe</td>
													<td>Innovation - Main Page</td>
													<td>21/07/2016</td>
													<td>12/05/2018</td>
													<td><span class="badge badge-danger-light">Pending</span></td>
												</tr>
												<tr>
													<td>3</td>
													<td><img src="../../../images/avatar/avatar-3.png" alt="table-user" class="bg-light me-2 rounded-circle" height="24"> Lucy Doe</td>
													<td>Projects</td>
													<td>18/03/2018</td>
													<td>28/09/2018</td>
													<td><span class="badge badge-success-light">Done</span></td>
												</tr>
												<tr>
													<td>4</td>
													<td><img src="../../../images/avatar/avatar-4.png" alt="table-user" class="bg-light me-2 rounded-circle" height="24"> ToodDoe</td>
													<td>Community Services</td>
													<td>02/10/2017</td>
													<td>07/05/2018</td>
													<td><span class="badge badge-info-light">Work in Progress</span></td>
												</tr>
												<tr>
													<td>5</td>
													<td><img src="../../../images/avatar/avatar-5.png" alt="table-user" class="bg-light me-2 rounded-circle" height="24"> Mical Doe</td>
													<td>Innovation 2</td>
													<td>17/01/2017</td>
													<td>25/05/2021</td>
													<td><span class="badge badge-warning-light">Coming soon</span></td>
												</tr>

											</tbody>
										</table>
									</div>

								</div> <!-- end tab-pane -->
								<!-- end about me section content -->

								<div class="tab-pane show active" id="timelinest">

									<!-- comment box -->
									<div class="border rounded mt-2 mb-3">
										<form action="#" class="comment-area-box">
											<textarea rows="3" class="form-control border-0 resize-none" placeholder="Write something...."></textarea>
											<div class="p-2 bg-gray-100 d-flex justify-content-between align-items-center">
												<div>
													<a href="#" class="btn btn-sm px-2 fs-16 btn-primary-light"><i class="mdi mdi-account-circle"></i></a>
													<a href="#" class="btn btn-sm px-2 fs-16 btn-primary-light"><i class="mdi mdi-map-marker"></i></a>
													<a href="#" class="btn btn-sm px-2 fs-16 btn-primary-light"><i class="mdi mdi-camera"></i></a>
													<a href="#" class="btn btn-sm px-2 fs-16 btn-primary-light"><i class="mdi mdi-emoticon"></i></a>
												</div>
												<button type="submit" class="btn btn-sm btn-primary-light waves-effect">Post</button>
											</div>
										</form>
									</div> <!-- end .border-->
									<!-- end comment box -->

									<!-- Story Box-->
									<div class="border border-light rounded p-2 mb-3">
										<div class="d-flex">
											<img class="me-2 rounded-circle bg-light" src="../../../images/avatar/avatar-1.png" alt="Generic placeholder image" height="32">
											<div>
												<h5 class="fs-15 m-0">Aine James</h5>
												<p class="text-muted"><small>about 2 minuts ago</small></p>
											</div>
										</div>
										<p class="text-fade">Story based around the idea of time lapse, animation to post soon!</p>

										<img src="../../../images/gallery/thumb-sm/1.jpg" alt="post-img" class="rounded bg-light me-1" height="100" />
										<img src="../../../images/gallery/thumb-sm/2.jpg" alt="post-img" class="rounded bg-light me-1" height="100" />
										<img src="../../../images/gallery/thumb-sm/3.jpg" alt="post-img" class="rounded bg-light" height="100" />

										<div class="mt-2">
											<a href="javascript: void(0);" class="btn btn-sm btn-primary-light"><i class="mdi mdi-reply"></i> Reply</a>
											<a href="javascript: void(0);" class="btn btn-sm btn-primary-light"><i class="mdi mdi-heart-outline"></i> Like</a>
											<a href="javascript: void(0);" class="btn btn-sm btn-primary-light"><i class="mdi mdi-share-variant"></i> Share</a>
										</div>
									</div>

									<!-- Story Box-->
									<div class="border border-light rounded p-2 mb-3">
										<div class="d-flex">
											<img class="me-2 rounded-circle bg-light" src="../../../images/avatar/avatar-1.png" alt="Generic placeholder image" height="32">
											<div>
												<h5 class="m-0 fs-15">Thelma Fridley</h5>
												<p class="text-muted">about 1 hour ago</p>
											</div>
										</div>
										<div class="fs-13 text-center fst-italic text-fade">
											<i class="mdi mdi-format-quote-open fs-13"></i> Cras sit amet nibh libero, in
											gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras
											purus odio, vestibulum in vulputate at, tempus viverra turpis. Duis
											sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper
											porta. Mauris massa.
										</div>

										<div class="my-2 p-15 mt-3">
											<div class="d-flex">
												<img class="me-2 rounded-circle bg-light" src="../../../images/avatar/avatar-2.png" alt="Generic placeholder image" height="32">
												<div>
													<h5 class="fs-15 mt-0 mb-0">Praise</h5> 
													<p class="text-muted">3 hours ago</p>
													<span class="text-fade">Nice work, makes me think of The Money Pit.</span>

													<br/>
													<a href="javascript: void(0);" class="text-muted d-inline-block mt-2"><i class="mdi mdi-reply"></i> Reply</a>

													<div class="d-flex mt-3">
														<a class="pe-2" href="#">
															<img class="me-2 rounded-circle bg-light" src="../../../images/avatar/avatar-3.png" alt="Generic placeholder image" height="32">
														</a>
														<div>
															<h5 class="fs-15 mt-0 mb-0">Isa</h5>
															<p class="text-muted">5 hours ago</p>
															<span class="text-fade">i'm in the middle of a timelapse animation myself! (Very different though.) Awesome stuff.</span>
														</div>
													</div>
												</div>
											</div>

											<div class="d-flex mt-2">
												<a class="pe-2" href="#">
													<img class="me-2 rounded-circle bg-light" src="../../../images/avatar/avatar-1.png" alt="Generic placeholder image" height="32">
												</a>
												<div class="w-100">
													<input type="text" id="simpleinput" class="form-control border-0 form-control-sm" placeholder="Add comment">
												</div>
											</div>
										</div>

										<div class="mt-2">
											<a href="javascript: void(0);" class="btn btn-sm btn-danger-light"><i class="mdi mdi-heart"></i> Like (28)</a>
											<a href="javascript: void(0);" class="btn btn-sm btn-danger-light"><i class="mdi mdi-share-variant"></i> Share</a>
										</div>
									</div>

									<!-- Story Box-->
									<div class="border border-light p-2 mb-3">
										<div class="d-flex">
											<img class="me-2 rounded-circle bg-light" src="../../../images/avatar/avatar-6.png" alt="Generic placeholder image" height="32">
											<div>
												<h5 class="fs-15 m-0">Achiever</h5>
												<p class="text-muted"><small>15 hours ago</small></p>
											</div>
										</div>
										<p class="text-fade">The parallax is a little odd but O.o that house build is awesome!!</p>

										<iframe src="https://player.vimeo.com/video/87993762" height="300" class="w-auto img-fluid border-0"></iframe>
									</div>

									<div class="text-center">
										<a href="javascript:void(0);" class="text-danger"><i class="mdi mdi-spin mdi-loading me-1"></i> Load more </a>
									</div>

								</div>
								<!-- end timeline content-->

								<div class="tab-pane" id="settings">
									<form>
										<h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle me-1"></i> Personal Info</h5>
										<div class="row">
											<div class="col-md-6">
												<div class="mb-3">
													<label for="firstname" class="form-label">First Name</label>
													<input type="text" class="form-control" id="firstname">
												</div>
											</div>
											<div class="col-md-6">
												<div class="mb-3">
													<label for="lastname" class="form-label">Last Name</label>
													<input type="text" class="form-control" id="lastname">
												</div>
											</div> <!-- end col -->
										</div> <!-- end row -->

										<div class="row">
											<div class="col-12">
												<div class="mb-3">
													<label for="userbio" class="form-label">Bio</label>
													<textarea class="form-control" id="userbio" rows="4"></textarea>
												</div>
											</div> <!-- end col -->
										</div> <!-- end row -->

										<div class="row">
											<div class="col-md-6">
												<div class="mb-3">
													<label for="useremail" class="form-label">Email Address</label>
													<input type="email" class="form-control" id="useremail">
													<span class="form-text text-muted">If you want to change email please <a href="javascript: void(0);">click</a> here.</span>
												</div>
											</div>
											<div class="col-md-6">
												<div class="mb-3">
													<label for="userpassword" class="form-label">Password</label>
													<input type="password" class="form-control" id="userpassword">
													<span class="form-text text-muted">If you want to change password please <a href="javascript: void(0);">click</a> here.</span>
												</div>
											</div> <!-- end col -->
										</div> <!-- end row -->

										<h5 class="mt-3 mb-4 text-uppercase"><i class="mdi mdi-office me-1"></i> Company Info</h5>
										<div class="row">
											<div class="col-md-6">
												<div class="mb-3">
													<label for="companyname" class="form-label">Company Name</label>
													<input type="text" class="form-control" id="companyname">
												</div>
											</div>
											<div class="col-md-6">
												<div class="mb-3">
													<label for="cwebsite" class="form-label">Website</label>
													<input type="text" class="form-control" id="cwebsite">
												</div>
											</div> <!-- end col -->
										</div> <!-- end row -->

										<h5 class="mt-3 mb-4 text-uppercase"><i class="mdi mdi-earth me-1"></i> Social</h5>
										<div class="row">
											<div class="col-md-6">
												<div class="mb-3">
													<label for="social-fb" class="form-label">Facebook</label>
													<div class="input-group">
														<span class="input-group-text"><i class="mdi mdi-facebook"></i></span>
														<input type="text" class="form-control" id="social-fb">
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="mb-3">
													<label for="social-tw" class="form-label">Twitter</label>
													<div class="input-group">
														<span class="input-group-text"><i class="mdi mdi-twitter"></i></span>
														<input type="text" class="form-control" id="social-tw">
													</div>
												</div>
											</div> <!-- end col -->
										</div> <!-- end row -->

										<div class="row">
											<div class="col-md-6">
												<div class="mb-3">
													<label for="social-insta" class="form-label">Instagram</label>
													<div class="input-group">
														<span class="input-group-text"><i class="mdi mdi-instagram"></i></span>
														<input type="text" class="form-control" id="social-insta">
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="mb-3">
													<label for="social-lin" class="form-label">Linkedin</label>
													<div class="input-group">
														<span class="input-group-text"><i class="mdi mdi-linkedin"></i></span>
														<input type="text" class="form-control" id="social-lin">
													</div>
												</div>
											</div> <!-- end col -->
										</div> <!-- end row -->

										<div class="row">
											<div class="col-md-6">
												<div class="mb-3">
													<label for="social-sky" class="form-label">Skype</label>
													<div class="input-group">
														<span class="input-group-text"><i class="mdi mdi-skype"></i></span>
														<input type="text" class="form-control" id="social-sky">
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="mb-3">
													<label for="social-gh" class="form-label">Github</label>
													<div class="input-group">
														<span class="input-group-text"><i class="mdi mdi-github-face"></i></span>
														<input type="text" class="form-control" id="social-gh">
													</div>
												</div>
											</div> <!-- end col -->
										</div> <!-- end row -->

										<div class="text-end">
											<button type="submit" class="btn btn-primary mt-2"><i class="mdi mdi-content-save"></i> Save</button>
										</div>
									</form>
								</div>
								<!-- end settings content-->

							</div> <!-- end tab-content -->
						</div> <!-- end card body -->
					</div> <!-- end card -->
				</div> <!-- end col -->
			</div>
			<!-- end row-->

		</section>
		<!-- /.content -->
	  </div>
  </div>
  <!-- /.content-wrapper -->
 
   <footer class="main-footer">
    <div class="pull-right d-none d-sm-inline-block">
        <ul class="nav nav-primary nav-dotted nav-dot-separated justify-content-center justify-content-md-end">
		  <li class="nav-item">
			<a class="nav-link" href="#" target="_blank">Wise Men</a>
		  </li>
		</ul>
    </div>
	  &copy; <script>document.write(new Date().getFullYear())</script> <a href="#">Group 10</a>. All Rights Reserved.
  </footer>
  <!-- Side panel --> 
  <!-- Control Sidebar -->
  <?php ?>
  <!-- /.control-sidebar -->
  
  <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
	
	
</div>
<!-- ./wrapper -->
	
	
	
	<!-- chat overlay box -->
	<?php include 'bars/chatbot_overlay.php'; ?>
	<!-- Page Content overlay -->
	
	
	<!-- Vendor JS -->
	<script src="../src/js/vendors.min.js"></script>
	<script src="../src/js/pages/chat-popup.js"></script>
    <script src="../../../assets/icons/feather-icons/feather.min.js"></script>	
	
	<!-- Lion Admin App -->
	<script src="../src/js/demo.js"></script>
	<script src="../src/js/template.js"></script>
	
	<script src="../src/js/pages/timeline.js"></script>
	
	
</body>
</html>
