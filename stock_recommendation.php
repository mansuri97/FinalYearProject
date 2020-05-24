<?php
session_start();
if(!isset($_SESSION["username"])){
header("Location: login.php");
exit(); }
?>

<html>

  <head>
    <meta charset="utf-8">
    <title>Stock Recommendation App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="recommendationPage.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  </head>

  <body>



    <?php
      // Create connection
      $conn = new mysqli("localhost", "root", "", "stockinvestment");
      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }


      $username = $_SESSION["username"];

      $sql = "SELECT * FROM recommendations WHERE user_name = '$username'";
      $result = $conn->query($sql);
      //pre_r($result->fetch_assoc());
      //pre_r($result->fetch_assoc());
      //pre_r($result);

      //function pre_r($array)
        //{
        //echo '<pre>';
        //print_r($array);
        //echo '</pre>';
      //}

      ?>


    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <a href="#" class="navbar-brand">StockCompanion</a>
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav">
                <a href="index.php" class="nav-item nav-link">News</a>
                <a href="portfolio.php" class="nav-item nav-link">Portfolio</a>
                <a href="stock_recommendation.php" class="nav-item nav-link active">Recommend Me Stocks</a>
                <a href="logout.php" class="nav-item nav-link">Sign out</a>
            </div>
        </div>
    </nav>



  <div class = "row no-gutters">

    <div class="col no-gutters">
      <div class="left_side">


      <form action="recommendation_algorithm.php" method="post">



        <div id="categoryOptions">
            <h6>Which category of stock do you have an interest in?</h6>
        <div id = "left_halfOptions">
          <input type="checkbox" name="category[]" value="gaming" />Gaming
          <input type="checkbox" name="category[]" value="tech" />Technology
          <input type="checkbox" name="category[]" value="education" />Education
          <input type="checkbox" name="category[]" value="commodity" />Commodity
        <div id = "right_halfOptions">

          <input type="checkbox" name="category[]" value="banks" />Banks
          <input type="checkbox" name="category[]" value="fashion" />Fashion
          <input type="checkbox" name="category[]" value="vehicle" />Vehicles
          <input type="checkbox" name="category[]" value="restaurant" />Restaurant
        </div>
    </div>
      </div>
        <br />

       <h6>What is your total investment amount?</h6>
        <div class="input-group mb-3" >
          <div class="input-group-prepend">
            <span class="input-group-text">£</span>
          </div>
          <input type="number" class="form-control" name="investment_amount" value = "1000" min="0" step="10"  >
        </div>


         <h6>What is your risk to reward ratio?</h6>
        <select name="risk_level">
          <option value="very low">Conservative (low risk)</option> <br />
          <option value="low">Moderately Conservative</option> <br />
          <option value="average">Moderately Aggressive</option> <br />
          <option value="high">Aggressive</option> <br />
          <option value="very high">Very Aggressive (high risk)</option> <br />
        </select>
        <br />
        <br />


         <h6>Do you mind investing in sin stocks?</h6>
        <!-- (for more details on sin stocks click here) <br /> -->

        <input type="radio" id="no" name="sinStock" value="no">
        <label for="age1" class = "pink">No I'm against this</label>
        <input type="radio" id="yes" name="sinStock" value="yes">
        <label for="age2" class="pink">Yes I don't mind</label><br>



         <h6>Short term or long term investment? </h6>
        <input type="radio" id="shortTerm" name="investmentDuration" value="shortTerm">
        <label for="age1" class="pink">Short Term (2-8 weeks)</label>
        <input type="radio" id="longTerm" name="investmentDuration" value="longTerm">
        <label for="age2" class="pink">Long Term (8 weeks+)</label><br>


</br>

        <input type="submit" name="formSubmit" value="Recommend Me Stocks" style="width:390px;" />

      </form>


      </div>
    </div>

    <div class="col no-gutters">
      <div class="right_side">
        <div id="recc-table">

          <table class="table table-dark table-hover">
          <thead class="thead-dark">
            <tr>
              <th scope="col">Symbol</th>
              <th scope="col">Volume</th>
              <th scope="col">Price</th>
              <th scope="col">% Change</th>
              <th scope="col"></th>

            </tr>
          </thead>
          <tbody id = "recommendation_table">

            <?php while($row = $result->fetch_assoc()): ?>
              <tr>
                <th class="symbolPink"> <?php echo $row['symbol']; ?> </th>
                <td> <?php echo $row['stock']; ?> </td>
                <td> <?php echo $row['price']; ?> </td>
                <td> <?php echo $row['percentChange']; ?> </td>

                <td> <a href="recommendation_algorithm.php?addToPortfolio= <?php echo $row['id']; ?>"
                 class = "btn btn-info">Add to Portfolio</a> </td>

              </tr>
            <?php endwhile; ?>


          </tbody>
        </table>



      </div>



      </div>
    </div>


  </div>




    <script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <script src="js/main.js"></script>

  </body>

</html>
