<?php

	require("../../../config.php");

	session_start();
	
	//**** SIGNUP ****
	
	function signUp ($email, $password, $firstname, $lastname, $day, $month, $year, $gender, $country, $address, $phonenumber) {
		
		$database = "if16_karlerik";
			$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
			
			$stmt = $mysqli->prepare("INSERT INTO bron_kasutajad (email, password, firstname, lastname, day, month, year, gender, country, address, phonenumber) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			
			echo $mysqli->error;
			
			$stmt->bind_param("ssssiiissss", $email, $password, $firstname, $lastname, $day, $month, $year, $gender, $country, $address, $phonenumber);
			
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
		$stmt = $mysqli->prepare("SELECT id, email, password, firstname, lastname FROM bron_kasutajad WHERE email = ?");
		
		echo $mysqli->error;
		$stmt->bind_param("s", $email);
		$stmt->bind_result($id, $emailFromDb, $passwordFromDb, $firstNameDb, $lastNameDb);
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
	
	
	
	function getAllUsers() {
		
		$database = "if16_karlerik";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("
			SELECT id, firstname, lastname, email, gender, dateofbirth, country, address, phonenumber FROM bron_kasutajad
		");
		
		$stmt->bind_result($id, $firstname, $lastname, $email, $gender, $dateofbirth, $country, $address, $phonenumber);
		$stmt->execute();
		
		//tekitan massiivi
		$result = array();
				
		//tee seda seni, kuni on rida andmeid mis vastab select lausele
		while($stmt->fetch()) {
			
			//tekitan objekti
			$user = new StdClass();
			
			$user->id = $id;
			$user->firstname = $firstname;
			$user->lastname = $lastname;
			$user->email = $email;
			$user->gender = $gender;
			$user->dateofbirth = $dateofbirth;
			$user->country = $country;
			$user->address = $address;
			$user->phonenumber = $phonenumber;
			
			
			//echo $firstname."<br>";
			//iga kord lisan massiivi numbrimargi
			array_push($result, $user);
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