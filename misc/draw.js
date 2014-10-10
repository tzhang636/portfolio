	

	var pathnames = new Array();
	var sizes = new Array();
	
	function insertToArrays(path, size) {
		document.write(path);
		pathnames.push(path);
		sizes.push(size);
	}
	
	function drawGraph(div_id) {
		document.write(div_id);
		google.load('visualization', '1.0', {'packages':['corechart']});
		google.setOnLoadCallback(function() { drawChart(div_id) });
	}
	
	function drawChart(div_id) {
		var data = new google.visualization.DataTable();      
		data.addColumn('string', 'filename');      
		data.addColumn('number', 'size');
		var i=0;
		while (i != pathnames.length) {
			data.addRow([ pathnames[i], sizes[i] ]);
		    i++;
		}
		      
		var options = {	'title': 'Files vs Sizes',
		      			'width': 400,
		      			'height': 300	};
		      					
		var chart = new google.visualization.BarChart(document.getElementById( chart_div )));
		chart.draw(data, options);
	}
	
	function clearArrays() {
		pathnames.length = 0;
		sizes.length = 0;
	}