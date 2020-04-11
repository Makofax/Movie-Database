<?php
include 'header.php';
?>



<div class="container">
<h1>Search page</h1>
    <?php
    if (isset($_POST['search-submit']) && isset($_POST['category']) && $_POST['category'] == "movie") {
        $search = mysqli_real_escape_string($conn, $_POST['search']);
        $sql = "SELECT * FROM movies WHERE title LIKE '%$search%' OR fullDescription LIKE '%$search%' OR shortDescription LIKE '%$search%' OR category LIKE '%$search%'";
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
            <td class="spacing">
            <h4><a href="movieinfo.php?movie_id=' . $row['movieID'] . '" title="' . $title . '"> ' . $title . '</a>';
                if ($dt > time()) {
                    echo "'<span> - [Opens '.$month.' '.$date.']</span>'";
                }
                echo '</h4>
            <p>
                <img title="R" alt="Certificate R" class="absmiddle certimage" src="https://m.media-amazon.com/images/G/01/imdb/images/certificates/us/r-2493392566._CB484113634_.png">
                        <time datetime="PT' . $movieLength . 'M">' . $movieLength . 'min</time>
            </p> 
            <div>
            ' . $shortDescription . '
            </div> 
            <div "txtblock">
                <h5 class="inline">Director:</h5>
                    <span>
                <a href="/name/nm2477891/?ref_=inth_ov_nm">Gary Dauberman</a></span>     
            </div> 
            <div class="txtblock">
                <h5 class="inline" >Stars: </h5>';
                $sqlm = "SELECT c.* FROM movies a INNER JOIN roles b INNER JOIN artists c on a.movieID = b.movieID and b.artistID = c.artistID WHERE a.movieID = " . $row['movieID'] . " ";
                $results = mysqli_query($conn, $sqlm);
                $queryResults = mysqli_num_rows($results);
                if ($queryResults > 0) {
                    while ($rows = mysqli_fetch_assoc($results)) {
                        echo '<a href="artistinfo.php?artist_id=' . $rows['artistID'] . '">' . $rows['firstName'] . " " . $rows['lastName'] . '</a>,';
                    }
                }
                echo '</div> 
             </td>
             </tr>
             </tbody>
            </table>
            </div> ';
                $i++;
            }
        }
        else{
            echo "There are no results matching your search!";
        }
    }
    if (isset($_POST['search-submit']) && isset($_POST['category']) && $_POST['category'] == "artist") {
        echo '
        <div id="content-2-wide" class="redesign">
            <div id="main">
                <div class="articles">
                    <br class="clear">
                    <div class="lister-list">';
                    
        $search = mysqli_real_escape_string($conn, $_POST['search']);
        $sql = "SELECT * FROM artists WHERE firstName LIKE '%$search%' OR lastName LIKE '%$search%' OR Nationality LIKE '%$search%'";
        $result = mysqli_query($conn, $sql);
        $queryResult = mysqli_num_rows($result);
        if($queryResult > 0){
            while ($row = mysqli_fetch_assoc($result)){
                echo '
                    <div class="lister-item mode-detail">
                        <div class="lister-item-image">';
                        $realmovieImage = str_replace("../","",$row['picture']);
                        if(file_exists($realmovieImage)){
                            echo '<a href="artistinfo.php?artist_id=' . $row['artistID'] . '"><img alt="' . $row['firstName'] . " ".$row['lastName']. '" title="' . $row['firstName'] . " ".$row['lastName']. '" src="'.$realmovieImage.'" class="loadlate" width="140" height="209"></a>';
                        }
                        else{
                            echo '<a href="artistinfo.php?artist_id=' . $row['artistID'] . '"><img alt="' . $row['firstName'] . " ".$row['lastName']. '" title="' . $row['firstName'] . " ".$row['lastName']. '" src="upload/artistdefault.png" class="loadlate" width="140" height="209"></a>';
                        }
                        
                        echo '</div>
                        <div class="lister-item-content">
                            <h3 class="lister-item-header">
                            <span class="lister-item-index unbold text-primary"></span>
                            <a href="artistinfo.php?artist_id='.$row['artistID'].'"> '.$row['firstName']." ".$row['lastName'].'</a></h3>
                            <p class="text-muted text-small">
                            Artist <span class="ghost">|</span>';
                            $sqlm = "SELECT a.* FROM movies a INNER JOIN roles b INNER JOIN artists c on a.movieID = b.movieID and b.artistID = c.artistID WHERE c.artistID = " . $row['artistID'] .  " limit 1";
                            $results = mysqli_query($conn, $sqlm);
                            $queryResults = mysqli_num_rows($results);
                            if($queryResults > 0){
                                while ($rows = mysqli_fetch_assoc($results)){
                                   echo '<a href=movieinfo.php?movie_id=' . $rows['movieID'] . '"> '.$rows['title'].'</a>';
                                }
                            }
                            echo '</p>
                            <p>';
                            $allowedlimit = 50;
                            if(mb_strlen($row['biography'])>$allowedlimit)
                            {
                                echo mb_substr($row['biography'],0,$allowedlimit)."...";
                            }
                            else{
                                echo $row['biography'];
                            }
                                            
                            echo '</p>
                        </div>
                    </div>
                        ';
                /*
                echo "<a href='artistinfo.php?artist_id=" . $row['artistID']."'><div> 
                <h3>".$row['firstName']. " " .$row['lastName'] . "</h3>
                <p>".$row['nationality']."</p>
                <p>".$row['yearOfBirth']."</p>
                <p>".$row['yearOfDeath']."</p>
                <p>".$row['biography']."</p>
                </div></a>";*/
            }
            
        }
        else{
            echo "There are no results matching your search!";
        }
        echo "</div></div></div></div>";
    }
    if (isset($_POST['search-submit']) && isset($_POST['category']) && $_POST['category'] == "both") {
        $search = mysqli_real_escape_string($conn, $_POST['search']);
        $sql = "SELECT * FROM movies WHERE  LIKE '%$search%' OR a_text LIKE '%$search%' OR a_author LIKE '%$search%' OR a_date LIKE '%$search%'";
        $result = mysqli_query($conn, $sql);
        $queryResult = mysqli_num_rows($result);
        if($queryResult > 0){
            while ($row = mysqli_fetch_assoc($result)){
                echo "<a href='article.php?title=".$row['a_title']."&date=".$row['a_date']."'><div> 
                <h3>".$row['a_title']."</h3>
                <p>".$row['a_text']."</p>
                <p>".$row['a_date']."</p>
                <p>".$row['a_author']."</p>
                </div></a>";
            }
        }
        else{
            echo "There are no results matching your search!";
        }
    }
    ?>

</div>
<?php
require "footer.php";
?>