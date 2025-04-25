<?php
//report all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();
$current_ur = 'index.php';
$current_pag = 'index';

// Check if user is NOT logged in OR not HRM
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'hrm') {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit();
}

//include backend calculator
// include '/EMPLOYEE-TRACKING-SYSTEM/scoring_calculator/university score/university_score.php';
include '../../scoring_calculator/university score/university_score.php';


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
    <link rel="stylesheet" href="css/stat-cards.css">';

    <link rel="stylesheet" href="../components/bootstrap/css/bootstrap.min.css">

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

                    $phds = $university_data['PhD']; // Dynamic value for PhD's
                    $masters = $university_data['Masters']; // Dynamic value for Masters Degrees
                    $bachelors_first_class = $university_data['First Class']; // Dynamic value for Bachelor's Honors
                    $trainings = $university_data['Other']; // Dynamic value for Professional Trainings
                    $patents = $university_data['Patent']; // Dynamic value for Patents
                    $publications = $university_data['Journal Articles (First Author)'] + $university_data['Journal Articles (Corresponding Author)'] + $university_data['Journal Articles (Co-author)'] + $university_data['Book with ISBN'] + $university_data['Book Chapter'];// Dynamic value for publications
                    $journal = $university_data['Journal Articles (First Author)']; // Dynamic value for Journal Articles for First Author      
                    $articles = $university_data['Journal Articles (Corresponding Author)']; // Dynamic value for Journal Articles for Corresponding Author
                    $co_author = $university_data['Journal Articles (Co-author)']; // Dynamic value for Journal Articles for Co-author
                    $book = $university_data['Book with ISBN']; // Dynamic value for Book with ISBN
                    $book_chapter = $university_data['Book Chapter']; // Dynamic value for Book Chapter
                    
                    $grants = $university_data['total_grant_amount']; // Dynamic value for Grants



                    ?>

                    <div class="row">
                        <!-- PhD's Info Box -->
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-info rounded"><i class="fa fa-id-card"></i></span>
                                <div class="info-box-content text-fade">
                                    <span class="info-box-number" style="font-size:40px"><?php echo $phds ?></b></span><br><br>
                                    <span class="info-box-text">Phd's</span>
                                </div>
                            </div>
                        </div>

                        <!-- Masters Degrees Info Box -->
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-success rounded"><i class="fas fa-thumbs-up"></i></span>
                                <div class="info-box-content text-fade">
                                    <span class="info-box-number" style="font-size:40px"><?= $masters ?></span>
                                    <span class="info-box-text">Masters Degrees</span><br>
                                </div>
                            </div>
                        </div>

                        <!-- Bachelor's Honors Info Box -->
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary rounded"><i class="fas fa-shopping-bag"></i></span>
                                <div class="info-box-content text-fade">
                                    <span class="info-box-number" style="font-size:40px"><?= $bachelors_first_class ?></span>
                                    <span class="info-box-text">Bachelor's Honors</span>
                                </div>
                            </div>
                        </div>

                        <!-- Professional Trainings Info Box -->
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger rounded"><i class="fa fa-id-card"></i></span>
                                <div class="info-box-content text-fade">
                                    <span class="info-box-number" style="font-size:40px"><?= $trainings ?></span>
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
                                            <h5><i class="fa fa-circle me-5 text--bs-indigo"></i>Masters</h5>
                                        </li>
                                        <li>
                                            <h5><i class="fa fa-circle me-5 text-primary"></i>First class</h5>
                                        </li>
                                        <li>
                                            <h5><i class="fa fa-circle me-5 text-primary"></i>First class</h5>
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
                                <div class="box-header" style="text-align: center;">
                                    <h3 class="box-title" style="font-size: 40px;"><b>RESEARCH AND INNOVATIONS</b></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h5 class="box-title">Publications by type</h5>
                                </div>                                
                                <div class="box-body" style="padding: 15px;">
                                    <div class="box-body chart-responsive" style="height: 180px;">
                                        <div class="chart" id="daily-inquery" style="height: 180px;"></div>
                                    </div>
                                    <ul class="list-inline" style="margin-bottom: 0;">
                                        <li class="flexbox mb-3 text-fade"> <!-- Reduced from mb-5 to mb-3 -->
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-danger"></span>
                                                <span>First Author</span>
                                            </div>
                                            <div><?= $journal ?></div>
                                        </li>
                                        <li class="flexbox mb-3 text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-warning"></span>
                                                <span>Corresponding Author</span>
                                            </div>
                                            <div><?= $articles ?></div>
                                        </li>
                                        <li class="flexbox mb-3 text-fade"> <!-- Added mb-3 for consistency -->
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-primary"></span>
                                                <span>Co-author</span>
                                            </div>
                                            <div><?= $co_author ?></div>
                                        </li>
                                        <li class="flexbox mb-3 text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-success"></span>
                                                <span>Book with ISBN</span>
                                            </div>
                                            <div><?= $book ?></div>
                                        </li>
                                        <li class="flexbox text-fade"> <!-- Last item doesn't need margin-bottom -->
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-info"></span>
                                                <span>Book Chapter</span>
                                            </div>
                                            <div><?= $book_chapter ?></div>
                                        </li>
                                    </ul>
                                </div>
                            </div>    
                        </div>

                        <div class="col-lg-8 col-12">
                            <!-- Stat Cards Container -->
                            <div class="stat-cards-container">
                                <!-- Publications Card -->
                                <div class="stat-card">
                                    <h3>Publications</h3>
                                    <p class="stat-number"><b><?= $publications ?></b></p>
                                    <p class="stat-change positive">
                                        +23% from last year
                                    </p>
                                </div>
                                
                                <!-- Grants Card -->                        
                                <div class="stat-card grants">
                                    <h3>Grants</h3>
                                    <p class="stat-number"><b><?= $grants ?></b></p>
                                    <p class="stat-change positive">
                                        +15% from last year
                                    </p>
                                </div>
                                
                                <!-- Patents Card -->
                                <div class="stat-card patents">     
                                    <h3>Patents</h3>
                                    <p class="stat-number"><b><?= $patents ?></b></p>
                                    <p class="stat-change positive">
                                        +10% from last year
                                    </p>
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
        <?php //include 'bars/footer.php'; ?>

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
    <script src="js/stat-cards.js"></script>
    <script src="../js/chart.js"></script>
    <!-- <script src="../components/src/js/pages/dashboard.php"></script> -->
    <?php
    include '../components/src/js/pages/dashboarddd.php';
    ?>


</body>


</html>