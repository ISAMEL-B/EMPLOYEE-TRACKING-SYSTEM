function refreshData() {
    // Simulate data fetch from external system
    const newData = simulateExternalDataFetch();
    Object.keys(newData).forEach(dataType => {
        updateDataset(dataType, newData[dataType]);
    });
}

function simulateExternalDataFetch() {
    // Simulate API response
    return {
        qualificationsData: [
            // Updated data structure
            {
                type: 'PhD',
                regularPoints: Math.floor(Math.random() * 5) + 10,
                clinicalPoints: Math.floor(Math.random() * 3) + 5,
                year: 2023
            }
            // Add more simulated data
        ]
    };
}

function filterByYear(year) {
    const filteredData = Object.keys(academicDataStore).reduce((acc, key) => {
        if (Array.isArray(academicDataStore[key])) {
            acc[key] = academicDataStore[key].filter(item => item.year === parseInt(year));
        }
        return acc;
    }, {});
    
    Object.keys(filteredData).forEach(dataType => {
        updateVisualizations(dataType);
    });
}
