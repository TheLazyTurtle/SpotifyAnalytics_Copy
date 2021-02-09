<script>
$(document).ready(function() {
    // This will auto compelete the artist name
    $('.artistPlayedAllSongs input[type="text"]').on("keyup input", function() {
	// Get input value on change
	var inputArtist = $(this).val();
	var resultDropdown = $(this).siblings(".artistPlayedAllSongsResult");
	if (inputArtist.length) {
	    $.get("http://localhost/assets/graphs/allSongsPlayed/liveSearchFetchName.php", {term: inputArtist}).done(function(data){
		// Display the returned data
		resultDropdown.html(data);
	    });
	} else {
	    resultDropdown.empty();
	}

	//Some magic stuff to automatically update the graph 
	$.ajax({
	    type: "GET",
	    url: 'http://localhost/assets/graphs/allSongsPlayed/updateData.php?artist='+inputArtist,
	    dataType: "json",
	    success: function(data) {
		updateGraphASP(data);
	    }
	})
    });

    // Set search input value on click of result item
    $(document).on("click", ".artistPlayedAllSongsResult p", function() {
	$(this).parents(".artistPlayedAllSongs").find("input[type='text']").val($(this).text());	
	$(".artistPlayedAllSongsResult").empty();
    })

    // This will auto change min played 
    $('.minPlayedAllSongs input[type="number"]').on("keyup input", function() {
	// Get input value on change
	var inputMinPlayed = $(this).val();
	   
	//Some magic stuff to automatically update the graph 
	$.ajax({
	    type: "GET",
	    url: 'http://localhost/assets/graphs/allSongsPlayed/updateData.php?minPlayed='+inputMinPlayed,
	    dataType: "json",
	    success: function(data) {
		updateGraphASP(data);
	    }
	})
    });

    // This will auto change max played 
    $('.maxPlayedAllSongs input[type="number"]').on("keyup input", function() {
	// Get input value on change
	var inputMaxPlayed = $(this).val();
	   
	//Some magic stuff to automatically update the graph 
	$.ajax({
	    type: "GET",
	    url: 'http://localhost/assets/graphs/allSongsPlayed/updateData.php?maxPlayed='+inputMaxPlayed,
	    dataType: "json",
	    success: function(data) {
		updateGraphASP(data);
	    }
	})
    });

    // This will auto change min date 
    $('.minDatePlayedAllSongs input[type="date"]').on("keyup input", function() {
	// Get input value on change
	var inputMinDate = $(this).val();
	   
	//Some magic stuff to automatically update the graph 
	$.ajax({
	    type: "GET",
	    url: 'http://localhost/assets/graphs/allSongsPlayed/updateData.php?minDate='+inputMinDate,
	    dataType: "json",
	    success: function(data) {
		updateGraphASP(data);
	    }
	})
    });

    // This will auto change max date 
    $('.maxDatePlayedAllSongs input[type="date"]').on("keyup input", function() {
	// Get input value on change
	var inputMaxDate = $(this).val();
	   
	//Some magic stuff to automatically update the graph 
	$.ajax({
	    type: "GET",
	    url: 'http://localhost/assets/graphs/allSongsPlayed/updateData.php?maxDate='+inputMaxDate,
	    dataType: "json",
	    success: function(data) {
		updateGraphASP(data);
	    }
	})
    });

})

function updateGraphASP(data) {
    chart.options.data[0].dataPoints = [];

    for (var i = 0; i <= data.length-1; i++) {
	chart.options.data[0].dataPoints.push(data[i]);
    }

    chart.render();
}
</script>
