$(document).ready(function() {
    // This will auto complete the artist name
    $('.artistTopSongs input[type="text"]').on("keyup input", function(){
	// Get input value on change
	var inputArtist = $(this).val();
	var resultDropdown = $(this).siblings(".artistTopSongsResult");

	if (inputArtist.length) {
	    $.get("./assets/graphs/topSongs/liveSearchFetchName.php", {term: inputArtist}).done(function(data) {
		// Display the returned data
		resultDropdown.html(data);
	    });
	} else {
	    resultDropdown.empty();
	    $.ajax({
		type: "GET",
		url: './assets/graphs/topSongs/updateData.php?artist=%',
		dataType: "json",
		success: function(data) {
		    updateGraphTS(data);
		}
	    })
	}
    });

    // If clicked set value
    $(document).on("click", ".artistTopSongsResult p", function() {
	$(this).parents(".artistTopSongs").find("input[type='text']").val($(this).text());

	$(".artistTopSongsResult").empty();
	$.ajax({
	    type: "GET",
	    url: './assets/graphs/topSongs/updateData.php?artist='+$(this).text(),
	    dataType: "json",
	    success: function(data) {
		updateGraphTS(data);
	    }
	})
    })

    //This will auto change amount
    $('.amountTopSongs input[type="number"]').on("keyup input", function() {
	// Get input on value change
	var inputAmount = $(this).val();

	// The magic stuff
	$.ajax({
	    type: "GET",
	    url: './assets/graphs/topSongs/updateData.php?amount='+inputAmount,
	    dataType: "json",
	    success: function(data) {
		updateGraphTS(data);
	    }
	})
    });

    // This will auto change min date
    $('.minDateTopSongs input[type="date"]').on("keyup input", function() {
	var inputMinDate = $(this).val();

	$.ajax({
	    type: "GET",
	    url: './assets/graphs/topSongs/updateData.php?minDate='+inputMinDate,
	    dataType: "json",
	    success: function(data) {
		updateGraphTS(data);
	    }
	})
    });

    // This will auto change max date
    $('.maxDateTopSongs input[type="date"]').on("keyup input", function() {
	var inputMaxDate = $(this).val();

	$.ajax({
	    type: "GET",
	    url: './assets/graphs/topSongs/updateData.php?maxDate='+inputMaxDate,
	    dataType: "json",
	    success: function(data) {
		updateGraphTS(data);
	    }
	})
    });
})

function updateGraphTS(data) {
	topSongs.options.data[0].dataPoints = [];

	for (var i = 0; i <= data.length-1; i++) {
	    topSongs.options.data[0].dataPoints.push(data[i]);
	}

	topSongs.render();

}
