/**
 * Contains all the functions that are ready to be 
 * executed upon the loading of a dom document, 
 * specifically, functions that are triggered by 
 * clicks.
 */
$(document).ready(function() {
	
	/**
	 * Loads in the homepage when the link
	 * to the homepage is clicked.
	 */
	$("a.homepage").click(function() {
		$.ajax( {
			url: "Invoker.php",
			cache: false,
			success: function(html) {
				$("body").html(html);
			}
		});
	});
	
	/**
	 * Loads in the comments page when
	 * the link to the comments page is 
	 * clicked.
	 */
	$("a.comments").click(function() {
		$.ajax( {
			url: "Comments.php",
			cache: false,
			success: function(html) {
				$("body").html(html);
			}
		});
	});
	
});