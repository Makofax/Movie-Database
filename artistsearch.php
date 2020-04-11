<?php
    require "header.php";
?>
<main>
    <h1>Names</h1>
    <div>
        <?php
        $movieID = $_SESSION['movie_id'];
        if (isset($_POST['search-submit'])) {
            $search = mysqli_real_escape_string($conn, $_POST['search']);
            $sql = "SELECT * FROM artists WHERE firstName LIKE '%$search%' OR lastName LIKE '%$search%'";
            $result = mysqli_query($conn, $sql);
            $queryResult = mysqli_num_rows($result);
            if ($queryResult > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<a href='movieinfo.php?movie_id=" . $movieID . "&firstName=" . $row['firstName'] . "&lastName=" . $row['lastName'] . "'>
                    <div> 
                        <p>" . $row['firstName'] . " " . $row['lastName'] . "</p>
                    </div>
                    </a>";
                }
            } else {
                echo "There are bi results matching your search!";
            }
        }
        ?>
        <div>
</main>
<?php
require "footer.php";
?>