<?php
include("config.php");

function checkIfLoggedIn(){
	global $conn;
	if(isset($_SERVER['HTTP_TOKEN'])){
		$token = $_SERVER['HTTP_TOKEN'];
		$result = $conn->prepare("SELECT * FROM KORISNICI WHERE TOKEN=?");
		$result->bind_param("s",$token);
		$result->execute();
		$result->store_result();
		$num_rows = $result->num_rows;
		if($num_rows > 0)
		{
			return true;
		}
		else{
			return false;
		}
	}
	else{
		return false;
	}
}

function login($username, $password){
	global $conn;
	$rarray = array();
	if(checkLogin($username,$password)){
		$id = sha1(uniqid());
		$result2 = $conn->prepare("UPDATE KORISNICI SET TOKEN=? WHERE USERNAME=?");
		$result2->bind_param("ss",$id,$username);
		$result2->execute();
		$rarray['token'] = $id;
	} else{
		header('HTTP/1.1 401 Unauthorized');
		$rarray['error'] = "Invalid username/password";
	}
	return json_encode($rarray);
}

function checkLogin($username, $password){
	global $conn;
	$password = md5($password);
	$result = $conn->prepare("SELECT * FROM KORISNICI WHERE USERNAME=? AND PASSWORD=?");
	$result->bind_param("ss",$username,$password);
	$result->execute();
	$result->store_result();
	$num_rows = $result->num_rows;
	if($num_rows > 0)
	{
		return true;
	}
	else{
		return false;
	}
}

function register($username, $password, $firstname, $lastname){
	global $conn;
	$rarray = array();
	$errors = "";
	if(checkIfUserExists($username)){
		$errors .= "Username already exists\r\n";
	}
	if(strlen($username) < 5){
		$errors .= "Username must have at least 5 characters\r\n";
	}
	if(strlen($password) < 5){
		$errors .= "Password must have at least 5 characters\r\n";
	}
	if(strlen($firstname) < 3){
		$errors .= "First name must have at least 3 characters\r\n";
	}
	if(strlen($lastname) < 3){
		$errors .= "Last name must have at least 3 characters\r\n";
	}
	if($errors == ""){
		$stmt = $conn->prepare("INSERT INTO KORISNICI (FIRSTNAME, LASTNAME, USERNAME, PASSWORD) VALUES (?, ?, ?, ?)");
		$pass =md5($password);
		$stmt->bind_param("ssss", $firstname, $lastname, $username, $pass);
		if($stmt->execute()){
			$id = sha1(uniqid());
			$result2 = $conn->prepare("UPDATE KORISNICI SET TOKEN=? WHERE USERNAME=?");
			$result2->bind_param("ss",$id,$username);
			$result2->execute();
			$rarray['token'] = $id;
		}else{
			header('HTTP/1.1 400 Bad request');
			$rarray['error'] = "Database connection error";
		}
	} else{
		header('HTTP/1.1 400 Bad request');
		$rarray['error'] = json_encode($errors);
	}

	return json_encode($rarray);
}

function checkIfUserExists($username){
	global $conn;
	$result = $conn->prepare("SELECT * FROM KORISNICI WHERE username=?");
	$result->bind_param("s",$username);
	$result->execute();
	$result->store_result();
	$num_rows = $result->num_rows;
	if($num_rows > 0)
	{
		return true;
	}
	else{
		return false;
	}
}

function addCategory($ime){
	global $conn;
	$rarray = array();
	$errors = "";

	if(checkIfLoggedIn()){
		if(strlen($ime) < 3){
			$errors .= "Ime kategorije mora imati bar 3 karaktera\r\n";
		}
		if($errors == ""){
			$stmt = $conn->prepare("INSERT INTO KATEGORIJA_PROIZVODA (IME) VALUES (?)");
			$stmt->bind_param("s", $ime);
			if($stmt->execute()){
				$rarray['success'] = "ok";
			}else{
				$rarray['error'] = "Database connection error";
			}
			return json_encode($rarray);
		} else{
			header('HTTP/1.1 400 Bad request');
			$rarray['error'] = json_encode($errors);
			return json_encode($rarray);
		}
	} else{
		$rarray['error'] = "Please log in";
		header('HTTP/1.1 401 Unauthorized');
		return json_encode($rarray);
	}

}

function getCategory(){
	global $conn;
	$rarray = array();
	if(checkIfLoggedIn()){
		$result = $conn->query("SELECT * FROM KATEGORIJA_PROIZVODA");
		$num_rows = $result->num_rows;
		$kategorije = array();
		if($num_rows > 0)
		{
			$result2 = $conn->query("SELECT * FROM KATEGORIJA_PROIZVODA");
			while($row = $result2->fetch_assoc()) {
				array_push($kategorije,$row);
			}
		}
		$rarray['kategorije'] = $kategorije;
		return json_encode($rarray);
	} else{
		$rarray['error'] = "Please log in";
		header('HTTP/1.1 401 Unauthorized');
		return json_encode($rarray);
	}
}

function addProduct($kategorija_proizvoda_id, $ime, $cena, $opis){
	global $conn;
	$rarray = array();
	$errors = "";
	if(checkIfLoggedIn()){
		if(strlen($ime) < 3){
			$errors .= "Name must have at least 3 characters\r\n";
		}
		if(strlen($cena) < 3){
			$errors .= "Cena must have at least 3 characters\r\n";
		}
		if(strlen($opis) < 3){
			$errors .= "Opis must have at least 3 characters\r\n";
		}
		if(!isset($kategorija_proizvoda_id)){
			$errors .= "You need to set author of a book\r\n";
		}
		if($errors == ""){
			$stmt = $conn->prepare("INSERT INTO PROIZVOD (KATEGORIJA_PROIZVODA_ID, IME, CENA, OPIS) VALUES (?, ?, ?, ?)");
			$stmt->bind_param("isss", $kategorija_proizvoda_id, $ime, $cena, $opis);
			if($stmt->execute()){
				$rarray['success'] = "ok";
			}else{
				$rarray['error'] = "Database connection error";
			}
			return json_encode($rarray);
		} else{
			header('HTTP/1.1 400 Bad request');
			$rarray['error'] = json_encode($errors);
			return json_encode($rarray);
		}
	} else{
		$rarray['error'] = "Please log in";
		header('HTTP/1.1 401 Unauthorized');
		return json_encode($rarray);
	}
}

function getCategoriesById($id){
	global $conn;
	$rarray = array();
	$id = intval($id);
	$result = $conn->query("SELECT * FROM KATEGORIJA_PROIZVODA WHERE ID=".$id);
	$num_rows = $result->num_rows;
	$rowtoreturn = array();
	if($num_rows > 0)
	{
		$result2 = $conn->query("SELECT * FROM KATEGORIJA_PROIZVODA WHERE ID=".$id);
		while($row = $result2->fetch_assoc()) {
			$rowtoreturn = $row;
		}
	}
	return $rowtoreturn['IME'];
}

function getProducts(){
	global $conn;
	$rarray = array();
	if(checkIfLoggedIn()){
		$result = $conn->query("SELECT * FROM PROIZVOD");
		$num_rows = $result->num_rows;
		$proizvodi = array();
		if($num_rows > 0)
		{
			$result2 = $conn->query("SELECT * FROM PROIZVOD");
			while($row = $result2->fetch_assoc()) {
				$row['KATEGORIJA_PROIZVODA_IME'] = getCategoriesById($row['KATEGORIJA_PROIZVODA_ID']);
				array_push($proizvodi,$row);
			}
		}
		$rarray['proizvodi'] = $proizvodi;
		return json_encode($rarray);
	} else{
		$rarray['error'] = "Please log in";
		header('HTTP/1.1 401 Unauthorized');
		return json_encode($rarray);
	}
}

function deleteProduct($id){
	global $conn;  
	$rarray = array();  
	if(checkIfLoggedIn()){
		$result = $conn->prepare("DELETE FROM PROIZVOD WHERE ID=?");   
		$result->bind_param("i",$id);   
		$result->execute();   
		$rarray['success'] = "Deleted successfully";  
	} else{   
		$rarray['error'] = "Please log in";  
		header('HTTP/1.1 401 Unauthorized');  
	}  
	return json_encode($rarray); 
}

function orderProduct($id){
	global $conn;
	$rarray = array();
	$errors = "";
	if(checkIfLoggedIn()){
		$token = $_SERVER['HTTP_TOKEN'];
		if($errors == ""){
			$stmt = $conn->prepare("INSERT INTO porudzbina (PROIZVOD_ID, KORISNICI_ID, DATUM)  VALUES (?,(SELECT ID from korisnici where TOKEN=?),NOW())");
			$stmt->bind_param("ss", $id, $token);
			if($stmt->execute()){
				$rarray['success'] = "ok";
			}else{
				$rarray['error'] = "Database connection error";
			}
			return json_encode($rarray);
		} else{
			header('HTTP/1.1 400 Bad request');
			$rarray['error'] = json_encode($errors);
			return json_encode($rarray);
		}
	} else{
		$rarray['error'] = "You must be logged in to use this functionality.";
		header('HTTP/1.1 401 Unauthorized');
		return json_encode($rarray);
	}
}

//function getOrder(){
//	global $conn;
//	$rarray = array();
//	if(checkIfLoggedIn()){
//		$token = $_SERVER['HTTP_TOKEN'];
//		$result = $conn->prepare("SELECT * FROM porudzbina WHERE KORISNICI_ID=(SELECT ID FROM KORISNICI WHERE TOKEN=?)");
//		$result->bind_param("s",$token);
//		$result->execute();
//		$result->store_result();
//		$num_rows = $result->num_rows;
//		$porudzbine = array();
//		if($num_rows > 0)
//		{
//			//$result2 = $conn->query("SELECT proizvod.ID as id, proizvod.IME as ime, proizvod.CENA as cena, proizvod.OPIS as opis, kategorija_prozivoda.IME as kategorija FROM proizvod, kategorija_prozivoda WHERE kategorija_proizvoda.ID=prozivod.KATEGORIJA_PROIZVODA_ID");
//			//$result2 = $conn->prepare("SELECT proizvod.ID as id, proizvod.IME as ime, proizvod.CENA as cena, proizvod.OPIS as opis, kategorija_prozivoda.IME as kategorija FROM proizvod, kategorija_prozivoda WHERE kategorija_proizvoda.ID=prozivod.KATEGORIJA_PROIZVODA_ID");
//			$result2 = $conn->prepare("SELECT por.ID as id, pro.IME as ime, pro.OCENA as ocena, pro.OPIS as opis FROM porudzbina as por, proizvod as pro WHERE por.KORISNICI_ID=(SELECT ID FROM KORISNICI WHERE TOKEN=?) and por.PROIZVOD_ID=pro.ID");
//			$result2->bind_param("s",$token);
//			$result2->execute();
//			$result2->store_result();
//			while($row = $result2->fetch_assoc()) {
//				array_push($porudzbine,$row);
//			}
//		}
//		$rarray['porudzbine'] = $porudzbine;
//		return json_encode($rarray);
//	} else{
//		$rarray['error'] = "Please log in";
//		header('HTTP/1.1 401 Unauthorized');
//		return json_encode($rarray);
//	}
//}

function getOrder(){
	global $conn;
	$rarray = array();

	if(checkIfLoggedIn()) {
		$token = $_SERVER['HTTP_TOKEN'];
		$result = $conn->prepare("SELECT * FROM porudzbina WHERE KORISNICI_ID=(SELECT ID FROM KORISNICI WHERE TOKEN=?)");
		$result->bind_param("s",$token);
		$result->execute();
		$result->store_result();
		$num_rows = $result->num_rows;
		$porudzbine = array();
		if($num_rows > 0){
			$result2 = $conn->query("SELECT * FROM porudzbina");
			while($row = $result2->fetch_assoc()) {
				array_push($porudzbine,$row);
			}
		}
		$rarray['porudzbine'] = $porudzbine;
		return json_encode($rarray);
	}else{
		$rarray['error'] = "Please log in";
		header('HTTP/1.1 401 Unauthorized');
		return json_encode($rarray);
	}
}

//function deleteOrder($id){
//	global $conn;
//	if(checkIfLoggedIn()){
//		$token = $_SERVER['HTTP_TOKEN'];
//		//stavljam i id korisnika iz sigurnosnih razloga, ako neko brise porudzbine sa random brojevima
//		$result = $conn->prepare("DELETE FROM porudzbina WHERE ID=? AND KORISNICI_ID=(SELECT ID FROM korisnici WHERE TOKEN=?)");
//		$result->bind_param("is",$id,$token);
//		$result->execute();
//	}
//}


function deleteOrder($id){
	global $conn;
	if(checkIfLoggedIn()){
		//$token = $_SERVER['HTTP_TOKEN'];
		//stavljam i id korisnika iz sigurnosnih razloga, ako neko brise porudzbine sa random brojevima
		$result = $conn->prepare("DELETE FROM porudzbina WHERE ID=?");
		$result->bind_param("i",$id);
		$result->execute();
	}
}
?>