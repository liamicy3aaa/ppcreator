<?php

session_start();
require_once "vendor/autoload.php";
require_once "classes/class.ppcreator.php";

if(isset($_GET["run"]) && $_GET["run"] == "runit" && isset($_GET["auth"]) && base64_decode($_GET["auth"]) == $_SESSION["AUTH"]) {
    
    // Clearing Auth value
    unset($_SESSION["AUTH"]);
    
    if(!isset($_POST["quiz"]) || !is_array($_POST["quiz"]) || !isset($_POST["categories"]) || !is_array($_POST["categories"])) {
        
        http_response_code(400);
        die("Missing parameters");
        
    }
    
    // Build quiz array
    $quiz = ppcreator::buildQuizArray($_POST);
    
    // Initate the creation of the presentation
    $process = ppcreator::createPresentation($quiz);

    // Checking creation was successful
    if($process["status"]) {
    
    print "<a class='btn btn-success my-2' href='" . $process["file"] ."' download>Download Presentation</a>";
    exit;
    
    } else {
        
        http_response_code(500);
        die("Error ocurred");
        
    }

} else {
    
    http_response_code(401);
    
}
