<?php
include "header.php";
?>

<main>
    <div class="container">
        <h1>Add Movie</h1>
        <?php
        if (isset($_GET['error'])) {
            if ($_GET['error'] == "emptyfields") {
                echo '<p class="wrong"> Fill in all fields!</p>';
            } else if ($_GET['error'] == "invalidmail") {
                echo '<p class="wrong"> Invalid email patern!</p>';
            } else if ($_GET['error'] == "invaliduid") {
                echo '<p class="wrong"> Invalid user pattern!</p>';
            } else if ($_GET['error'] == "passwordcheck") {
                echo '<p class="wrong"> Password don\'t match!</p>';
            }
        }
        ?>
        <form action="scripts/movieinsert.php" method="POST" enctype="multipart/form-data">
            <input class="form-control form-control-sm btn-bg-color2" type="text" name="title" placeholder="Movie Title">
            <input class="form-control form-control-sm btn-bg-color2" type="file" name="file">
            <textarea class="form-control form-control-sm btn-bg-color2" name="fullDescription" placeholder="Full Description"></textarea>
            <textarea class="form-control form-control-sm btn-bg-color2" name="shortDescription" placeholder="Short Description"></textarea>
            <input class="form-control form-control-sm btn-bg-color2" type="text" name="category" placeholder="Category of The Movie">
            <input class="form-control form-control-sm btn-bg-color2" type="date" name="yearOfWork" placeholder="Year Of Work">
            <input class="form-control form-control-sm btn-bg-color2" type="text" name="movieLength" placeholder="Movie Length">
            <button class="form-control form-control-sm btn-bg-color1 btn-color" type="submit" name="movieinsert-submit">Submit Movie</button><br>
        </form>
    </div>
</main>

<?php
require "footer.php";
?>