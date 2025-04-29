<?php
session_start();   // start every time this file is loaded
require 'common.php';

// TRUE - login,  FALSE - guest */
function isUserLoggedIn()
{
    if (isset($_SESSION['Active']) && $_SESSION['Active'] === true) {
        return true;
    }
    return false;
}

// current user role in lowercase, defaults to GUEST
function getUserRole()
{
    if (isset($_SESSION['Role'])) {
        return strtolower($_SESSION['Role']);
    }
    return 'guest';
}

// current username, defaults to GUEST
function getUsername()
{
    if (isset($_SESSION['Username'])) {
        return $_SESSION['Username'];
    }
    return 'Guest';
}

// status message
function displayLoginStatus()
{
    if (isUserLoggedIn()) {
        echo 'Status: You are logged in as <strong>'
           . escape(getUsername())
           . '</strong> (' . getUserRole() . ')';
    } else {
        echo 'Status: You are logged in as <strong>Guest</strong>';
    }
}