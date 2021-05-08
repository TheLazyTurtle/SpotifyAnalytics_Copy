<?php
require 'header.php';

?>

<body>
    <h1>Welcome my friend</h1>
    <h2>Lets make a api request for songs</h2>
    
<script>
    $.ajax({
	url: "api/song/read.php",
	type: "post",
	contenType: "application/json",
	success: function(result) {
	    console.table(result["records"]);
	}
    });
</script>

</body>
</html>
