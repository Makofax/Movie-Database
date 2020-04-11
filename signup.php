<?php
require "header.php";
?>

<main>
	<div class="container">
		<h1>Signup</h1>
		<?php
		if (isset($_GET['error'])) {
			if ($_GET['error'] == "emptyfields") {
				echo '<p class="wrong"> Fill in all fields!</p>';
			}
			else if ($_GET['error'] == "invalidmail") {
				echo '<p class="wrong"> Invalid email patern!</p>';
			}
			else if ($_GET['error'] == "invaliduid") {
				echo '<p class="wrong"> Invalid user pattern!</p>';
			}
			else if ($_GET['error'] == "passwordcheck") {
				echo '<p class="wrong"> Password don\'t match!</p>';
			}
		
		}
		?>
		<form action="includes/signup.inc.php" method="post">
			<input class="form-control form-control-sm btn-bg-color2" type="text" name="uid" placeholder="Username"><br>
			<input class="form-control form-control-sm btn-bg-color2" type="text" name="first" placeholder="First"><br>
			<input class="form-control form-control-sm btn-bg-color2" type="text" name="last" placeholder="Last"><br>
			<input class="form-control form-control-sm btn-bg-color2" type="text" name="mail" placeholder="E-mail"><br>
			<input class="form-control form-control-sm btn-bg-color2" type="password" name="pwd" placeholder="Password"><br>
			<input class="form-control form-control-sm btn-bg-color2" type="password" name="pwd-repeat" placeholder="Repeat Password"><br>
			<button class="form-control form-control-sm btn-bg-color1 btn-color" type="submit" name="signup-submit">Signup</button>
		</form>
	</div>
</main>

<?php
require "footer.php";
?>