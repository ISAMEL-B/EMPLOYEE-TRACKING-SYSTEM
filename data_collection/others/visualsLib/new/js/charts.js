document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts only if elements exist
    const qualificationsChart = document.getElementById('qualificationsChart');
    if (qualificationsChart) {
        new Chart(qualificationsChart, {
            type: 'bar',
            data: chartData.qualifications,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    }

    const publicationsChart = document.getElementById('publicationsChart');
    if (publicationsChart) {
        new Chart(publicationsChart, {
            type: 'bar',
            data: chartData.publications,
            options: {
                responsive: true
            }
        });
    }

    const teachingChart = document.getElementById('teachingChart');
    if (teachingChart) {
        new Chart(teachingChart, {
            type: 'line',
            data: chartData.teaching,
            options: {
                responsive: true
            }
        });
    }

    const grantsChart = document.getElementById('grantsChart');
    if (grantsChart) {
        new Chart(grantsChart, {
            type: 'pie',
            data: chartData.grants,
            options: {
                responsive: true
            }
        });
    }
});
    // Supervision Bar Chart
    new Chart(supervisionCtx, {
        type: 'bar',
        data: supervisionData,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Points per Student'
                    }
                }
            }
        }
    });

    // Innovations Pie Chart
    new Chart(innovationsCtx, {
        type: 'pie',
        data: innovationsData,
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Innovation Points Distribution'
                }
            }
        }
    });

    // Academic Activities Chart
    new Chart(academicActivitiesCtx, {
        type: 'bar',
        data: academicActivitiesData,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Points'
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Other Academic Activities'
                }
            }
        }
    });

    // University Service Chart
    new Chart(universityServiceCtx, {
        type: 'radar',
        data: {
            labels: universityServiceData.labels,
            datasets: [{
                label: 'Service Points',
                data: universityServiceData.datasets[0].data,
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                pointBackgroundColor: 'rgba(153, 102, 255, 1)'
            }]
        },
        options: {
            scales: {
                r: {
                    beginAtZero: true,
                    max: 5
                }
            }
        }
    });

    // Community Service Chart
    new Chart(communityServiceCtx, {
        type: 'bar',
        data: communityServiceData,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    max: 6
                }
            }
        }
    });

    // Membership Chart
    new Chart(membershipCtx, {
        type: 'pie',
        data: professionalMembershipData,
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Professional Body Memberships'
                }
            }
        }
    });

    // Performance Chart
    new Chart(performanceCtx, {
        type: 'line',
        data: performanceData,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    max: 4
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Performance Appraisal Trend'
                }
            }
        }
    });
