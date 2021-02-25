$(document).ready(function() {
    // If you press the add button it will make your artist primary
    $('.primary-add-btn').click(function() {
	var value = $(this).attr("value");

	$.ajax({
	    type: "GET",
	    url: "./primarySwitcher.php?add=" + value,
	    dataType: "text",
	    success: function(data) {
		$(".primary-check-" + value).attr("src", "https://openclipart.org/image/2400px/svg_to_png/202732/checkmark.png");
	    }
	})
    });

    // If you press the remove button this will remove it as primary artist
    $('.primary-remove-btn').click(function() {
	var value = $(this).attr("value");

	$.ajax({
	    type: "GET",
	    url: "./primarySwitcher.php?remove=" + value,
	    dataType: "text",
	    success: function(data) {
		$(".primary-check-" + value).attr("src", "http://www.pngall.com/wp-content/uploads/2016/04/Red-Cross-Mark-PNG.png");
	    }
	})
    })
});
