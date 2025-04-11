<?php
    session_start();
    if ($_SESSION['user_role'] !== 'hrm') {
        header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../components/images/favicon.ico">

    <title>Dashboard</title>

    <!-- mine Style-->
    <link rel="stylesheet" href="../components/src/fontawesome/css/all.min.css">
    <!-- Style-->
    <link rel="stylesheet" href="../components/src/css/style.css">

    <link rel="stylesheet" href="../components/bootstrap/css/bootstrap.min.css">

    <!-- my css -->
    <!-- <link rel="stylesheet" href="bars/nav_sidebar/nav_side_bar.css">
    <link rel="stylesheet" href="bars/main_nav_side_bar.css"> -->
    <style>
        /* Main content adjustments */
        .content-wrapper {
            margin-top: 15px;
            /* Small margin for all devices */
        }

        /* Mobile - full width */
        @media (max-width: 991.98px) {
            .content-wrapper {
                margin-left: 0 !important;
                margin-top: 12% !important;
                /* Smaller margin for mobile */
                padding-top: 0;
            }
        }

        /* Remove extra spacing from boxes */
        .box {
            margin-bottom: 1rem;
        }

        .box-header {
            padding: 0.75rem 1.25rem;
        }

        /* Info boxes adjustments */
        .info-box {
            margin-bottom: 1rem;
        }

        /* Chart containers */
        .chart {
            margin-top: 0.5rem;
        }

        /* Specific section headers */
        .box-title {
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
        }

        /* Smaller font size for section headers on mobile */
        @media (max-width: 767.98px) {
            .box-title {
                font-size: 1.25rem;
            }

            .box-header h3.box-title {
                font-size: 1.75rem !important;
            }
        }
    </style>

</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed sidebar-collapse">


    <div class="wrapper">

        <!-- <div id="loader"></div> -->

        <!--side bar  -->
        <?php include 'bars/nav_bar.php';
        ?>
        <?php include 'bars/side_bar.php';
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="container-full">
                <!-- Main content -->
                <section class="content">
                    <!-- ---------------------------------------------------------------------------------------------------  


                           SECTION FOR ACADEMIC PERFORMANCE


                       ------------------------------------------------------------------------------------------------------>
                    <div class="row">
                        <div class="col-lg-12 col-12">
                            <div class="box">
                                <div class="box-header" style="text-align : center ;">
                                    <h3 class="box-title" style="font-size: 40px; " style="text-align : center; "><b>ACADEMIC PERFORMANCE</b></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- actual dynamic data from your database / API -->
                    <?php

                    $phds = 85; // Dynamic value for PhD's
                    $masters = 600; // Dynamic value for Masters Degrees
                    $bachelors = 900; // Dynamic value for Bachelor's Honors
                    $trainings = 1100; // Dynamic value for Professional Trainings
                    ?>

                    <div class="row">
                        <!-- PhD's Info Box -->
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-info rounded"><i class="fa fa-id-card"></i></span>
                                <div class="info-box-content text-fade">
                                    <span class="info-box-number" style="font-size:40px"><b><?php echo $phds; ?></b></span><br><br>
                                    <span class="info-box-text">Phd's</span>
                                </div>
                            </div>
                        </div>

                        <!-- Masters Degrees Info Box -->
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-success rounded"><i class="fas fa-thumbs-up"></i></span>
                                <div class="info-box-content text-fade">
                                    <span class="info-box-number" style="font-size:40px"><b><?php echo $masters; ?></b></span>
                                    <span class="info-box-text">Masters Degrees</span><br>
                                </div>
                            </div>
                        </div>

                        <!-- Bachelor's Honors Info Box -->
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary rounded"><i class="fas fa-shopping-bag"></i></span>
                                <div class="info-box-content text-fade">
                                    <span class="info-box-number" style="font-size:40px"><b><?php echo $bachelors; ?></b></span>
                                    <span class="info-box-text">Bachelor's Honors</span>
                                </div>
                            </div>
                        </div>

                        <!-- Professional Trainings Info Box -->
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger rounded"><i class="fa fa-id-card"></i></span>
                                <div class="info-box-content text-fade">
                                    <span class="info-box-number" style="font-size:40px"><b><?php echo $trainings; ?></b></span>
                                    <span class="info-box-text">Professional Trainings</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FIRST PART OF ACADEMIC PERFORMANCE -->

                    <div class="row">
                        <div class="col-xl-3 col-12"> </div>
                        <div class="col-xl-12 col-12">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title"><b>ACADEMIC PERFORMANCE</b></h3>
                                </div>
                                <div class="box-body">
                                    <ul class="list-inline text-end">
                                        <li>
                                            <h5><i class="fa fa-circle me-5 text-info"></i>Phd's </h5>
                                        </li>
                                        <li>
                                            <h5><i class="fa fa-circle me-5 text-secondary"></i>Masters</h5>
                                        </li>
                                        <li>
                                            <h5><i class="fa fa-circle me-5 text-primary"></i>Bachelor's</h5>
                                        </li>
                                        <li>
                                            <h5><i class="fa fa-circle me-5 text-danger"></i>Certifications</h5>
                                        </li>
                                    </ul>
                                    <div id="morris-area-chart3" style="height: 245px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ---------------------------------------------------------------------------------------------------

                   SECTION FOR RESEARCH AND INNOVATIONS

                   ---------------------------------------------------------------------------------------------------- -->

                    <div class="row">
                        <div class="col-lg-12 col-12">
                            <div class="box">
                                <div class="box-header" style="text-align : center ;">
                                    <h3 class="box-title" style="font-size: 40px; " style="text-align : center; "><b>RESEARCH AND INNOVATIONS</b></h3>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h5 class="box-title">Publications per publishing application</h5>
                                </div>
                                <div class="box-body">
                                    <div class="box-body chart-responsive">
                                        <div class="chart" id="daily-inquery" style="height: 305px;"></div>
                                    </div>

                                    <ul class="list-inline">
                                        <li class="flexbox mb-5 text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-danger"></span>
                                                <span>Google</span>
                                            </div>
                                            <div>300</div>
                                        </li>
                                        <li class="flexbox mb-5 text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-warning"></span>
                                                <span>Research Gate</span>
                                            </div>
                                            <div>55</div>
                                        </li>
                                        <li class="flexbox text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-primary"></span>
                                                <span>Academia</span>
                                            </div>
                                            <div>100</div>
                                        </li>
                                        <li class="flexbox text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-success"></span>
                                                <span>Others</span>
                                            </div>
                                            <div>10</div>
                                        </li>
                                        <li class="flexbox text-fade">
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-8 col-12">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title"><b>PUBLICATIONS</b></h3>
                                </div>
                                <div class="box-body">
                                    <ul class="list-inline text-center">
                                        <li>
                                            <h5><i class="fa fa-circle me-5 text-success"></i>Peer Reviewed Publications</h5>
                                        </li>
                                        <li>
                                            <h5><i class="fa fa-circle me-5 text-primary"></i>Number of Citations</h5>
                                        </li>
                                    </ul>
                                    <div class="chart">
                                        <div class="chart" id="revenue-chart" style="height: 233px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-12 col-xl-4">
                            <div class="box box-body bg-primary">
                                <div class="flexbox">
                                    <div id="linechart">1,4,3,7,6,4,8,9,6,8,12</div>
                                    <div class="text-end">
                                        <span style="font-size:40px;"><b>113</b></span><br>
                                        <span>
                                            <i class="ion-ios-arrow-up text-white"></i>
                                            <span class="fs18 ms-1" style="font-size:20px">First-Author peer-reviewed</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-xl-4">
                            <div class="box box-body bg-success">
                                <div class="flexbox">
                                    <div id="barchart">1,3,5</div>
                                    <div class="text-end">


                                        <span style="font-size:40px;"><b>34578</b></span><br><br>
                                        <span>
                                            <i class="ion-ios-arrow-up text-white"></i>
                                            <span class="fs18 ms-1" style="font-size:20px">MUST repository</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-xl-4">
                            <div class="box box-body bg-danger">
                                <div class="flexbox">
                                    <div id="discretechart">1,4,3,7,6,4,8,9,6,8,12</div>
                                    <div class="text-end">
                                        <span style="font-size:40px;"><b>234</b></span><br><br>
                                        <span>
                                            <i class="ion-ios-arrow-up text-white"></i>
                                            <span class="fs-18 ms-1" style="font-size:20px;">Co authored</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!---------------------------------------------------------------------------------------------------


                                       SECTION FOR COMMUNITY SERVICES
                                      
                   ---------------------------------------------------------------------------------------------------->
                    <div class="row">
                        <div class="col-lg-12 col-12">
                            <div class="box">
                                <div class="box-header" style="text-align : center ;">
                                    <h3 class="box-title" style="font-size: 40px; " style="text-align : center; "><b>COMMUNITY SERVICE</b></h3>
                                </div>

                            </div>
                        </div>


                    </div>


                    <div class="row">
                        <div class="col-xl-4 col-md-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-info rounded"><i class="fa fa-id-card"></i></span>
                                <div class="info-box-content text-fade">
                                    <span class="info-box-number" style="font-size:40px"><b>85</b><small></small></span><br><br>
                                    <span class="info-box-text">Community Outreach Programs <br> </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-success rounded"><i class="fas fa-thumbs-up"></i></span>
                                <div class="info-box-content text-fade">
                                    <span class="info-box-number" style="font-size:40px"><b>600</b></span>
                                    <span class="info-box-text">Clinical Practices <br> </span><br>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary rounded"><i class="fas fa-shopping-bag"></i></span>
                                <div class="info-box-content text-fade">
                                    <span class="info-box-number" style="font-size:40px"><b>900</b></span>
                                    <span class="info-box-text">Supervisions of students <br>in community placements</span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h5 class="box-title">Community outreach programs</h5>
                                </div>
                                <div class="box-body">
                                    <div class="box-body chart-responsive">
                                        <div class="chart" id="communityoutreachprograms" style="height: 305px;"></div>
                                    </div>


                                    <!-- <div class="text-center py-20">
                                       <div class="communityoutreachprograms">
                                           data-peity='{ "fill": ["#ef5350", "#fec801", "#398bf7"], "radius": 78, "innerRadius": 58  }'
                                           9,6,5
                                       </div>
                                   </div> -->


                                    <ul class="list-inline">
                                        <li class="flexbox mb-5 text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-warning"></span>
                                                <span>Awareness campaigns</span>
                                            </div>
                                            <div>300</div>
                                        </li>
                                        <li class="flexbox mb-5 text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-info"></span>
                                                <span>Embedded projects</span>
                                            </div>
                                            <div>55</div>
                                        </li>
                                        <li class="flexbox text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-danger"></span>
                                                <span>Workshops/Trainings</span>
                                            </div>
                                            <div>100</div>
                                        </li>

                                        <li class="flexbox text-fade">
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-8 col-12">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title"><b>COMMUNITY SERVICE</b></h3>
                                </div>
                                <div class="box-body">
                                    <ul class="list-inline text-center">
                                        <li>
                                            <h5><i class="fa fa-circle me-5 text-primary"></i>Supervision of Students in Community/Industrial Placements</h5>
                                        </li>
                                        <li>
                                            <h5><i class="fa fa-circle me-5 text-success"></i>Community Outreach Programs </h5>
                                        </li>
                                        <li>
                                            <h5><i class="fa fa-circle me-5 text-danger"></i>Clinical Practices</h5>
                                        </li>
                                    </ul>
                                    <div class="chart">
                                        <div class="chart" id="communityservice" style="height: 233px;"></div>
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

        <!-- footer -->
        <?php include 'bars/footer.php'; ?>

    </div>
    <!-- ./wrapper -->


    <!-- Page Content overlay -->
    <?php include 'bars/chatbot_overlay.php'; ?>


    <!-- Vendor JS -->
    <script src="../components/src/js/vendors.min.js"></script>
    <!-- for chat bot popups -->
    <script src="../components/src/js/pages/chat-popup.js"></script>
    <script src="../components/assets/icons/feather-icons/feather.min.js"></script>


    <script src="../components/assets/vendor_components/raphael/raphael.min.js"></script>
    <script src="../components/assets/vendor_components/morris.js/morris.min.js"></script>
    <script src="../components/assets/vendor_components/apexcharts-bundle/dist/apexcharts.js"></script>
    <script src="../components/assets/vendor_plugins/weather-icons/WeatherIcon.js"></script>
    <script src="../components/assets/vendor_components/moment/min/moment.min.js"></script>
    <script src="../components/assets/vendor_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="../components/assets/vendor_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
    <script src="../components/assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js"></script>
    <script src="../components/assets/vendor_components/jquery.peity/jquery.peity.js"></script>
    <script src="../components/src/js/demo.js"></script>
    <script src="../components/src/js/template.js"></script>
    <script src="../components/src/js/pages/dashboard.js"></script>


</body>


</html>