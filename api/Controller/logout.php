<?php
session_start();
session_unset(); 

$_SESSION['sucesso'] = "Logout realizado com sucesso!";
header("Location: ../Views/pages/login.php");
exit();
