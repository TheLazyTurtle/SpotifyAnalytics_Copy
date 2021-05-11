<?php
// This checks if the jwt cookie is set and is valid. 
// If its not set or not valid than kill the connection and don't show the data.
// This is done to prevent api requests from people who aren't autenticated 
// to get the data they asked for.
function checkCookie() {
    $jwtCookie = isset($_COOKIE["jwt"]) ? $_COOKIE["jwt"] : null;

    if ($jwtCookie == null) {
	    die();
    }

    $data = array("jwt" => $jwtCookie);

    $options = array(
	    'http' => array(
		    'header' => 'Content-type: application/json',
		    'method' => 'POST',
		    'content' => http_build_query($data)
	    )
    );
    $context = stream_context_create($options);
    $result = file_get_contents("../../api/system/validate_token.php", false, $context);

    if ($result === false) {
	    die();
    }
}
?>
