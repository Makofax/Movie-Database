<?php
session_start();
if (isset($_POST['movieupdate-submit'])) {
    require '../includes/dbh.inc.php';

    //this part takes care of the file location.
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
        header("Location: ../movieinfo.php?movie_id=$movieID&error=invalidtitle&fullDescription=" . $fullDescription . "&shortDescription=" . $shortDescription . "&category=" . $category . "&yearOfWork=" . $yearOfWork . "&movieLength=" . $movieLength);
        exit();
    } else if (!preg_match("/^[a-zA-Z0-9 .,-]*$/", $fullDescription)) {
        header("Location: ../movieinfo.php?movie_id=$movieID&error=invalidfullDescription&title=" . $title . "&shortDescription=" . $shortDescription . "&category=" . $category . "&yearOfWork=" . $yearOfWork . "&movieLength=" . $movieLength);
        exit();
    } else if (!preg_match("/^[a-zA-Z0-9 .,-]*$/", $shortDescription)) {
        header("Location: ../movieinfo.php?movie_id=$movieID&error=invalidshortDescription&title=" . $title . "&fullDescription =" . $fullDescription . "&category=" . $category . "&yearOfWork=" . $yearOfWork . "&movieLength=" . $movieLength);
        exit();
    } else if (!preg_match("/^[a-zA-Z0-9 ]*$/", $category)) {
        header("Location: ../movieinfo.php?movie_id=$movieID&error=invalidcategory&title=" . $title . "&fullDescription =" . $fullDescription . "&shortDescription=" . $shortDescription . "&yearOfWork=" . $yearOfWork . "&movieLength=" . $movieLength);
        exit();
    } else if (!preg_match("/^[a-zA-Z0-9 ]*$/", $movieLength)) {
        header("Location: ../movieinfo.php?movie_id=$movieID&error=invalidmovieLength&title=" . $title . "&fullDescription =" . $fullDescription . "&shortDescription=" . $shortDescription . "&category=" . $category . "&yearOfWork=" . $yearOfWork);
        exit();
    } else {
        //$sql = "UPDATE movies SET ";
        $fields = array('title','file', 'fullDescription', 'shortDescription', 'category', 'yearOfWork', 'movieLength');
        foreach ($fields as $field) {
            $sql = "UPDATE movies SET ";
            if (isset($_POST[$field]) && !empty($_POST[$field])) {
                
                $esc = mysqli_real_escape_string($conn, $_POST[$field]);
                $sql .= strtoupper($field) . " = '$esc'";
                $sql .= " WHERE movieID = '$movieID'";
                mysqli_query($conn, $sql);
            }
            
            if(isset($_POST[$image]) || in_array($fileActualExt,$allowed)){
                if($fileError === 0){
                    if($fileSize < 1000000){
                        $fileNameNew = "movie".$movieID.".".$fileActualExt;
                        $fileDestination = '../upload/'.$fileNameNew;
                        move_uploaded_file($fileTmpName,$fileDestination);
                        $sql = "UPDATE movies SET movieImage = '$fileDestination' WHERE movieID='$movieID';";
                        mysqli_query($conn, $sql);
                    }
                    else{ 
                        echo "Your file is too big!";
                    }
        
                }
                else{
                    echo "There was an error uploading your file!";
                }
            }
            else{
                echo "You cannot upload files of this type!";
            }
        }
        header("Location: ../movieinfo.php?movie_id=" . $movieID . "");

    }
} else {
    header("Location: ../movieinfo.php?movie_id= " . $_SESSION['movie_id']);
}
