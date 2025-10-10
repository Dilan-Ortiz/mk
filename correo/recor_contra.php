<?php
session_start(); //inicia la sesion y verifica los datos del usuario logueado
require_once("../config/database.php");
$db = new Database();
$con = $db->conectar();

?>