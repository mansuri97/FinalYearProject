<!DOCTYPE html>
<html>
<meta charset="utf-8">
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
// If form submitted, insert values into the database.
if (isset($_REQUEST['username'])){
        // removes backslashes
	$username = stripslashes($_REQUEST['username']);
        //escapes special characters in a string
	$username = mysqli_real_escape_string($con,$username);
	$email = stripslashes($_REQUEST['email']);
	$email = mysqli_real_escape_string($con,$email);
	$password = stripslashes($_REQUEST['password']);
	$password = mysqli_real_escape_string($con,$password);
	$trn_date = date("Y-m-d H:i:s");
        $query = "INSERT into `users` (username, password, email, trn_date)
VALUES ('$username', '".md5($password)."', '$email', '$trn_date')";
        $result = mysqli_query($con,$query);
        if($result){
            echo "<div class='form'>
<h3>Your Account is Created! Registration Complete.</h3>
<br/>Click here to <a href='login.php'>Login</a></div>";

//----------------------


$url = "https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=AAPL&apikey=CU3AJCQWIX7D1J7G";
$jsonData = file_get_contents($url);
$jsonData = json_decode($jsonData,true);

$close = $jsonData['Global Quote']['05. price'];
$open = $jsonData['Global Quote']['02. open'];
$high = $jsonData['Global Quote']['03. high'];
$low = $jsonData['Global Quote']['04. low'];
$volume = $jsonData['Global Quote']['06. volume'];
$percentageChange = $jsonData['Global Quote']['10. change percent'];



$query = "INSERT into portfolio_data (user_name, stock_symbol, open, close, low, high, volume, percentChange)
VALUES ('$username','AAPL','$open','$close','$low','$high','$volume', '$percentageChange')";

$result = mysqli_query($con,$query);



//-------------

        }
    }else{
?>
<div class="wrapper">
<div class="form">
<h1>Registration</h1>
<form name="registration" action="" method="post">
<input type="text" name="username" placeholder="Username" required />
<input type="email" name="email" placeholder="Email" required />
<input type="password" name="password" placeholder="Password" required /> </br> </br>
<input type="submit" name="submit" value="Register" style="width:200px;"/>
</form>
</br>
<p>Already have an account? <a href='login.php'>Login Here</a></p>
</div>
</div>
<?php } ?>
</body>
</html>
