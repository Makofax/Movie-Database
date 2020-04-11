<?php
require "header.php";
?>

<main>
    <div class="container">
        <?php

        echo "<p>" . $_GET['movie_id'] . "</p>";
        $_SESSION['movie_id'] = $_GET['movie_id'];
        if (isset($_GET['error'])) {
            if ($_GET['error'] == "invalidtitle") {
                echo '<p class="wrong"> Input a proper title</p>';
            } else if ($_GET['error'] == "invalidfullDescription") {
                echo '<p class="wrong"> Input a proper full description</p>';
            } else if ($_GET['error'] == "invalidshortDescription") {
                echo '<p class="wrong"> Input a proper short description</p>';
            } else if ($_GET['error'] == "invalidcategory") {
                echo '<p class="wrong"> Input a proper category</p>';
            } else if ($_GET['error'] == "invalidmovieLength") {
                echo '<p class="wrong"> Number only values</p>';
            }
        }
        if (isset($_SESSION['movie_id'])) {
            $movieID = $_SESSION['movie_id'];

            $sql = "SELECT * FROM movies WHERE movieID=$movieID";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $title = $row['title'];
                    $fullDescription = $row['fullDescription'];
                    $shortDescription = $row['shortDescription'];
                    $category = $row['category'];
                    $yearOfWork = $row['yearOfWork'];
                    $movieLength = $row['movieLength'];
                    $link = $row['link'];
                    $movieImage = $row['movieImage'];
                    $realmovieImage = str_replace("../","",$movieImage);

                    $dt = strtotime($yearOfWork);
                    $month = date('F', $dt);
                    $monthnum = date('n', $dt);
                    $date = date('d', $dt);
                    $datenum = date('j', $dt);
                    $year = date('Y', $dt);

                    function convertToHoursMins($time, $format = '%02d:%02d')
                    {
                        if ($time < 1) {
                            return;
                        }
                        $hours = floor($time / 60);
                        $minutes = ($time % 60);
                        return sprintf($format, $hours, $minutes);
                    }
                    $movieLengthhr = convertToHoursMins($movieLength, '%02d h %02d min');
                    echo '
                    
                        <div class="vital">
                            <div class="title_block">
                                <div class="title_bar_wrapper">
                                    <div class="titleBar">
                                        <div class="title_wrapper">
                                            <h1 class="">' . $title . '&nbsp;<span id="titleYear">(<a href="#">' . $year . '</a>)</span>            </h1>
                                                <div class="subtext">
                                                    G
                                                    <span class="ghost">|</span><time datetime="PT100M">' . $movieLengthhr . '</time>
                                                    <span class="ghost">|</span>
                                                    <a href="#">' . $category . '</a>
                                                        <span class="ghost">|</span>
                                                    <a href="%" title="See more release dates">' . $date . " " . $month . " " . $year . '
                                                    </a>           
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="slate_wrapper">
                                <div class="poster">';
                    if (file_exists($realmovieImage)) {
                        echo '<a href="#"> <img class="ss" alt="' . $title . ' Poster" title="' . $title . ' Poster" src="' .$realmovieImage. ' " width="180" height="268"></a>';
                    } else {
                        echo '<a href="#"> <img class="ss" alt="' . $title . ' Poster" title="' . $title . ' Poster" src="upload/moviedefault.png" width="180" height="268"></a>';
                    }

                    echo '</div>
                                <div class="txts-block">
                                <p>
                                '.$shortDescription.'
                                </p>
                                </div>
                                <div class="credit_summary_item">
                                    <h4 class="inline">Stars:</h4>';
                    $sqlm = "SELECT c.* FROM movies a INNER JOIN roles b INNER JOIN artists c on a.movieID = b.movieID and b.artistID = c.artistID WHERE a.movieID = " . $row['movieID'] . " LIMIT 3 ";
                    $results = mysqli_query($conn, $sqlm);
                    $queryResults = mysqli_num_rows($results);
                    $i = 0;
                    if ($queryResults > 0) {
                        while ($rows = mysqli_fetch_assoc($results)) {
                            $str = '';
                            $str = '<a href="artistinfo.php?artist_id=' . $rows['artistID'] . '">' . $rows['firstName'] . " " . $rows['lastName'] . '</a>';
                            if ($i++ - 2) {
                                $str .= ', ';
                            }
                            echo $str;
                        }
                    }
                    echo '<span class="ghost">|</span>
                                    <a href="fullcredits/?ref_=tt_ov_st_sm">See full cast &amp; crew</a>&nbsp;»
                                </div>
                            </div>
                        </div>';
                    if (isset($_SESSION['userId'])) {
                        echo '<div><form method="POST">
                        <button  class="form-control form-control-sm bg-dark btn-color" type="submit" name="delete-btn" >Delete</button>
                        <button  class="form-control form-control-sm bg-dark btn-color" type="submit" name="update-btn" >Update</button>
                        <button  class="form-control form-control-sm bg-dark btn-color" type="submit" name="addartist-btn" >Assign an actor</button>
                        </form>';
                        if (isset($_POST['update-btn'])) {
                            echo '<form action="scripts/movieupdate.php" method="POST" enctype="multipart/form-data">
                        <input class="form-control form-control-sm btn-bg-color2" type="text" name="title" placeholder="Movie Title">
                        <input class="form-control form-control-sm btn-bg-color2" type="file" name="file" >
                        <textarea class="form-control form-control-sm btn-bg-color2" name="fullDescription" placeholder="Full Description"></textarea>
                        <textarea class="form-control form-control-sm btn-bg-color2" name="shortDescription" placeholder="Short Description"></textarea>
                        <input class="form-control form-control-sm btn-bg-color2" type="text" name="category" placeholder="Category of The Movie">
                        <input class="form-control form-control-sm btn-bg-color2" type="date" name="yearOfWork" placeholder="Year Of Work">
                        <input class="form-control form-control-sm btn-bg-color2" type="text" name="movieLength" placeholder="Movie Length">
                        <button class="form-control form-control-sm btn-bg-color1 btn-color" type="submit" name="movieupdate-submit">Update</button><br>
                        </form>';
                        }
                        if (isset($_POST['addartist-btn'])) {
                            echo '<form method="POST">
                            <button class="form-control form-control-sm btn-bg-color btn-color"  type="submit" class="form-control form-control-sm" name="search-artist" >Search</button>
                            <button  type="submit" class="form-control form-control-sm btn-bg-color btn-color" name="insert-artist" >Insert</button>
                            </form>';
                        }
                        if (isset($_POST['search-artist'])) {
                            echo '<form action="artistsearch.php" method="POST">
                            <input class="form-control form-control-sm btn-bg-color2 btn-color" type="text" name="search" placeholder="Search">
                            <button class="form-control form-control-sm btn-bg-color1 btn-color" type="submit" name="search-submit">Search</button>
                            </form>';
                        }
                        if (isset($_POST['insert-artist'])) {
                            echo '<form action="scripts/artistinsert.php" method="POST" enctype="multipart/form-data">
                            <input class="form-control form-control-sm btn-bg-color2" type="file" name="picture" >
                            <input  class="form-control form-control-sm btn-bg-color2" type="text" name="firstName" placeholder="First Name">
                            <input class="form-control form-control-sm btn-bg-color2 " class="form-control form-control-sm" type="text" name="lastName" placeholder="Last Name">
                            <input class="form-control form-control-sm btn-bg-color2 " type="text" name="nationality" placeholder="Nationality">
                            <textarea class="form-control form-control-sm btn-bg-color2" name="biography" placeholder="Biography"></textarea>
                            <input class="form-control form-control-sm btn-bg-color2" type="date" name="yearOfBirth" placeholder="Year Of Birth">
                            <input class="form-control form-control-sm btn-bg-color2" type="date" name="yearOfDeath" placeholder="Year Of Death">
                            <button class="form-control form-control-sm btn-bg-color1 btn-color" onsubmit="myFunction(\'' . "Are you sure you want to update this artist?" . '\')" type="submit" name="inartist-submit">Insert</button><br>
                            </form>';
                        }
                    }
                  
                    $sqlm = "SELECT c.* FROM movies a INNER JOIN roles b INNER JOIN artists c on a.movieID = b.movieID and b.artistID = c.artistID WHERE a.movieID = $movieID ";
                    $results = mysqli_query($conn, $sqlm);
                    if (mysqli_num_rows($results) > 0) {
                        echo '<div class="article">
                        <div id="filmography">
                        <div id="" class="head" > <a name="actress">Cast</a></div>';
                        while ($rows = mysqli_fetch_assoc($results)) {
                            if ($i % 2 == 0) {
                                $roll = "evens";
                            } else {
                                $roll = "odds";
                            }
                            echo '<div class="filmo-row ' . $roll . '" id="actress-tt1618434">';
                            if (file_exists($rows['picture'])) {
                                echo '<a href="artistinfo.php?artist_id=' . $rows['artistID'] . '"><img alt="' . $rows['firstName'] . " " . $rows['lastName'] . '" title="' . $rows['firstName'] . " " . $rows['lastName'] . '" src="' . $rows['picture'] . '" class="loadlate" width="32" height="44"></a>';
                            } else {
                                echo '<a href="artistinfo.php?artist_id=' . $rows['artistID'] . '"><img alt="' . $rows['firstName'] . " " . $rows['lastName'] . '" title="' . $rows['firstName'] . " " . $rows['lastName'] . '" src="upload/artistdefault.png" class="loadlate" width="32" height="44"></a>';
                            }
                            echo '
                                <b><a href="artistinfo.php?artist_id=' . $rows['artistID'] . '">' . $rows['firstName'] . " " . $rows['lastName'] . '</a></b>
                                <br>
                            </div>';
                            $i++;
                        }
                    }
                }
            }
            if (isset($_GET['firstName']) || isset($_GET['lastName'])) {
                $firstName = mysqli_real_escape_string($conn, $_GET['firstName']);
                $lastName = mysqli_real_escape_string($conn, $_GET['lastName']);

                $sqlm = "SELECT * FROM artists Where firstName='$firstName' AND lastName ='$lastName'";
                $results = mysqli_query($conn, $sqlm);
                $queryResults = mysqli_num_rows($results);
                if ($queryResults > 0) {
                    while ($row = mysqli_fetch_assoc($results)) {
                        echo "<div>
                    <p>" . $row['firstName'] . "</p>
                    <p>" . $row['lastName'] . "</p>
                    <form method='POST'>
                    <button type='submit' name='actorchosen'>Apply</button>
                    </form>
                    </div>";
                        if (isset($_POST['actorchosen'])) {
                            $query = "INSERT INTO roles (artistID, movieID) VALUES (" . $row['artistID'] . "," . $movieID . ") ";
                            mysqli_query($conn, $query);
                        }
                    }
                }
            }

            if (isset($_POST['delete-btn'])) {
                $movieID = $_GET['movie_id'];
                $sqlm = "DELETE FROM roles WHERE movieID=$movieID";
                mysqli_query($conn, $sqlm);
                $sqlm = "DELETE FROM movies WHERE movieID=$movieID";
                mysqli_query($conn, $sqlm);
                header("Location: index.php?delete=success");
                exit();
            }
        }
        ?>
    </div>
</main>
<?php
require "footer.php";
?>