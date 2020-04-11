<?php
require "header.php";
?>

<main>

    <script>
        type = "text/javascript";

        function myFunction(p) {
            confirm(p);
        }
    </script>
    <div class="container">
        <?php
        echo "<p>" . $_GET['artist_id'] . "</p>";
        $_SESSION['artist_id'] = $_GET['artist_id'];
        if (!isset($_SESSION['artist_id'])) {
            $_SESSION['artist_id'] = $_GET['artist_id'];
        } else {


            //$_SESSION['artist_id'] = $_GET['artist_id'];
            if (isset($_GET['error'])) {
                if ($_GET['error'] == "invalidfirstName") {
                    echo '<p class="wrong"> Input a proper first name</p>';
                } else if ($_GET['error'] == "invalidlastName") {
                    echo '<p class="wrong"> Input a proper last name</p>';
                } else if ($_GET['error'] == "invalidnationality") {
                    echo '<p class="wrong"> Input a proper nationality</p>';
                } else if ($_GET['error'] == "invalidbiography") {
                    echo '<p class="wrong"> Input a proper biography</p>';
                } else if ($_GET['error'] == "invalidmovieLength") {
                    echo '<p class="wrong"> Number only values</p>';
                }
            }
            if(isset($_GET['insert'])){
                if ($_GET['insert'] == "success") {
                    echo '<p class="right"> Insert Sucessful</p>';
                }
            }
            if (isset($_SESSION['artist_id'])) {
                $artistID = $_SESSION['artist_id'];

                $sql = "SELECT * FROM artists WHERE artistID=$artistID";
                $result = mysqli_query($conn, $sql);
                $i = 0;
                $roll = "odds";
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $firstName = $row['firstName'];
                        $lastName = $row['lastName'];
                        $nationality = $row['nationality'];
                        $yearOfBirth = $row['yearOfBirth'];
                        $yearOfDeath = $row['yearOfDeath'];
                        $biography  = $row['biography'];
                        $picture = $row['picture'];
                        $realPicture = str_replace('../', '', $picture);
                        $dt = strtotime($yearOfBirth);
                        $month = date('F', $dt);
                        $monthnum = date('n', $dt);
                        $date = date('d', $dt);
                        $datenum = date('j', $dt);
                        $year = date('Y', $dt);
                        $deathdate = strtotime($yearOfDeath);
                        $month2 = date('F', $deathdate);
                        $monthnum2 = date('n', $deathdate);
                        $date2 = date('d', $deathdate);
                        $datenum2 = date('j', $deathdate);
                        $year2 = date('Y', $deathdate);
                        echo '
                        <div class="article name-overview with-hero">
                            <div  id="name-overview-widget" class="name-overview-widget">
                                <table id="name-overview-widget-layout" cellspacing="0" cellpadding="0" border="0">
                                    <tbody>
                                        <tr>
                                            <td class="name-overview-widget__section">
                                                <h1 class="header"> <span class="itemprop">' . $firstName . " " . $lastName . '</span></h1>
                                                <hr>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td id="img_primary">
                                                <div class="poster-hero-container">
                                                    <div class="image">';
                        if (file_exists($realPicture)) {
                            echo '<img id="name-poster" alt="' . $firstName . " " . $lastName . ' Picture" title="' . $firstName . " " . $lastName . ' Picture" src="' . $realPicture . '" width="214" height="317"></a>';
                        } else {
                            echo '<img id="name-poster" alt="' . $firstName . " " . $lastName . ' Picture" title="' . $firstName . " " . $lastName . ' Picture" src="upload/artistdefault.png" width="214" height="317"></a>  ';
                        }

                        echo '</div> 
                                                    <div class="txt-block" id="name-bio-text">
                                                        <div class="name-trivia-bio-text">
                                                            <div class="inline">
                                                            ' . $biography . '
                                                            <span class="see-more inline nobr-only">
                                                            <a href="#">See full bio</a> »
                                                            </span>
                                                            </div>       
                                                        </div>
                                                        <div id="name-born-info" class="txt-block">
                                                            <h4 class="inline">Born: </h4>
                                                            <time datetime="' . $year . '-' . $monthnum . '-' . $datenum . '">
                                                            <a href="#">' . $month . " " . $date . '</a>, <a href="#">' . $year . '</a>
                                                            </time>
                                                            in
                                                            <a href="#">' . $nationality . '</a>
                                                        </div>';

                        if ($yearOfDeath < date("Y-m-d")) {
                            echo '<div class="txt-block">
                                                            <h4 class="inline">Death: </h4>
                                                            <time datetime="' . $year2 . '-' . $monthnum2 . '-' . $datenum2 . '">
                                                            <a href="#">' . $month2 . " " . $date2 . '</a>, <a href="#">' . $year2 . '</a>
                                                            </time>
                                                        </div>';
                        }
                        echo '</div>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td id="overview-top" class="name-overview-widget__section">';
                        if (isset($_SESSION['userId'])) {
                            echo '
                                            <hr><div><form method="POST">
                                            <button class="form-control form-control-sm bg-dark btn-color" type="submit" name="delete-btn" >Delete</button>
                                            <button class="form-control form-control-sm bg-dark btn-color" type="submit" name="update-btn" >Update</button>
                                            </form></div>';
                            if (isset($_POST['update-btn'])) {
                                echo '<form action="scripts/artistupdate.php" method="POST" enctype="multipart/form-data">
                                            <input class="form-control form-control-sm btn-bg-color2" type="file" name="picture" >
                                            <input class="form-control form-control-sm btn-bg-color2" type="text" name="firstName" placeholder="First Name">
                                            <input class="form-control form-control-sm btn-bg-color2" type="text" name="lastName" placeholder="Last Name">
                                            <input class="form-control form-control-sm btn-bg-color2" type="text" name="nationality" placeholder="Nationality">
                                            <textarea class="form-control form-control-sm btn-bg-color2" name="biography" placeholder="Biography"></textarea>
                                            <input class="form-control form-control-sm btn-bg-color2" type="date" name="yearOfBirth" placeholder="Year Of Birth">
                                            <input class="form-control form-control-sm btn-bg-color2" type="date" name="yearOfDeath" placeholder="Year Of Death">
                                            <button class="form-control form-control-sm btn-bg-color1 btn-color" onsubmit="myFunction(\'' . "Are you sure you want to update this artist?" . '\')" type="submit" name="artistupdate-submit">Update</button><br>
                                            </form>';
                            }
                        }
                        echo '</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>';

                        
                        $sqlm = "SELECT a.* FROM movies a INNER JOIN roles b INNER JOIN artists c on a.movieID = b.movieID and b.artistID = c.artistID WHERE c.artistID = $artistID ";
                        $results = mysqli_query($conn, $sqlm);
                        if (mysqli_num_rows($results) > 0) {
                            echo '<div class="article">
                        <div id="filmography">
                        <div id="" class="head" > <a name="actress">Also acted in</a></div>';
                            while ($rows = mysqli_fetch_assoc($results)) {
                                if ($i % 2 == 0) {
                                    $roll = "evens";
                                } else {
                                    $roll = "odds";
                                }
                                $yearOfWork = $rows['yearOfWork'];
                                $dat = strtotime($yearOfWork);
                                $yearnum = date('Y', $dat);
                                echo '<div class="filmo-row ' . $roll . '" id="actress-tt1618434">
                                <span class="year_column">&nbsp;' . $yearnum . '</span>
                                <b><a href="movieinfo.php?movie_id=' . $rows['movieID'] . '">' . $rows['title'] . '</a></b>
                                <br>
                            </div>';
                                $i++;
                            }
                        }

                        echo "</div></div>";
                    }
                }
            }
            if (isset($_POST['delete-btn'])) {
                echo "works";
                $artistID = $_GET['artist_id'];
                $sqlm = "DELETE FROM roles WHERE artistID=$artistID";
                mysqli_query($conn, $sqlm);
                $sqlm = "DELETE FROM artists WHERE artistID=$artistID";
                mysqli_query($conn, $sqlm);
                header("Location: index.php?removedartist");
                exit();
            }
        }
        ?>
    </div>
    <main>
    <?php
require "footer.php";
?>