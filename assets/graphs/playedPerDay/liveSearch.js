$(document).ready(function() {
    //This will auto complete the artist name
    $('.songPlayedPerDay input[type="text"]').on("keyup input", function () {
	// Get input on value change
	var inputSong = $(this).val();
	var resultDropdown = $(this).siblings(".songPlayedPerDayResult");
	if (inputSong.length) {
	    $.get("./assets/graphs/playedPerDay/liveSearchFetchName.php", {term: inputSong}).done(function(data) {
		//display the returned data
		resultDropdown.html(data);
	    });
	} else {
	    resultDropdown.empty();
	}

	$(document).on("click", ".songPlayedPerDayResult p", function() {
	    $(this).parents(".songPlayedPerDay").find("input[type='text']").val($(this).text());
	    $(".songPlayedPerDayResult").empty();
	    // Some magic stuff to automatically update the graph
	    $.ajax({
		type: "GET",
		url: './assets/graphs/playedPerDay/updateData.php?song='+$(this).text(),
		dataType: "json",
		success: function(data) {
		    updateGraphPPD(data);
		}
	    })
	})	
    });

    // This will update graph based on input
    $('.minDatePlayedPerDay input[type="date"]').on("keyup input", function() {
	// Get input value on change
	var inputMinDate = $(this).val();

	$.ajax({
	    type: "GET",
	    url: "./assets/graphs/playedPerDay/updateData.php?minDate="+inputMinDate,
	    dataType: "json",
	    success: function(data) {
		updateGraphPPD(data);
	    }
	})
    });

    // This will update graph based on input
    $('.maxDatePlayedPerDay input[type="date"]').on("keyup input", function() {
	// Get input value on change
	var inputMaxDate = $(this).val();

	$.ajax({
	    type: "GET",
	    url: "./assets/graphs/playedPerDay/updateData.php?maxDate="+inputMaxDate,
	    dataType: "json",
	    success: function(data) {
		updateGraphPPD(data);
	    }
	})
    });
})

function updateGraphPPD(data) {
    setTimeout(function() {
    playedPerDay.options.data[0].dataPoints = [];
    playedPerDay.options.data[0].dataPoints = data;

    playedPerDay.render();
    }, 2000);
}

