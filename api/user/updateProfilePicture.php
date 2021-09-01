<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

session_start();

include '../config/database.php';
include '../objects/user.php';

// make db and user object
$database = new Database();
$db = $database->getConnection();
$user = new User($db);

// Process the img
$target_dir = realpath(dirname(getcwd())) . "/../uploads/profile/";
$fileName = basename($_FILES["file"]["name"]);
$imageFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
$fileName = $_COOKIE["username"] . "." . $imageFileType;
$target_file = $target_dir . $fileName;
$uploadOk = 1;

// Check if image file is a actual image or fake image
if (isset($_POST["file"])) {
	$check = getimagesize($_FILES["file"]["tmp_name"]);
	if ($check !== false) {
		$uploadOk = 1;
	} else {
		echo json_encode(array("message" => "File is not an image"));
		$uploadOk = 0;
		die();
	}
}

// Check file size
// Max file size = 800kb
if ($_FILES["file"]["size"] > 800000) {
	http_response_code(400);
	echo json_encode(array("message" => "File is too big"));
	$uploadOk = 0;
	die();
}

// Allow certain file formats
if (
	$imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif"
) {
	http_response_code(400);
	echo json_encode(array("message" => "Unsupported file format"));
	$uploadOk = 0;
	die();
}

// If everything is ok then upload
if ($uploadOk == 1) {
	$user->img = "/uploads/profile/" . $fileName;
	$user->id = $_SESSION["userID"];

	// If the file already exists then remove it
	if (file_exists($target_file)) {
		unlink($target_file);
		$uploadOk = 1;
	}

	// Move the new file into the directory
	if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file) && $user->updateProfilePicture()) {
		http_response_code(200);
		echo json_encode(array("message" => "The file has been uploaded"));
	} else {
		http_response_code(503);

		echo json_encode(array("message" => "There was an error uploading the file"));
	}
}
