$(function () {

  'use strict';
	
	  
	// bootstrap WYSIHTML5 - text editor
	  $('.textarea').wysihtml5();

	  $('.daterange').daterangepicker({
		ranges   : {
		  'Today'       : [moment(), moment()],
		  'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
		  'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
		  'Last 30 Days': [moment().subtract(29, 'days'), moment()],
		  'This Month'  : [moment().startOf('month'), moment().endOf('month')],
		  'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		},
		startDate: moment().subtract(29, 'days'),
		endDate  : moment()
	  }, function (start, end) {
		window.alert('You chose: ' + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
	  });

	  // SLIMSCROLL FOR CHAT WIDGET
	  $('#direct-chat').slimScroll({
		height: '300px'
	  });

	// donut chart
			$('.donut').peity('donut');

	// chart
		$("#linechart").sparkline([1,4,3,7,6,4,8,9,6,8,12], {
				type: 'line',
				width: '100',
				height: '38',
				lineColor: '#ffffff',
				fillColor: '#4d7cff00',
				lineWidth: 2,
				minSpotColor: '#0fc491',
				maxSpotColor: '#0fc491',
			});

			$("#barchart").sparkline([1,4,3,7,6,4,8,9,6,8,12], {
				type: 'bar',
				height: '38',
				barWidth: 6,
				barSpacing: 4,
				barColor: '#ffffff',
			});
			$("#discretechart").sparkline([1,4,3,7,6,4,8,9,6,8,12], {
				type: 'discrete',
				width: '50',
				height: '38',
				lineColor: '#ffffff',
			});

	//sparkline charts
	$(document).ready(function() {
	   var sparklineLogin = function() { 

			$("#sparkline8").sparkline([2,4,4,6,8,5,6,4,8,6,6,2 ], {
				type: 'line',
				width: '100%',
				height: '55',
				lineColor: '#f2426d',
				fillColor: '#f2426d',
				maxSpotColor: '#f2426d',
				highlightLineColor: 'rgba(0, 0, 0, 0.2)',
				highlightSpotColor: '#f2426d'
			});

	   }
		var sparkResize;

			$(window).resize(function(e) {
				clearTimeout(sparkResize);
				sparkResize = setTimeout(sparklineLogin, 500);
			});
			sparklineLogin();

	});

		// Morris-chart AS WELL AS SECOND PART OF ACADEMIC PERFORMANCE

		Morris.Bar({
			element: 'morris-area-chart3',
			data: [
				{ year: '2019', phd: 10, masters: 20, degree_first: 50, degree_second: 80, certifications: 30 },
				{ year: '2020', phd: 15, masters: 25, degree_first: 60, degree_second: 90, certifications: 40 },
				{ year: '2021', phd: 12, masters: 22, degree_first: 55, degree_second: 85, certifications: 35 },
				{ year: '2022', phd: 18, masters: 28, degree_first: 70, degree_second: 95, certifications: 50 },
				{ year: '2023', phd: 14, masters: 24, degree_first: 65, degree_second: 100, certifications: 45 },
				{ year: '2024', phd: 20, masters: 30, degree_first: 75, degree_second: 110, certifications: 55 },
				{ year: '2025', phd: 25, masters: 35, degree_first: 80, degree_second: 120, certifications: 60 }
			],
			xkey: 'year', // The x-axis key
			ykeys: ['phd', 'masters', 'degree_first', 'degree_second', 'certifications'], // Keys for bar data
			labels: ['PhD', 'Masters', 'Degree (First Class)', 'Degree (Second Class)', 'Certifications'], // Labels for the bars
			barColors: ['#007bff', '#6610f2', '#6f42c1', '#e83e8c', '#ffc107'], // Distinct colors for each category
			stacked: false, // Standard bar graph (not stacked)
			hideHover: 'auto', // Hides hover details automatically
			resize: true // Makes it responsive
		});
	
	//donut chart to show applications wehre the publications are hosted
	var donut = new Morris.Donut({
		element: 'daily-inquery',
		resize: true,
		colors: ["#dc3545", "#ffc107", "#0d6efd", "#198754"],
		data: [
		  {label: "Google", value: 300},
		  {label: "Research Gate", value: 55},
		  {label: "Academia", value: 100},
		  {label: "Others", value: 10},
		],
		hideHover: 'auto'
	  });

	// WeatherIcon	
		WeatherIcon.add('icon1'	, WeatherIcon.SLEET , {stroke:false , shadow:false , animated:true } );	

	// AREA CHART
	 var area = new Morris.Area({
			element: 'revenue-chart',
			data: [{
				period: '2019',
				Citations: 0,
				Peer_reviewed: 0,

			}, {
				period: '2020',
				Citations: 130,
				Peer_reviewed: 100,

			}, {
				period: '2021',
				Citations: 30,
				Peer_reviewed: 60,

			}, {
				period: '2022',
				Citations: 30,
				Peer_reviewed: 200,

			}, {
				period: '2023',
				Citations: 200,
				Peer_reviewed: 150,

			}, {
				period: '2024',
				Citations: 105,
				Peer_reviewed: 90,

			},
			 {
				period: '2025',
				Citations: 250,
				Peer_reviewed: 150,

			}],
			xkey: 'period',
			ykeys: ['Citations', 'Peer_reviewed'],
			labels: ['Citations', 'Peer_reviewed'],
			pointSize: 0,
			fillOpacity: 0.4,
			pointStrokeColors:['#b4becb', '#01c0c8'],
			behaveLikeLine: true,
			gridLineColor: '#e0e0e0',
			lineWidth: 0,
			smooth: true,
			hideHover: 'auto',
			lineColors: ['#007bff', '#28a745'],
			resize: true        
		});

		// bar chart
		Morris.Bar({
			element: 'communityservice', // ID of the element where the chart will render
			data: [
				{ year: '2021/2022', supervision: 200, outreach: 150, clinical: 120 },
			],
			xkey: 'year', // X-axis key
			ykeys: ['supervision', 'outreach', 'clinical'], // Keys for the bar data
			labels: ['Student Supervision', 'Community Outreach', 'Clinical Practices'], // Labels for the bars
			barColors: ['#007bff', '#28a745', '#dc3545'], // Colors for each category
			stacked: false, // Non-stacked bar chart
			hideHover: 'auto', // Hides hover details automatically
			resize: true // Makes the chart responsive
		});

		// donout chart
		Morris.Donut({
			element: 'communityoutreachprograms', // ID of the element where the chart will render
			data: [
				{ label: "Awareness Campaigns", value: 40 },
				{ label: "Embedded Projects", value: 35 },
				{ label: "Workshops/Training", value: 25 }
			],
			colors: ['#ffc107', '#6610f2', '#dc3545'], // Colors for each slice
			resize: true // Makes the chart responsive
		});
		
		
	
}); // End of use strict


		