<?php
session_start();
include_once 'includes/dbh.inc.php';

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieDB</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>

<body>

    <header>
        <div class="container">
            <nav class="mb-1 navbar navbar-expand-lg navbar-dark bg-dark " role="navigation">
                <a class="navbar-brand" href="index.php">
                    <img src="img/logo.png" alt="logo">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-555" aria-controls="navbarSupportedContent-555" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent-555">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                        <li class="nav-item"><a href="#" class="nav-link">Artist</a></li>
                        <li class="nav-item"><a href="#" class="nav-link">Movies</a></li>
                        <li class="nav-item"><a href="#" class="nav-link">Forum</a></li>
                    </ul>
                    <ul class="navbar-nav mr-auto">
                        <form action="search.php" method="POST" class="navbar-nav mr-auto ml-auto"   role="search">
                            <div class="form-group form-inline">
                                <input type="text" name="search" class="form-control form-control-sm"  placeholder="Search">
                                <select class="form-control form-control-sm" name="category">
                                    <option value="movie">Movies</option>
                                    <option value="artist">Artists</option>
                                    <option value="both">Both</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-secondary" name="search-submit">Search</button>
                            </div>
                        </form>
                    </ul>
                    <?php

                    if (isset($_SESSION['userId'])) {
                        echo '<ul class="navbar-nav ml-auto nav-flex-icons">
                        <li class="nav-item avatar dropdown ">
                        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-55" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        $sql = "SELECT * FROM users WHERE id='" . $_SESSION['userId'] . "'";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $id = $row['id'];
                                $sqlImg = "SELECT * FROM profileimg Where id ='$id'";
                                $resultImg = mysqli_query($conn, $sqlImg);
                                while ($rowImg = mysqli_fetch_assoc($resultImg)) {

                                    if ($rowImg['status'] == 0) {
                                        echo "<img src='upload/profile" . $id . ".jpg?" . mt_rand() . " ' width='60px' height='60px' class='rounded-circle z-depth-0' alt='avatar image'> </a> <div class='dropdown-menu dropdown-menu-lg-right dropdown-secondary'
                                        aria-labelledby='navbarDropdownMenuLink-55'>";
                                    } else {
                                        echo "<img src='upload/profiledefault.jpg' width='60px' height='60px' class='rounded-circle z-depth-0' alt='avatar image'> </a> <div class='dropdown-menu dropdown-menu-lg-right dropdown-secondary'
                                        aria-labelledby='navbarDropdownMenuLink-55'>";
                                    }
                                    echo "<p class='btn-color'>" . $row['first'] . " " . $row['last'] . "</p>";
                                }
                            }
                        }
                        echo " 
                            <div class='form-group'>
                            <form  action='upload.php' method='POST' enctype='multipart/form-data'>
                            <input  class='form-control form-control-sm btn-bg-color2' type='file' name='file'>
                            <div class='input-group-append'>
                            <button class='btn btn-sm btn-secondary btn-block' type='submit' name='submit'>upload</button>
                            </div>
                            </form> 
                            </div>
                            
                            <div class='form-group'>
                            <form action='deleteprofile.php' method='POST'>
                            <button class='btn btn-sm btn-secondary btn-block' type='submit' name='submit'>Delete Profile Image</button>
                            </form>
                            </div>

                            <div class='form-group'>
                            <form action='includes/logout.inc.php' method='post'>
                            <button class='btn btn-sm btn-secondary btn-block btn-bg-color1' type='submit' name='logout-submit'>logout</button>
                            </form>
                            </div>
                            </div> 
                            </li> 
                            </ul>";
                    } else {
                        echo '
                        <ul class="nav navbar-nav flex-row justify-content-between ml-auto">
                            <li class="dropdown order-1">
                                <button type="button" id="dropdownMenu1" data-toggle="dropdown" class="btn btn-outline-secondary dropdown-toggle">Login <span class="caret"></span></button>
                                <ul class="dropdown-menu dropdown-menu-right mt-2">
                                <li class="px-3 py-2">
                                    <form class="form" role="form" action="includes/login.inc.php" method="post">
                                            <div class="form-group">
                                                <input id="emailInput" type="text" name="mailuid" placeholder="Username/Email..." class="form-control form-control-sm">
                                            </div>
                                            <div class="form-group">
                                                <input id="passwordInput" type="password" name="pwd" placeholder="Password..." class="form-control form-control-sm">
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-secondary btn-block" name="login-submit">Login</button>
                                            </div>
                                            <div class="form-group text-center">
                                                <small><a href="#" data-toggle="modal" data-target="#modalPassword">Forgot password?</a></small>
                                            </div>
                                        </form>
                                        <div class="form-group text-center">
                                            <a class="btn btn-secondary " href="signup.php">Create a new account</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                            ';
                    }
                    ?>


                </div>
            </nav>
        </div>


    </header>