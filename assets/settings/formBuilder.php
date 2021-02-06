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

    echo "<div class='$inputName'>";
    echo "<input type='$type' name='$inputName' placeholder='$placeholder' value='$value' autocomplete='off'>";
    echo "<div class='result'></div>";
    echo "</div>";
}

function submitForm($buttonName) {
    echo "<input type='submit' name='$buttonName' value='update'>";
}

function endForm() {
    echo '</form>';
}

?>

