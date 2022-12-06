<?php
session_start();

$_SESSION = [];

header('location: /');

var_dump($_SESSION);