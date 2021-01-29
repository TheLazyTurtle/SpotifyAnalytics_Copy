<?php

// Different options to build the forms
function startForm() {
    echo '<form action="#" method="GET">';
}

function inputForm($type, $inputName, $placeholder, $value) {
    // If last result value is a % sign than leave it empty
    if ($value == "%") {
	$value = "";
    }

    echo "<input type='$type' name='$inputName' placeholder='$placeholder' value='$value'>";
}

function submitForm($buttonName) {
    echo "<input type='submit' name='$buttonName' value='update'>";
}

function endForm() {
    echo '</form>';
}

// The settings form for all songs ever played
function allSongsForm($minPlayedAllSongs, $maxPlayedAllSongs, $minDatePlayedAllSongs, $maxDatePlayedAllSongs, $artistPlayedAllSongs) {
    startForm();

    inputForm("number", "minPlayedAllSongs", "Minimaal afgespeeld", $minPlayedAllSongs);
    inputForm("number", "maxPlayedAllSongs", "Maximaal afgespeeld", $maxPlayedAllSongs);
    inputForm("text", "artistPlayedAllSongs", "Artiest naam", $artistPlayedAllSongs);
    inputForm("date", "minDatePlayedAllSongs", "Vanaf datum", $minDatePlayedAllSongs);
    inputForm("date", "maxDatePlayedAllSongs", "Tot datum", $maxDatePlayedAllSongs);
    submitForm("submitAllSongs");

    endForm();
}

function topSongsForm($artistTopSongs, $minDateTopSongs, $maxDateTopSongs, $amountTopSongs) {
    startForm();

    inputForm("text", "artistTopSongs", "artiest naam", $artistTopSongs);
    inputForm("number", "amountTopSongs", "Top hoeveel", $amountTopSongs);
    inputForm("date", "minDateTopSongs", "Vanaf datum", $minDateTopSongs);
    inputForm("date", "maxDateTopSongs", "Tot datum", $maxDateTopSongs);
    submitForm("submitTopSongs");

    endForm();

}

function topArtistForm($amountTopArtist, $minDateTopArtist, $maxDateTopArtist) {
    startForm();

    inputForm("number", "amountTopArtist", "Top hoeveel", $amountTopArtist);
    inputForm("date", "minDateTopArtist", "Vanaf datum", $minDateTopArtist);
    inputForm("date", "maxDateTopArtist", "Tot datum", $maxDateTopArtist);
    submitForm("submitTopArtist");

    endForm();
}

// The settings form of played per day
function playedPerDayForm($playedPerDaySong, $minDatePlayedPerDay, $maxDatePlayedPerDay) {
    startForm();

    inputForm("text", "playedPerDaySong", "Nummer naam", $playedPerDaySong);
    inputForm("date", "minDatePlayedPerDay", "Vanaf datum", $minDatePlayedPerDay);
    inputForm("date", "maxDatePlayedPerDay", "Tot datum", $maxDatePlayedPerDay);
    submitForm("submitPlayedPerDay");

    endForm();
}
?>

