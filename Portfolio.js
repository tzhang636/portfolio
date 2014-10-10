/**
 * Contains all the functions that are ready to be 
 * executed upon the loading of a dom document, 
 * specifically, functions that are triggered by 
 * clicks.
 */
$(document).ready(function() {
		
	/**
	 * Hide all the contents upon loading.
	 */
	$(".accordionContent").hide();
	
	/**
	 * Upon a click to an accordion button, 
	 * 	- remove the on class from all buttons
	 *  - close all open slides
	 *  - add the on class to the button 
	 * 	- open the slide.
	 */
	$('.accordionButton').click(function() {
		$('.accordionButton').removeClass('on');
		$('.accordionContent').slideUp('normal');

		if($(this).next().is(':hidden') == true) {
			$(this).addClass('on');
			$(this).next().slideDown('normal');
		}
		
	 });
 
	/**
	 * Loads in the comments page when the 
	 * "leave comments" link is clicked.
	 */
	$("a.comments").click(function() {
		$.ajax( {
			url: "Comments.php",
			dataType: "html",
			success: function(html) {
				$("body").html(html);
			}
		});
	});
			
});

/**
 * Two arrays to hold the pathnames and data.
 */
var pathnames = Array();
var sizes = Array();

/**
 * Adds path and size to their respective arrays.
 * @param path - the path to add to pathnames
 * @param size - the size to add to sizes
 */
function addToArray(path, size) { 
	pathnames.push(path);
	sizes.push(size);
}

/**
 * Declares two new arrays for pathnames and sizes.
 */
function clearArray() {
	pathnames = Array();
	sizes = Array();
}

/**
 * Draws the graph by passing in the two arrays using temporary variables.
 * @param div_id - the id of the div in which to store the graph
 */
function drawGraph(div_id) { 
	google.load('visualization', '1.0', {'packages':['corechart']});
	var path_names = pathnames;
	var path_sizes = sizes;
	clearArray();
	google.setOnLoadCallback(function() {
		drawChart(div_id, path_names, path_sizes)
	});            
}  

/**
 * Callback function that was passed into google.setOnLoadCallback.
 * Instantiates the data table and options, and draws the chart.
 * @param div_id - the id of the div in which to store the graph
 * @param path_names - the array of path_names
 * @param path_sizes - the array of path_sizes
 */
function drawChart(div_id, path_names, path_sizes) {      
	var data = new google.visualization.DataTable();      
	data.addColumn('string', 'Filename');      
	data.addColumn('number', 'Size');
	data.addRows(path_names.length);

	var i=0;
	for (i=0; i<path_names.length; i++) {
		for (j=0; j<2; j++) {
			if (j==0)
				data.setValue(i, j, path_names[i]);
			else
				data.setValue(i, j, path_sizes[i]);
		}
	}
		
	var options = { 'title':'File vs Size', 'width': 700, 'height':400,
					'chartArea': { left:20, top:60, width:"50%", height:"75%" },
					'backgroundColor': '#E8E8E8' };      
	   
	var chart = new google.visualization.PieChart(document.getElementById(div_id));      
	chart.draw(data, options);
}