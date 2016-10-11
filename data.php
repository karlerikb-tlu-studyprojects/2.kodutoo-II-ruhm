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

	
	
	if(isset($_POST["plate"]) && isset($_POST["color"]) &&
		!empty($_POST["plate"]) && !empty($_POST["color"])) {
			saveCar(cleanInput($_POST["plate"]),$_POST["color"]);
		}
	
	
	
	//saan koik autoandmed
	$carData = getAllCars();
	echo "<pre>";
	var_dump($carData);
	echo "</pre>";
	
	
?>



<h1>Data</h1>

<?=$msg;?>




<p>Tere tulemast <?=$_SESSION["userFirstName"];?> <?=$_SESSION["userLastName"];?></p>
<a href="?logout=1">Logi välja</a>

<form method="POST">
	<h2>Salvesta auto</h2>
	<input name="plate" type="text" placeholder="numbrimärk">
	<input name="color" type="color" placeholder="auto värv">
	<input type="submit" value="salvesta">
</form>



<h2>Autod</h2>


<?php
	
	
	$html = "<table>";
	
	$html .= "<tr>";
		$html .= "<th>id</th>";
		$html .= "<th>plate</th>";
		$html .= "<th>color</th>";
	$html .= "</tr>";
	
	
	//iga liikme kohta massiivis
	foreach($carData as $c) {
		//iga auto on $c
		//echo $c->plate."<br>";
		
		$html .= "<tr>";
			$html .= "<td>".$c->id."</td>";
			$html .= "<td>".$c->plate."</td>";
			$html .= "<td style='background-color:".$c->carColor."'>".$c->carColor."</td>";
		$html .= "</tr>";
		
	}
		
	$html .= "</table>";
	
	echo $html;
	

	$listHtml = "<br><br><br>";
	
	foreach($carData as $c) {
		
		$listHtml .= "<h1 style='color:".$c->carColor."'>".$c->plate."</h1>";
		$listHtml .= "<p>color = ".$c->carColor."</p>";
	}

	echo $listHtml





?>


























