<?php
require_once "../objects/user.php";
require_once "../config/database.php";

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

function hasViewingRights($userID, $userToCheck) {
	if (isAccountPublic($userID) || isAdmin($userID) || isFollowing($userID, $userToCheck)) {
		return True;
	}

	return False;
}

function isAdmin($userID) {
	global $user;
	return $user->isAdmin($userID);
}

function isAccountPublic($userID) {
	return False;
}

function isFollowing($userID, $usernameToCheck) {
	global $user;
	$userIDToCheck = $user->getUserIDByusername($usernameToCheck);
	$user->id = $userID;
	return $user->isFollowing($userIDToCheck);
}
?>
