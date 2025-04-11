const academicData = {
    qualificationsData: {
        labels: ['PhD', 'Masters', 'Bachelors'],
        datasets: [{
            label: 'Regular Points',
            data: [12, 8, 6],
            backgroundColor: 'rgba(32, 148, 139, 0.5)',
            borderColor: 'rgba(32, 148, 139, 1)',
            borderWidth: 1
        }]
    },
    
    publicationsData: {
        labels: ['Journal Articles', 'Books', 'Conference Papers'],
        datasets: [{
            label: 'Publication Points',
            data: [15, 10, 5],
            backgroundColor: 'rgba(138, 170, 229, 0.5)',
            borderColor: 'rgba(138, 170, 229, 1)',
            borderWidth: 1
        }]
    },
    
    teachingData: {
        labels: ['2019', '2020', '2021', '2022', '2023'],
        datasets: [{
            label: 'Teaching Experience Points',
            data: [5, 7, 9, 11, 13],
            borderColor: 'rgba(151, 188, 98, 1)',
            tension: 0.1,
            fill: false
        }]
    }
};

// Data access functions
function getData(type) {
    return academicData[type];
}

// Data update functions
function updateDataset(dataType, newData) {
    academicData[dataType] = newData;
    updateVisualizations(dataType);
}

function updateVisualizations(dataType) {
    const charts = {
        qualificationsData: 'qualificationsChart',
        publicationsData: 'publicationsChart',
        teachingData: 'teachingChart'
    };
    
    if (charts[dataType]) {
        const chartId = charts[dataType];
        const chart = Chart.getChart(chartId);
        chart.data = getData(dataType);
        chart.update();
    }
}