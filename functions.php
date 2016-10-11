<?php

	require("../../../config.php");

	session_start();
	
	//**** SIGNUP ****
	
	function signUp ($email, $password, $firstname, $lastname, $day, $month, $year, $gender) {
		
		$database = "if16_karlerik";
			$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
			
			$stmt = $mysqli->prepare("INSERT INTO user_sample (email, password, firstname, lastname, day, month, year, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
			
			echo $mysqli->error;
			
			$stmt->bind_param("ssssiiis", $email, $password, $firstname, $lastname, $day, $month, $year, $gender);
			
			if($stmt->execute()) {
				echo "salvestamine õnnestus";	
			} else {
				echo "ERROR ".$stmt->error;
			}
			$stmt->close();
			$mysqli->close();
	}
	
	
	
	function login ($email, $password) {
		$error = "";
		$database = "if16_karlerik";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		$stmt = $mysqli->prepare("SELECT id, email, password, created, firstname, lastname FROM user_sample WHERE email = ?");
		
		echo $mysqli->error;
		$stmt->bind_param("s", $email);
		$stmt->bind_result($id, $emailFromDb, $passwordFromDb, $created, $firstNameDb, $lastNameDb);
		$stmt->execute();
		
		if($stmt->fetch()) {
			$hash = hash("sha512", $password);
			if($hash == $passwordFromDb) {
				echo "Kasutaja logis sisse ".$id;
				$_SESSION["userId"] = $id;
				$_SESSION["userEmail"] = $emailFromDb;
				$_SESSION["userFirstName"] = $firstNameDb;
				$_SESSION["userLastName"] = $lastNameDb;
				
				$_SESSEION["message"] = "<h1>Tere tulemast!</h1>";
				
				header("Location: data.php");
				exit();
				
			} else {
				$error = "vale parool";
			}
		} else {
			$error = "ei ole sellist emaili";
		}
		
		return $error;
		
	}
	
	
	function saveCar ($plate, $color) {
		
		$database = "if16_karlerik";
			$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
			
			$stmt = $mysqli->prepare("INSERT INTO cars_and_colors (plate, color) VALUES (?, ?)");
			
			echo $mysqli->error;
			
			$stmt->bind_param("ss", $plate, $color);
			
			if($stmt->execute()) {
				echo "salvestamine õnnestus";	
			} else {
				echo "ERROR ".$stmt->error;
			}
			$stmt->close();
			$mysqli->close();
	}
	
	
	function getAllCars() {
		
		$database = "if16_karlerik";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("
			SELECT id, plate, color FROM cars_and_colors
		");
		
		$stmt->bind_result($id, $plate, $color);
		$stmt->execute();
		
		//tekitan massiivi
		$result = array();
				
		//tee seda seni, kuni on rida andmeid mis vastab select lausele
		while($stmt->fetch()) {
			
			//tekitan objekti
			$car = new StdClass();
			
			$car->id = $id;
			$car->plate = $plate;
			$car->carColor = $color;
			
			
			//echo $plate."<br>";
			//iga kord lisan massiivi numbrimargi
			array_push($result, $car);
		}
						
		$stmt->close();
		$mysqli->close();
		
		return $result;
		
	}
	
	
	
	function cleanInput($input) {
		
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		
		return $input;
		
	}
	
	
	
	
	
	
	




?>