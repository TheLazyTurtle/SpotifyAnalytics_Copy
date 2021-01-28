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
    inputForm("date", "minDatePlayedAllSongs", "Vanaf datum", $minDatePlayedAllSongs);
    inputForm("date", "maxDatePlayedAllSongs", "Tot datum", $maxDatePlayedAllSongs);
    inputForm("text", "artistPlayedAllSongs", "Artiest naam", $artistPlayedAllSongs);
    submitForm("submitAllSongs");

    endForm();
}

function topSongsForm($artistTopSongs, $minDateTopSongs, $maxDateTopSongs) {
    startForm();

    inputForm("text", "artistTopSongs", "artiest naam", $artistTopSongs);
    inputForm("date", "minDateTopSongs", "Vanaf datum", $minDateTopSongs);
    inputForm("date", "maxDateTopSongs", "Tot datum", $maxDateTopSongs);
    submitForm("submitTopSongs");

    endForm();

}

// The settings form of played per day
function playedPerDayForm($playedPerDaySong) {
    startForm();

    inputForm("text", "playedPerDaySong", "Nummer naam", $playedPerDaySong);
    submitForm("submitPlayedPerDay");

    endForm();
}
?>

