<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University - Faculty Performance Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --accent-color: #e74c3c;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: #333;
            padding: 20px;
        }

        .dashboard {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }

        .header h1 {
            color: var(--dark-color);
        }

        .faculty-selector {
            padding: 8px 15px;
            border-radius: var(--border-radius);
            border: 1px solid #ddd;
            background-color: white;
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
        }

        .card h3 {
            color: var(--dark-color);
            margin-bottom: 10px;
            font-size: 16px;
        }

        .card .value {
            font-size: 28px;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .card .subtext {
            color: #777;
            font-size: 14px;
        }

        .card.trend-up .value {
            color: var(--secondary-color);
        }

        .card.trend-down .value {
            color: var(--accent-color);
        }

        .main-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .large-card {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
            height: 400px;
        }

        .chart-container {
            position: relative;
            height: 350px;
            width: 100%;
        }

        .section-title {
            margin-bottom: 15px;
            color: var(--dark-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .department-comparison {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
            margin-bottom: 20px;
        }

        .department-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .department-table th, .department-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .department-table th {
            background-color: #f8f9fa;
            color: var(--dark-color);
        }

        .department-table tr:hover {
            background-color: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-primary {
            background-color: #d4edff;
            color: var(--primary-color);
        }

        .badge-success {
            background-color: #d4f5e0;
            color: var(--secondary-color);
        }

        .badge-warning {
            background-color: #fff3cd;
            color: #ffc107;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: var(--accent-color);
        }

        .footer-notes {
            font-size: 12px;
            color: #777;
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .tab-container {
            margin-bottom: 20px;
        }

        .tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            overflow-x: auto;
            padding-bottom: 2px;
        }

        .tab {
            padding: 10px 15px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .tab.active {
            border-bottom: 3px solid var(--primary-color);
            color: var(--primary-color);
            font-weight: bold;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .view-details {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
            padding: 4px 8px;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .view-details:hover {
            background-color: rgba(52, 152, 219, 0.1);
            text-decoration: underline;
        }

        .department-table th:nth-child(7),
        .department-table td:nth-child(7) {
            text-align: center;
        }

        @media (max-width: 1200px) {
            .summary-cards {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .main-content {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .summary-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="header">
            <h1>Faculty Performance Dashboard</h1>
            <select class="faculty-selector">
                <option>Faculty of Medicine</option>
                <option>Faculty of Engineering</option>
                <option>Faculty of Science</option>
                <option>Faculty of Arts</option>
            </select>
        </div>

        <div class="summary-cards">
            <div class="card">
                <h3>Total Staff</h3>
                <div class="value">142</div>
                <div class="subtext">32 Professors, 65 Lecturers</div>
            </div>
            <div class="card trend-up">
                <h3>Total publications</h3>
                <div class="value">34</div>
            </div>
            <div class="card trend-up">
                <h3>Research Grants</h3>
                <div class="value">UGX 3.2B</div>
            </div>
            <div class="card trend-down">
                <h3>Total Innovations</h3>
                <div class="value">43</div>
            </div>
        </div>

        <div class="tab-container">
            <div class="tabs">
                <div class="tab active" onclick="switchTab('overview')">Overview</div>
                <div class="tab" onclick="switchTab('academic')">Academic Performance</div>
                <div class="tab" onclick="switchTab('publications')">Publications</div>
                <div class="tab" onclick="switchTab('research')">Research & Innovations</div>
                <div class="tab" onclick="switchTab('community')">Community Service</div>
            </div>
        </div>

        <!-- Overview Tab Content -->
        <div class="tab-content active" id="overview">
            <div class="main-content">
                <div class="large-card">
                    <div class="section-title">
                        <h2>Faculty Overview</h2>
                    </div>
                    <div class="chart-container">
                        <canvas id="facultyOverviewChart"></canvas>
                    </div>
                </div>
                <div class="large-card">
                    <div class="section-title">
                        <h2>Key Metrics Summary</h2>
                    </div>
                    <div class="chart-container">
                        <div style="width: 100%; max-width: 700px; overflow-x: auto;">
                            <canvas id="keyMetricsChart" width="1000" height="400"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Performance Tab Content -->
        <div class="tab-content" id="academic">
            <div class="main-content">
                <div class="large-card">
                    <div class="section-title">
                        <h2>Qualifications Distribution</h2>
                    </div>
                    <div class="chart-container">
                        <canvas id="qualificationsChart"></canvas>
                    </div>
                </div>
                <div class="large-card">
                    <div class="section-title">
                        <h2>Teaching Experience</h2>
                    </div>
                    <div class="chart-container">
                        <canvas id="teachingExpChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Publications Tab Content -->
        <div class="tab-content" id="publications">
            <div class="main-content">
                <div class="large-card">
                    <div class="section-title">
                        <h2>Publications vs Citations</h2>
                    </div>
                    <div class="chart-container">
                        <canvas id="pubCitationChart"></canvas>
                    </div>
                </div>
                <div class="large-card">
                    <div class="section-title">
                        <h2>Publication Types</h2>
                    </div>
                    <div class="chart-container">
                        <canvas id="publicationTypesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Research & Innovations Tab Content -->
        <div class="tab-content" id="research">
            <div class="main-content">
                <div class="large-card">
                    <div class="section-title">
                        <h2>Research Grants</h2>
                    </div>
                    <div class="chart-container">
                        <canvas id="grantTrendChart"></canvas>
                    </div>
                </div>
                <div class="large-card">
                    <div class="section-title">
                        <h2>Innovations</h2>
                    </div>
                    <div class="chart-container">
                        <canvas id="innovationsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Community Service Tab Content -->
        <div class="tab-content" id="community">
            <div class="main-content">
                <div class="large-card">
                    <div class="section-title">
                        <h2>Community Engagement</h2>
                    </div>
                    <div class="chart-container">
                        <canvas id="communityEngagementChart"></canvas>
                    </div>
                </div>
                <div class="large-card">
                    <div class="section-title">
                        <h2>University Service</h2>
                    </div>
                    <div class="chart-container">
                        <canvas id="universityServiceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Comparison Section (Visible on all tabs) -->
        <div class="department-comparison">
            <div class="section-title">
                <h2>Department Metrics</h2>
            </div>
            <table class="department-table">
                <thead>
                    <tr>
                        <th>Department</th>
                        <th>Avg. Score</th>
                        <th>Publications</th>
                        <th>Grants (UGX)</th>
                        <th>Innovations</th>
                        
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Medicine</td>
                        <td>8.2</td>
                        <td>12.4</td>
                        <td>1.8B</td>
                        <td>24</td>
                        
                        <td><a href="#" class="view-details">View Details</a></td>
                    </tr>
                    <tr>
                        <td>Surgery</td>
                        <td>7.9</td>
                        <td>10.1</td>
                        <td>1.2B</td>
                        <td>18</td>
                        <td><a href="#" class="view-details">View Details</a></td>
                    </tr>
                    <tr>
                        <td>Pediatrics</td>
                        <td>7.5</td>
                        <td>8.7</td>
                        <td>800M</td>
                        <td>15</td>
                        <td><a href="#" class="view-details">View Details</a></td>
                    </tr>
                    <tr>
                        <td>Pathology</td>
                        <td>6.8</td>
                        <td>7.2</td>
                        <td>500M</td>
                        <td>10</td>
                        <td><a href="#" class="view-details">View Details</a></td>
                    </tr>
                    <tr>
                        <td>Public Health</td>
                        <td>6.2</td>
                        <td>5.8</td>
                        <td>300M</td>
                        <td>8</td>
                        <td><a href="#" class="view-details">View Details</a></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="footer-notes">
            Data updated: June 2023 | Next review: December 2023
        </div>
    </div>

    <script>
        // Tab switching functionality
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            document.getElementById(tabId).classList.add('active');
            document.querySelector(`.tab[onclick="switchTab('${tabId}')"]`).classList.add('active');
        }

        // Charts initialization
        document.addEventListener('DOMContentLoaded', function() {
            // Faculty Overview Chart
            const overviewCtx = document.getElementById('facultyOverviewChart').getContext('2d');
            new Chart(overviewCtx, {
                type: 'bar',
                data: {
                    labels: ['Medicine', 'Surgery', 'Pediatrics', 'Pathology', 'Public Health'],
                    datasets: [{
                        label: 'Overall Performance',
                        data: [8.2, 7.9, 7.5, 6.8, 6.2],
                        backgroundColor: 'rgba(52, 152, 219, 0.7)'
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 10
                        }
                    }
                }
            });

            // Key Metrics Chart
            const keyMetricsCtx = document.getElementById('keyMetricsChart').getContext('2d');
            new Chart(keyMetricsCtx, {
                type: 'bar',
                data: {
                    labels: ['Publications', 'Grants', 'Postgraduate Supervisions', 'Innovations'],
                    datasets: [
                        {
                            label: 'Medicine',
                            data: [9.2, 8.8, 8.5, 7.2],
                            backgroundColor: 'rgba(52, 152, 219, 0.7)'
                        },
                        {
                            label: 'Engineering',
                            data: [8.1, 9.2, 7.8, 8.5],
                            backgroundColor: 'rgba(46, 204, 113, 0.7)'
                        },
                        {
                            label: 'Science',
                            data: [7.5, 7.8, 8.2, 6.9],
                            backgroundColor: 'rgba(155, 89, 182, 0.7)'
                        },
                        {
                            label: 'Arts',
                            data: [6.8, 6.2, 7.5, 5.8],
                            backgroundColor: 'rgba(241, 196, 15, 0.7)'
                        },
                        {
                            label: 'Business',
                            data: [7.2, 8.1, 6.9, 7.5],
                            backgroundColor: 'rgba(231, 76, 60, 0.7)'
                        },
                        {
                            label: 'Law',
                            data: [6.5, 5.8, 7.1, 6.2],
                            backgroundColor: 'rgba(26, 188, 156, 0.7)'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    // maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.raw.toFixed(1);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Competencies'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            suggestedMax: 10,
                            ticks: {
                                stepSize: 2
                            },
                            title: {
                                display: true,
                                text: 'Scores'
                            }
                        }
                    }
                }
            });


            // Qualifications Chart
            const qualificationsCtx = document.getElementById('qualificationsChart').getContext('2d');
            new Chart(qualificationsCtx, {
                type: 'pie',
                data: {
                    labels: ['PhD', 'Masters', 'Bachelor', 'Other'],
                    datasets: [{
                        data: [45, 35, 15, 5],
                        backgroundColor: [
                            'rgba(52, 152, 219, 0.7)',
                            'rgba(46, 204, 113, 0.7)',
                            'rgba(155, 89, 182, 0.7)',
                            'rgba(241, 196, 15, 0.7)'
                        ]
                    }]
                }
            });

            // Teaching Experience Chart
            const teachingExpCtx = document.getElementById('teachingExpChart').getContext('2d');
            new Chart(teachingExpCtx, {
                type: 'line',
                data: {
                    labels: ['0-5 yrs', '6-10 yrs', '11-15 yrs', '16-20 yrs', '20+ yrs'],
                    datasets: [{
                        label: 'Number of Staff',
                        data: [25, 40, 35, 20, 15],
                        borderColor: 'rgba(52, 152, 219, 1)',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Publications vs Citations Chart
            const pubCiteCtx = document.getElementById('pubCitationChart').getContext('2d');
            new Chart(pubCiteCtx, {
                type: 'scatter',
                data: {
                    datasets: [
                        {
                            label: 'Medicine',
                            data: [
                                {x: 12, y: 45},
                                {x: 8, y: 30},
                                {x: 15, y: 60},
                                {x: 6, y: 18},
                                {x: 10, y: 35}
                            ],
                            backgroundColor: 'rgba(52, 152, 219, 0.7)'
                        },
                        {
                            label: 'Surgery',
                            data: [
                                {x: 10, y: 32},
                                {x: 7, y: 25},
                                {x: 9, y: 28},
                                {x: 5, y: 15},
                                {x: 8, y: 22}
                            ],
                            backgroundColor: 'rgba(46, 204, 113, 0.7)'
                        },
                        {
                            label: 'Public Health',
                            data: [
                                {x: 6, y: 12},
                                {x: 4, y: 8},
                                {x: 5, y: 10},
                                {x: 3, y: 5},
                                {x: 7, y: 15}
                            ],
                            backgroundColor: 'rgba(231, 76, 60, 0.7)'
                        }
                    ]
                },
                options: {
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Publications'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Citations'
                            }
                        }
                    }
                }
            });

            // Publication Types Chart
            const pubTypesCtx = document.getElementById('publicationTypesChart').getContext('2d');
            new Chart(pubTypesCtx, {
                type: 'bar',
                data: {
                    labels: ['Journal Articles', 'Book Chapters', 'Books'],
                    datasets: [{
                        label: 'Publication Types',
                        data: [305, 63, 19],
                        backgroundColor: [
                            'rgba(52, 152, 219, 0.7)',
                            'rgba(46, 204, 113, 0.7)',
                            'rgba(155, 89, 182, 0.7)'
                        ]
                    }]
                }
            });

            // Grant Trend Chart
            const grantCtx = document.getElementById('grantTrendChart').getContext('2d');
            new Chart(grantCtx, {
                type: 'line',
                data: {
                    labels: ['2018', '2019', '2020', '2021', '2022', '2023'],
                    datasets: [
                        {
                            label: 'Medicine',
                            data: [500, 650, 800, 950, 1200, 1800],
                            borderColor: 'rgba(52, 152, 219, 1)',
                            backgroundColor: 'rgba(52, 152, 219, 0.1)',
                            borderWidth: 2,
                            tension: 0.3
                        },
                        {
                            label: 'Surgery',
                            data: [300, 400, 450, 600, 750, 1200],
                            borderColor: 'rgba(46, 204, 113, 1)',
                            backgroundColor: 'rgba(46, 204, 113, 0.1)',
                            borderWidth: 2,
                            tension: 0.3
                        },
                        {
                            label: 'Public Health',
                            data: [100, 150, 200, 250, 300, 500],
                            borderColor: 'rgba(231, 76, 60, 1)',
                            backgroundColor: 'rgba(231, 76, 60, 0.1)',
                            borderWidth: 2,
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            title: {
                                display: true,
                                text: 'Grant Amount (Millions UGX)'
                            }
                        }
                    }
                }
            });

            // Innovations Chart
            const innovationsCtx = document.getElementById('innovationsChart').getContext('2d');
            new Chart(innovationsCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Patents', 'Utility Models', 'Copyrights', 'Products', 'Trademarks'],
                    datasets: [{
                        data: [8, 12, 15, 5, 3],
                        backgroundColor: [
                            'rgba(52, 152, 219, 0.7)',
                            'rgba(46, 204, 113, 0.7)',
                            'rgba(155, 89, 182, 0.7)',
                            'rgba(241, 196, 15, 0.7)',
                            'rgba(231, 76, 60, 0.7)'
                        ]
                    }]
                }
            });

            // Community Engagement Chart
            const communityCtx = document.getElementById('communityEngagementChart').getContext('2d');
            new Chart(communityCtx, {
                type: 'bar',
                data: {
                    labels: ['Medicine', 'Surgery', 'Pediatrics', 'Pathology', 'Public Health'],
                    datasets: [{
                        label: 'Community Service Score',
                        data: [4.2, 3.8, 4.5, 3.2, 4.8],
                        backgroundColor: 'rgba(52, 152, 219, 0.7)'
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 5
                        }
                    }
                }
            });

            // University Service Chart
            const universityCtx = document.getElementById('universityServiceChart').getContext('2d');
            new Chart(universityCtx, {
                type: 'polarArea',
                data: {
                    labels: ['Committee Members', 'HODs', 'Deputy Deans', 'Deans'],
                    datasets: [{
                        data: [45, 12, 8, 5],
                        backgroundColor: [
                            'rgba(52, 152, 219, 0.7)',
                            'rgba(46, 204, 113, 0.7)',
                            'rgba(155, 89, 182, 0.7)',
                            'rgba(241, 196, 15, 0.7)'
                        ]
                    }]
                }
            });
        });
    </script>
</body>
</html>