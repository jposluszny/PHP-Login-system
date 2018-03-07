<html>
<head>
<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body bgcolor="black" text='white'>
<?php
session_start();

if (isset($_POST['email'])){
	$wszystko_ok=true;
	$nick=$_POST['nick'];
	if ((strlen($nick)<3)||(strlen($nick)>20)){
		$wszystko_ok=false;
		$_SESSION['e_nick']='Nick musi zawierac się w przedziale od 3 do 20 znaków!';
	}
	if (ctype_alnum($nick)!==true){
		$wszystko_ok=false;
		$_SESSION['e_nick']='Hasło może zawierać tylko cyfry i litery bez polskich znaków!';
	}
	$haslo1=$_POST['haslo1'];
	$haslo2=$_POST['haslo2'];
	if ((strlen($haslo1)<2)||(strlen($haslo1)>10)){
		$wszystko_ok=false;
		$_SESSION['e_haslo']='Hasło musi zawierać się w przedziale od 2 do 10 znaków!';
		
	}
	if (ctype_alnum($haslo1)!==true){
		$wszystko_ok=false;
		$_SESSION['e_haslo']='Nick może zawierać tylko cyfry i litery bez polskich znaków!';
	}
	if (($haslo1!=$haslo2) || (isset($_SESSION['e_haslo']))){
		$wszystko_ok=false;
		$_SESSION['e_haslo1']='Powtórz poprawnie hasło!';
	}
	
	// wprowadzenie email
	$email=$_POST['email'];
	$email1=filter_var($email,FILTER_SANITIZE_EMAIL);
	if ((filter_var($email,FILTER_VALIDATE_EMAIL)==false)||($email!==$email1)){
		$wszystko_ok=false;
		$_SESSION['e_email']='Wprowadź poprawny email!';
	}
	$haslo_hash=password_hash($haslo1,PASSWORD_DEFAULT);
	
	// zaznaczenie regulaminu
	if (isset($_POST['regulamin'])==false){
		$wszystko_ok=false;
		$_SESSION['e_regulamin']='Zaakceptuj regulamin!';
	}
	
	// zaznaczenie captcha
	 $sekret = "6LfHFEMUAAAAAHf7oQubonTx41BExvdLgPNLG6Nv";
     $sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);
     $odpowiedz = json_decode($sprawdz); 
     if($odpowiedz->success==false){
		$wszystko_ok=false;
		$_SESSION['e_bot']='Udowodnij że nie jesteś robotem!';
	}
	
	//Zapamietywanie wprowadzonych danych
	$_SESSION['fr_nick']=$nick;
	$_SESSION['fr_haslo1']=$haslo1;
	$_SESSION['fr_haslo2']=$haslo2;
	$_SESSION['fr_email']=$email;
	if(isset($_POST['regulamin'])){
		$_SESSION['fr_regulamin']=true;
	}
	
	// Nawiazanie polaczenia z serwerem
	mysqli_report(MYSQLI_REPORT_STRICT);
	try{
	$polaczenie=new mysqli('localhost','root','','dane_logowania');
	if ($polaczenie->connect_errno!=0){
		throw new Exception(mysqli_connect_errno());
	}
	else {
		// Czy podany email jest juz uzywany
		$rezultat=$polaczenie->query("SELECT id FROM dane_logowania WHERE email='$email'");
		if ($polaczenie==false) throw new Exception($polaczenie->error);
		$ile_takich_maili=$rezultat->num_rows;
		if ($ile_takich_maili>0){
			$wszystko_ok=false;
			$_SESSION['e_email']='Podany email jest już używany!';
		}
		
		// Czy podany nick jest juz uzywany
		$rezultat1=$polaczenie->query("SELECT id FROM dane_logowania WHERE user='$nick'");
		if ($polaczenie==false) throw new Exception($polaczenie->error);
		$ile_takich_userow=$rezultat1->num_rows;
		if ($ile_takich_userow>0){
			$wszystko_ok=false;
			$_SESSION['e_nick']='Podany nick jest już używany!';
		}
		
		// Zapisywanie danych do bazy
		if ($wszystko_ok==true) {
			if ($polaczenie->query("INSERT INTO dane_logowania VALUES (null,'$nick','$haslo_hash','$email')")){
				$_SESSION['udanarejstracja']=true;
				header('Location: udanarejestracja.php');
				
			}
			
			else {
				 throw new Exception($polaczenie->error);
			}
		}
		
	}
}
	catch(Exception $e){
		echo 'Bląd polaczenia z serwerem <br>';
		echo 'Informacja'.$e;
	}
$polaczenie->close();
}

?>
<body>
<form  method='Post'>
Nazwa uzytkownika <br>
<input type='text' name='nick' value='<?php
if (isset($_SESSION['fr_nick'])){
	echo $_SESSION['fr_nick'];
	unset ($_SESSION['fr_nick']);
}

?>'><br>
<?php
if (isset($_SESSION['e_nick'])){
	echo '<br> <span style="color:red;">'.$_SESSION['e_nick'].'</span> <br><br>';
	unset($_SESSION['e_nick']);
}
?>
Haslo <br>
<input type='password' name='haslo1' value='<?php
if (isset($_SESSION['fr_haslo1'])){
	echo $_SESSION['fr_haslo1'];
	unset ($_SESSION['fr_haslo1']);
}

?>' ><br>
<?php
if (isset($_SESSION['e_haslo'])){
	echo '<br> <span style="color:red;">'.$_SESSION['e_haslo'].'</span> <br><br>';
	unset($_SESSION['e_haslo']);
}
?>
Powtorz haslo <br>
<input type='password' name='haslo2' value='<?php
if (isset($_SESSION['fr_haslo2'])){
	echo $_SESSION['fr_haslo2'];
	unset ($_SESSION['fr_haslo2']);
}

?>'><br>
<?php
if (isset($_SESSION['e_haslo1'])){
	echo '<br> <span style="color:red;">'.$_SESSION['e_haslo1'].'</span> <br><br>';
	unset($_SESSION['e_haslo1']);
}
?>
Email<br>
<input type='text' name='email' value='<?php
if (isset($_SESSION['fr_email'])){
	echo $_SESSION['fr_email'];
	unset ($_SESSION['fr_email']);
}

?>'><br>
<?php
if (isset($_SESSION['e_email'])){
	echo '<br> <span style="color:red;">'.$_SESSION['e_email'].'</span> <br><br>';
	unset($_SESSION['e_email']);
}
?>
<input type='submit' value='Przeslij'> 
<input type='reset' value='Wyczysc'><br><br>

<label>
<input type='checkbox' name='regulamin' 
<?php
if(isset($_SESSION['fr_regulamin'])){
	echo 'checked';
	unset ($_SESSION['fr_regulamin']);
}
	
?>><b>Zaakceptuj regulamin</b><br>
</label>
<?php
if (isset($_SESSION['e_regulamin'])){
	echo '<br> <span style="color:red;">'.$_SESSION['e_regulamin'].'</span> <br><br>';
	unset ($_SESSION['e_regulamin']);
	}
?>
<div class="g-recaptcha" data-sitekey="6LfHFEMUAAAAABRdi8oY7u3CvIPFBJ9S1i4Y0vFC"></div>
<?php
if (isset($_SESSION['e_bot'])){
	echo '<br> <span style="color:red;">'.$_SESSION['e_bot'].'</span> <br><br>';
	unset ($_SESSION['e_bot']);
}

?>
</form>


</body>
</html>
