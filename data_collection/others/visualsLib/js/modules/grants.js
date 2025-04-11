const grantsData = {
    faculties: ['FCI', 'FAST', 'FOM', 'FOBMS', 'FIS'],
    allocated: [1000000000, 800000000, 750000000, 900000000, 600000000],
    used: [800000000, 600000000, 700000000, 850000000, 450000000]
};

document.addEventListener('DOMContentLoaded', function() {
    // Faculty Grants Chart
    const facultyGrantsCtx = document.getElementById('facultyGrantsChart');
    if (facultyGrantsCtx) {
        new Chart(facultyGrantsCtx, {
            type: 'bar',
            data: {
                labels: grantsData.faculties,
                datasets: [
                    {
                        label: 'Allocated Grants (UGX)',
                        data: grantsData.allocated,
                        backgroundColor: '#20948B'
                    },
                    {
                        label: 'Used Grants (UGX)',
                        data: grantsData.used,
                        backgroundColor: '#6AB187'
                    }
                ]
            },
            options: {
                responsive: true,
                onClick: (evt, elements) => {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        if (grantsData.faculties[index] === 'FCI') {
                            window.location.href = 'fcidepartmentgrants.html';
                        }
                    }
                }
            }
        });
    }

    // Grant Distribution Chart
    const distributionCtx = document.getElementById('grantDistributionChart');
    if (distributionCtx) {
        new Chart(distributionCtx, {
            type: 'pie',
            data: {
                labels: grantsData.faculties,
                datasets: [{
                    data: grantsData.allocated,
                    backgroundColor: ['#20948B', '#6AB187', '#97BC62', '#A7BEAE', '#8AAAE5']
                }]
            },
            options: {
                responsive: true
            }
        });
    }

    // Efficiency Chart
    const efficiencyCtx = document.getElementById('efficiencyChart');
    if (efficiencyCtx) {
        const efficiency = grantsData.faculties.map((_, index) => 
            (grantsData.used[index] / grantsData.allocated[index]) * 100
        );

        new Chart(efficiencyCtx, {
            type: 'bar',
            data: {
                labels: grantsData.faculties,
                datasets: [{
                    label: 'Usage Efficiency (%)',
                    data: efficiency,
                    backgroundColor: '#97BC62'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }
});

// Add department data for FCI
const fciDepartmentData = {
    departments: {
        'Software Engineering': {
            allocated: 400000000,
            used: 320000000,
            projects: ['Research Lab', 'Student Projects', 'Industry Collaboration']
        },
        'Computer Science': {
            allocated: 350000000,
            used: 280000000,
            projects: ['AI Research', 'Computing Infrastructure', 'Academic Programs']
        },
        'Information Technology': {
            allocated: 250000000,
            used: 200000000,
            projects: ['Network Lab', 'Security Research', 'IT Infrastructure']
        }
    }
};

// Add this to your existing DOMContentLoaded event listener
const fciDepartmentCtx = document.getElementById('fciDepartmentChart');
new Chart(fciDepartmentCtx, {
    type: 'bar',
    data: {
        labels: Object.keys(fciDepartmentData.departments),
        datasets: [
            {
                label: 'Allocated Grants',
                data: Object.values(fciDepartmentData.departments).map(dept => dept.allocated),
                backgroundColor: '#20948B'
            },
            {
                label: 'Used Grants',
                data: Object.values(fciDepartmentData.departments).map(dept => dept.used),
                backgroundColor: '#6AB187'
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Amount (UGX)'
                }
            }
        }
    }
});
