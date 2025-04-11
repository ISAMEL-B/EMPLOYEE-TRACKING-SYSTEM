const chartData = {
    qualifications: {
        labels: ['PhD', 'Masters', 'Bachelors'],
        datasets: [{
            label: 'Points',
            data: [12, 8, 6],
            backgroundColor: ['#20948B', '#6AB187', '#97BC62']
        }]
    },
    publications: {
        labels: ['Journal Articles', 'Books', 'Conference Papers'],
        datasets: [{
            label: 'Count',
            data: [15, 5, 8],
            backgroundColor: '#8AAAE5'
        }]
    },
    teaching: {
        labels: ['2019', '2020', '2021', '2022', '2023'],
        datasets: [{
            label: 'Experience Points',
            data: [5, 7, 9, 11, 13],
            borderColor: '#20948B',
            tension: 0.1,
            fill: false
        }]
    },
    grants: {
        labels: ['>1B UGX', '500M-1B UGX', '100M-500M UGX', '<100M UGX'],
        datasets: [{
            label: 'Grant Points',
            data: [12, 8, 6, 4],
            backgroundColor: ['#20948B', '#6AB187', '#97BC62', '#A7BEAE']
        }]
    }
};
const supervisionData = {
  labels: ['PhD Students', 'Masters Students'],
  datasets: [{
      label: 'Points per Student',
      data: [6, 2],
      backgroundColor: [
          'rgba(153, 102, 255, 0.5)',
          'rgba(54, 162, 235, 0.5)'
      ],
      borderColor: [
          'rgba(153, 102, 255, 1)',
          'rgba(54, 162, 235, 1)'
      ],
      borderWidth: 1
  }]
};

const innovationsData = {
  labels: ['Patent', 'Utility Model', 'Copyright', 'Product', 'Trademark'],
  datasets: [{
      data: [5, 4, 3, 3, 1],
      backgroundColor: [
          'rgba(255, 99, 132, 0.5)',
          'rgba(54, 162, 235, 0.5)',
          'rgba(255, 206, 86, 0.5)',
          'rgba(75, 192, 192, 0.5)',
          'rgba(153, 102, 255, 0.5)'
      ]
  }]
};  

// Additional data structures for new metrics
const academicActivitiesData = {
    labels: ['External Examination', 'Internal Examination', 'Conference Presentations', 'Journal Editor'],
    datasets: [{
        label: 'Points Earned',
        data: [10, 10, 2, 3],
        backgroundColor: [
            'rgba(255, 99, 132, 0.5)',
            'rgba(54, 162, 235, 0.5)',
            'rgba(255, 206, 86, 0.5)',
            'rgba(75, 192, 192, 0.5)'
        ],
        borderWidth: 1
    }]
};

const universityServiceData = {
    labels: ['Dean/Director', 'Deputy Dean/Director', 'Head of Department', 'Committee Membership'],
    datasets: [{
        label: 'Points',
        data: [5, 3, 2, 1],
        backgroundColor: 'rgba(153, 102, 255, 0.5)',
        borderColor: 'rgba(153, 102, 255, 1)',
        borderWidth: 1
    }]
};

const communityServiceData = {
    labels: ['Community Service'],
    datasets: [{
        label: 'Points',
        data: [5],
        backgroundColor: 'rgba(255, 159, 64, 0.5)',
        borderColor: 'rgba(255, 159, 64, 1)',
        borderWidth: 1
    }]
};

const professionalMembershipData = {
    labels: ['Body 1', 'Body 2'],
    datasets: [{
        data: [1, 1],
        backgroundColor: [
            'rgba(54, 162, 235, 0.5)',
            'rgba(255, 206, 86, 0.5)'
        ],
        borderWidth: 1
    }]
};

const performanceData = {
    labels: ['2021', '2022', '2023'],
    datasets: [{
        label: 'Performance Appraisal Points',
        data: [2.5, 2.8, 3.0],
        borderColor: 'rgba(75, 192, 192, 1)',
        tension: 0.1,
        fill: false
    }]
};
