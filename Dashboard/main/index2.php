<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MUST HRM - Faculty Performance Scorecard</title>
   <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="../components/bootstrap/css/bootstrap.min.css">
    <script src="../components/Chart.js/dist/Chart.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        :root {
            --must-green: #006633;
            --must-yellow: #FFCC00;
            --must-blue: #003366;
            --must-light-green: #e6f2ec;
            --must-light-yellow: #fff9e6;
            --must-light-blue: #e6ecf2;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-content {
            margin-left: 250px;
            margin-top: 5%;
            padding: 20px;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            border: none;
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            font-size: 20px;
            color: var(--must-blue);
            border-radius: 10px 10px 0 0 !important;
        }

        .department-card {
            border-left: 4px solid var(--must-green);
            transition: all 0.3s ease;
        }

        .department-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--must-green);
        }

        .stat-card .stat-label {
            color: var(--must-blue);
            font-weight: 500;
        }

        .highlight-yellow {
            background-color: var(--must-light-yellow);
            border-left: 4px solid var(--must-yellow);
        }

        .highlight-blue {
            background-color: var(--must-light-blue);
            border-left: 4px solid var(--must-blue);
        }

        .dropdown-menu {
            max-height: 300px;
            overflow-y: auto;
        }

        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }

        .comparison-table th {
            background-color: var(--must-green);
            color: white;
        }

        .department-title {
            color: var(--must-green);
            font-weight: 700;
            border-bottom: 2px solid var(--must-yellow);
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .search-box {
            border: 2px solid var(--must-green);
            border-radius: 5px;
        }

        .btn-must {
            background-color: var(--must-green);
            color: white;
        }

        .btn-must:hover {
            background-color: var(--must-blue);
            color: white;
        }

        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 0.9rem;
            }

            .navbar-brand img {
                height: 25px;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Top Navigation Bar -->
    <?php include 'bars/nav_bar.php'; ?>

    <!-- Sidebar -->
    <?php include 'bars/side_bar.php'; ?>

    <!-- Main Content Area -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Page Header with Faculty Selection -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="faculty-title">Faculty Performance Scorecard</h2>
                <div class="dropdown">
                    <button class="btn btn-must dropdown-toggle" type="button" id="facultyDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Select Faculty
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="facultyDropdown">
                        <input type="text" class="form-control search-box mb-2" placeholder="Search faculties..." id="facultySearch">
                        <div id="facultyList">
                            <a class="dropdown-item" href="#" data-faculty="medicine">Faculty of Medicine</a>
                            <a class="dropdown-item" href="#" data-faculty="science">Faculty of Science</a>
                            <a class="dropdown-item" href="#" data-faculty="business">Faculty of Business & Management Sciences</a>
                            <a class="dropdown-item" href="#" data-faculty="computing">Faculty of Computing & Informatics</a>
                            <a class="dropdown-item" href="#" data-faculty="applied">Faculty of Applied Sciences & Technology</a>
                            <a class="dropdown-item" href="#" data-faculty="interdisciplinary">Faculty of Interdisciplinary Studies</a>
                            <a class="dropdown-item" href="#" data-faculty="nursing">Faculty of Nursing</a>
                            <a class="dropdown-item" href="#" data-faculty="education">Faculty of Education</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Faculty Comparison Section (Default View) -->
            <div class="row mb-4" id="comparisonSection">
                <div class="col-12">
                    <div class="card faculty-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Faculty Comparative Performance Overview</span>
                            <small class="text-muted">Last updated: Today, 10:45 AM</small>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">Select a faculty from the dropdown above to view detailed performance metrics. Below is a comparison of all faculties across key performance indicators.</p>

                            <!-- Comparison Table -->
                            <div class="table-responsive">
                                <table class="table table-hover comparison-table">
                                    <thead>
                                        <tr>
                                            <th>Faculty</th>
                                            <th>Academic Staff</th>
                                            <th>Avg. Teaching Score</th>
                                            <th>Research Output</th>
                                            <th>Grant Funding (UGX)</th>
                                            <th>Community Projects</th>
                                            <th>Student Satisfaction</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Medicine</strong></td>
                                            <td>68</td>
                                            <td>4.3</td>
                                            <td>42 publications</td>
                                            <td>1.2B</td>
                                            <td>15</td>
                                            <td>88%</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Science</strong></td>
                                            <td>52</td>
                                            <td>4.1</td>
                                            <td>38 publications</td>
                                            <td>850M</td>
                                            <td>12</td>
                                            <td>85%</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Business & Management</strong></td>
                                            <td>45</td>
                                            <td>4.4</td>
                                            <td>28 publications</td>
                                            <td>420M</td>
                                            <td>18</td>
                                            <td>91%</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Computing & Informatics</strong></td>
                                            <td>38</td>
                                            <td>4.2</td>
                                            <td>35 publications</td>
                                            <td>680M</td>
                                            <td>10</td>
                                            <td>89%</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Applied Sciences</strong></td>
                                            <td>41</td>
                                            <td>4.0</td>
                                            <td>31 publications</td>
                                            <td>540M</td>
                                            <td>14</td>
                                            <td>84%</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Interdisciplinary Studies</strong></td>
                                            <td>32</td>
                                            <td>4.1</td>
                                            <td>25 publications</td>
                                            <td>380M</td>
                                            <td>8</td>
                                            <td>86%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Comparison Charts -->
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="chart-container">
                                        <canvas id="facultySizeChart"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="chart-container">
                                        <canvas id="facultyResearchChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Faculty Detail Section (Hidden by default) -->
            <div class="row mb-4 d-none" id="detailSection">
                <div class="col-12">
                    <div class="card faculty-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span id="facultyDetailTitle">Faculty Performance Details</span>
                            <button class="btn btn-sm btn-outline-secondary" id="backToComparison">
                                <i class="bi bi-arrow-left"></i> Back to Comparison
                            </button>
                        </div>
                        <div class="card-body">
                            <!-- Key Metrics Summary -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="stat-card">
                                        <div class="stat-value" id="facultyStaff">--</div>
                                        <div class="stat-label">Academic Staff</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-card highlight-yellow">
                                        <div class="stat-value" id="facultyTeaching">--</div>
                                        <div class="stat-label">Avg. Teaching Score</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-card highlight-blue">
                                        <div class="stat-value" id="facultyResearch">--</div>
                                        <div class="stat-label">Research Output</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-card">
                                        <div class="stat-value" id="facultyGrants">--</div>
                                        <div class="stat-label">Grant Funding</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Detailed Performance Charts -->
                            <div class="row">
                                <!-- Left Column Charts -->
                                <div class="col-md-6">
                                    <!-- Academic Performance Breakdown -->
                                    <div class="card mb-4">
                                        <div class="card-header">Academic Performance Breakdown</div>
                                        <div class="card-body">
                                            <div class="chart-container">
                                                <canvas id="facultyAcademicChart"></canvas>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Community Engagement -->
                                    <div class="card">
                                        <div class="card-header">Community Engagement</div>
                                        <div class="card-body">
                                            <div class="chart-container">
                                                <canvas id="facultyCommunityChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column Charts -->
                                <div class="col-md-6">
                                    <!-- Research Output by Type -->
                                    <div class="card mb-4">
                                        <div class="card-header">Research Output by Type</div>
                                        <div class="card-body">
                                            <div class="chart-container">
                                                <canvas id="facultyResearchTypeChart"></canvas>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Grant Funding Allocation -->
                                    <div class="card">
                                        <div class="card-header">Grant Funding Allocation</div>
                                        <div class="card-body">
                                            <div class="chart-container">
                                                <canvas id="facultyGrantsChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Faculty Activity -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">Recent Faculty Achievements</div>
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush" id="facultyActivity">
                                                <!-- Activity items will be added here dynamically -->
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Key Insights and Action Items -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card highlight-yellow">
                        <div class="card-header">Key Performance Insights</div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <strong>Faculty of Medicine:</strong> Leads in research output and grant funding. Consider sharing best practices with other faculties.
                            </div>
                            <div class="alert alert-warning">
                                <strong>Faculty of Business:</strong> Highest student satisfaction but lower research output. Encourage research-teaching balance.
                            </div>
                            <div class="alert alert-success">
                                <strong>Faculty of Computing:</strong> Strong growth in both research and teaching. Model for interdisciplinary collaboration.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card highlight-blue">
                        <div class="card-header">Strategic Action Items</div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Organize research methodology workshop for Business faculty
                                    <span class="badge bg-must-green rounded-pill">High Priority</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Develop interdisciplinary research grants program
                                    <span class="badge bg-must-green rounded-pill">High Priority</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Review teaching loads in Applied Sciences
                                    <span class="badge bg-warning rounded-pill">Medium Priority</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Plan community engagement fair for all faculties
                                    <span class="badge bg-primary rounded-pill">Low Priority</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        /**
         * Faculty Performance Data
         * This object contains performance metrics for all faculties at Mbarara University
         * Data includes academic, research, and community service metrics
         */
        const faculties = {
            medicine: {
                name: "Faculty of Medicine",
                staff: 68,
                teachingScore: 4.3,
                researchOutput: 42,
                grants: "1.2B",
                communityProjects: 15,
                satisfaction: "88%",
                academicBreakdown: {
                    labels: ["Lectures", "Practical Sessions", "Clinical Teaching", "Student Supervision", "Curriculum Dev"],
                    data: [4.2, 4.5, 4.6, 4.1, 3.9]
                },
                researchTypes: {
                    labels: ["Journal Articles", "Conference Papers", "Book Chapters", "Patents", "Research Reports"],
                    data: [22, 12, 5, 2, 1]
                },
                communityEngagement: {
                    labels: ["Health Camps", "Community Training", "Policy Work", "Public Lectures", "Outreach"],
                    data: [6, 4, 3, 1, 1]
                },
                grantAllocation: {
                    labels: ["Equipment", "Personnel", "Research", "Training", "Administration"],
                    data: [450000000, 350000000, 300000000, 80000000, 20000000]
                },
                achievements: [
                    "5 new research publications in Q1 2023",
                    "Received UGX 300M grant for infectious disease research",
                    "Organized successful community health camp in Mbarara",
                    "3 faculty members promoted to Associate Professor"
                ]
            },
            science: {
                name: "Faculty of Science",
                staff: 52,
                teachingScore: 4.1,
                researchOutput: 38,
                grants: "850M",
                communityProjects: 12,
                satisfaction: "85%",
                academicBreakdown: {
                    labels: ["Lectures", "Lab Sessions", "Field Work", "Student Supervision", "Curriculum Dev"],
                    data: [4.0, 4.3, 4.2, 4.0, 3.8]
                },
                researchTypes: {
                    labels: ["Journal Articles", "Conference Papers", "Book Chapters", "Patents", "Research Reports"],
                    data: [20, 10, 4, 3, 1]
                },
                communityEngagement: {
                    labels: ["Science Outreach", "School Programs", "Policy Work", "Public Lectures", "Consulting"],
                    data: [5, 3, 2, 1, 1]
                },
                grantAllocation: {
                    labels: ["Equipment", "Personnel", "Research", "Training", "Administration"],
                    data: [300000000, 250000000, 200000000, 70000000, 30000000]
                },
                achievements: [
                    "New biochemistry lab equipment installed",
                    "2 faculty members awarded national science prizes",
                    "Partnership with local schools for science education",
                    "Published groundbreaking environmental research"
                ]
            },
            business: {
                name: "Faculty of Business & Management Sciences",
                staff: 45,
                teachingScore: 4.4,
                researchOutput: 28,
                grants: "420M",
                communityProjects: 18,
                satisfaction: "91%",
                academicBreakdown: {
                    labels: ["Lectures", "Case Studies", "Group Work", "Student Supervision", "Curriculum Dev"],
                    data: [4.5, 4.6, 4.3, 4.2, 4.0]
                },
                researchTypes: {
                    labels: ["Journal Articles", "Conference Papers", "Book Chapters", "Case Studies", "Industry Reports"],
                    data: [12, 8, 4, 3, 1]
                },
                communityEngagement: {
                    labels: ["Business Training", "Startup Support", "Policy Work", "Public Lectures", "Consulting"],
                    data: [8, 5, 3, 1, 1]
                },
                grantAllocation: {
                    labels: ["Equipment", "Personnel", "Research", "Training", "Administration"],
                    data: [100000000, 150000000, 100000000, 50000000, 20000000]
                },
                achievements: [
                    "Launched entrepreneurship incubator program",
                    "Faculty consulting generated UGX 150M in revenue",
                    "Student team won national business competition",
                    "New MBA program accredited"
                ]
            },
            computing: {
                name: "Faculty of Computing & Informatics",
                staff: 38,
                teachingScore: 4.2,
                researchOutput: 35,
                grants: "680M",
                communityProjects: 10,
                satisfaction: "89%",
                academicBreakdown: {
                    labels: ["Lectures", "Practical Sessions", "Projects", "Student Supervision", "Curriculum Dev"],
                    data: [4.1, 4.4, 4.3, 4.0, 3.9]
                },
                researchTypes: {
                    labels: ["Journal Articles", "Conference Papers", "Software", "Patents", "Technical Reports"],
                    data: [15, 12, 5, 2, 1]
                },
                communityEngagement: {
                    labels: ["Digital Literacy", "App Development", "Policy Work", "Public Lectures", "Consulting"],
                    data: [4, 3, 1, 1, 1]
                },
                grantAllocation: {
                    labels: ["Equipment", "Personnel", "Research", "Training", "Administration"],
                    data: [250000000, 200000000, 150000000, 60000000, 20000000]
                },
                achievements: [
                    "Developed health information system for regional hospitals",
                    "2 new software patents filed",
                    "Partnered with tech companies for student internships",
                    "Hosted national computing conference"
                ]
            },
            applied: {
                name: "Faculty of Applied Sciences & Technology",
                staff: 41,
                teachingScore: 4.0,
                researchOutput: 31,
                grants: "540M",
                communityProjects: 14,
                satisfaction: "84%",
                academicBreakdown: {
                    labels: ["Lectures", "Lab Work", "Field Work", "Student Supervision", "Curriculum Dev"],
                    data: [3.9, 4.2, 4.1, 3.8, 3.7]
                },
                researchTypes: {
                    labels: ["Journal Articles", "Conference Papers", "Technical Solutions", "Patents", "Research Reports"],
                    data: [14, 10, 4, 2, 1]
                },
                communityEngagement: {
                    labels: ["Tech Transfer", "Vocational Training", "Policy Work", "Public Lectures", "Consulting"],
                    data: [6, 4, 2, 1, 1]
                },
                grantAllocation: {
                    labels: ["Equipment", "Personnel", "Research", "Training", "Administration"],
                    data: [200000000, 150000000, 140000000, 40000000, 10000000]
                },
                achievements: [
                    "Developed water purification system for rural communities",
                    "3 faculty members received innovation awards",
                    "New diploma program in renewable energy launched",
                    "Secured industry partnership for student projects"
                ]
            },
            interdisciplinary: {
                name: "Faculty of Interdisciplinary Studies",
                staff: 32,
                teachingScore: 4.1,
                researchOutput: 25,
                grants: "380M",
                communityProjects: 8,
                satisfaction: "86%",
                academicBreakdown: {
                    labels: ["Lectures", "Seminars", "Field Work", "Student Supervision", "Curriculum Dev"],
                    data: [4.0, 4.3, 4.0, 3.9, 3.8]
                },
                researchTypes: {
                    labels: ["Journal Articles", "Conference Papers", "Book Chapters", "Policy Papers", "Research Reports"],
                    data: [10, 8, 4, 2, 1]
                },
                communityEngagement: {
                    labels: ["Community Dialogues", "Training", "Policy Work", "Public Lectures", "Consulting"],
                    data: [3, 2, 2, 1, 0]
                },
                grantAllocation: {
                    labels: ["Equipment", "Personnel", "Research", "Training", "Administration"],
                    data: [80000000, 150000000, 100000000, 40000000, 10000000]
                },
                achievements: [
                    "Published influential policy paper on regional development",
                    "Hosted international interdisciplinary conference",
                    "New MA program in Development Studies accredited",
                    "Faculty research featured in national media"
                ]
            },
            nursing: {
                name: "Faculty of Nursing",
                staff: 28,
                teachingScore: 4.2,
                researchOutput: 22,
                grants: "320M",
                communityProjects: 12,
                satisfaction: "87%",
                academicBreakdown: {
                    labels: ["Lectures", "Clinical Training", "Simulation", "Student Supervision", "Curriculum Dev"],
                    data: [4.1, 4.4, 4.3, 4.0, 3.9]
                },
                researchTypes: {
                    labels: ["Journal Articles", "Conference Papers", "Clinical Guides", "Policy Papers", "Research Reports"],
                    data: [10, 7, 3, 1, 1]
                },
                communityEngagement: {
                    labels: ["Health Education", "Screening Camps", "Policy Work", "Public Lectures", "Training"],
                    data: [5, 4, 2, 1, 0]
                },
                grantAllocation: {
                    labels: ["Equipment", "Personnel", "Research", "Training", "Administration"],
                    data: [70000000, 120000000, 90000000, 30000000, 10000000]
                },
                achievements: [
                    "Developed new community health nursing curriculum",
                    "2 faculty members appointed to national health committees",
                    "Research on nurse retention published in international journal",
                    "Organized regional nursing conference"
                ]
            },
            education: {
                name: "Faculty of Education",
                staff: 35,
                teachingScore: 4.0,
                researchOutput: 20,
                grants: "290M",
                communityProjects: 10,
                satisfaction: "85%",
                academicBreakdown: {
                    labels: ["Lectures", "Practicum", "Workshops", "Student Supervision", "Curriculum Dev"],
                    data: [3.9, 4.2, 4.1, 3.8, 3.7]
                },
                researchTypes: {
                    labels: ["Journal Articles", "Conference Papers", "Teaching Materials", "Policy Papers", "Research Reports"],
                    data: [8, 7, 3, 1, 1]
                },
                communityEngagement: {
                    labels: ["Teacher Training", "School Support", "Policy Work", "Public Lectures", "Consulting"],
                    data: [4, 3, 2, 1, 0]
                },
                grantAllocation: {
                    labels: ["Equipment", "Personnel", "Research", "Training", "Administration"],
                    data: [50000000, 100000000, 100000000, 30000000, 10000000]
                },
                achievements: [
                    "Launched teacher professional development program",
                    "Developed new science teaching kits for schools",
                    "Faculty research influences national education policy",
                    "Partnership with 10 regional schools established"
                ]
            }
        };

        /**
         * Initialize Comparison Charts
         * These charts show faculty performance in comparison to each other
         */
        const facultySizeCtx = document.getElementById('facultySizeChart').getContext('2d');
        const facultySizeChart = new Chart(facultySizeCtx, {
            type: 'bar',
            data: {
                labels: ['Medicine', 'Science', 'Business', 'Computing', 'Applied', 'Interdisciplinary'],
                datasets: [{
                    label: 'Academic Staff Count',
                    data: [68, 52, 45, 38, 41, 32],
                    backgroundColor: [
                        'rgba(0, 102, 51, 0.7)',
                        'rgba(0, 51, 102, 0.7)',
                        'rgba(255, 204, 0, 0.7)',
                        'rgba(0, 102, 51, 0.5)',
                        'rgba(0, 51, 102, 0.5)',
                        'rgba(255, 204, 0, 0.5)'
                    ],
                    borderColor: [
                        'rgba(0, 102, 51, 1)',
                        'rgba(0, 51, 102, 1)',
                        'rgba(255, 204, 0, 1)',
                        'rgba(0, 102, 51, 1)',
                        'rgba(0, 51, 102, 1)',
                        'rgba(255, 204, 0, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Faculty Size Comparison',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Academic Staff'
                        }
                    }
                }
            }
        });

        const facultyResearchCtx = document.getElementById('facultyResearchChart').getContext('2d');
        const facultyResearchChart = new Chart(facultyResearchCtx, {
            type: 'radar',
            data: {
                labels: ['Publications', 'Grants', 'Community Impact', 'Teaching Quality', 'Student Satisfaction'],
                datasets: [{
                        label: 'Medicine',
                        data: [42, 95, 85, 86, 88],
                        backgroundColor: 'rgba(0, 102, 51, 0.2)',
                        borderColor: 'rgba(0, 102, 51, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(0, 102, 51, 1)'
                    },
                    {
                        label: 'Business',
                        data: [28, 60, 90, 88, 91],
                        backgroundColor: 'rgba(255, 204,0, 0.2)',
                        borderColor: 'rgba(255, 204, 0, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(255, 204, 0, 1)'
                    },
                    {
                        label: 'Computing',
                        data: [35, 75, 70, 84, 89],
                        backgroundColor: 'rgba(0, 51, 102, 0.2)',
                        borderColor: 'rgba(0, 51, 102, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(0, 51, 102, 1)'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Faculty Performance Metrics',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    r: {
                        angleLines: {
                            display: true
                        },
                        suggestedMin: 0,
                        suggestedMax: 100
                    }
                }
            }
        });
        /**
         * Department Detail Charts (initialized when faculty is selected)
         * These will show detailed metrics for individual faculties
         */
        let facultyAcademicChart, facultyResearchTypeChart, facultyCommunityChart, facultyGrantsChart;

        /**
         * Search functionality for faculty dropdown
         */
        document.getElementById('facultySearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const items = document.querySelectorAll('#facultyList .dropdown-item');

            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        /**
         * Faculty selection handler
         */
        document.querySelectorAll('#facultyList .dropdown-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const facultyId = this.getAttribute('data-faculty');
                showFacultyDetails(facultyId);
            });
        });

        /**
         * Back to comparison button handler
         */
        document.getElementById('backToComparison').addEventListener('click', function() {
            document.getElementById('comparisonSection').classList.remove('d-none');
            document.getElementById('detailSection').classList.add('d-none');
        });

        /**
         * Show detailed faculty performance metrics
         * @param {string} facultyId - The ID of the faculty to show details for
         */
        function showFacultyDetails(facultyId) {
            const faculty = faculties[facultyId];

            // Update basic info
            document.getElementById('facultyDetailTitle').textContent = faculty.name + " Performance Scorecard";
            document.getElementById('facultyStaff').textContent = faculty.staff;
            document.getElementById('facultyTeaching').textContent = faculty.teachingScore;
            document.getElementById('facultyResearch').textContent = faculty.researchOutput + " outputs";
            document.getElementById('facultyGrants').textContent = "UGX " + faculty.grants;

            // Update activity list
            const activityList = document.getElementById('facultyActivity');
            activityList.innerHTML = '';
            faculty.achievements.forEach(activity => {
                const li = document.createElement('li');
                li.className = 'list-group-item';
                li.innerHTML = `<i class="bi bi-check-circle-fill text-success me-2"></i>${activity}`;
                activityList.appendChild(li);
            });

            // Initialize or update charts
            updateFacultyCharts(faculty);

            // Show detail section and hide comparison
            document.getElementById('comparisonSection').classList.add('d-none');
            document.getElementById('detailSection').classList.remove('d-none');
        }

        /**
         * Update faculty detail charts with current faculty data
         * @param {object} faculty - The faculty data object
         */
        function updateFacultyCharts(faculty) {
            // Academic Performance Breakdown chart
            if (facultyAcademicChart) {
                facultyAcademicChart.data.labels = faculty.academicBreakdown.labels;
                facultyAcademicChart.data.datasets[0].data = faculty.academicBreakdown.data;
                facultyAcademicChart.update();
            } else {
                const facultyAcademicCtx = document.getElementById('facultyAcademicChart').getContext('2d');
                facultyAcademicChart = new Chart(facultyAcademicCtx, {
                    type: 'bar',
                    data: {
                        labels: faculty.academicBreakdown.labels,
                        datasets: [{
                            label: 'Performance Score (1-5)',
                            data: faculty.academicBreakdown.data,
                            backgroundColor: 'rgba(0, 102, 51, 0.7)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Teaching Performance by Activity',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            },
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 5,
                                title: {
                                    display: true,
                                    text: 'Performance Score'
                                }
                            }
                        }
                    }
                });
            }

            // Research Type Breakdown chart
            if (facultyResearchTypeChart) {
                facultyResearchTypeChart.data.labels = faculty.researchTypes.labels;
                facultyResearchTypeChart.data.datasets[0].data = faculty.researchTypes.data;
                facultyResearchTypeChart.update();
            } else {
                const facultyResearchTypeCtx = document.getElementById('facultyResearchTypeChart').getContext('2d');
                facultyResearchTypeChart = new Chart(facultyResearchTypeCtx, {
                    type: 'doughnut',
                    data: {
                        labels: faculty.researchTypes.labels,
                        datasets: [{
                            data: faculty.researchTypes.data,
                            backgroundColor: [
                                'rgba(0, 102, 51, 0.7)',
                                'rgba(0, 51, 102, 0.7)',
                                'rgba(255, 204, 0, 0.7)',
                                'rgba(0, 102, 51, 0.5)',
                                'rgba(0, 51, 102, 0.5)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Research Output by Type',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            // Community Engagement chart
            if (facultyCommunityChart) {
                facultyCommunityChart.data.labels = faculty.communityEngagement.labels;
                facultyCommunityChart.data.datasets[0].data = faculty.communityEngagement.data;
                facultyCommunityChart.update();
            } else {
                const facultyCommunityCtx = document.getElementById('facultyCommunityChart').getContext('2d');
                facultyCommunityChart = new Chart(facultyCommunityCtx, {
                    type: 'polarArea',
                    data: {
                        labels: faculty.communityEngagement.labels,
                        datasets: [{
                            data: faculty.communityEngagement.data,
                            backgroundColor: [
                                'rgba(0, 102, 51, 0.7)',
                                'rgba(0, 51, 102, 0.7)',
                                'rgba(255, 204, 0, 0.7)',
                                'rgba(0, 102, 51, 0.5)',
                                'rgba(0, 51, 102, 0.5)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Community Engagement Activities',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            // Grant Allocation chart
            if (facultyGrantsChart) {
                facultyGrantsChart.data.labels = faculty.grantAllocation.labels;
                facultyGrantsChart.data.datasets[0].data = faculty.grantAllocation.data;
                facultyGrantsChart.update();
            } else {
                const facultyGrantsCtx = document.getElementById('facultyGrantsChart').getContext('2d');
                facultyGrantsChart = new Chart(facultyGrantsCtx, {
                    type: 'pie',
                    data: {
                        labels: faculty.grantAllocation.labels,
                        datasets: [{
                            data: faculty.grantAllocation.data,
                            backgroundColor: [
                                'rgba(0, 102, 51, 0.7)',
                                'rgba(0, 51, 102, 0.7)',
                                'rgba(255, 204, 0, 0.7)',
                                'rgba(0, 102, 51, 0.5)',
                                'rgba(0, 51, 102, 0.5)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Grant Funding Allocation',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
        }

        // Initialize the dashboard with Medicine faculty data by default
        document.addEventListener('DOMContentLoaded', function() {
            showFacultyDetails('medicine');
        });
    </script>
</body>

</html>