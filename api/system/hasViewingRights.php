<?php
require_once "../objects/user.php";
require_once "../config/database.php";

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

function hasViewingRights($userID, $userToCheck) {
	return isAccountPublic($userToCheck) || isAdmin($userID) || isFollowing($userID, $userToCheck);
}

function isAdmin($userID) {
	global $user;
	return $user->checkIfAdmin($userID);
}

function isAccountPublic($userID) {
	global $user;
	return $user->isAccountPublic($userID);
}

function isFollowing($userID, $usernameToCheck) {
	global $user;
	$userIDToCheck = $user->getUserIDByusername($usernameToCheck);
	$user->id = $userID;
	return $user->isFollowing($userIDToCheck);
}
?>
