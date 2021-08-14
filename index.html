<html>
<head></head>
<body bgcolor="black" text='white'>
<?php
session_start();
$_SESSION['zalogowany']=false;
if ((isset($_POST['user'])) || (isset($_POST['password']))){
	$user=$_POST['user'];
	$password=$_POST['password'];
	// Nawiazanie polaczenia z baza
	mysqli_report(MYSQLI_REPORT_STRICT);
	try {
		$polaczenie=new mysqli('localhost','root','','dane_logowania');
		if ($polaczenie->connect_errno>0) {
			throw new Exception(mysqli_connect_errno());
		}
		else {
			//echo 'Polaczenie sie udalo';
			
			$result=$polaczenie->query("SELECT password FROM dane_logowania WHERE user='$user'");
			$wiersz=$result->fetch_assoc();
			$password_hash=$wiersz['password'];
			
			if ($result->num_rows==0){
				$_SESSION['e_log']='Użytkownik o podanej nazwie nie istnieje!';
			}
			else if (password_verify($password,$password_hash)==false){
				$_SESSION['e_log']='Podane hasło jest niepoprawne!';
			}
			else {
				$_SESSION['zalogowany']=true;
				$_SESSION['komunikat']='Jesteś zalogowany jako '.$user.' ';
				
			}
				
		}
	}
	
	catch (Exception $e){
		echo 'Polaczenie sie nie udalo';
	}
	$polaczenie->close();
}	

if ($_SESSION['zalogowany']==false){
	echo	'<form method="POST">
	Logowanie: <a href="rejestracja.php">Rejestracja konta</a><br>
	Użytkownik:<input type="text" name="user">
	Hasło:<input type="password" name="password">
	<input type="submit" value="Prześlij">
	<input type="reset" value="Wyczyść">
	</form>';
	unset ($_SESSION['zalogowany']);
}
else{
	echo '<span style="color:green">'.$_SESSION['komunikat'].'</span>';
	echo '<a href="wyloguj.php" > Wyloguj się</a>';
 
	}
	
if (isset ($_SESSION['e_log'])){
	echo '<span style="color:red;">'.$_SESSION['e_log'].'</span> ';
	unset ($_SESSION['e_log']);
}
			
?>

</form>
</body>
</html>
