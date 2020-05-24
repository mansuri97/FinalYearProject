<?php
session_start();
if(!isset($_SESSION["username"])){
header("Location: login.php");
exit(); }
?>

<html>

  <head>
    <meta charset="utf-8">
    <title>Stock Companion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="index.css">
  </head>


  <body>

    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <a href="#" class="navbar-brand">Stock Companion</a>
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav">
                <a href="home.php" class="nav-item nav-link active">News</a>
                <a href="portfolio.php" class="nav-item nav-link">Portfolio</a>
                <a href="stock_recommendation.php" class="nav-item nav-link">Recommend Me Stocks</a>
                <a href="logout.php" class="nav-item nav-link">Sign out</a>
            </div>
        </div>
    </nav>



          <form class = 'enterNewsq' id = "enteredNews"  method="POST">
            <div class="NewsType" style="width:550px;">
              <input id="myNewsInput" type="text" name="NewsTypeEntered" placeholder="Search For Stock News -  e.g. tesla">
            </div>

              <input type="submit" name="save" >
          </form>






    <?php
      $url = 'https://newsapi.org/v2/top-headlines?language=en&q=invest&apiKey=ab4133f7827c41b797cc1a1fb7f3d358';
      $response = file_get_contents($url);
      $NewsData = json_decode($response);
    ?>




    <div id="test" class="container-fluid" >

        <?php
          foreach ($NewsData -> articles as $News)
          {
        ?>

        <div class = "row NewsGrid">

          <div class = "col-md-3">
            <img src = "<?php echo $News -> urlToImage  ?>" alt="News Thumbnail" class="rounded">
          </div>

          <div class = "col-md-8">
            <h2><?php echo $News -> title  ?> </h2> </br>
            <h5> <?php echo $News -> description  ?> </h5> </br>
            <a href = "<?php echo  $News -> url  ?>"><?php echo  $News -> url  ?></a>
            <h6>Published: <?php echo $News -> publishedAt  ?> </h6>
          </div>

        </div>
        <?php
          }
        ?>

      </div>


      <?php
      if(isset($_POST["save"]))
      {
        $newsCategory = $_POST['NewsTypeEntered'];


        $url = 'https://newsapi.org/v2/top-headlines?language=en&q=' . $newsCategory . '&apiKey=ab4133f7827c41b797cc1a1fb7f3d358';
        $response = @file_get_contents($url);
        $no_news = json_decode($response,true);
        if ($no_news['totalResults'] == 0)
        {

          ?>
          <script>window.alert("Wrong data type, please enter correct letter only");</script>
          <?php

        }
        else{
?>
          <script type="text/javascript">

      document.getElementById("test").style.display = "none";

          </script>
<?php

          $NewsData = json_decode($response);

          ?>

          <div class="container-fluid">

              <?php
                foreach ($NewsData -> articles as $News)
                {
              ?>

              <div class = "row NewsGrid">

                <div class = "col-md-3">
                  <img src = "<?php echo $News -> urlToImage  ?>" alt="News Thumbnail" class="rounded">
                </div>

                <div class = "col-md-8">
                  <h2><?php echo $News -> title  ?> </h2> </br>
                  <h5> <?php echo $News -> description  ?> </h5> </br>
                  <a href = "<?php echo  $News -> url  ?>"><?php echo  $News -> url  ?></a>
                  <h6>Published: <?php echo $News -> publishedAt  ?> </h6>
                </div>


              </div>
              <?php
                }
              ?>

          </div>

          <?php

}
      }

        ?>


    <script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <script src="js/main.js"></script>

  </body>

</html>
