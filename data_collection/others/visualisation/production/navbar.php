<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScoreCard Dashboard</title>
    <!-- Bootstrap -->
  <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- jQuery custom content scroller -->
  <link href="../vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet"/>
  <!-- Custom Theme Style -->
  <link href="../build/css/custom.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* Additional styles for the sidebar */
        .nav-md .left_col {
            position: fixed;
            height: 100%;
            overflow-y: auto;
        }

        .right_col {
            margin-left: 250px; /* adjust based on sidebar width */
            padding: 20px;
        }

        @media (max-width: 768px) {
            .right_col {
                margin-left: 0; /* reset for smaller screens */
            }
        }
        /* ----budge style------*/
    .badge-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .badge-image {
        width: 100px; /* Adjust the size as needed */
        height: auto;
        border-radius: 50%; /* Makes it circular if the image is square */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        margin-top: -10px; /* Adjust position above title */
    }
    /* //----budge style------*/
    </style>
</head>
<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col menu_fixed">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="dashboard.php" class="site_title"><i class="fa fa-trophy"></i> <span>ScoreCard</span></a>
                </div>
                <div class="clearfix"></div>

                <div class="profile clearfix">
                    <div class="profile_pic">
                        <img src="images/user.png" alt="..." class="img-circle profile_img">
                    </div>
                    <div class="profile_info">
                        <span>Welcome,</span>
                        <h2>HRM</h2>
                    </div>
                </div>

                <br />

                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <h3>Score Management</h3>
                        <ul class="nav side-menu">
                            <li><a><i class="fa fa-dashboard"></i> Dashboard <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="dashboard.php">Main Dashboard</a></li>
                                    <li><a href="analytics.html">Analytics</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-upload"></i> Upload-Data <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="../csv/csv_receiver/upload.php">Upload Csv</a></li>
                                    <li><a href="../csv/csv_receiver/modify/column.php">Edit Csv csv_receiver</a></li>
                                    <li><a href="../csv/csv_receiver/upload.php">Check current Criteria</a></li>
                                    <li><a href="../csv/csv_receiver/criteria/criteria_upload.php">Edit Csv csv_receiver</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-bar-chart"></i> Reports <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="monthly_reports.html">Monthly Reports</a></li>
                                    <li><a href="annual_reports.html">Annual Reports</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-users"></i> Team Performance <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="team_overview.html">Overview</a></li>
                                    <li><a href="individual_performance.html">Individual Scores</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-trophy"></i> Achievements <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="grants_graph.html">Goals</a></li>
                                    <li><a href="enhance.php">Milestones</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-bar-chart-o"></i> Data Presentation <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="chartjs.html">Chart JS</a></li>
                                    <li><a href="chartjs2.html">Chart JS2</a></li>
                                    <li><a href="morisjs.html">Moris JS</a></li>
                                    <li><a href="echarts.html">ECharts</a></li>
                                    <li><a href="other_charts.html">Other Charts</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="sidebar-footer hidden-small">
                    <a data-toggle="tooltip" data-placement="top" title="Settings">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Lock">
                        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>
            </div>
        </div>

        <div class="top_nav">
            <div class="nav_menu">
                <div class="nav toggle">
                    <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                </div>
                <nav class="nav navbar-nav">
                    <ul class="navbar-right">
                        <li class="nav-item dropdown open" style="padding-left: 15px;">
                            <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                                <img src="images/user.png" alt="">Isa.k
                            </a>
                            <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="javascript:;"> Profile</a>
                                <a class="dropdown-item" href="javascript:;"> Settings</a>
                                <a class="dropdown-item" href="javascript:;">Help</a>
                                <a class="dropdown-item" href="login.html"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="right_col" role="main">
            <div class="page-title">
                <!-- Page Title Section -->
            </div>

            <div class="main-content">
                <div class="badge-container text-center mb-3">
                    <img src="images/mustlogo.png" alt="Badge" class="badge-image">
                </div>

                <h1 class="text-center my-4">Expert Score - Dashboard</h1>

                <div class="container mt-4">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <span class="count_top"><i class="fa fa-user"></i> Total Employees</span>
                                    <div class="count">2500</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <span class="count_top"><i class="fa fa-clock-o"></i> Average Time</span>
                                    <div class="count">123.50</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <span class="count_top"><i class="fa fa-user"></i> Total Males</span>
                                    <div class="count">2,500</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <span class="count_top"><i class="fa fa-user"></i> Total Females</span>
                                    <div class="count">500</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Other Content -->
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="../vendors/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="../vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<!-- FastClick -->
<script src="../vendors/fastclick/lib/fastclick.js"></script>
<!-- NProgress -->
<script src="../vendors/nprogress/nprogress.js"></script>
<!-- jQuery custom content scroller -->
<script src="../vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
<!-- Custom Theme Scripts -->
<script src="../build/js/custom.min.js"></script>
</body>
</html>
