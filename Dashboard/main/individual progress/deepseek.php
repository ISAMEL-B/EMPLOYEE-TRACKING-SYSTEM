<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Tracking System - Individual Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --accent-color: #e74c3c;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --warning-color: #f39c12;
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
            line-height: 1.6;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            display: flex;
            align-items: center;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--primary-color);
            margin-right: 20px;
        }
        
        .profile-info h1 {
            color: var(--dark-color);
            margin-bottom: 5px;
        }
        
        .profile-info p {
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        
        .score-card {
            margin-left: auto;
            text-align: center;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 15px 25px;
            border-radius: 8px;
            color: white;
        }
        
        .score-card .score {
            font-size: 2.5rem;
            font-weight: bold;
        }
        
        .score-card .label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .dashboard {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 20px;
        }
        
        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .card-header h2 {
            color: var(--dark-color);
            font-size: 1.2rem;
        }
        
        .card-header .badge {
            background-color: var(--primary-color);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        
        .grid-col-4 {
            grid-column: span 4;
        }
        
        .grid-col-6 {
            grid-column: span 6;
        }
        
        .grid-col-8 {
            grid-column: span 8;
        }
        
        .grid-col-12 {
            grid-column: span 12;
        }
        
        .progress-container {
            margin-top: 15px;
        }
        
        .progress-item {
            margin-bottom: 10px;
        }
        
        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .progress-bar {
            height: 10px;
            background-color: #ecf0f1;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            border-radius: 5px;
            transition: width 0.5s ease;
        }
        
        .insight-card {
            background-color: #f8f9fa;
            border-left: 4px solid var(--primary-color);
            padding: 15px;
            margin-top: 15px;
            border-radius: 0 5px 5px 0;
        }
        
        .insight-card h4 {
            color: var(--dark-color);
            margin-bottom: 8px;
        }
        
        .insight-card p {
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        .badge-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }
        
        .badge {
            background-color: var(--light-color);
            color: var(--dark-color);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
        }
        
        .badge i {
            margin-right: 5px;
            color: var(--primary-color);
        }
        
        .tab-container {
            margin-top: 20px;
        }
        
        .tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }
        
        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
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
        
        .comparison-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        
        .comparison-item {
            flex: 1;
            text-align: center;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin: 0 10px;
        }
        
        .comparison-item h3 {
            color: var(--dark-color);
            margin-bottom: 10px;
        }
        
        .comparison-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .comparison-label {
            font-size: 0.8rem;
            color: #7f8c8d;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        @media (max-width: 1200px) {
            .grid-col-4, .grid-col-6, .grid-col-8 {
                grid-column: span 12;
            }
            
            .header {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-pic {
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .score-card {
                margin: 15px 0 0 0;
            }
            
            .comparison-container {
                flex-direction: column;
            }
            
            .comparison-item {
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Profile Picture" class="profile-pic">
            <div class="profile-info">
                <h1>Dr. John M. Kato</h1>
                <p>Associate Professor</p>
                <p>Department of Computer Science • Faculty of Computing & IT</p>
                <p>Years at current rank: 5 • Years of service: 12</p>
            </div>
            <div class="score-card">
                <div class="score">78</div>
                <div class="label">Total Score</div>
            </div>
        </div>
        
        <!-- Dashboard Grid -->
        <div class="dashboard">
            <!-- Qualifications Card -->
            <div class="card grid-col-4">
                <div class="card-header">
                    <h2>Qualifications</h2>
                    <span class="badge">12/12 points</span>
                </div>
                <div class="chart-container">
                    <canvas id="qualificationsChart"></canvas>
                </div>
                <div class="insight-card">
                    <h4>Highest Qualification Achieved</h4>
                    <p>You have the maximum points for qualifications at your rank. Consider pursuing additional certifications for professional development.</p>
                </div>
            </div>
            
            <!-- Research Output Card -->
            <div class="card grid-col-8">
                <div class="card-header">
                    <h2>Research Output</h2>
                    <span class="badge">32/40 points</span>
                </div>
                <div class="tabs">
                    <div class="tab active" onclick="openTab(event, 'publicationsTab')">Publications</div>
                    <div class="tab" onclick="openTab(event, 'grantsTab')">Grants</div>
                    <div class="tab" onclick="openTab(event, 'innovationsTab')">Innovations</div>
                </div>
                
                <div id="publicationsTab" class="tab-content active">
                    <div class="chart-container">
                        <canvas id="publicationsChart"></canvas>
                    </div>
                    <div class="insight-card">
                        <h4>Publication Impact</h4>
                        <p>Your citation count is lower than expected for your number of publications. Consider focusing on higher-impact journals or promoting your work through conferences.</p>
                    </div>
                </div>
                
                <div id="grantsTab" class="tab-content">
                    <div class="chart-container">
                        <canvas id="grantsChart"></canvas>
                    </div>
                    <div class="insight-card">
                        <h4>Grant Performance</h4>
                        <p>You're in the top 30% of your department for grant funding. Consider collaborating with colleagues to target larger grants.</p>
                    </div>
                </div>
                
                <div id="innovationsTab" class="tab-content">
                    <div class="badge-container">
                        <div class="badge"><i>📜</i> Copyright (3 pts)</div>
                        <div class="badge"><i>🏗️</i> Product (3 pts)</div>
                    </div>
                    <div class="insight-card">
                        <h4>Innovation Potential</h4>
                        <p>You have good innovation output. Consider pursuing a patent (5 pts) for your next project to maximize points.</p>
                    </div>
                </div>
            </div>
            
            <!-- Teaching & Supervision Card -->
            <div class="card grid-col-6">
                <div class="card-header">
                    <h2>Teaching & Supervision</h2>
                    <span class="badge">17/21 points</span>
                </div>
                <div class="chart-container">
                    <canvas id="teachingChart"></canvas>
                </div>
                <div class="progress-container">
                    <div class="progress-item">
                        <div class="progress-label">
                            <span>Teaching Experience</span>
                            <span>5/15 points</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 33%"></div>
                        </div>
                    </div>
                    <div class="progress-item">
                        <div class="progress-label">
                            <span>PhD Supervisions</span>
                            <span>2/3 required</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 66%"></div>
                        </div>
                    </div>
                </div>
                <div class="insight-card">
                    <h4>Supervision Progress</h4>
                    <p>You need 1 more PhD completion to reach the maximum points. Two of your current students are in their final year.</p>
                </div>
            </div>
            
            <!-- Service & Leadership Card -->
            <div class="card grid-col-6">
                <div class="card-header">
                    <h2>Service & Leadership</h2>
                    <span class="badge">10/15 points</span>
                </div>
                <div class="chart-container">
                    <canvas id="serviceChart"></canvas>
                </div>
                <div class="badge-container">
                    <div class="badge"><i>👥</i> Faculty Committee (1 pt)</div>
                    <div class="badge"><i>🏛️</i> Head of Department (2 pts)</div>
                    <div class="badge"><i>🌍</i> Community Service (5 pts)</div>
                </div>
                <div class="insight-card">
                    <h4>Leadership Opportunities</h4>
                    <p>Consider applying for a Deputy Dean position (3 pts) to boost your leadership score.</p>
                </div>
            </div>
            
            <!-- Progress Over Time Card -->
            <div class="card grid-col-12">
                <div class="card-header">
                    <h2>Progress Over Time</h2>
                    <span class="badge">Last 5 Years</span>
                </div>
                <div class="chart-container">
                    <canvas id="progressChart"></canvas>
                </div>
                <div class="insight-card">
                    <h4>Trend Analysis</h4>
                    <p>Your research output has increased steadily, but teaching points have plateaued. Consider balancing your focus areas.</p>
                </div>
            </div>
            
            <!-- Peer Comparison Card -->
            <div class="card grid-col-12">
                <div class="card-header">
                    <h2>Peer Comparison</h2>
                    <span class="badge">Associate Professors</span>
                </div>
                <div class="comparison-container">
                    <div class="comparison-item">
                        <h3>Research Score</h3>
                        <div class="comparison-value">32</div>
                        <div class="comparison-label">Department Avg: 28</div>
                    </div>
                    <div class="comparison-item">
                        <h3>Teaching Score</h3>
                        <div class="comparison-value">17</div>
                        <div class="comparison-label">Department Avg: 19</div>
                    </div>
                    <div class="comparison-item">
                        <h3>Service Score</h3>
                        <div class="comparison-value">10</div>
                        <div class="comparison-label">Department Avg: 8</div>
                    </div>
                    <div class="comparison-item">
                        <h3>Total Score</h3>
                        <div class="comparison-value">78</div>
                        <div class="comparison-label">Department Avg: 72</div>
                    </div>
                </div>
                <div class="insight-card">
                    <h4>Benchmarking</h4>
                    <p>You're performing above average in research and service, but slightly below in teaching. Focus on completing student supervisions to improve.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab functionality
        function openTab(evt, tabName) {
            const tabContents = document.getElementsByClassName("tab-content");
            for (let i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove("active");
            }
            
            const tabs = document.getElementsByClassName("tab");
            for (let i = 0; i < tabs.length; i++) {
                tabs[i].classList.remove("active");
            }
            
            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.classList.add("active");
        }
        
        // Chart configurations
        document.addEventListener('DOMContentLoaded', function() {
            // Qualifications Chart
            const qualCtx = document.getElementById('qualificationsChart').getContext('2d');
            new Chart(qualCtx, {
                type: 'doughnut',
                data: {
                    labels: ['PhD (12 pts)', 'Masters (8 pts)', 'Other (2 pts)'],
                    datasets: [{
                        data: [12, 0, 0],
                        backgroundColor: [
                            '#2ecc71',
                            '#3498db',
                            '#e74c3c'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'Qualification Points Breakdown',
                            font: {
                                size: 14
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label;
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
            
            // Publications Chart
            const pubCtx = document.getElementById('publicationsChart').getContext('2d');
            new Chart(pubCtx, {
                type: 'bar',
                data: {
                    labels: ['2019', '2020', '2021', '2022', '2023'],
                    datasets: [
                        {
                            label: 'Publications',
                            data: [3, 5, 7, 6, 8],
                            backgroundColor: '#3498db',
                            borderColor: '#2980b9',
                            borderWidth: 1
                        },
                        {
                            label: 'Citations',
                            data: [15, 22, 30, 28, 35],
                            backgroundColor: '#2ecc71',
                            borderColor: '#27ae60',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Publications vs Citations (Last 5 Years)',
                            font: {
                                size: 14
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                }
            });
            
            // Grants Chart
            const grantsCtx = document.getElementById('grantsChart').getContext('2d');
            new Chart(grantsCtx, {
                type: 'pie',
                data: {
                    labels: ['> UGX 1B (12 pts)', 'UGX 500M-1B (8 pts)', 'UGX 100M-500M (6 pts)', '< UGX 100M (4 pts)'],
                    datasets: [{
                        data: [1, 2, 3, 1],
                        backgroundColor: [
                            '#2ecc71',
                            '#3498db',
                            '#f39c12',
                            '#e74c3c'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'Grant Funding Tiers',
                            font: {
                                size: 14
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    return `${label}: ${value} grants`;
                                }
                            }
                        }
                    }
                }
            });
            
            // Teaching Chart
            const teachCtx = document.getElementById('teachingChart').getContext('2d');
            new Chart(teachCtx, {
                type: 'radar',
                data: {
                    labels: ['Teaching Exp.', 'PhD Supervisions', 'Masters Supervisions'],
                    datasets: [{
                        label: 'Current Points',
                        data: [5, 12, 10],
                        backgroundColor: 'rgba(52, 152, 219, 0.2)',
                        borderColor: 'rgba(52, 152, 219, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(52, 152, 219, 1)'
                    }, {
                        label: 'Max Possible',
                        data: [15, 18, 15],
                        backgroundColor: 'rgba(46, 204, 113, 0.2)',
                        borderColor: 'rgba(46, 204, 113, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(46, 204, 113, 1)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            angleLines: {
                                display: true
                            },
                            suggestedMin: 0,
                            suggestedMax: 20
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Teaching & Supervision Performance',
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            });
            
            // Service Chart
            const serviceCtx = document.getElementById('serviceChart').getContext('2d');
            new Chart(serviceCtx, {
                type: 'horizontalBar',
                data: {
                    labels: ['University Service', 'Community Service', 'Professional Memberships'],
                    datasets: [{
                        label: 'Points',
                        data: [3, 5, 2],
                        backgroundColor: [
                            'rgba(52, 152, 219, 0.7)',
                            'rgba(46, 204, 113, 0.7)',
                            'rgba(155, 89, 182, 0.7)'
                        ],
                        borderColor: [
                            'rgba(52, 152, 219, 1)',
                            'rgba(46, 204, 113, 1)',
                            'rgba(155, 89, 182, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    scales: {
                        x: {
                            beginAtZero: true,
                            max: 10
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Service & Leadership Points',
                            font: {
                                size: 14
                            }
                        },
                        legend: {
                            display: false
                        }
                    }
                }
            });
            
            // Progress Chart
            const progressCtx = document.getElementById('progressChart').getContext('2d');
            new Chart(progressCtx, {
                type: 'line',
                data: {
                    labels: ['2019', '2020', '2021', '2022', '2023'],
                    datasets: [
                        {
                            label: 'Research',
                            data: [18, 22, 25, 28, 32],
                            borderColor: '#3498db',
                            backgroundColor: 'rgba(52, 152, 219, 0.1)',
                            borderWidth: 3,
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Teaching',
                            data: [12, 14, 15, 16, 17],
                            borderColor: '#2ecc71',
                            backgroundColor: 'rgba(46, 204, 113, 0.1)',
                            borderWidth: 3,
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Service',
                            data: [5, 6, 7, 8, 10],
                            borderColor: '#9b59b6',
                            backgroundColor: 'rgba(155, 89, 182, 0.1)',
                            borderWidth: 3,
                            tension: 0.3,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Annual Performance Trends',
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>