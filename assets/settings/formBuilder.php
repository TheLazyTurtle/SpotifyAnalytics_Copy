<?php

// Different options to build the forms
function startForm() {
    echo '<form action="#" method="GET">';
}

function inputForm($type, $inputName, $placeholder, $value, $result = False) {
    // If last result value is a % sign than leave it empty
    if ($value == "%") {
	$value = "";
    }

    echo "<div class='$inputName'>";
    echo "<input type='$type' name='$inputName' placeholder='$placeholder' value='$value' autocomplete='off'>";

    if ($result) {
	$res = $inputName."Result";
	echo "<div class='$res'></div>";
    }

    echo "</div>";
}

function endForm() {
    echo '</form>';
}

?>

