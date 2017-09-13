<?php

// forbindelse til mySQL serveren ved brug af mysqli metoden (i står for "Improved")

// 1. Variabler (konstanter) til forbindelsen
// const = ALTID I STORE BOGSTAVER

const HOSTNAME = 'localhost'; 
const MYSQLUSER = 'root'; // 
const MYSQLPASS = 'root'; // 
const MYSQLDB = 'image_gallery'; // database name
// $DB_PORT = '8889';
	
// 2. Forbindelsen via mysqli metoden

$con = new mysqli(HOSTNAME, MYSQLUSER, MYSQLPASS, MYSQLDB);

// at sikre sig, at alle utf8-tegn kan blive brukt under forbindelsen - standard ting
$con->set_charset ('utf8');

// 3. Check
// Hvis der er fejl i forbindelsen
if ($con->connect_error) {
	die($con->connect_error);
	// Hvis forbindelsen er oprættet uden problemer
} else {
	// echo '<p>Connected to the Database! :)</p>';
}


// php end/slut tag - ikke nødvendigt hvis det er "ren/kun" PHP!