<?php

if (isset($_POST['movie-add'])) {
    require '../includes/dbh.inc.php';
    $file = $_FILES['file'];
    $fileName = $_FILES['file']['name']; 
    $fileTmpName = $_FILES['file']['tmp_name']; 
    $fileSize = $_FILES['file']['size']; 
    $fileError = $_FILES['file']['error']; 
    $fileType = $_FILES['file']['type']; 
    $fileExt = explode('.', $fileName);

    $fileActualExt = strtolower(end($fileExt));
    $allowed = array('jpg', 'jpeg', 'png' , 'pdf');

    $movieID = $_SESSION['movie_id'];

    $title = $_POST['title'];
    $image = $_POST['file'];
    $fullDescription = $_POST['fullDescription'];
    $shortDescription = $_POST['shortDescription'];
    $category = $_POST['category'];
    $yearOfWork = $_POST['yearOfWork'];
    $movieLength = $_POST['movieLength'];
    if (!preg_match("/^[a-zA-Z0-9 ]*$/", $title)) {
        header("Location: ../movieinfo.php?movie_id='" . $movieID . "'&error=invalidtitle&fullDescription =" . $fullDescription . "&shortDescription" . $shortDescription . "&category" . $category . "&yearOfWork" . $yearOfWork . "&movieLength" . $movieLength);
        exit();
    } else if (!preg_match("/^[a-zA-Z0-9 ]*$/", $fullDescription)) {
        header("Location: ../movieinfo.php?movie_id='" . $movieID . "'&error=invalidfullDescription&title =" . $title . "&shortDescription" . $shortDescription . "&category" . $category . "&yearOfWork" . $yearOfWork . "&movieLength" . $movieLength);
        exit();
    } else if (!preg_match("/^[a-zA-Z0-9 ]*$/", $shortDescription)) {
        header("Location: ../movieinfo.php?movie_id='" . $movieID . "'&error=invalidshortDescription&title =" . $title . "&fullDescription =" . $fullDescription . "&category" . $category . "&yearOfWork" . $yearOfWork . "&movieLength" . $movieLength);
        exit();
    } else if (!preg_match("/^[a-zA-Z0-9 ]*$/", $category)) {
        header("Location: ../movieinfo.php?movie_id='" . $movieID . "'&error=invalidcategory&title =" . $title . "&fullDescription =" . $fullDescription . "&shortDescription" . $shortDescription . "&yearOfWork" . $yearOfWork . "&movieLength" . $movieLength);
        exit();
    } else if (!preg_match("/^[a-zA-Z0-9 ]*$/", $movieLength)) {
        header("Location: ../movieinfo.php?movie_id='" . $movieID . "'&error=invalidmovieLength&title =" . $title . "&fullDescription =" . $fullDescription . "&shortDescription" . $shortDescription . "&category" . $category . "&yearOfWork" . $yearOfWork);
        exit();
    } else {
        if (empty($title) && empty($fullDescription) && empty($shortDescription) && empty($category) && empty($yearOfWork) && empty($movieLength) && !isset($image)) {
            header("Location: ../movieinfo.php?movie_id=" . $movieID . "&error=emptyfields");
            exit();
        } else {
            if ($stmt =  $conn->prepare("INSERT INTO movies (title, movieImage, fullDescription, shortDescription, category, yearOfWork, movieLength,) VALUES ( ?, ?, ?, ?, ?, ?, ?)")) {
                $fileDestination = "";
                if (isset($_POST[$picture]) || in_array($fileActualExt, $allowed)) {
                    if ($fileError === 0) {
                        if ($fileSize < 1000000) {
                            $fileNameNew = "movie" . $movieID . "." . $fileActualExt;
                            $fileDestination .= '../upload/' . $fileNameNew;
                            $stmt->bind_param("sssssss", $title , $fullDescription, $shortDescription, $category, $yearOfWork, $movieLength, $fileDestination);
                            $last_id = 0;
                            if ($stmt->execute()) {
                                $last_id = $conn->insert_id;
                                $fileRealDestination = str_replace("../upload/artist.$fileActualExt", "../upload/artist$last_id.$fileActualExt", $fileDestination);
                                $sql = "UPDATE movies SET movieImage = '$fileRealDestination' WHERE movieID='$last_id';";
                                move_uploaded_file($fileTmpName, $fileRealDestination);
                                mysqli_query($conn,$sql);
                            }
                            $stmt->close();
                            header("Location: ../movieinfo.php?movie_id=" . $movieID . "&insert=success");
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


                $stmt->bind_param("sssssss", $title , $fullDescription, $shortDescription, $category, $yearOfWork, $movieLength, $fileDestination);
                $stmt->execute();
                $stmt->close();
                header("Location: ../movieinfo.php?movie_id=" . $movieID . "&insert=success");
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
        header("Location: ../artistinfo.php?movie_id=" . $movieID . "");
    }
} else {
    header("Location: ../artistinfo.php?movie_id= " . $movieID);
}
