<?php

function getSettings($userID, $type) {
    $connection = getConnection();
    $query = "SELECT * FROM user_settings WHERE userID = '$userID' AND type='$type'";
    $res = mysqli_query($connection, $query);

    $value = mysqli_fetch_assoc($res);

    mysqli_free_result($res);
    mysqli_close($connection);

    return $value;
}

function checkSettingExists($userID, $type) {
    $connection = getConnection();
    $query = "SELECT * FROM user_settings WHERE userID = '$userID' AND type = '$type'";
    $res = mysqli_query($connection, $query);

    $exists = mysqli_num_rows($res);
    mysqli_free_result($res);
    mysqli_close($connection);

    return $exists;
}

function updateSetting($userID, $type, $value) {
    $connection = getConnection();
    $query = "UPDATE user_settings SET value = '$value' WHERE type = '$type' AND userID = '$userID'";
    mysqli_query($connection, $query);
    mysqli_close($connection);
}

function makeSetting($userID, $type, $value) {
    $connection = getConnection();
    $query = "INSERT INTO user_settings (userID, type, value) VALUES ('$userID', '$type', '$value')";
    mysqli_query($connection, $query);
    mysqli_close($connection);
}

function savedSettings($userID, $settings) {
    $savedSettings = array();

    foreach ($settings as $setting) {
	if (checkSettingExists($userID, $setting)) {
	    $savedSettings[$setting] = getSettings($userID, $setting);
	}
    }
    return $savedSettings;
}
?>
