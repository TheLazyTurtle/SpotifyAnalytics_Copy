$(document).ready(function() {
    $(".add-btn").click(function() {
	var val = $(this).attr("value");

	$.ajax({
	    type: "GET",
	    url: "./addRemoveAutoArtist.php?add=" + val,
	    success: function(data) {
		$(".auto-artist-" + val).attr("src", "https://openclipart.org/image/2400px/svg_to_png/202732/checkmark.png");
		console.log(data);
	    }
	})
    })

    $(".remove-btn").click(function() {
	var val = $(this).attr("value");

	$.ajax({
	    type: "GET",
	    url: "./addRemoveAutoArtist.php?remove=" + val,
	    success: function(data) {
		$(".auto-artist-" + val).attr("src", "http://www.pngall.com/wp-content/uploads/2016/04/Red-Cross-Mark-PNG.png");
	    }
	})
    })
})
