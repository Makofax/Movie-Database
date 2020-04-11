<?php
require "header.php";
?>

<main>
    <div class="container">
        <?php
        if (isset($_SESSION['userId'])) {
        echo '<a href="movieadd.php">Add movie</a>';
        }
        echo "<h1>LATEST MOVIES</h1>";
        echo '<div class="list detail sub-list">';
        $sql = "SELECT z.* FROM movies z WHERE NOW() > z.yearOfWork  ORDER BY z.yearOfWork DESC";
        $result = mysqli_query($conn, $sql);
        $queryResult = mysqli_num_rows($result);
        $i = 0;
        $roll = "odd";
        if ($queryResult > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                if ($i % 2 == 0) {
                    $roll = "even";
                } else {
                    $roll = "odd";
                }
                
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
                $month = date('M', $dt);
                $date = date('d', $dt);
                echo  '<div itemscope="" itemtype="http://schema.org/Movie" class="list_item ' . $roll . '">
                <table class="shell" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                        <tr>
                            <td rowspan="2" id="img_primary" valign="top">
                                <div class="image">
                                    <a href="movieinfo.php?movie_id=' . $row['movieID'] . '"> 
                                        <div class="hover-over-image zero-z-index">';
                                        if(file_exists($realmovieImage)){
                                            echo '<img class="shadowing" alt="' . $title . ' Poster" title="' . $title . '" src="'.$realmovieImage.'" width="140" height="209">';
                                        }
                                        else{
                                           echo '<img class="shadowing" alt="' . $title . ' Poster" title="' . $title . '" src="upload/moviedefault.png" width="140" height="209">'; 
                                        }
                                        echo '</div>
                                    </a>
                                </div>
                            </td>
                            <td class="overview-top">
                                <h4><a href="movieinfo.php?movie_id=' . $row['movieID'] . '" title="' . $title . '"> ' . $title . '</a>';
                if ($dt > time()) {
                    echo "'<span> - [Opens '.$month.' '.$date.']</span>'";
                }
                echo '</h4>
            <p class="cert-runtime-genre">
                <img title="R" alt="Certificate R" class="absmiddle certimage" src="https://m.media-amazon.com/images/G/01/imdb/images/certificates/us/r-2493392566._CB484113634_.png">
                        <time datetime="PT' . $movieLength . 'M">' . $movieLength . 'min</time>
            </p> 
            <div class="outline">
            ' . $shortDescription . '
            </div>';
            echo '<div class="txtblock">
                <h5 class="inline" >Stars: </h5>';
                $sqlm = "SELECT c.* FROM movies a INNER JOIN roles b INNER JOIN artists c on a.movieID = b.movieID and b.artistID = c.artistID WHERE a.movieID = " . $row['movieID'] . " LIMIT 3 ";
                $results = mysqli_query($conn, $sqlm);
                $queryResults = mysqli_num_rows($results);
                $i=0;
                if ($queryResults > 0) {
                    while ($rows = mysqli_fetch_assoc($results)) {
                        $str = '';
                        $str = '<a href="artistinfo.php?artist_id=' . $rows['artistID'] . '">' . $rows['firstName'] . " " . $rows['lastName'] . '</a>';
                        if($i++ -2){
                            $str .= ', ';
                        }
                        echo $str;
                    }
                }
                echo '</div> 
             </td>
             </tr>
             </tbody>
            </table>
            </div> ';
                $i++;
                /* echo "<div>
                <a href='movieinfo.php?movie_id="   . $row['movieID'] . "'> 
                  <h3>" . $row['title'] . "</h3>
                  <p>" . $row['fullDescription'] . "</p>
                  <p>" . $row['shortDescription'] . "</p>
                  <p> " . $row['category'] . "</p>
                  <p>" . $row['yearOfWork'] . "</p>
                </a>
                </div>
                ";*/
            }
        }
        echo "</div>";
        if (isset($_SESSION['userId'])) { } else { }
        ?>
    </div>
</main>

<?php
require "footer.php";
?>