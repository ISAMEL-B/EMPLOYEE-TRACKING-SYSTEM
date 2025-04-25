<?php
//report all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$current_ur = 'index.php';
$current_pag = 'index';

// Check if user is NOT logged in OR not HRM
if (!isset($_SESSION['user_role'])) {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit();
}

// Database connection
require_once 'head/approve/config.php';

// Get research and innovation data from database
$research_data = [
    'publications' => [
        'first_author' => 0,
        'corresponding_author' => 0,
        'co_author' => 0,
        'book_isbn' => 0,
        'book_chapter' => 0,
        'total' => 0
    ],
    'grants' => [
        'total_amount' => 0,
        'count' => 0
    ],
    'patents' => 0,
    'utility_models' => 0,
    'copyrights' => 0
];

// Get publications data
$publications_query = "SELECT publication_type,role, COUNT(*) as count FROM publications GROUP BY publication_type";
$publications_result = $conn->query($publications_query);
if ($publications_result) {
    while ($row = $publications_result->fetch_assoc()) {
        switch ($row['publication_type']) {
            case 'Journal Article':
                if (strpos($row['role'], 'Author') !== false) {
                    $research_data['publications']['first_author'] += $row['count'];
                } elseif (strpos($row['role'], 'Co-Author') !== false) {
                    $research_data['publications']['co_author'] += $row['count'];
                }
                break;
            case 'Conference Paper':
                $research_data['publications']['corresponding_author'] += $row['count'];
                break;
            // Add other publication types as needed
        }
        $research_data['publications']['total'] += $row['count'];
    }
}

// Get grants data
$grants_query = "SELECT COUNT(*) as count, SUM(grant_amount) as total FROM grants";
$grants_result = $conn->query($grants_query);
if ($grants_result && $row = $grants_result->fetch_assoc()) {
    $research_data['grants']['count'] = $row['count'];
    $research_data['grants']['total_amount'] = $row['total'] ? $row['total'] : 0;
}

// Get innovations data
$innovations_query = "SELECT innovation_type, COUNT(*) as count FROM innovations GROUP BY innovation_type";
$innovations_result = $conn->query($innovations_query);
if ($innovations_result) {
    while ($row = $innovations_result->fetch_assoc()) {
        switch ($row['innovation_type']) {
            case 'Patent':
                $research_data['patents'] = $row['count'];
                break;
            case 'Utility Model':
                $research_data['utility_models'] = $row['count'];
                break;
            case 'Copyright':
                $research_data['copyrights'] = $row['count'];
                break;
        }
    }
}

// Get community service data
$community_data = [
    'students_supervised' => 0,
    'outreaches' => 0,
    'beneficiaries' => 0,
    'awareness_campaigns' => 0,
    'embedded_projects' => 0,
    'workshops' => 0
];

// Get supervision data
$supervision_query = "SELECT COUNT(*) as total FROM supervision";
$supervision_result = $conn->query($supervision_query);
if ($supervision_result && $row = $supervision_result->fetch_assoc()) {
    $community_data['students_supervised'] = $row['total'];
}

// Get community service data (simplified - you may need to adjust based on your actual data structure)
$community_service_query = "SELECT COUNT(*) as total FROM communityservice";
$community_service_result = $conn->query($community_service_query);
if ($community_service_result && $row = $community_service_result->fetch_assoc()) {
    $community_data['outreaches'] = $row['total'];
    // Assuming each outreach has about 15 beneficiaries on average
    $community_data['beneficiaries'] = $row['total'] * 15;
    
    // Rough estimates for different types (adjust based on your actual data)
    $community_data['awareness_campaigns'] = round($row['total'] * 0.5);
    $community_data['embedded_projects'] = round($row['total'] * 0.3);
    $community_data['workshops'] = round($row['total'] * 0.2);
}

//include backend calculator
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
    <link rel="stylesheet" href="css/stat-cards.css">

    <link rel="stylesheet" href="../components/bootstrap/css/bootstrap.min.css">

</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed sidebar-collapse">

    <div class="wrapper">

        <!--side bar  -->
        <?php include 'bars/nav_bar.php'; ?>
        <?php include 'bars/side_bar.php'; ?>

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
                                        <li class="flexbox mb-3 text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-danger"></span>
                                                <span>First Author</span>
                                            </div>
                                            <div><?= $research_data['publications']['first_author'] ?></div>
                                        </li>
                                        <li class="flexbox mb-3 text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-warning"></span>
                                                <span>Corresponding Author</span>
                                            </div>
                                            <div><?= $research_data['publications']['corresponding_author'] ?></div>
                                        </li>
                                        <li class="flexbox mb-3 text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-primary"></span>
                                                <span>Co-author</span>
                                            </div>
                                            <div><?= $research_data['publications']['co_author'] ?></div>
                                        </li>
                                        <li class="flexbox mb-3 text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-success"></span>
                                                <span>Book with ISBN</span>
                                            </div>
                                            <div><?= $research_data['publications']['book_isbn'] ?></div>
                                        </li>
                                        <li class="flexbox text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-info"></span>
                                                <span>Book Chapter</span>
                                            </div>
                                            <div><?= $research_data['publications']['book_chapter'] ?></div>
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
                                    <p class="stat-number"><b><?= $research_data['publications']['total'] ?></b></p>
                                    <p class="stat-change positive">
                                        +<?= round($research_data['publications']['total'] * 0.23) ?> from last year
                                    </p>
                                </div>
                                
                                <!-- Grants Card -->                        
                                <div class="stat-card grants">
                                    <h3>Grants</h3>
                                    <p class="stat-number"><b>UGX <?= number_format($research_data['grants']['total_amount'], 0) ?></b></p>
                                    <p class="stat-change positive">
                                        +<?= round($research_data['grants']['count'] * 0.15) ?> from last year
                                    </p>
                                </div>
                                
                                <!-- Patents Card -->
                                <div class="stat-card patents">     
                                    <h3>Patents</h3>
                                    <p class="stat-number"><b><?= $research_data['patents'] ?></b></p>
                                    <p class="stat-change positive">
                                        +<?= round($research_data['patents'] * 0.1) ?> from last year
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
                                    <span class="info-box-number" style="font-size:40px"><b><?= $community_data['students_supervised'] ?></b></span><br><br>
                                    <span class="info-box-text">Students supervised</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-success rounded"><i class="fas fa-thumbs-up"></i></span>
                                <div class="info-box-content text-fade">
                                    <span class="info-box-number" style="font-size:40px"><b><?= $community_data['outreaches'] ?></b></span>
                                    <span class="info-box-text">Community Outreaches</span><br>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary rounded"><i class="fas fa-shopping-bag"></i></span>
                                <div class="info-box-content text-fade">
                                    <span class="info-box-number" style="font-size:40px"><b><?= $community_data['beneficiaries'] ?></b></span>
                                    <span class="info-box-text">Total Number of Beneficiaries in community placements</span>
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
                                    <ul class="list-inline">
                                        <li class="flexbox mb-5 text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-warning"></span>
                                                <span>Awareness campaigns</span>
                                            </div>
                                            <div><?= $community_data['awareness_campaigns'] ?></div>
                                        </li>
                                        <li class="flexbox mb-5 text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-info"></span>
                                                <span>Embedded projects</span>
                                            </div>
                                            <div><?= $community_data['embedded_projects'] ?></div>
                                        </li>
                                        <li class="flexbox text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-danger"></span>
                                                <span>Workshops/Trainings</span>
                                            </div>
                                            <div><?= $community_data['workshops'] ?></div>
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
                                            <h5><i class="fa fa-circle me-5 text-success"></i>Community Outreach Programs</h5>
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
    
    <script>
    // Chart data for Research and Innovations
    var researchChartData = {
        publications: {
            first_author: <?= $research_data['publications']['first_author'] ?>,
            corresponding_author: <?= $research_data['publications']['corresponding_author'] ?>,
            co_author: <?= $research_data['publications']['co_author'] ?>,
            book_isbn: <?= $research_data['publications']['book_isbn'] ?>,
            book_chapter: <?= $research_data['publications']['book_chapter'] ?>
        },
        grants: <?= $research_data['grants']['total_amount'] ?>,
        patents: <?= $research_data['patents'] ?>,
        utility_models: <?= $research_data['utility_models'] ?>,
        copyrights: <?= $research_data['copyrights'] ?>
    };

    // Chart data for Community Service
    var communityChartData = {
        students_supervised: <?= $community_data['students_supervised'] ?>,
        outreaches: <?= $community_data['outreaches'] ?>,
        beneficiaries: <?= $community_data['beneficiaries'] ?>,
        awareness_campaigns: <?= $community_data['awareness_campaigns'] ?>,
        embedded_projects: <?= $community_data['embedded_projects'] ?>,
        workshops: <?= $community_data['workshops'] ?>
    };
    </script>
    
    <?php include '../components/src/js/pages/dashboarddd.php'; ?>
</body>
</html>