<?php
session_start();
if(!isset($_SESSION["username"]) ){
  //AND !isset($_GET['displayChart'])
header("Location: login.php");
exit(); }
?>



<?php
require('db.php');



//appending the recommended stock to the user portfolio
if (isset($_GET['addToPortfolio']))
{
  $id = $_GET['addToPortfolio'];
  $query = "SELECT symbol FROM recommendations WHERE id=$id";
  $result = mysqli_query($con,$query);
  $row = $result->fetch_array(MYSQLI_NUM);

  $url = "https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=" . $row[0] . "&apikey=6NY6T4WCXM9DCPYC";
  $jsonData = file_get_contents($url);
  $jsonData = json_decode($jsonData,true);

  $open = $jsonData['Global Quote']['02. open'];
  $close = $jsonData['Global Quote']['05. price'];
  $high = $jsonData['Global Quote']['03. high'];
  $low = $jsonData['Global Quote']['04. low'];
  $volume = $jsonData['Global Quote']['06. volume'];
  $username = $_SESSION["username"];

  $query = "INSERT into portfolio_data (user_name, stock_symbol, open, close, low, high, volume)
  VALUES ('$username','$row[0]','$open','$close','$low','$high','$volume')";

  $result = mysqli_query($con,$query);

  header('location:portfolio.php');
  exit;

}

//boolean function to check whether if the category checkbox is ticked
function is_category_checked($category,$value)
{
    if(!empty($_POST['category']))
    {
        foreach($_POST['category'] as $categoryValue)
        {
            if($categoryValue == $value)
            {
                return true;
            }
        }
    }
    return false;
}



//when the recommendation form is submitted, then execute this code
if (isset($_POST['formSubmit']))
{
  //creating new arrays
  $Reccomendation_List = array();
  $stockPrice_List = array();
  $percentChange_List= array();
  $volume_List = array();
  $allUserPortfolio = array();
  $sinStock_List = ["EA"];

  //delete the current data in the user's recommendation table
  $username = $_SESSION["username"];
  $query = "DELETE FROM recommendations WHERE user_name='$username'";
  $result = mysqli_query($con,$query);


  //filter by bank stocks
  if(is_category_checked('category','banks'))
  {

    $conn = new mysqli("localhost", "root", "", "stockinvestment");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM banksnew";
    $result = $conn->query($sql);

    while($row = $result->fetch_assoc()):
      array_push($Reccomendation_List, $row['symbol']);
      array_push($stockPrice_List, $row['price']);
      array_push($percentChange_List, $row['percentChange']);
      array_push($volume_List, $row['volume']);
    endwhile;

  }




  //filter by game stocks
  if(is_category_checked('category','gaming'))
  {

    $conn = new mysqli("localhost", "root", "", "stockinvestment");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM gamingnew";
    $result = $conn->query($sql);

    while($row = $result->fetch_assoc()):
      array_push($Reccomendation_List, $row['symbol']);
      array_push($stockPrice_List, $row['price']);
      array_push($percentChange_List, $row['percentChange']);
      array_push($volume_List, $row['volume']);
    endwhile;

  }


  //filter by fashion stocks
  if(is_category_checked('category','fashion'))
  {

    $conn = new mysqli("localhost", "root", "", "stockinvestment");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM fashionnew";
    $result = $conn->query($sql);

    while($row = $result->fetch_assoc()):
      array_push($Reccomendation_List, $row['symbol']);
      array_push($stockPrice_List, $row['price']);
      array_push($percentChange_List, $row['percentChange']);
      array_push($volume_List, $row['volume']);
    endwhile;

  }


  //filter by commodity stocks
  if(is_category_checked('category','commodity'))
  {

    $conn = new mysqli("localhost", "root", "", "stockinvestment");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM commoditynew";
    $result = $conn->query($sql);

    while($row = $result->fetch_assoc()):
      array_push($Reccomendation_List, $row['symbol']);
      array_push($stockPrice_List, $row['price']);
      array_push($percentChange_List, $row['percentChange']);
      array_push($volume_List, $row['volume']);
    endwhile;

  }


  //filter by tech stocks
  if(is_category_checked('category','tech'))
  {

    $conn = new mysqli("localhost", "root", "", "stockinvestment");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM technew";
    $result = $conn->query($sql);

    while($row = $result->fetch_assoc()):
      array_push($Reccomendation_List, $row['symbol']);
      array_push($stockPrice_List, $row['price']);
      array_push($percentChange_List, $row['percentChange']);
      array_push($volume_List, $row['volume']);
    endwhile;

  }


  //filter by education stocks
  if(is_category_checked('category','education'))
  {

    $conn = new mysqli("localhost", "root", "", "stockinvestment");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM educationnew";
    $result = $conn->query($sql);

    while($row = $result->fetch_assoc()):
      array_push($Reccomendation_List, $row['symbol']);
      array_push($stockPrice_List, $row['price']);
      array_push($percentChange_List, $row['percentChange']);
      array_push($volume_List, $row['volume']);
    endwhile;

  }


  //filter by vehicle stocks
  if(is_category_checked('category','vehicle'))
  {

    $conn = new mysqli("localhost", "root", "", "stockinvestment");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM vehiclenew";
    $result = $conn->query($sql);

    while($row = $result->fetch_assoc()):
      array_push($Reccomendation_List, $row['symbol']);
      array_push($stockPrice_List, $row['price']);
      array_push($percentChange_List, $row['percentChange']);
      array_push($volume_List, $row['volume']);
    endwhile;

  }


  //filter by restaurant stocks
  if(is_category_checked('category','restaurant'))
  {

    $conn = new mysqli("localhost", "root", "", "stockinvestment");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM restaurantnew";
    $result = $conn->query($sql);

    while($row = $result->fetch_assoc()):
      array_push($Reccomendation_List, $row['symbol']);
      array_push($stockPrice_List, $row['price']);
      array_push($percentChange_List, $row['percentChange']);
      array_push($volume_List, $row['volume']);
    endwhile;

  }




  //this variable will hold the risk level of the user
  $selected_val = $_POST['risk_level'];

  //if very low risk selected
  if($selected_val == "very low")
  {

    for($x=0;$x<count($percentChange_List);$x++)
    {
      while($percentChange_List[$x] > 2):


                unset($stockPrice_List[$x]);
               $stockPrice_List = array_merge($stockPrice_List);
               unset($Reccomendation_List[$x]);
               $Reccomendation_List = array_merge($Reccomendation_List);
               unset($percentChange_List[$x]);
               $percentChange_List = array_merge($percentChange_List);
               unset($volume_List[$x]);
              $volume_List = array_merge($volume_List);

        endwhile;
      }

    }



  //if low risk selected
  if($selected_val == "low")
  {

    for($x=0;$x<count($percentChange_List);$x++)
    {
      while($percentChange_List[$x] > 3 ):
                unset($stockPrice_List[$x]);
               $stockPrice_List = array_merge($stockPrice_List);
               unset($Reccomendation_List[$x]);
               $Reccomendation_List = array_merge($Reccomendation_List);
               unset($percentChange_List[$x]);
               $percentChange_List = array_merge($percentChange_List);
               unset($volume_List[$x]);
              $volume_List = array_merge($volume_List);
        endwhile;
      }

  }



  //if average risk selected
  if($selected_val == "average")
  {

    for($x=0;$x<count($percentChange_List);$x++)
    {
      while($percentChange_List[$x] > 4 ):
                unset($stockPrice_List[$x]);
               $stockPrice_List = array_merge($stockPrice_List);
               unset($Reccomendation_List[$x]);
               $Reccomendation_List = array_merge($Reccomendation_List);
               unset($percentChange_List[$x]);
               $percentChange_List = array_merge($percentChange_List);
               unset($volume_List[$x]);
              $volume_List = array_merge($volume_List);
        endwhile;

      }

  }



  //if high risk selected
  if($selected_val == "high")
  {

    for($x=0;$x<count($percentChange_List);$x++)
    {
      while($percentChange_List[$x] > 5 ):
                unset($stockPrice_List[$x]);
               $stockPrice_List = array_merge($stockPrice_List);
               unset($Reccomendation_List[$x]);
               $Reccomendation_List = array_merge($Reccomendation_List);
               unset($percentChange_List[$x]);
               $percentChange_List = array_merge($percentChange_List);
               unset($volume_List[$x]);
              $volume_List = array_merge($volume_List);
        endwhile;

      }

  }



//if very high risk selected
if($selected_val == "very high")
{

  for($x=0;$x<count($percentChange_List);$x++)
  {
    while($percentChange_List[$x] > 6):
              unset($stockPrice_List[$x]);
             $stockPrice_List = array_merge($stockPrice_List);
             unset($Reccomendation_List[$x]);
             $Reccomendation_List = array_merge($Reccomendation_List);
             unset($percentChange_List[$x]);
             $percentChange_List = array_merge($percentChange_List);
             unset($volume_List[$x]);
            $volume_List = array_merge($volume_List);
      endwhile;

    }

}




  //filter by user desired investment amount
  $amount = $_POST['investment_amount'];

  for($x=0;$x<count($stockPrice_List);$x++)
  {
    while($stockPrice_List[$x] > $amount):
              unset($stockPrice_List[$x]);
             $stockPrice_List = array_merge($stockPrice_List);
             unset($Reccomendation_List[$x]);
             $Reccomendation_List = array_merge($Reccomendation_List);
             unset($percentChange_List[$x]);
             $percentChange_List = array_merge($percentChange_List);
             unset($volume_List[$x]);
            $volume_List = array_merge($volume_List);
      endwhile;

    }


  //filter out the sin stocks if user doesn't want to invest in them
  if(isset($_POST['sinStock']))
  {
      $value = $_POST['sinStock'];
      if($value == "no")
      {
          foreach ($sinStock_List as $key => $sinStock)
           {
             foreach ($Reccomendation_List as $x => $y)
             {
               while($Reccomendation_List[$x] == $sinStock):

                  unset($stockPrice_List[$x]);
                  $stockPrice_List = array_merge($stockPrice_List);
                  unset($Reccomendation_List[$x]);
                  $Reccomendation_List = array_merge($Reccomendation_List);
                  unset($percentChange_List[$x]);
                  $percentChange_List = array_merge($percentChange_List);
                  unset($volume_List[$x]);
                  $volume_List = array_merge($volume_List);

                endwhile;
               }

           }
        }

      }




      //filter out the stocks which have -ve percent changes for short term investment users
      if(isset($_POST['investmentDuration']))
      {
          $value = $_POST['investmentDuration'];
          if($value == "shortTerm")
          {

                 foreach ($percentChange_List as $x => $y)
                 {
                   while($percentChange_List[$x] < 0.00):

                      unset($stockPrice_List[$x]);
                      $stockPrice_List = array_merge($stockPrice_List);
                      unset($Reccomendation_List[$x]);
                      $Reccomendation_List = array_merge($Reccomendation_List);
                      unset($percentChange_List[$x]);
                      $percentChange_List = array_merge($percentChange_List);
                      unset($volume_List[$x]);
                      $volume_List = array_merge($volume_List);

                    endwhile;
                   }

               }
            }









// sort the volume_list array using bubble sort, in descending order so that the highest volume is at the start
// then use the same swapping of indexes to sort the rest of the arrays appropriately
        $n = sizeof($volume_List);
        if($n>1){
        for ($i = 1; $i < $n; $i++)
        {
            for ($j = $n - 1; $j >= $i; $j--)
            {
                if($volume_List[$j-1] <= $volume_List[$j])
                {

                    $tmpVolume = $volume_List[$j - 1];
                    $volume_List[$j - 1] = $volume_List[$j];
                    $volume_List[$j] = $tmpVolume;

                    $tmpSymbol = $Reccomendation_List[$j - 1];
                    $Reccomendation_List[$j - 1] = $Reccomendation_List[$j];
                    $Reccomendation_List[$j] = $tmpSymbol;

                    $tmpPrice = $stockPrice_List[$j - 1];
                    $stockPrice_List[$j - 1] = $stockPrice_List[$j];
                    $stockPrice_List[$j] = $tmpPrice;

                    $tmpPercentChange = $percentChange_List[$j - 1];
                    $percentChange_List[$j - 1] = $percentChange_List[$j];
                    $percentChange_List[$j] = $tmpPercentChange;

                }
            }
        }
      }



  // machine learning - the recommendation will be encouraged by the portfolio of other users
  // looking at other user's porfolio, we can see which stocks have a good rating (more popular)and which ones are not so much
  $conn = new mysqli("localhost", "root", "", "stockinvestment");
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }


  $sql = "SELECT stock_symbol FROM portfolio_data";
  $result = $conn->query($sql);
  $followingdata = $result->fetch_assoc();

  while($row = $result->fetch_assoc()):
    array_push($allUserPortfolio, $followingdata['stock_symbol']);
  endwhile;

  //get the most famous stock in my app based on all users
  $acv = array_count_values($allUserPortfolio);
  $mostFamousStock = array_search(max($acv), $acv);

  //if the mostFamousStock is not in the recommendation list, then get all of its details and append it to the start of the list
  //if it already exists, then move it to the top as if its the most popular, then volume will also be high
  if (in_array($mostFamousStock, $Reccomendation_List, TRUE))
  {
    echo "user already has the most famous stock";
  }

  else
  {
    //get the data for the stock and append to the list
    $url = "https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=" . $mostFamousStock . "&apikey=6NY6T4WCXM9DCPYC";
    $jsonData = file_get_contents($url);
    $jsonData = json_decode($jsonData,true);


    $close = $jsonData['Global Quote']['05. price'];
    $volume = $jsonData['Global Quote']['06. volume'];
    $percentageChange = $jsonData['Global Quote']['10. change percent'];

    array_unshift($Reccomendation_List, $mostFamousStock );
    array_unshift($percentChange_List, $percentageChange );
    array_unshift($volume_List, $volume );
    array_unshift($stockPrice_List, $close);

  }




    //insert recommendations into table
    $n = sizeof($volume_List);
    for ($i = 0; $i < 7; $i++)
    {
      if (!empty($volume_List[$i]))
      {
        $stockPercentChange = $percentChange_List[$i];
        $stockPrice1 = $stockPrice_List[$i];
        $stockSymbol1 = $Reccomendation_List[$i];
        $stockVolume1 = $volume_List[$i];

        $username = $_SESSION["username"];
        $query = "INSERT into recommendations (user_name, symbol, price, stock, percentChange)
        VALUES ('$username','$stockSymbol1','$stockPrice1','$stockVolume1','$stockPercentChange')";

        $result = mysqli_query($con,$query);

      }

    }

  // code used for testing
  // foreach($volume_List as $index => $value){
  //     echo $index . "<br>";
  //     echo $value . "<br>";
  // }

  // foreach($percentChange_List as $index => $value){
  //     echo $index ;
  //     echo $value . "<br>";
  // }
  //
  // foreach($Reccomendation_List as $index => $value){
  //     echo $index ;
  //     echo $value . "<br>";
  // }
  //
  // foreach($stockPrice_List as $index => $value){
  //     echo $index ;
  //     echo $value . "<br>";
  // }
  //
  // foreach($volume_List as $index => $value){
  //     echo $index ;
  //     echo $value . "<br>";
  // }
  //
  // echo $mostFamousStock;

  //if i want to see my errors don't redirect
  header('location: stock_recommendation.php');
  exit;



}





?>
