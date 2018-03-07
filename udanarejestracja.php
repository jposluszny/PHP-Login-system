<html>
<head></head>
<body bgcolor="black" text='white'>
<?php
session_start();
if (isset($_SESSION['udanarejstracja'])){
	echo 'Rejestracja przebiegła pomyślnie teraz możesz przejść do panelu logowania';
	unset ($_SESSION['udanarejstracja']);
	
}
else{
	header('Location:index.php');
}
if(isset($_SESSION['fr_nick'])) unset ($_SESSION['fr_nick']);
if(isset($_SESSION['fr_haslo1'])) unset ($_SESSION['fr_haslo1']);
if(isset($_SESSION['fr_haslo2'])) unset ($_SESSION['fr_haslo2']);
if(isset($_SESSION['fr_email'])) unset ($_SESSION['fr_email']);
if(isset($_SESSION['fr_regulamin'])) unset ($_SESSION['fr_regulamin']);
?>
<a href="index.php"> Powrót do rejestracji</a>
</body>
</html>
