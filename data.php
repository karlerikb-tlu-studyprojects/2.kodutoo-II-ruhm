<?php

	require("functions.php");
	
	if(!isset($_SESSION["userId"] )) {
		header("Location: login.php");
		exit();
	}
	
	if(isset($_GET["logout"] )) {
		session_destroy();
		header("Location: login.php");
		exit();
	}
	
	$msg = "";
	if(isset($_SESSION["message"] )) {
		$msg = $_SESSION["message"];
		
		//kui uhe naitame siis kustuta ara, et parast refreshi ei naitaks
		unset($_SESSION["message"] );
	}
	
	if(isset($_POST["campSite"]) && !empty($_POST["campSite"]) &&
	isset($_POST["bookDayStart"]) && !empty($_POST["bookDayStart"]) &&
	isset($_POST["bookMonthStart"]) && !empty($_POST["bookMonthStart"]) &&
	isset($_POST["bookYearStart"]) && !empty($_POST["bookYearStart"]) &&
	isset($_POST["bookDayEnd"]) && !empty($_POST["bookDayEnd"]) &&
	isset($_POST["bookMonthEnd"]) && !empty($_POST["bookMonthEnd"]) &&
	isset($_POST["bookYearEnd"]) && !empty($_POST["bookYearEnd"])
	) {
		saveBookingDate(cleanInput($_POST["campSite"]),
			cleanInput($_POST["bookDayStart"]), cleanInput($_POST["bookMonthStart"]), cleanInput($_POST["bookYearStart"]),
			cleanInput($_POST["bookDayEnd"]), cleanInput($_POST["bookMonthEnd"]), cleanInput($_POST["bookYearEnd"])
			);
	}
	
	$bookingData = getAllBookings();
	

	
	/*
	if(isset($_POST["plate"]) && isset($_POST["color"]) &&
		!empty($_POST["plate"]) && !empty($_POST["color"])) {
			saveCar(cleanInput($_POST["plate"]), cleanInput($_POST["color"]));
		}
	*/
	
	
	$userData = getAllUsers();
	
	//echo "<pre>";
	//var_dump($userData);
	//echo "</pre>";
	
	
?>



<h1>Data</h1>

<?=$msg;?>




<p>Tere tulemast <?=$_SESSION["userFirstName"];?> <?=$_SESSION["userLastName"];?></p>
<a href="?logout=1">Logi välja</a>

<form method="POST">
	<h2>Broneeri plats</h2>
	
	<label>Vali plats</label><br>
	<select name="campSite">
		<option value="campSite_1">Plats 1</option>
		<option value="campSite_2">Plats 2</option>
		<option value="campSite_3">Plats 3</option>
		<option value="campSite_4">Plats 4</option>
		<option value="campSite_5">Plats 5</option>
	</select>
	<br><br>
	
	<label>Broneeringu algus</label><br>
	<input name="bookDayStart" type="number" min="1" max="31" placeholder="Päev">
	<input name="bookMonthStart" type="number" min="1" max="12" placeholder="Kuu">
	<input name="bookYearStart" type="number" value="2016" min="2016" max="2016" placeholder="Aasta">
	<br><br>
	
	<label>Broneeringu lõpp</label><br>
	<input name="bookDayEnd" type="number" min="1" max="31" placeholder="Päev">
	<input name="bookMonthEnd" type="number" min="1" max="12" placeholder="Kuu">
	<input name="bookYearEnd" type="number" min="2016" max="2018" placeholder="Aasta">
	<br><br>
	
	<input type="submit" value="Broneeri">
	<input type="reset" value="Tühista">


</form>

<br><br>
<h2>Broneeringud</h2>

<?php

	$html = "<table>";
	
	$html .= "<tr>";
		$html .= "<th>id</th>";
		$html .= "<th>campsite</th>";
		$html .= "<th>startdate</th>";
		$html .= "<th>enddate</th>";
	$html .= "</tr>";
	
	foreach($bookingData as $b) {
		$html .= "<tr>";
			$html .= "<td>".$b->id."</td>";
			$html .= "<td>".$b->campsite."</td>";
			$html .= "<td>".$b->startdate."</td>";
			$html .= "<td>".$b->enddate."</td>";
		$html .= "</tr>";
	}
	
	$html .= "</table>";
	
	echo $html;

?>


<!--
<form method="POST">
	<h2>Salvesta auto</h2>
	<input name="plate" type="text" placeholder="numbrimärk">
	<input name="color" type="color" placeholder="auto värv">
	<input type="submit" value="salvesta">
</form>
-->



<h2>Kasutajatabel</h2>


<?php
	
	
	$html = "<table>";
	
	$html .= "<tr>";
		$html .= "<th>id</th>";
		$html .= "<th>firstname</th>";
		$html .= "<th>lastname</th>";
		$html .= "<th>email</th>";
		$html .= "<th>gender</th>";
		$html .= "<th>date of birth</th>";
		$html .= "<th>country</th>";
		$html .= "<th>address</th>";
		$html .= "<th>phonenumber</th>";
	$html .= "</tr>";
	
	
	//iga liikme kohta massiivis
	foreach($userData as $u) {
		//iga auto on $c
		//echo $c->plate."<br>";
		
		$html .= "<tr>";
			$html .= "<td>".$u->id."</td>";
			$html .= "<td>".$u->firstname."</td>";
			$html .= "<td>".$u->lastname."</td>";
			$html .= "<td>".$u->email."</td>";
			$html .= "<td>".$u->gender."</td>";
			$html .= "<td>".$u->dateofbirth."</td>";
			$html .= "<td>".$u->country."</td>";
			$html .= "<td>".$u->address."</td>";
			$html .= "<td>".$u->phonenumber."</td>";
		$html .= "</tr>";
		
	}
		
	$html .= "</table>";
	
	echo $html;
	

	$listHtml = "<br><br><br>";
	
	




?>


























