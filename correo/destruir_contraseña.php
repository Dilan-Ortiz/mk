<?php
session_start();

unset($_SESSION['code']);
unset($_SESSION['usuario']);

session_destroy();
session_write_close();

header("location:../index.html");
exit();
?>