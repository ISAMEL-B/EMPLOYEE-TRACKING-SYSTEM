<?php
session_start();
if ($_SESSION['user_role'] !== 'hrm') {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit();
}

// Database connection
require_once 'head/approve/config.php';

// Fetch academic performance data
$academic_data = [
    'phds' => 0,
    'masters' => 0,
    'bachelors' => 0,
    'trainings' => 0
];

// Get PhD count
$phd_query = "SELECT COUNT(*) as count FROM degrees WHERE degree_name LIKE '%PhD%'";
$phd_result = $conn->query($phd_query);
if ($phd_result && $row = $phd_result->fetch_assoc()) {
    $academic_data['phds'] = $row['count'];
}

// Get Masters count
$masters_query = "SELECT COUNT(*) as count FROM degrees WHERE degree_name LIKE '%Master%'";
$masters_result = $conn->query($masters_query);
if ($masters_result && $row = $masters_result->fetch_assoc()) {
    $academic_data['masters'] = $row['count'];
}

// Get Bachelors count
$bachelors_query = "SELECT COUNT(*) as count FROM degrees WHERE degree_name LIKE '%Bachelor%'";
$bachelors_result = $conn->query($bachelors_query);
if ($bachelors_result && $row = $bachelors_result->fetch_assoc()) {
    $academic_data['bachelors'] = $row['count'];
}

// Get professional trainings count (assuming this is in degrees table)
$trainings_query = "SELECT COUNT(*) as count FROM degrees WHERE degree_name LIKE '%Certificate%' OR degree_name LIKE '%Training%'";
$trainings_result = $conn->query($trainings_query);
if ($trainings_result && $row = $trainings_result->fetch_assoc()) {
    $academic_data['trainings'] = $row['count'];
}

// Fetch research and innovations data
$research_data = [
    'publications' => [
        'google' => 0,
        'research_gate' => 0,
        'academia' => 0,
        'others' => 0,
        'total' => 0
    ],
    'peer_reviewed' => 0,
    'citations' => 0,
    'first_author' => 0,
    'must_repository' => 0,
    'co_authored' => 0
];

// Get publications by platform (simplified - you may need to adjust based on actual data structure)
$publications_query = "SELECT COUNT(*) as total FROM publications";
$publications_result = $conn->query($publications_query);
if ($publications_result && $row = $publications_result->fetch_assoc()) {
    $research_data['publications']['total'] = $row['total'];
    // Distribute across platforms (adjust based on your actual data)
    $research_data['publications']['google'] = round($row['total'] * 0.5);
    $research_data['publications']['research_gate'] = round($row['total'] * 0.3);
    $research_data['publications']['academia'] = round($row['total'] * 0.15);
    $research_data['publications']['others'] = $row['total'] - ($research_data['publications']['google'] + $research_data['publications']['research_gate'] + $research_data['publications']['academia']);
}

// Get peer-reviewed publications count
$peer_reviewed_query = "SELECT COUNT(*) as count FROM publications WHERE publication_type = 'Journal Article'";
$peer_reviewed_result = $conn->query($peer_reviewed_query);
if ($peer_reviewed_result && $row = $peer_reviewed_result->fetch_assoc()) {
    $research_data['peer_reviewed'] = $row['count'];
}

// Get citations count (assuming this is stored somewhere)
$citations_query = "SELECT SUM(publication_id) as total FROM publications"; // Adjust based on your schema
$citations_result = $conn->query($citations_query);
if ($citations_result && $row = $citations_result->fetch_assoc()) {
    $research_data['citations'] = $row['total'] ? $row['total'] : 0;
}

// Get first-author publications
$first_author_query = "SELECT COUNT(*) as count FROM publications WHERE role LIKE '%Author%'";
$first_author_result = $conn->query($first_author_query);
if ($first_author_result && $row = $first_author_result->fetch_assoc()) {
    $research_data['first_author'] = $row['count'];
}

// Get co-authored publications
$co_authored_query = "SELECT COUNT(*) as count FROM publications WHERE role LIKE '%Co-Author%'";
$co_authored_result = $conn->query($co_authored_query);
if ($co_authored_result && $row = $co_authored_result->fetch_assoc()) {
    $research_data['co_authored'] = $row['count'];
}

// MUST repository count (assuming this is stored)
$repository_query = "SELECT COUNT(*) as count FROM publications WHERE source = 'MUST Repository'"; // Adjust based on your schema
$repository_result = $conn->query($repository_query);
if ($repository_result && $row = $repository_result->fetch_assoc()) {
    $research_data['must_repository'] = $row['count'] * 100; // Multiply for demonstration
}

// Fetch community service data
$community_data = [
    'outreach_programs' => 0,
    'clinical_practices' => 0,
    'student_supervisions' => 0,
    'awareness_campaigns' => 0,
    'embedded_projects' => 0,
    'workshops' => 0
];

// Get community service counts
$community_query = "SELECT COUNT(*) as total FROM communityservice";
$community_result = $conn->query($community_query);
if ($community_result && $row = $community_result->fetch_assoc()) {
    $community_data['outreach_programs'] = $row['total'];
    // Distribute across types
    $community_data['awareness_campaigns'] = round($row['total'] * 0.5);
    $community_data['embedded_projects'] = round($row['total'] * 0.3);
    $community_data['workshops'] = round($row['total'] * 0.2);
}

// Get clinical practices count (assuming this is in communityservice table)
$clinical_query = "SELECT COUNT(*) as count FROM communityservice WHERE description LIKE '%Clinical%'";
$clinical_result = $conn->query($clinical_query);
if ($clinical_result && $row = $clinical_result->fetch_assoc()) {
    $community_data['clinical_practices'] = $row['count'];
}

// Get student supervisions count
$supervision_query = "SELECT COUNT(*) as count FROM supervision";
$supervision_result = $conn->query($supervision_query);
if ($supervision_result && $row = $supervision_result->fetch_assoc()) {
    $community_data['student_supervisions'] = $row['count'];
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

    <!-- Font Awesome -->
    <link rel="stylesheet" href="../components/src/fontawesome/css/all.min.css">
    <!-- Custom Style-->
    <link rel="stylesheet" href="../components/src/css/style.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../components/bootstrap/css/bootstrap.min.css">
    <!-- Morris Charts -->
    <link rel="stylesheet" href="../components/src/css/morris.css">

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

                    <div class="row">
                        <!-- PhD's Info Box -->
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-info rounded"><i class="fa fa-id-card"></i></span>
                                <div class="info-box-content text-fade">
                                    <span class="info-box-number" style="font-size:40px"><b><?php echo $academic_data['phds']; ?></b></span><br><br>
                                    <span class="info-box-text">Phd's</span>
                                </div>
                            </div>
                        </div>

                        <!-- Masters Degrees Info Box -->
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-success rounded"><i class="fas fa-thumbs-up"></i></span>
                                <div class="info-box-content text-fade">
                                    <span class="info-box-number" style="font-size:40px"><b><?php echo $academic_data['masters']; ?></b></span>
                                    <span class="info-box-text">Masters Degrees</span><br>
                                </div>
                            </div>
                        </div>

                        <!-- Bachelor's Honors Info Box -->
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary rounded"><i class="fas fa-shopping-bag"></i></span>
                                <div class="info-box-content text-fade">
                                    <span class="info-box-number" style="font-size:40px"><b><?php echo $academic_data['bachelors']; ?></b></span>
                                    <span class="info-box-text">Bachelor's Honors</span>
                                </div>
                            </div>
                        </div>

                        <!-- Professional Trainings Info Box -->
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger rounded"><i class="fa fa-id-card"></i></span>
                                <div class="info-box-content text-fade">
                                    <span class="info-box-number" style="font-size:40px"><b><?php echo $academic_data['trainings']; ?></b></span>
                                    <span class="info-box-text">Professional Trainings</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FIRST PART OF ACADEMIC PERFORMANCE -->
                    <div class="row">
                        <div class="col-xl-12 col-12">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title"><b>ACADEMIC PERFORMANCE OVERVIEW</b></h3>
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
                                    <div id="academic-performance-chart" style="height: 245px;"></div>
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
                                        <div class="chart" id="publications-platform-chart" style="height: 305px;"></div>
                                    </div>
                                    <ul class="list-inline">
                                        <li class="flexbox mb-5 text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-danger"></span>
                                                <span>Google</span>
                                            </div>
                                            <div><?= $research_data['publications']['google'] ?></div>
                                        </li>
                                        <li class="flexbox mb-5 text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-warning"></span>
                                                <span>Research Gate</span>
                                            </div>
                                            <div><?= $research_data['publications']['research_gate'] ?></div>
                                        </li>
                                        <li class="flexbox text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-primary"></span>
                                                <span>Academia</span>
                                            </div>
                                            <div><?= $research_data['publications']['academia'] ?></div>
                                        </li>
                                        <li class="flexbox text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-success"></span>
                                                <span>Others</span>
                                            </div>
                                            <div><?= $research_data['publications']['others'] ?></div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8 col-12">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title"><b>PUBLICATIONS ANALYSIS</b></h3>
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
                                        <div class="chart" id="publications-analysis-chart" style="height: 233px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-xl-4">
                            <div class="box box-body bg-primary">
                                <div class="flexbox">
                                    <div id="first-author-chart"></div>
                                    <div class="text-end">
                                        <span style="font-size:40px;"><b><?= $research_data['first_author'] ?></b></span><br>
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
                                    <div id="repository-chart"></div>
                                    <div class="text-end">
                                        <span style="font-size:40px;"><b><?= $research_data['must_repository'] ?></b></span><br><br>
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
                                    <div id="co-authored-chart"></div>
                                    <div class="text-end">
                                        <span style="font-size:40px;"><b><?= $research_data['co_authored'] ?></b></span><br><br>
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
                                    <span class="info-box-number" style="font-size:40px"><b><?= $community_data['outreach_programs'] ?></b></span><br><br>
                                    <span class="info-box-text">Community Outreach Programs</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-success rounded"><i class="fas fa-thumbs-up"></i></span>
                                <div class="info-box-content text-fade">
                                    <span class="info-box-number" style="font-size:40px"><b><?= $community_data['clinical_practices'] ?></b></span>
                                    <span class="info-box-text">Clinical Practices</span><br>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary rounded"><i class="fas fa-shopping-bag"></i></span>
                                <div class="info-box-content text-fade">
                                    <span class="info-box-number" style="font-size:40px"><b><?= $community_data['student_supervisions'] ?></b></span>
                                    <span class="info-box-text">Supervisions of students in community placements</span>
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
                                        <div class="chart" id="community-outreach-chart" style="height: 305px;"></div>
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
                                    <h3 class="box-title"><b>COMMUNITY SERVICE ACTIVITIES</b></h3>
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
                                        <div class="chart" id="community-service-chart" style="height: 233px;"></div>
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
    <!-- Morris Charts -->
    <script src="../components/src/js/raphael.min.js"></script>
    <script src="../components/src/js/morris.min.js"></script>

    <script>
        // Pass PHP data to JavaScript for charts
        var academicData = {
            phds: <?= $academic_data['phds'] ?>,
            masters: <?= $academic_data['masters'] ?>,
            bachelors: <?= $academic_data['bachelors'] ?>,
            trainings: <?= $academic_data['trainings'] ?>
        };

        var researchData = {
            publications: {
                google: <?= $research_data['publications']['google'] ?>,
                research_gate: <?= $research_data['publications']['research_gate'] ?>,
                academia: <?= $research_data['publications']['academia'] ?>,
                others: <?= $research_data['publications']['others'] ?>
            },
            peer_reviewed: <?= $research_data['peer_reviewed'] ?>,
            citations: <?= $research_data['citations'] ?>,
            first_author: <?= $research_data['first_author'] ?>,
            must_repository: <?= $research_data['must_repository'] ?>,
            co_authored: <?= $research_data['co_authored'] ?>
        };

        var communityData = {
            outreach_programs: <?= $community_data['outreach_programs'] ?>,
            clinical_practices: <?= $community_data['clinical_practices'] ?>,
            student_supervisions: <?= $community_data['student_supervisions'] ?>,
            awareness_campaigns: <?= $community_data['awareness_campaigns'] ?>,
            embedded_projects: <?= $community_data['embedded_projects'] ?>,
            workshops: <?= $community_data['workshops'] ?>
        };

        // Initialize charts when the page loads
        $(document).ready(function() {
            // Academic Performance Chart
            Morris.Area({
                element: 'academic-performance-chart',
                data: [{
                        year: '2020',
                        phds: academicData.phds * 0.2,
                        masters: academicData.masters * 0.2,
                        bachelors: academicData.bachelors * 0.2,
                        trainings: academicData.trainings * 0.2
                    },
                    {
                        year: '2021',
                        phds: academicData.phds * 0.5,
                        masters: academicData.masters * 0.5,
                        bachelors: academicData.bachelors * 0.5,
                        trainings: academicData.trainings * 0.5
                    },
                    {
                        year: '2022',
                        phds: academicData.phds * 0.8,
                        masters: academicData.masters * 0.8,
                        bachelors: academicData.bachelors * 0.8,
                        trainings: academicData.trainings * 0.8
                    },
                    {
                        year: '2023',
                        phds: academicData.phds,
                        masters: academicData.masters,
                        bachelors: academicData.bachelors,
                        trainings: academicData.trainings
                    }
                ],
                xkey: 'year',
                ykeys: ['phds', 'masters', 'bachelors', 'trainings'],
                labels: ['PhDs', 'Masters', 'Bachelors', 'Trainings'],
                lineColors: ['#17a2b8', '#6c757d', '#007bff', '#dc3545'],
                hideHover: 'auto',
                resize: true
            });

            // Publications by Platform Chart
            Morris.Donut({
                element: 'publications-platform-chart',
                data: [{
                        label: "Google",
                        value: researchData.publications.google
                    },
                    {
                        label: "Research Gate",
                        value: researchData.publications.research_gate
                    },
                    {
                        label: "Academia",
                        value: researchData.publications.academia
                    },
                    {
                        label: "Others",
                        value: researchData.publications.others
                    }
                ],
                colors: ['#dc3545', '#ffc107', '#007bff', '#28a745'],
                resize: true
            });

            // Publications Analysis Chart
            Morris.Bar({
                element: 'publications-analysis-chart',
                data: [{
                        y: 'Peer Reviewed',
                        a: researchData.peer_reviewed
                    },
                    {
                        y: 'Citations',
                        a: researchData.citations
                    }
                ],
                xkey: 'y',
                ykeys: ['a'],
                labels: ['Count'],
                barColors: ['#28a745', '#007bff'],
                hideHover: 'auto',
                resize: true
            });

            // First Author Chart
            Morris.Line({
                element: 'first-author-chart',
                data: [{
                        y: '2020',
                        value: researchData.first_author * 0.2
                    },
                    {
                        y: '2021',
                        value: researchData.first_author * 0.5
                    },
                    {
                        y: '2022',
                        value: researchData.first_author * 0.8
                    },
                    {
                        y: '2023',
                        value: researchData.first_author
                    }
                ],
                xkey: 'y',
                ykeys: ['value'],
                labels: ['First Author'],
                lineColors: ['#ffffff'],
                gridTextColor: '#ffffff',
                grid: false,
                hideHover: 'auto',
                resize: true
            });

            // Repository Chart
            Morris.Bar({
                element: 'repository-chart',
                data: [{
                        y: '2020',
                        value: researchData.must_repository * 0.2
                    },
                    {
                        y: '2021',
                        value: researchData.must_repository * 0.5
                    },
                    {
                        y: '2022',
                        value: researchData.must_repository * 0.8
                    },
                    {
                        y: '2023',
                        value: researchData.must_repository
                    }
                ],
                xkey: 'y',
                ykeys: ['value'],
                labels: ['Repository'],
                barColors: ['#ffffff'],
                gridTextColor: '#ffffff',
                grid: false,
                hideHover: 'auto',
                resize: true
            });

            // Co-Authored Chart
            Morris.Line({
                element: 'co-authored-chart',
                data: [{
                        y: '2020',
                        value: researchData.co_authored * 0.2
                    },
                    {
                        y: '2021',
                        value: researchData.co_authored * 0.5
                    },
                    {
                        y: '2022',
                        value: researchData.co_authored * 0.8
                    },
                    {
                        y: '2023',
                        value: researchData.co_authored
                    }
                ],
                xkey: 'y',
                ykeys: ['value'],
                labels: ['Co-Authored'],
                lineColors: ['#ffffff'],
                gridTextColor: '#ffffff',
                grid: false,
                hideHover: 'auto',
                resize: true
            });

            // Community Outreach Chart
            Morris.Donut({
                element: 'community-outreach-chart',
                data: [{
                        label: "Awareness Campaigns",
                        value: communityData.awareness_campaigns
                    },
                    {
                        label: "Embedded Projects",
                        value: communityData.embedded_projects
                    },
                    {
                        label: "Workshops/Trainings",
                        value: communityData.workshops
                    }
                ],
                colors: ['#ffc107', '#17a2b8', '#dc3545'],
                resize: true
            });

            // Community Service Chart
            Morris.Bar({
                element: 'community-service-chart',
                data: [{
                        y: 'Supervisions',
                        a: communityData.student_supervisions
                    },
                    {
                        y: 'Outreach',
                        a: communityData.outreach_programs
                    },
                    {
                        y: 'Clinical',
                        a: communityData.clinical_practices
                    }
                ],
                xkey: 'y',
                ykeys: ['a'],
                labels: ['Count'],
                barColors: ['#007bff', '#28a745', '#dc3545'],
                hideHover: 'auto',
                resize: true
            });
        });
    </script>
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
</body>

</html>