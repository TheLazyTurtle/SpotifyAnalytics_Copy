$(document).ready(function() {
    // Should change graph to days 
    $('#day').click(function() {
	$.ajax({
	    type: "GET",
	    url: "./assets/info_slider/sliderQueries.php?date=day",
	    dataType: "JSON",
	    success: function(data) {
		setData(data);
	    }
	})
    });

    $('#week').click(function() {
	$.ajax({
	    type: "GET",
	    url: "./assets/info_slider/sliderQueries.php?date=week",
	    dataType: "JSON",
	    success: function(data) {
		setData(data);
	    }
	})
    });

    $('#month').click(function() {
	$.ajax({
	    type: "GET",
	    url: "./assets/info_slider/sliderQueries.php?date=month",
	    dataType: "JSON",
	    success: function(data) {
		setData(data);
	    }
	})
    });

    $('#year').click(function() {
	$.ajax({
	    type: "GET",
	    url: "./assets/info_slider/sliderQueries.php?date=year",
	    dataType: "JSON",
	    success: function(data) {
		setData(data);
	    }
	})
    });

    $('#allTime').click(function() {
	$.ajax({
	    type: "GET",
	    url: "./assets/info_slider/sliderQueries.php?date=allTime",
	    dataType: "JSON",
	    success: function(data) {
		setData(data);
	    }
	})
    });

})

function setData(data) {
    // Top song text and img
    $('#topSong').html("Top song this " + data["time"] + ": " + data["topSong"]["name"] + " - " + data["topSong"]["times"]);
    $('#topSongImg').attr("src", data["topSong"]["img"]);

    // Top artist text and img
    $('#topArtist').html("Top artist this " + data["time"] + ": " + data["topArtist"]["name"] + " - " + data["topArtist"]["times"]);
    $('#topArtistImg').attr("src", data["topArtist"]["img"]);

    // Time listend text and img
    $('#timeListend').html("Time listend this " + data["time"] + ": " + data["timeListend"]["totalTime"]);
    $('#timeListendImg').attr('src', data['timeListend']["img"]);

    // Total songs listend text and img
    $('#amountSongs').html("Total songs listend this " + data["time"] + ": " + data["amountSongs"]["times"]);
    $('#amountSongsImg').attr('src', data["amountSongs"]["img"]);

    // New songs today text and img
    $('#amountNewSongs').html("New songs this " + data["time"] + ": " + data["amountNewSong"]["new"]);
    $('#amountNewSongsImg').attr('src', data["amountNewSong"]["img"]);
}
