$(document).ready(function() {
    $(".primaryArtistSwitch").click(function() {
	var switched = $(".primaryArtistSwitch").prop("checked");

	$.ajax({
	    type: "GET",
	    url: './assets/header.php?primaryArtist=' + switched,
	    success: function(data) {
	    }
	})
    });
})
