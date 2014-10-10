/**
 * Contains all the functions that are ready to be 
 * executed upon the loading of a dom document, 
 * specifically, functions that are triggered by 
 * clicks.
 */
$(document).ready(function() {
	
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
	 * Hide all the contents upon loading.
	 */
	$(".accordionContent").hide();
	
	/**
	 * Loads in the homepage when the 
	 * homepage button is clicked.
	 */
	$("a.homepage").click(function() {
		$.ajax( {
			type: "GET",
			dataType: "html",
			url: "Invoker.php",
			cache: false,
			success: function(html) {
				$("body").html(html);	
			}
		});
	});
	
	/**
	 * Loads in the query page when the submit
	 * button is pressed (to submit a comment). 
	 */
    $(".comment_submit").click(function() {
    	var element = $(this);
    	var id = element.attr("id");
    	
    	var comment = $("#comment"+id).val();
    	var assignment = $("#assignment"+id).val();
		var parent_id = $("#parent_id"+id).val();
		
		var dataString = "assignment="+assignment + "&parent_id="+parent_id + "&comment="+comment;
		
		if (comment == "") {
			alert("Please enter a comment");
		}
		else {
			$.ajax( {
				type: "POST",
				url: "Query.php",
				data: dataString,
				cache: false,
				success: function(html) {
					$("body").html(html);
				}
			});
							
		}
		return false;
    });
    
});

/**
 * Toggers comment form visibility.
 * @param id - the id of the comment form
 */
function toggle_visibility(id) {
	var e = document.getElementById(id);
	if(e.style.display == 'block')
		e.style.display = 'none';
	else
		e.style.display = 'block';
}