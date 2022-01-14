<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>The North Bay Gentlemen's Club (NBGC)</title>
        <!--#353535 #3C6E71 #FFFFFF #D9D9D9 #284B63 #000000-->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="description" content="The Northbay Gentlemen's Club Clubhouse">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="NBGC, North Bay, Gentlemen's Club, Club, Petaluma, Sonoma, Santa Rosa, Rohnert Park, Cotati, Sebastopol">
        <meta name="geo.region" CONTENT="US">
        
        <link rel="stylesheet" href="css/styles.css?v=1.0" />
        <link rel="stylesheet" href="css/dropdownmenu.css?v=1.0" />
        <link rel="stylesheet" href="css/errors.css?v=1.0" />
        <link rel="stylesheet" href="css/content.css?v=1.0" />
        <link rel="stylesheet" href="css/calendar.css?v=1.0" />
        <link rel="stylesheet" href="css/links.css?v=1.0" />
    </head>
    
    <?php
        session_start();
        require "./includes/sessionutilities.inc.php";

        $Utilities = new sessionUtilities();