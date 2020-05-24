
<?php

if(!isset($_SESSION["username"]) AND !isset($_GET['delete']) ){
  //AND !isset($_GET['displayChart'])
header("Location: login.php");
exit(); }
?>

<?php
require('db.php');





if (isset($_GET['delete']))
{
  $id = $_GET['delete'];
  $query = "DELETE FROM portfolio_data WHERE id=$id";
  $result = mysqli_query($con,$query);
  header('location:portfolio.php');
  exit;
}


if (isset($_POST['save']))
{


  $symbol = $_POST['stockEntered'];
  // Create connection
  $conn = new mysqli("localhost", "root", "", "stockinvestment");
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $username = $_SESSION["username"];

  $sql = "SELECT stock_symbol FROM portfolio_data WHERE user_name = '$username'";
  $result = $conn->query($sql);

  while($row = mysqli_fetch_assoc($result)){

    if ($row['stock_symbol'] == $symbol)
    {

      ?>
      <script>window.alert("Stock Already Exists In Portfolio");</script>
      <?php
      break;



    }


    else{
      $symbol = $_POST['stockEntered'];

      $url = "https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=" . $symbol . "&apikey=6NY6T4WCXM9DCPYC";
      $jsonData = file_get_contents($url);
      $jsonData = json_decode($jsonData,true);

      //$close = $jsonData['Global Quote']['05. price'];
//"Thank you for using Alpha Vantage! Our standard API call frequency is 5 calls per minute and 500 calls per day. Please visit https://www.alphavantage.co/premium/ if you would like to target a higher API call frequency."
      if(isset($jsonData['Note']) )
      {
        ?>
        <script>window.alert("Sorry API request limit reached. Only 5 API requests per minute. Please use the app sensible to not exceed this limit");</script>
        <?php
        break;
      }

      elseif ($jsonData['Error Message'] == "Invalid API call. Please retry or visit the documentation (https://www.alphavantage.co/documentation/) for GLOBAL_QUOTE.")
        {
          ?>
          <script>window.alert("Sorry Stock Not Available, Please Enter A Correct Stock Symbol");</script>
          <?php
          break;

      }

      else{


      $open = $jsonData['Global Quote']['02. open'];
      $close = $jsonData['Global Quote']['05. price'];
      $high = $jsonData['Global Quote']['03. high'];
      $low = $jsonData['Global Quote']['04. low'];
      $volume = $jsonData['Global Quote']['06. volume'];
      $percentageChange = $jsonData['Global Quote']['10. change percent'];


      $username = $_SESSION["username"];

      $query = "INSERT into portfolio_data (user_name, stock_symbol, open, close, low, high, volume, percentChange)
      VALUES ('$username','$symbol','$open','$close','$low','$high','$volume', '$percentageChange')";

      $result = mysqli_query($con,$query);

      header('location:portfolio.php');
      exit;
      break;
    }
  }

}


}
?>
