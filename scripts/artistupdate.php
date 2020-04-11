<?php
session_start();
if (isset($_POST['artistupdate-submit'])) {
    require '../includes/dbh.inc.php';

    //this part takes care of the file location.
    $file = $_FILES['picture'];
    $fileName = $_FILES['picture']['name'];
    $fileTmpName = $_FILES['picture']['tmp_name'];
    $fileSize = $_FILES['picture']['size'];
    $fileError = $_FILES['picture']['error'];
    $fileType = $_FILES['picture']['type'];
    $fileExt = explode('.', $fileName);

    $fileActualExt = strtolower(end($fileExt));
    $allowed = array('jpg', 'jpeg', 'png', 'pdf');

    $artistID = $_SESSION['artist_id'];

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $nationality = $_POST['nationality'];
    $yearOfBirth = $_POST['yearOfBirth'];
    $yearOfDeath = $_POST['yearOfDeath'];
    $biography  = $_POST['biography'];
    $picture = $_POST['picture'];
    if (!preg_match("/^[a-zA-Z0-9 ]*$/", $firstName)) {
        header("Location: ../artistinfo.php?artist_id=$artistID&error=invalidfirstName&lastName =" . $lastName . "&nationality" . $nationality . "&biography" . $biography);
        exit();
    } else if (!preg_match("/^[a-zA-Z0-9 ]*$/", $lastName)) {
        header("Location: ../artistinfo.php?artist_id=$artistID&error=invalidlastName&firstName =" . $firstName . "&nationality" . $nationality . "&biography" . $biography);
        exit();
    } else if (!preg_match("/^[a-zA-Z0-9 ]*$/", $nationality)) {
        header("Location: ../artistinfo.php?artist_id=$artistID&error=invalidnationality&firstName =" . $firstName . "&nationality" . $nationality . "&biography" . $biography);
        exit();
    } else if (!preg_match("/^[a-zA-Z0-9 .,-]*$/", $biography)) {
        header("Location: ../artistinfo.php?artist_id=$artistID&error=invalidbiography&firstName =" . $firstName . "&lastName =" . $lastName . "&nationality" . $nationality);
        exit();
    } else {
        $artistfields = array('picture', 'firstName', 'lastName', 'nationality', 'yearOfBirth', 'yearOfDeath', 'biography');
        foreach ($artistfields as $field) {
            $sql = "UPDATE artists SET ";
            if (isset($_POST[$field]) and !empty($_POST[$field])) {

                $esc = mysqli_real_escape_string($conn, $_POST[$field]);
                $sql .= strtoupper($field) . " = '$esc'";
                $sql .= " WHERE artistID = '$artistID'";
                mysqli_query($conn, $sql);
            }

            if (isset($_POST[$picture]) || in_array($fileActualExt, $allowed)) {
                if ($fileError === 0) {
                    if ($fileSize < 1000000) {
                        $fileNameNew = "artist" . $artistID . "." . $fileActualExt;
                        $fileDestination = '../upload/' . $fileNameNew;
                        move_uploaded_file($fileTmpName, $fileDestination);
                        $sql = "UPDATE artists SET picture = '$fileDestination' WHERE artistID='$artistID';";
                        mysqli_query($conn, $sql);
                    } else {
                        echo "Your file is too big!";
                    }
                } else {
                    echo "There was an error uploading your file!";
                }
            } else {
                echo "You cannot upload files of this type!";
            }
        }
        header("Location: ../artistinfo.php?artist_id= $artistID ");
    }
} else {
    header("Location: ../artistinfo.php?artist_id= ".$_SESSION['artist_id']."");
}
