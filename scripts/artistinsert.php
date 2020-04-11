<?php
session_start();
if (isset($_POST['inartist-submit'])) {
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

    $movieID = $_SESSION['movie_id'];
    $artistID = $_SESSION['artist_id'];

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $nationality = $_POST['nationality'];
    $yearOfBirth = $_POST['yearOfBirth'];
    $yearOfDeath = $_POST['yearOfDeath'];
    $biography  = $_POST['biography'];
    if (isset($_POST['picture'])) {
        $picture = $_POST['picture'];
    }
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
        if (empty($firstName) && empty($lastName) && empty($nationality) && empty($yearOfBirth) && empty($yearOfDeath) && empty($biography) && !isset($picture)) {
            header("Location: ../movieinfo.php?movie_id=$movieID&error=emptyfields");
            exit();
        } else {
            if ($stmt =  $conn->prepare("INSERT INTO artists (firstName, lastName, nationality, yearOfBirth , yearOfDeath, biography, picture) VALUES ( ?, ?, ?, ?, ?, ?, ?)")) {
                $fileDestination = "";
                if (isset($_POST[$picture]) || in_array($fileActualExt, $allowed)) {
                    if ($fileError === 0) {
                        if ($fileSize < 1000000) {
                            $fileNameNew = "artist" . $artistID . "." . $fileActualExt;
                            $fileDestination .= '../upload/' . $fileNameNew;
                            $stmt->bind_param("sssssss", $firstName, $lastName, $nationality, $yearOfBirth, $yearOfDeath, $biography, $fileDestination);
                            //$stmt->execute();
                            $last_id = 0;
                            if ($stmt->execute()) {
                                $last_id = $conn->insert_id;
                                $sqlm = "INSERT INTO roles (artistID, movieID) VALUES (" . $last_id . "," . $movieID . ") ";
                                mysqli_query($conn, $sqlm);
                                $fileRealDestination = str_replace("../upload/artist.$fileActualExt", "../upload/artist$last_id.$fileActualExt", $fileDestination);
                                $sql = "UPDATE artists SET picture = '$fileRealDestination' WHERE artistID='$last_id';";
                                move_uploaded_file($fileTmpName, $fileRealDestination);
                                mysqli_query($conn,$sql);
                            }
                            $stmt->close();
                            header("Location: ../movieinfo.php?movie_id=$movieID&insert=success");
                            exit();
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


                $stmt->bind_param("sssssss", $firstName, $lastName, $nationality, $yearOfBirth, $yearOfDeath, $biography, $fileDestination);
                //$stmt->execute();
                if ($stmt->execute()) {
                    $last_id = $conn->insert_id;
                    $sqlm = "INSERT INTO roles (artistID, movieID) VALUES (" . $last_id . "," . $movieID . ") ";
                    mysqli_query($conn, $sqlm);
                }
                $stmt->close();
                header("Location: ../movieinfo.php?movie_id=$movieID&insert=success");
                exit();
            }
        }
        /*
            if (isset($_POST[$picture]) || in_array($fileActualExt, $allowed)) {
                if ($fileError === 0) {
                    if ($fileSize < 1000000) {
                        $fileNameNew = "artist" . $artistID . "." . $fileActualExt;
                        $fileDestination = 'upload/' . $fileNameNew;
                        move_uploaded_file($fileTmpName, $fileDestination);
                        $sql = "INSERT INTO artists (picture) Values ('$fileDestination');";
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
        }*/
        header("Location: ../movieinfo.php?movie_id=" . $movieID);
    }
} else {
    header("Location: ../movieinfo.php?movie_id= " . $movieID);
}
