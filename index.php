<?php 
	require_once 'user/controllers/authController.php';

	//verify the user using token
	if(isset($_GET['token'])){
		$token = $_GET['token'];
		verifyUser($token);
		exit();
	}

	//verify if user not logged in
	if(!isset($_SESSION['firstname'])){
		header('location: login.php');
		exit();
	}
?>


<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>HomePage</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- MATERIAL DESIGN ICONIC FONT -->
		<link rel="stylesheet" href="fonts/material-design-iconic-font/css/material-design-iconic-font.min.css">
		
		<!-- STYLE CSS -->
		<link rel="stylesheet" href="css/style.css">

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	</head>

	<body>

		<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
			<ul class="navbar-nav">
				<li class="nav-item active">
					<a class="nav-link" href="home.php">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="login.php">Sign In</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="index.php?logout=1">Logout-<?php echo $_SESSION['firstname']; ?></a>
				</li>
			</ul>
		</nav>

		<div class="container" style="background-image: url('');">
			<div class="inner">
			<?php if(isset($_SESSION['message'])):?>
				<div class="alert" <?php echo $_SESSION['alert-class']; ?>>
					<?php 
						echo $_SESSION['message']; 
						unset($_SESSION['message']);
						unset($_SESSION['alert-classs']);
					?>
                </div>
			<?php endif; ?>

            <h3>Welcome, <?php echo $_SESSION['firstname']; ?></h3>

                <a href="index.php?logout=1">Logout</a>
				<?php if(!$_SESSION['isEmailConfirmed']): ?>
                <div class="alert alert-warning">
                    You have to verify your account.
                    Please verify your email using the link aleady sent to your email account.
					<?php if(isset($_SESSION['email'])):?>
                    <strong><?php echo $_SESSION['email']; ?></strong>
					<?php endif; ?>
                 </div>
				<?php endif; ?>

				<?php if($_SESSION['isEmailConfirmed']): ?>
					<button class="btn btn-block btn-lg btn-primary" onclick=" relocate_home()">Iam Verified</button>
				<?php endif; ?>

			</div>
		</div>

	</body>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script>
		function relocate_home()
		{
			location.href = "home.php";
		} 
	</script>
</html>