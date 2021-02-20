$(document).ready(function() {
    $("#funInfoSliderJ").load("./assets/info_slider/slider.php");
    // Initial load of the slider
    $.ajax({
	type: "GET",
	url: "./assets/info_slider/sliderQueries.php?date=day",
	dataType: "JSON",
	success: function(data) {
	    setData(data);
	}
    });


    $("#allSongsPlayedJ").load("./assets/graphs/allSongsPlayed/allSongsPlayed.php");
    $("#topSongsJ").load("./assets/graphs/topSongs/topSongs.php");
    $("#topArtistsJ").load("./assets/graphs/topArtist/topArtist.php");
    $("#playedPerDayJ").load("./assets/graphs/playedPerDay/playedPerDay.php");
})
