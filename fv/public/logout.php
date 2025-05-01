<?php
// start session so we have access
session_start();

require_once '../src/session.php';

// OOP method
$session = new session(); //create object of session class from session.php
$session->forgetSession(); // kills session & redirects to login


/*
session_destroy();
header("Location: login.php");
exit;
*/
