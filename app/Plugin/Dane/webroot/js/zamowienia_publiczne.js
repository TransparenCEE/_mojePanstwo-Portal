jQuery(document).ready(function () {
 	$('.mp-zamowienia_publiczne').each(function(index){
	 	
	 	var div = $(this);
	 	var highstock_div = div.find('.highstock');
	 	
	 	var url = decodeURIComponent(div.attr('data-url'));
	 	console.log('url', url);
	 	
	 	$.getJSON(url, function (data) {
		 	
		 	console.log('data', data);
		 	
	        // Create the chart
	        highstock_div.highcharts('StockChart', {
	
				chart: {
					height: 350,
					type: 'column'
				},
				
				navigator: {
					height: 100
				},
				
				credits: {
					enabled: false
				},
				
	            rangeSelector : {
	                selected : 1
	            },
	
	            title : {
	                text : 'Kwoty wydawane w zamówieniach publicznych:'
	            },
	
	            series : [{
	                name : 'Suma',
	                data : data['aggs']['dni'],
	                tooltip: {
	                    valueDecimals: 2
	                }
	            }]
	        });
	    });
	 	
 	}); 
});