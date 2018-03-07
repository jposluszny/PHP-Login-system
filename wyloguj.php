<html>
<head></head>
<body bgcolor="black" text='white'>
<?php
session_start();
if ($_SESSION['zalogowany']=true){
	session_abort();
	unset ($_SESSION['zalogowany']);
}
header('Location:index.php');
?>
</body>
</html>
