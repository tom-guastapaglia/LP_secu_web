<?php
try {
	$db = new PDO('mysql:host=localhost;dbname=secu_web;charset=utf8mb4', 'root', 'root');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
	echo "Connection failed : ". $e->getMessage();
}