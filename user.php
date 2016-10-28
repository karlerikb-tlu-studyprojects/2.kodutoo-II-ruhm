<?php

	require("functions.php");
	
	if(!isset($_SESSION["userId"])) {
		header("Location: login.php");
		exit();
	}
	
	if(isset($_GET["logout"])) {
		session_destroy();
		header("Location: login.php");
		exit();
	}
	$msg = "";
	if(isset($_SESSION["message"])) {
		$msg = $_SESSION["message"];
		unset($_SESSION["message"]);
	}
	
	



?>

<h1><a href="data.php"> <--tagasi</a> Kasutaja leht</h1>
<?=$msg;?>
<p>
	Tere tulemast <?=$_SESSION["userFirstName"];?> <?=$_SESSION["userLastName"];?>
	<a href="?logout=1">Logi välja</a>
</p>