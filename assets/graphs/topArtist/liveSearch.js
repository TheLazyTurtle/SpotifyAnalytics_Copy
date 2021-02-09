$(document).ready(function() {
    // This will update graph based on input
    $('.amountTopArtist input[type="number"]').on("keyup input", function() {
	// Get input value on change
	var inputAmount = $(this).val();

	$.ajax({
	    type: "GET",
	    url: 'http://localhost/assets/graphs/topArtist/updateData.php?amount='+inputAmount,
	    dataType: "json",
	    success: function(data) {
		updateGraphTA(data);
	    }
	})
    });

    // This will update graph based on input
    $('.minDateTopArtist input[type="date"]').on("keyup input", function() {
	// Get input value on change
	var inputMinDate = $(this).val();

	$.ajax({
	    type: "GET",
	    url: 'http://localhost/assets/graphs/topArtist/updateData.php?minDate='+inputMinDate,
	    dataType: "json",
	    success: function(data) {
		updateGraphTA(data);
	    }
	})
    });

     // This will update graph based on input
    $('.maxDateTopArtist input[type="date"]').on("keyup input", function() {
	// Get input value on change
	var inputMaxDate = $(this).val();

	$.ajax({
	    type: "GET",
	    url: 'http://localhost/assets/graphs/topArtist/updateData.php?maxDate='+inputMaxDate,
	    dataType: "json",
	    success: function(data) {
		updateGraphTA(data);
	    }
	})
    });
})

function updateGraphTA(data) {
    topArtists.options.data[0].dataPoints = [];

    for (var i = 0; i <= data.length-1; i++) {
	topArtists.options.data[0].dataPoints.push(data[i]);
    }

    topArtists.render();
}
