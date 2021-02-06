<script>
$(document).ready(function() {
    //This will auto complete the artist name
    $('.artistPlayedPerDay input[type="text"]').on("keyup input", function () {
	// Get input on value change
	var inputArtist = $(this).val();
	var resultDropdown = $(this).siblings(".result");
	if (inputArtist.length) {
	    $.get("./assets/graphs/playedPerDay/liveSearchFetchName.php", {term: inputArtist}).done(function(data) {
		//display the returned data
		resultDropdown.html(data);
	    });
	} else {
	    resultDropdown.empty();
	}

	// Some magic stuff to automatically update the graph
	$.ajax({
	    type: "GET",
	    url: './assets/graphs/playedPerDay/updateData.php?artist='+inputArtist,
	    dataType: "json",
	    success: function(data) {
		updateGraph(data);
	    }
	})
    });

})

function updateGraph(data) {
    chart.options.data[0].dataPoints = [];

    for (var i = 0; i <= data.length; i++) {
	chart.options.data[0].dataPoints.push(data[i]);
    }

    chart.render();
}
</script>
