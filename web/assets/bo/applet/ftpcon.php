<?php
session_start();
echo $_SESSION['ftp'];
$_SESSION['ftp'] = NULL;
unset($_SESSION['ftp']);
?>