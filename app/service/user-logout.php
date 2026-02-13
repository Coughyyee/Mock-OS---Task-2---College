<?php
namespace Service;

session_start();
$_SESSION = [];
session_destroy();

header('Location: ../../public/index.php');