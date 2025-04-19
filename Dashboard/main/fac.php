<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Performance Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>:root {
    --primary-color: #2c3e50;
    --secondary-color: #3498db;
    --accent-color: #e74c3c;
    --light-color: #ecf0f1;
    --dark-color: #2c3e50;
    --success-color: #27ae60;
    --warning-color: #f39c12;
    --danger-color: #e74c3c;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background-color: #f5f7fa;
    color: var(--dark-color);
    line-height: 1.6;
}

header {
    background-color: var(--primary-color);
    color: white;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

header h1 {
    margin-bottom: 1rem;
    font-size: 1.8rem;
}

.controls {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.controls select {
    padding: 0.5rem 1rem;
    border-radius: 4px;
    border: 1px solid #ddd;
    background-color: white;
    min-width: 200px;
}

main {
    padding: 1.5rem;
}

.dashboard-container {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
}

.overview-section {
    background-color: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.metrics-summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.metric-card {
    background-color: white;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    border-top: 4px solid var(--secondary-color);
    transition: transform 0.3s ease;
}

.metric-card:hover {
    transform: translateY(-5px);
}

.metric-card h3 {
    font-size: 1rem;
    color: #7f8c8d;
    margin-bottom: 0.5rem;
}

.metric-value {
    font-size: 2rem;
    font-weight: bold;
    color: var(--primary-color);
}

.metric-change {
    font-size: 0.9rem;
    color: var(--success-color);
}

.visualization-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
}

.chart-container {
    background-color: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    min-height: 300px;
}

.chart {
    width: 100%;
    height: 100%;
}

.details-section {
    background-color: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.table-container {
    overflow-x: auto;
    margin-top: 1rem;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 0.75rem 1rem;
    text-align: left;
    border-bottom: 1px solid #eee;
}

th {
    background-color: #f8f9fa;
    font-weight: 600;
}

tr:hover {
    background-color: #f8f9fa;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .visualization-section {
        grid-template-columns: 1fr;
    }
    
    .metrics-summary {
        grid-template-columns: 1fr;
    }
    
    .controls {
        flex-direction: column;
    }
    
    .controls select {
        width: 100%;
    }
}</style>
</head>
<body>
    <header>
        <h1>University Performance Dashboard</h1>
        <div class="controls">
            <select id="faculty-select">
                <option value="">All Faculties</option>
                <!-- Will be populated by JavaScript -->
            </select>
            <select id="department-select" disabled>
                <option value="">All Departments</option>
                <!-- Will be populated by JavaScript -->
            </select>
            <select id="metric-select">
                <option value="academic">Academic Performance</option>
                <option value="research">Research & Innovations</option>
                <option value="community">Community Service</option>
            </select>
            <select id="view-level">
                <option value="faculty">Faculty Level</option>
                <option value="department">Department Level</option>
                <option value="individual">Individual Level</option>
            </select>
        </div>
    </header>

    <main>
        <div class="dashboard-container">
            <div class="overview-section">
                <h2 id="current-view-title">Faculty Performance Overview</h2>
                <div class="metrics-summary">
                    <div class="metric-card" id="academic-card">
                        <h3>Academic Performance</h3>
                        <div class="metric-value">0</div>
                        <div class="metric-change">↑ 0%</div>
                    </div>
                    <div class="metric-card" id="research-card">
                        <h3>Research & Innovations</h3>
                        <div class="metric-value">0</div>
                        <div class="metric-change">↑ 0%</div>
                    </div>
                    <div class="metric-card" id="community-card">
                        <h3>Community Service</h3>
                        <div class="metric-value">0</div>
                        <div class="metric-change">↑ 0%</div>
                    </div>
                </div>
            </div>

            <div class="visualization-section">
                <div class="chart-container">
                    <div id="hierarchical-chart" class="chart"></div>
                </div>
                <div class="chart-container">
                    <div id="trend-chart" class="chart"></div>
                </div>
                <div class="chart-container">
                    <canvas id="comparison-chart"></canvas>
                </div>
                <div class="chart-container">
                    <div id="distribution-chart" class="chart"></div>
                </div>
            </div>

            <div class="details-section">
                <h3>Performance Details</h3>
                <div class="table-container">
                    <table id="performance-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Faculty</th>
                                <th>Academic</th>
                                <th>Research</th>
                                <th>Community</th>
                                <th>Overall</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Sample data structure (in a real app, this would come from CSV/API)
const facultyStructure = {
    "Faculty of Computing and Informatics": [
        "Department of Software Engineering",
        "Department of Computer Science",
        "Department of Information Technology",
        "Department of Computing Services",
        "Software Incubation and Innovations"
    ],
    "Faculty of Science": [
        "Department of Chemistry",
        "Department of Biology",
        "Department of Mathematics",
        "Department of Physics",
        "Department of Education Foundations and Psychology",
        "Diploma in Science Laboratory Technology",
        "Higher Education Access Certificate - HEAC"
    ],
    "Faculty of Applied Sciences and Technology": [
        "Department of Biomedical Sciences and Engineering",
        "Electrical and Electronics Engineering",
        "Department of Mechanical and Industrial Engineering",
        "Department of Civil And Building Engineering"
    ],
    "Faculty of Business and Management Sciences": [
        "Department of Accounting and Finance",
        "Department of Economics and Entrepreneurship",
        "Department of Human Resource Management",
        "Department of Procurement and Marketing"
    ],
    "Faculty of Interdisciplinary Studies": [
        "Planning and Governance",
        "Human Development and relational Sciences",
        "Environment and livelihood support system",
        "Community Engagement and service learning"
    ],
    "Faculty Of Medicine": [
        "Anesthesia",
        "Biochemistry",
        "Community Health",
        "Dental Surgery",
        "Dermatology",
        "Ears, Nose and Throat (ENT)",
        "Emergency Medicine",
        "Family Medicine",
        "Internal Medicine",
        "Medical Laboratory Science",
        "Microbiology and Parasitology",
        "Nursing",
        "Obstetrics and Gynaecology",
        "Ophthalmology",
        "Pathology",
        "Paediatrics and Child Health",
        "Pharmaceutical Sciences",
        "Pharmacology",
        "Pharmacy",
        "Physiology"
    ]
};

// Generate sample data
function generateSampleData() {
    const individuals = [];
    let id = 1;
    
    for (const faculty in facultyStructure) {
        const departments = facultyStructure[faculty];
        
        departments.forEach(department => {
            // Generate 5-15 individuals per department
            const numIndividuals = Math.floor(Math.random() * 10) + 5;
            
            for (let i = 0; i < numIndividuals; i++) {
                individuals.push({
                    id: id++,
                    name: `Staff ${id}`,
                    department: department,
                    faculty: faculty,
                    academic: Math.floor(Math.random() * 100),
                    research: Math.floor(Math.random() * 100),
                    community: Math.floor(Math.random() * 100),
                    trend: Array.from({length: 12}, () => Math.floor(Math.random() * 100))
                });
            }
        });
    }
    
    return individuals;
}

const allIndividuals = generateSampleData();

// Initialize the dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Populate faculty dropdown
    const facultySelect = document.getElementById('faculty-select');
    Object.keys(facultyStructure).forEach(faculty => {
        const option = document.createElement('option');
        option.value = faculty;
        option.textContent = faculty;
        facultySelect.appendChild(option);
    });
    
    // Update department dropdown based on faculty selection
    facultySelect.addEventListener('change', function() {
        const departmentSelect = document.getElementById('department-select');
        departmentSelect.innerHTML = '<option value="">All Departments</option>';
        
        if (this.value) {
            departmentSelect.disabled = false;
            facultyStructure[this.value].forEach(dept => {
                const option = document.createElement('option');
                option.value = dept;
                option.textContent = dept;
                departmentSelect.appendChild(option);
            });
        } else {
            departmentSelect.disabled = true;
        }
        
        updateDashboard();
    });
    
    // Update dashboard when any control changes
    document.getElementById('department-select').addEventListener('change', updateDashboard);
    document.getElementById('metric-select').addEventListener('change', updateDashboard);
    document.getElementById('view-level').addEventListener('change', updateDashboard);
    
    // Initial dashboard render
    updateDashboard();
});

function updateDashboard() {
    const faculty = document.getElementById('faculty-select').value;
    const department = document.getElementById('department-select').value;
    const metric = document.getElementById('metric-select').value;
    const viewLevel = document.getElementById('view-level').value;
    
    // Filter individuals based on selections
    let filteredIndividuals = [...allIndividuals];
    
    if (faculty) {
        filteredIndividuals = filteredIndividuals.filter(ind => ind.faculty === faculty);
    }
    
    if (department) {
        filteredIndividuals = filteredIndividuals.filter(ind => ind.department === department);
    }
    
    // Update title
    const title = document.getElementById('current-view-title');
    if (faculty && department) {
        title.textContent = `${department} Performance (${faculty})`;
    } else if (faculty) {
        title.textContent = `${faculty} Performance`;
    } else {
        title.textContent = 'University Performance Overview';
    }
    
    // Update metrics summary
    updateMetricsSummary(filteredIndividuals);
    
    // Update visualizations based on view level
    if (viewLevel === 'individual') {
        renderIndividualVisualizations(filteredIndividuals, metric);
    } else if (viewLevel === 'department') {
        renderDepartmentVisualizations(filteredIndividuals, metric, faculty);
    } else {
        renderFacultyVisualizations(filteredIndividuals, metric);
    }
    
    // Update performance table
    updatePerformanceTable(filteredIndividuals);
}

function updateMetricsSummary(individuals) {
    const academicAvg = individuals.reduce((sum, ind) => sum + ind.academic, 0) / individuals.length || 0;
    const researchAvg = individuals.reduce((sum, ind) => sum + ind.research, 0) / individuals.length || 0;
    const communityAvg = individuals.reduce((sum, ind) => sum + ind.community, 0) / individuals.length || 0;
    
    document.getElementById('academic-card').querySelector('.metric-value').textContent = academicAvg.toFixed(1);
    document.getElementById('research-card').querySelector('.metric-value').textContent = researchAvg.toFixed(1);
    document.getElementById('community-card').querySelector('.metric-value').textContent = communityAvg.toFixed(1);
    
    // Simple random change indicator for demo
    const randomChange = () => (Math.random() * 10).toFixed(1);
    document.getElementById('academic-card').querySelector('.metric-change').textContent = `↑ ${randomChange()}%`;
    document.getElementById('research-card').querySelector('.metric-change').textContent = `↑ ${randomChange()}%`;
    document.getElementById('community-card').querySelector('.metric-change').textContent = `↑ ${randomChange()}%`;
}

function renderIndividualVisualizations(individuals, metric) {
    // Clear previous charts
    document.getElementById('hierarchical-chart').innerHTML = '';
    document.getElementById('trend-chart').innerHTML = '';
    document.getElementById('distribution-chart').innerHTML = '';
    
    // Sort individuals by selected metric
    const sortedIndividuals = [...individuals].sort((a, b) => b[metric] - a[metric]);
    
    // Top performers chart (using Chart.js)
    const ctx = document.getElementById('comparison-chart').getContext('2d');
    if (window.comparisonChart) {
        window.comparisonChart.destroy();
    }
    
    const topPerformers = sortedIndividuals.slice(0, 10);
    window.comparisonChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: topPerformers.map(ind => ind.name),
            datasets: [{
                label: metric.charAt(0).toUpperCase() + metric.slice(1) + ' Score',
                data: topPerformers.map(ind => ind[metric]),
                backgroundColor: '#3498db',
                borderColor: '#2980b9',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: `Top 10 Performers - ${metric.charAt(0).toUpperCase() + metric.slice(1)}`
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
    
    // Trend chart (using ApexCharts)
    if (individuals.length > 0) {
        const trendOptions = {
            series: [{
                name: "Performance Trend",
                data: individuals[0].trend
            }],
            chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            title: {
                text: `${individuals[0].name}'s Monthly Performance Trend`,
                align: 'left'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'],
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            },
            colors: ['#e74c3c']
        };
        
        const trendChart = new ApexCharts(document.getElementById('trend-chart'), trendOptions);
        trendChart.render();
    }
    
    // Distribution chart (using ApexCharts)
    const distributionOptions = {
        series: [{
            name: "Score",
            data: sortedIndividuals.map(ind => ind[metric])
        }],
        chart: {
            type: 'boxPlot',
            height: 350
        },
        title: {
            text: `${metric.charAt(0).toUpperCase() + metric.slice(1)} Score Distribution`,
            align: 'left'
        },
        plotOptions: {
            boxPlot: {
                colors: {
                    upper: '#3498db',
                    lower: '#2ecc71'
                }
            }
        },
        xaxis: {
            categories: ['Score Distribution']
        }
    };
    
    const distributionChart = new ApexCharts(document.getElementById('distribution-chart'), distributionOptions);
    distributionChart.render();
}

function renderDepartmentVisualizations(individuals, metric, facultyFilter) {
    // Group individuals by department
    const departments = {};
    
    individuals.forEach(ind => {
        if (!departments[ind.department]) {
            departments[ind.department] = [];
        }
        departments[ind.department].push(ind);
    });
    
    // Calculate department averages
    const departmentAverages = Object.keys(departments).map(dept => {
        const deptIndividuals = departments[dept];
        const avg = deptIndividuals.reduce((sum, ind) => sum + ind[metric], 0) / deptIndividuals.length;
        return {
            department: dept,
            faculty: deptIndividuals[0].faculty,
            average: avg
        };
    });
    
    // Sort departments by average
    departmentAverages.sort((a, b) => b.average - a.average);
    
    // Hierarchical chart (using D3.js)
    renderHierarchicalChart(departmentAverages, 'department', metric);
    
    // Comparison chart (using Chart.js)
    const ctx = document.getElementById('comparison-chart').getContext('2d');
    if (window.comparisonChart) {
        window.comparisonChart.destroy();
    }
    
    window.comparisonChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: departmentAverages.map(dept => dept.department),
            datasets: [{
                label: `Average ${metric.charAt(0).toUpperCase() + metric.slice(1)} Score`,
                data: departmentAverages.map(dept => dept.average),
                backgroundColor: departmentAverages.map(dept => 
                    dept.faculty === facultyFilter ? '#e74c3c' : '#3498db'
                ),
                borderColor: '#2980b9',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: `Department Performance - ${metric.charAt(0).toUpperCase() + metric.slice(1)}`
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
    
    // Trend chart (average trend for departments)
    const trendOptions = {
        series: departmentAverages.slice(0, 5).map(dept => ({
            name: dept.department,
            data: calculateDepartmentTrend(departments[dept.department], metric)
        })),
        chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        title: {
            text: 'Top Departments - Monthly Performance Trend',
            align: 'left'
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'],
                opacity: 0.5
            },
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        },
        colors: ['#e74c3c', '#3498db', '#2ecc71', '#f39c12', '#9b59b6']
    };
    
    document.getElementById('trend-chart').innerHTML = '';
    const trendChart = new ApexCharts(document.getElementById('trend-chart'), trendOptions);
    trendChart.render();
    
    // Distribution chart (department distribution)
    document.getElementById('distribution-chart').innerHTML = '';
    const distributionOptions = {
        series: [{
            name: "Score",
            data: departmentAverages.map(dept => dept.average)
        }],
        chart: {
            type: 'boxPlot',
            height: 350
        },
        title: {
            text: `Department ${metric.charAt(0).toUpperCase() + metric.slice(1)} Score Distribution`,
            align: 'left'
        },
        plotOptions: {
            boxPlot: {
                colors: {
                    upper: '#3498db',
                    lower: '#2ecc71'
                }
            }
        },
        xaxis: {
            categories: ['Department Averages']
        }
    };
    
    const distributionChart = new ApexCharts(document.getElementById('distribution-chart'), distributionOptions);
    distributionChart.render();
}

function renderFacultyVisualizations(individuals, metric) {
    // Group individuals by faculty
    const faculties = {};
    
    individuals.forEach(ind => {
        if (!faculties[ind.faculty]) {
            faculties[ind.faculty] = [];
        }
        faculties[ind.faculty].push(ind);
    });
    
    // Calculate faculty averages
    const facultyAverages = Object.keys(faculties).map(faculty => {
        const facultyIndividuals = faculties[faculty];
        const avg = facultyIndividuals.reduce((sum, ind) => sum + ind[metric], 0) / facultyIndividuals.length;
        return {
            faculty: faculty,
            average: avg
        };
    });
    
    // Sort faculties by average
    facultyAverages.sort((a, b) => b.average - a.average);
    
    // Hierarchical chart (using D3.js)
    renderHierarchicalChart(facultyAverages, 'faculty', metric);
    
    // Comparison chart (using Chart.js)
    const ctx = document.getElementById('comparison-chart').getContext('2d');
    if (window.comparisonChart) {
        window.comparisonChart.destroy();
    }
    
    window.comparisonChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: facultyAverages.map(f => f.faculty),
            datasets: [{
                label: `Average ${metric.charAt(0).toUpperCase() + metric.slice(1)} Score`,
                data: facultyAverages.map(f => f.average),
                backgroundColor: '#3498db',
                borderColor: '#2980b9',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: `Faculty Performance - ${metric.charAt(0).toUpperCase() + metric.slice(1)}`
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
    
    // Trend chart (average trend for faculties)
    const trendOptions = {
        series: facultyAverages.map(faculty => ({
            name: faculty.faculty,
            data: calculateFacultyTrend(faculties[faculty.faculty], metric)
        })),
        chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        title: {
            text: 'Faculty Performance Trends',
            align: 'left'
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'],
                opacity: 0.5
            },
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        },
        colors: ['#e74c3c', '#3498db', '#2ecc71', '#f39c12', '#9b59b6', '#1abc9c']
    };
    
    document.getElementById('trend-chart').innerHTML = '';
    const trendChart = new ApexCharts(document.getElementById('trend-chart'), trendOptions);
    trendChart.render();
    
    // Distribution chart (faculty distribution)
    document.getElementById('distribution-chart').innerHTML = '';
    const distributionOptions = {
        series: [{
            name: "Score",
            data: facultyAverages.map(faculty => faculty.average)
        }],
        chart: {
            type: 'boxPlot',
            height: 350
        },
        title: {
            text: `Faculty ${metric.charAt(0).toUpperCase() + metric.slice(1)} Score Distribution`,
            align: 'left'
        },
        plotOptions: {
            boxPlot: {
                colors: {
                    upper: '#3498db',
                    lower: '#2ecc71'
                }
            }
        },
        xaxis: {
            categories: ['Faculty Averages']
        }
    };
    
    const distributionChart = new ApexCharts(document.getElementById('distribution-chart'), distributionOptions);
    distributionChart.render();
}

function renderHierarchicalChart(data, level, metric) {
    const container = document.getElementById('hierarchical-chart');
    container.innerHTML = '';
    
    const width = container.clientWidth;
    const height = 400;
    const margin = {top: 20, right: 20, bottom: 30, left: 40};
    
    const svg = d3.select(container)
        .append('svg')
        .attr('width', width)
        .attr('height', height);
    
    const x = d3.scaleBand()
        .domain(data.map(d => level === 'faculty' ? d.faculty : d.department))
        .range([margin.left, width - margin.right])
        .padding(0.1);
    
    const y = d3.scaleLinear()
        .domain([0, 100])
        .nice()
        .range([height - margin.bottom, margin.top]);
    
    svg.append('g')
        .attr('fill', '#3498db')
        .selectAll('rect')
        .data(data)
        .join('rect')
            .attr('x', d => x(level === 'faculty' ? d.faculty : d.department))
            .attr('y', d => y(d.average))
            .attr('height', d => y(0) - y(d.average))
            .attr('width', x.bandwidth())
            .attr('rx', 4)
            .attr('ry', 4);
    
    svg.append('g')
        .attr('transform', `translate(0,${height - margin.bottom})`)
        .call(d3.axisBottom(x))
        .selectAll('text')
            .attr('transform', 'rotate(-45)')
            .style('text-anchor', 'end')
            .attr('dx', '-0.8em')
            .attr('dy', '0.15em');
    
    svg.append('g')
        .attr('transform', `translate(${margin.left},0)`)
        .call(d3.axisLeft(y));
    
    svg.append('text')
        .attr('x', width / 2)
        .attr('y', margin.top)
        .attr('text-anchor', 'middle')
        .text(`${level.charAt(0).toUpperCase() + level.slice(1)} ${metric.charAt(0).toUpperCase() + metric.slice(1)} Performance`);
}

function calculateDepartmentTrend(departmentIndividuals, metric) {
    // Calculate monthly average for the department
    const monthlyAverages = Array(12).fill(0).map((_, month) => {
        const sum = departmentIndividuals.reduce((total, ind) => total + ind.trend[month], 0);
        return sum / departmentIndividuals.length;
    });
    return monthlyAverages;
}

function calculateFacultyTrend(facultyIndividuals, metric) {
    // Calculate monthly average for the faculty
    const monthlyAverages = Array(12).fill(0).map((_, month) => {
        const sum = facultyIndividuals.reduce((total, ind) => total + ind.trend[month], 0);
        return sum / facultyIndividuals.length;
    });
    return monthlyAverages;
}

function updatePerformanceTable(individuals) {
    const tableBody = document.querySelector('#performance-table tbody');
    tableBody.innerHTML = '';
    
    individuals.forEach(ind => {
        const row = document.createElement('tr');
        const overall = (ind.academic + ind.research + ind.community) / 3;
        
        row.innerHTML = `
            <td>${ind.name}</td>
            <td>${ind.department}</td>
            <td>${ind.faculty}</td>
            <td>${ind.academic.toFixed(1)}</td>
            <td>${ind.research.toFixed(1)}</td>
            <td>${ind.community.toFixed(1)}</td>
            <td>${overall.toFixed(1)}</td>
        `;
        
        tableBody.appendChild(row);
    });
}
    </script>
</body>
</html>