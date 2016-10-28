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
	
	if(isset($_POST["selectCampSite"]) && !empty($_POST["selectCampSite"]) &&
	isset($_POST["bookStartDate"]) && !empty($_POST["bookStartDate"]) &&
	isset($_POST["bookEndDate"]) && !empty($_POST["bookEndDate"])
	) {
		saveUserCampSite(cleanInput($_POST["selectCampSite"]), cleanInput($_POST["bookStartDate"]), cleanInput($_POST["bookEndDate"]));
	}
	
	$bookingdates = getAllDates();
	$campsites = getAllCampSites();



?>

<h2><a href="data.php"> <--tagasi</a></h2>
<h1>Kasutaja leht</h1>

<?=$msg;?>
<p>
	Tere tulemast <?=$_SESSION["userFirstName"];?> <?=$_SESSION["userLastName"];?>!
	<br>
	<a href="?logout=1">Logi välja</a>
</p>
<br>


<!-- **** Platsi broneerimisvorm **** -->

<h2>Broneeri plats</h2>

<form method="POST">

	<label>Vali plats</label>
	<select name="selectCampSite" type="text">	
		<?php
		
			$listHtml = "";
			
			foreach($campsites as $c) {
				$listHtml .= "<option value='".$c->id."'>".$c->campsite."</option>";
			}
			echo $listHtml;
		
		?>
	</select>
	<br><br>
	
	<label>Vali algusaeg</label>
	<select name="bookStartDate" type="text">
		<?php
		
			$listHtml = "";
			
			foreach($bookingdates as $d) {
				$listHtml .= "<option value='".$d->id."'>".$d->fulldate."</option>";
			}
			echo $listHtml;
			
		?>
	</select>
	<br><br>
	
	<label>Vali lõpuaeg</label>
	<select name="bookEndDate" type="text">
		<?php
		
			$listHtml = "";
			
			foreach($bookingdates as $d) {
				$listHtml .= "<option value='".$d->id."'>".$d->fulldate."</option>";
			}
			echo $listHtml;
			
		?>
	</select>
	<br><br>
	
	
	<input type="submit" value="Broneeri">
	

</form>


















