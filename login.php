<!DOCTYPE html>
<html>
<meta charset="utf-8">
<title>Login</title>
<link rel="stylesheet" href="style.css" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>

	<nav class="navbar navbar-expand-md navbar-dark bg-dark">
			<a href="#" class="navbar-brand">Stock Companion</a>
			<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
					<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarCollapse">
					<div class="navbar-nav">
							<a href="index.php" class="nav-item nav-link">News</a>
							<a href="portfolio.php" class="nav-item nav-link">Portfolio</a>
							<a href="stock_recommendation.php" class="nav-item nav-link">Reccomend Me Stocks</a>

					</div>
			</div>
	</nav>


<?php
require('db.php');
session_start();

if (isset($_POST['username'])){

	$username = stripslashes($_REQUEST['username']);

	$username = mysqli_real_escape_string($con,$username);
	$password = stripslashes($_REQUEST['password']);
	$password = mysqli_real_escape_string($con,$password);

        $query = "SELECT * FROM `users` WHERE username='$username'
and password='".md5($password)."'";
	$result = mysqli_query($con,$query) or die(mysql_error());
	$rows = mysqli_num_rows($result);
        if($rows==1){
	    $_SESSION['username'] = $username;


	    header("Location: index.php");
         }else{
	echo "<div class='form'>
<h4>Sorry but your username or password is invalid.</h4>
<br/>Click here to <a href='login.php'>Login</a></div>";
	}
    }else{
?>
<div class="wrapper">
<div class="form">
<h1>Log In</h1>
<form action="" method="post" name="login">
<input type="text" name="username" placeholder="Username" required />
<input type="password" name="password" placeholder="Password" required />
<br>
</br>
<input name="submit" type="submit" value="Login" style="width:200px;" />

</form>
</br>
<p>Not registered yet? <a href='registration.php'>Register Here</a></p>
</div>
</div>
<?php } ?>


</body>
</html>
