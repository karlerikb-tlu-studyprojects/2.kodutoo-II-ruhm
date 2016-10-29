<?php

	require("../../../config.php");

	session_start();
	
	//**** SIGNUP ****
	
	function signUp ($email, $password, $firstname, $lastname, $day, $month, $year, $gender, $country, $address, $phonenumber) {
		
		$database = "if16_karlerik";
			$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
			
			$stmt = $mysqli->prepare("INSERT INTO booking_users (email, password, firstname, lastname, day, month, year, gender, country, address, phonenumber, dateofbirth) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			
			echo $mysqli->error;
					
			$dateofbirth = $year."-".$month."-".$day;
			
			$stmt->bind_param("ssssiiisssss", $email, $password, $firstname, $lastname, $day, $month, $year, $gender, $country, $address, $phonenumber, $dateofbirth);
			
			if($stmt->execute()) {
				echo "salvestamine õnnestus";	
			} else {
				echo "ERROR ".$stmt->error;
			}
			
			
			$stmt->close();
			$mysqli->close();
	}
	
	
	//**** LOGIN ****
	
	function login ($email, $password) {
		$error = "";
		$database = "if16_karlerik";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		$stmt = $mysqli->prepare("SELECT id, email, password, firstname, lastname FROM booking_users WHERE email = ?");
		
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
				
				$_SESSION["message"] = "<h1>Tere tulemast!</h1>";
				
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
	
	
	//**** DATE FUNCTIONS ****
	
	/*function saveDate ($dateday, $datemonth, $dateyear) {
		
		$database = "if16_karlerik";
			$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
			
			$stmt = $mysqli->prepare("INSERT INTO booking_dates (dateday, datemonth, dateyear, fulldate) VALUES (?, ?, ?, ?)");
			echo $mysqli->error;
			
			$fulldate = $dateyear."-".$datemonth."-".$dateday;
			
			$stmt->bind_param("iiis", $dateday, $datemonth, $dateyear, $fulldate);
			
			if($stmt->execute()) {
				echo "Kuupäeva salvestamine õnnestus!";
			} else {
				echo "ERROR ".$stmt->error;
			}
			
			$stmt->close();
			$mysqli->close();
	}*/
	
	
	function getAllStartDates() {
		
		$database = "if16_karlerik";
			
			$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
			
			$stmt = $mysqli->prepare("SELECT id, fulldate_start FROM booking_dates_start");
			$stmt->bind_result($id, $fulldate);
			$stmt->execute();
			
			$result = array();
			
			while($stmt->fetch()) {
				$uniquedate = new StdClass();
				
				$uniquedate->id = $id;
				$uniquedate->fulldate_start = $fulldate;
				
				array_push($result, $uniquedate);
			}
			
			$stmt->close();
			$mysqli->close();
			
			return $result;
	}
	
	function getAllEndDates() {
		
		$database = "if16_karlerik";
			
			$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
			
			$stmt = $mysqli->prepare("SELECT id, fulldate_end FROM booking_dates_end");
			$stmt->bind_result($id, $fulldate);
			$stmt->execute();
			
			$result = array();
			
			while($stmt->fetch()) {
				$uniquedate = new StdClass();
				
				$uniquedate->id = $id;
				$uniquedate->fulldate_end = $fulldate;
				
				array_push($result, $uniquedate);
			}
			
			$stmt->close();
			$mysqli->close();
			
			return $result;
	}
	
	
	//**** CAMPSITE FUNCTIONS ****
	
	function saveCampSite ($campsite) {
		
		$database = "if16_karlerik";
			
			$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
			
			$stmt = $mysqli->prepare("INSERT INTO booking_campsites (campsite) VALUES (?)");
			echo $mysqli->error;
			$stmt->bind_param("s", $campsite);
			
			if($stmt->execute()) {
				echo "Platsi lisamine õnnestus!";
			} else {
				echo "ERROR ".$stmt->error;
			}
			
			$stmt->close();
			$mysqli->close();
	}
	
	function getAllCampSites() {
		
		$database = "if16_karlerik";
			
			$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
			
			$stmt = $mysqli->prepare("SELECT id, campsite FROM booking_campsites");
			echo $mysqli->error;
			$stmt->bind_result($id, $campsite);
			$stmt->execute();
			
			$result = array();
			while($stmt->fetch()) {
				$c = new StdClass();
				
				$c->id = $id;
				$c->campsite = $campsite;
				
				array_push($result, $c);
			}
			
			$stmt->close();
			$mysqli->close();
			
			return $result;
	}
	
	
	// **** USER BOOKING FUNCTIONS ****
	
	function saveUserCampSite($campsite, $startdate, $enddate) {
		
		$database = "if16_karlerik";
			
			$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
			
			$stmt = $mysqli->prepare("INSERT INTO booking_reserv (userid, campsiteid, startdateid, enddateid) VALUES (?, ?, ?, ?)");
			echo $mysqli->error;
			$stmt->bind_param("iiii", $_SESSION["userId"], $campsite, $startdate, $enddate);
			
			if($stmt->execute()) {
				echo "Broneerimine õnnestus!";
			} else {
				echo "ERROR ".$stmt->error;
			}
	}
	
	function getAllUserBookings() {
		$database = "if16_karlerik";
			$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
			
			$stmt = $mysqli->prepare("
				SELECT campsite, fulldate_start, fulldate_end
				FROM booking_campsites c
				JOIN booking_reserv r ON c.id=r.campsiteid
				JOIN booking_dates_start s ON r.startdateid=s.id
				JOIN booking_dates_end e ON r.enddateid=e.id
				WHERE r.userid = ?
			");
			echo $mysqli->error;
			$stmt->bind_param("i", $_SESSION["userId"]);
			
			$stmt->bind_result($campsite, $startdate, $enddate);
			$stmt->execute();
			
			$result = array();
			while($stmt->fetch()) {
				$b = new StdClass();
				
				$b->campsite = $campsite;
				$b->fulldate_start = $startdate;
				$b->fulldate_end = $enddate;
				
				array_push($result, $b);
			}
			$stmt->close();
			$mysqli->close();
			return $result;
	}
	
	
	/*function getUserCampSites() {
		$database = "if16_karlerik";
			$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
			$stmt = $mysqli->prepare("
				SELECT campsite FROM booking_campsites
				JOIN booking_reserv ON booking_campsites.id=booking_reserv.campsiteid
				WHERE booking_reserv.userid = ?
			");
			echo $mysqli->error;
			$stmt->bind_param("i", $_SESSION["userId"]);
			$stmt->bind_result($campsite);
			$stmt->execute();
			
			$result = array();
			while ($stmt->fetch()) {
				$c = new StdClass();
				$c->campsite = $campsite;
				array_push($result, $c);
			}
			$stmt->close();
			$mysqli->close();
			return $result;
	}*/
	
	/*function getUserBookStart() {
		$database = "if16_karlerik";
			$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
			$stmt = $mysqli->prepare("
				SELECT fulldate FROM booking_dates
				JOIN booking_reserv ON booking_dates.id=booking_reserv.startdateid
				WHERE booking_reserv.userid = ?
			");
			echo $mysqli->error;
			$stmt->bind_param("i", $_SESSION["userId"]);
			$stmt->bind_result($fulldate);
			$stmt->execute();
			
			$result = array();
			while ($stmt->fetch()) {
				$s = new StdClass();
				$s->fulldate = $fulldate;
				array_push($result, $s);
			}
			$stmt->close();
			$mysqli->close();
			return $result;
	}*/
	
	/*function getUserBookEnd() {
		$database = "if16_karlerik";
			$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
			$stmt = $mysqli->prepare("
				SELECT fulldate FROM booking_dates
				JOIN booking_reserv ON booking_dates.id=booking_reserv.enddateid
				WHERE booking_reserv.userid = ?
			");
			echo $mysqli->error;
			$stmt->bind_param("i", $_SESSION["userId"]);
			$stmt->bind_result($fulldate);
			$stmt->execute();
			
			$result = array();
			while ($stmt->fetch()) {
				$e = new StdClass();
				$e->fulldate = $fulldate;
				array_push($result, $e);
			}
			$stmt->close();
			$mysqli->close();
			return $result;
	}*/
		
	/*saveBookingDate
	function saveBookingDate ($campsite, $daystart, $monthstart, $yearstart, $dayend, $monthend, $yearend) {
		
		$database = "if16_karlerik";
		
			$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
			
			$stmt = $mysqli->prepare("INSERT INTO bookings (campsite, daystart, monthstart, yearstart, dayend, monthend, yearend, startdate, enddate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
			echo $mysqli->error;
			
			$startdate = $yearstart."-".$monthstart."-".$daystart;
			$enddate = $yearend."-".$monthend."-".$dayend;
			
			$stmt->bind_param("siiiiiiss", $campsite, $daystart, $monthstart, $yearstart, $dayend, $monthend, $yearend, $startdate, $enddate);
			
			if($stmt->execute()) {
				echo "Salvestamine õnnestus";
			} else {
				echo "ERROR ".$stmt->error;
			}
			
			$stmt->close();
			$mysqli->close();
	}*/
	
	/*getAllBookings
	function getAllBookings () {
		$database = "if16_karlerik";
			
			$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
			
			$stmt = $mysqli->prepare("SELECT id, campsite, startdate, enddate FROM bookings");
			$stmt->bind_result($id, $campsite, $startdate, $enddate);
			$stmt->execute();
			
			$result = array();
			
			while($stmt->fetch()) {
				$booking = new StdClass();
				$booking->id = $id;
				$booking->campsite = $campsite;
				$booking->startdate = $startdate;
				$booking->enddate = $enddate;
				
				array_push($result, $booking);
			}
			
			$stmt->close();
			$mysqli->close();
			
			return $result;
	}
	*/
		
	/* saveCar ja getAllCars
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
	*/
	
	
	function getAllUsers() {
		
		$database = "if16_karlerik";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("
			SELECT id, firstname, lastname, email, gender, dateofbirth, country, address, phonenumber FROM booking_users
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