<?php
session_start();
include_once 'includes/dbh.inc.php';

$sessionid = $_SESSION['userId'];

$filename = "upload/profile" . $sessionid . "*"; //Since the file extension is unkown to us, it is better to leave it as "*" which means just anything
$fileinfo = glob($filename); //gets all files with similar name.
$fileext = explode(".", $fileinfo[0]); //break the string into pieces after the Dot(.)
$fileactualext = $fileext[1]; //Gets the second part of the string which is after the Dot(.)

$file = "upload/profile" . $sessionid . "." . $fileactualext; //Now we have a proper address

if (!unlink($file)) {
    echo "File was not deleted";
} else{ //deletes a file
    echo "File was deleted";
}

$sql = "UPDATE profileimg SET status=1 WHERE userIdVal='$sessionid';";
mysqli_query($conn,$sql);
header("Location: index.php?Deletesuccess");