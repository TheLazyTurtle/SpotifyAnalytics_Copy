<?php
require "connect.php";

/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$link = getConnection();
 
if(isset($_REQUEST["term"])){
    $test = $_REQUEST["term"];

    // Prepare a select statement
    $sql = mysqli_query($link, "SELECT * FROM song WHERE name LIKE '$test%'"); 
           
    // Check number of rows in the result set
    if(mysqli_num_rows($sql) > 0){
	// Fetch result rows as an associative array
	while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)){
	    echo "<p>" . $row["name"] . "</p>";
	}    
    }
}
 
// close connection
mysqli_close($link);
?>
