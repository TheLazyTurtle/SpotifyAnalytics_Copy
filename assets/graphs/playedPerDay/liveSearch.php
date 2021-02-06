<script>
$(document).ready(function() {
    //This will auto complete the artist name
    $('.songPlayedPerDay input[type="text"]').on("keyup input", function () {
	// Get input on value change
	var inputSong = $(this).val();
	var resultDropdown = $(this).siblings(".result");
	if (inputSong.length) {
	    $.get("./assets/graphs/playedPerDay/liveSearchFetchName.php", {term: inputSong}).done(function(data) {
		//display the returned data
		resultDropdown.html(data);
	    });
	} else {
	    resultDropdown.empty();
	}

	$(document).on("click", ".result p", function() {
	    $(this).parents(".songPlayedPerDay").find("input[type='text']").val($(this).text());
	    $(".result").empty();
	})

	// Some magic stuff to automatically update the graph
	$.ajax({
	    type: "GET",
	    url: './assets/graphs/playedPerDay/updateData.php?song='+inputSong,
	    dataType: "json",
	    success: function(data) {
		updateGraph(data);
	    }
	})
    });

})

function updateGraph(data) {
    playedPerDay.options.data[0].dataPoints = [];
    playedPerDay.options.data[0].dataPoints = data;

    playedPerDay.render();
}
</script>
