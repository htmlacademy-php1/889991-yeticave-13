<?php
session_start();
$is_auth = isset($_SESSION["name"]);

$user_name = $_SESSION["name"];

