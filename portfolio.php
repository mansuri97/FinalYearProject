<?php
session_start();
if(!isset($_SESSION["username"])){
header("Location: login.php");
exit(); }
?>

<html>

  <head>
    <meta charset="utf-8">
    <title>Stock Reccomendation App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <link rel="stylesheet" type="text/css" href="main.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  </head>

  <body>

    <?php require_once 'showStockInfo.php'; ?>


    <?php
      // Create connection
      $conn = new mysqli("localhost", "root", "", "stockinvestment");
      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }


      $username = $_SESSION["username"];

      $sql = "SELECT * FROM portfolio_data WHERE user_name = '$username'";
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
                <a href="portfolio.php" class="nav-item nav-link active">Portfolio</a>
                <a href="stock_recommendation.php" class="nav-item nav-link">Recommend Me Stocks</a>
                <a href="logout.php" class="nav-item nav-link">Sign out</a>
            </div>
        </div>
    </nav>


    <div class="container">


        <form class = 'enterStock' autocomplete="off" id = "enteredStock"  method="POST">
          <div class="autocomplete" style="width:550px;">
            <input id="myInput" type="text" name="stockEntered" placeholder="Search For Stocks -  e.g. AMZN">
          </div>
            <input type="submit" name="save">
        </form>







    <table class="table table-dark table-hover">
      <thead class="thead-dark">
        <tr>
          <th scope="col">Symbol</th>
          <th scope="col">Low</th>
          <th scope="col">High</th>
          <th scope="col">Close</th>
          <th scope="col">% Change</th>
          <th scope="col">Volume</th>
          <th scope="col"></th>
          <th scope="col"></th>
          <th scope="col"></th>

        </tr>
      </thead>
      <tbody id = "stock_table">
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <th scope="row" class="symbolPink"> <?php echo $row['stock_symbol']; ?> </th>

            <td> <?php echo $row['low']; ?> </td>
            <td> <?php echo $row['high']; ?> </td>
            <td> <?php echo $row['close']; ?> </td>
            <td> <?php echo $row['percentChange']; ?> </td>
            <td> <?php echo $row['volume']; ?> </td>

            <td> <a href="portfolio.php?displayChart= <?php echo $row['id']; ?>"
             class = "btn btn-info">Display Chart</a> </td>

             <td> <a href="portfolio.php?addHoldings= <?php echo $row['id']; ?>"
              class = "btn btn-info"> Add Transaction</a> </td>

            <td> <a href="showStockInfo.php?delete= <?php echo $row['id']; ?>"
             class = "btn btn-danger">Delete</a> </td>




          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>





    </div>




    <?php

    if(isset($_GET["addHoldings"]))
    {
      // got the id of the row in the portfolio table

        $id = $_GET['addHoldings'];

        $conn = new mysqli("localhost", "root", "", "stockinvestment");
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT shares,priceBoughtAt,stock_symbol FROM portfolio_data WHERE id = '$id'";
        $result = $conn->query($sql);
        $followingdata = $result->fetch_assoc();





?>
<div class="TransactionForm" id="myForm">
<form method="POST">
  <?php echo $followingdata['stock_symbol']; ?> Holdings
</br>
</br>
  <label>Number Of Shares:</label> </br>
  <input type='number' name='shareNumber' value = "<?php echo $followingdata['shares']; ?>"/> </br> </br>
  <label>Price Of Each Share at:</label> </br>
  <input type='number' name='priceboughtAt' value = "<?php echo $followingdata['priceBoughtAt']; ?>"/> </br> </br>

  <input type = 'submit' name= 'saveTransaction' value='save'  />
</form>
</div>
















<?php



if (isset($_POST['saveTransaction']))
{
  ?>
<script>
  var div = document.getElementById('myForm');
  div.style.visibility = "hidden";
  div.style.display = "none";
</script>
  <?php


      $id = $_GET['addHoldings'];

      $shareNumber =  $_POST['shareNumber'];
        $sharePrice =  $_POST['priceboughtAt'];


      $query = "UPDATE portfolio_data SET shares = '$shareNumber', priceBoughtAt = '$sharePrice' WHERE id = '$id'";

      $result = mysqli_query($con,$query);

      //hide form
      $DisplayForm = False;





    }
}
    ?>













    <?php

    if(isset($_GET["displayChart"]))
    {

      $id = $_GET['displayChart'];
        $sql = "SELECT stock_symbol FROM portfolio_data WHERE id=$id";

        $result = $conn->query($sql);

        while($row = mysqli_fetch_assoc($result)){

          $symbol = $row['stock_symbol'];
          break;
        }
    	$keyAPI='CU3AJCQWIX7D1J7G';

      $URL = 'https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol='.$symbol."&outputsize=full".'&apikey='.$keyAPI;
      $data_JSON = json_decode(@file_get_contents($URL));


    	if(isset($data_JSON->{'Time Series (Daily)'})){
      	$i=0;
      	$data_for_chart=array();
        $data_for_graph=$data_JSON->{'Time Series (Daily)'};

        foreach ($data_for_graph as $name => $key)
    		{
        	if($i==0)
    			{
          	$data_for_chart['ticker_symbol']=$symbol;
            $data_for_chart['close']= $key->{'4. close'};
            $data_for_chart['open']= $key->{'1. open'};
            $data_for_chart['low']= $key->{'3. low'};
            $data_for_chart['high']= $key->{'2. high'};
            $data_for_chart['volume']= number_format($key->{'5. volume'});
            $data_for_chart['date']= $name;
          }
        }

    ?>



    <?php

    	class object_JSON
    	{
      	var $refresh_Prev;
        var $pv_dta;
      }

    	$object_RESP=new object_JSON;
      $object_RESP->refresh_Prev=$data_JSON->{'Meta Data'}->{'3. Last Refreshed'};

    	class vol_cost
    	{
      	var $price;
        var $volume;
      }

    	$data_for_graph=$data_JSON->{'Time Series (Daily)'};
      $chart_data=array();

    	foreach ($data_for_graph as $name => $key)
    	{
      	$chart_data[$name]=new vol_cost;
        $chart_data[$name]->price=$key->{'4. close'};
        $chart_data[$name]->volume=$key->{'5. volume'};
    	}

    	$object_RESP->pv_dta=$chart_data;

    ?>


    <div id="chart" style="width: 100%; height: 600px;">
        	<div style="width:10%;height: 100%; float: left"></div>
        	<div style="width:80%; height: 100%;float: left">
        			<div id="container" style="border-style:solid; border-width: 1px; padding-left: 1px;width: 99.85%;height: 99.75%"></div>
        	</div>
        	<div style="width:10%;height: 100%; float: left"></div>
    </div>


    <script type="text/javascript">

    Data_FOR_Chart();


    function Get_new_Date(date)
    {
    return(date.split(" ")[0]+" "+"14:07:23");
    }

     function Data_FOR_Chart(){
        var xmlGet= JSON.parse('<?php echo json_encode($object_RESP) ;?>');
        var RFreshDate= xmlGet["refresh_Prev"];
        var symbol= '<?php echo $symbol; ?>';

            RFreshDate= Get_new_Date(RFreshDate);
            var new_date=new Date(RFreshDate);
            var monthCompare=new_date.getMonth()+1;

            var costData = xmlGet["pv_dta"];
            var volume=[];
            var category_data=[];
            var price=[];

            var low=10000,f=0;
            for(var values in costData){
                start_date=Get_new_Date(values);
                console.log(start_date+"\n")
                var ADATE=new Date(start_date);
                var ADAY= ADATE.getDate();
                var AMONTH= ADATE.getMonth()+1;
                day_month=WithinSTR(AMONTH)+"/"+WithinSTR(ADAY);
                category_data.push(day_month);
                volume.push(parseFloat(costData[values]["volume"]/1000000));
                price.push(parseFloat(costData[values]["price"]));
                if(parseFloat(costData[values]["price"])<low){
                    low=parseFloat(costData[values]["price"]);
                }
                if(monthCompare-AMONTH>5 && f%5==0) {
                                break;
                            }
                f=f+1;
            }
            low=low/10;
            low=(low*10)-5;
            volume=volume.reverse();
            price=price.reverse();


    Highcharts.chart('container', {
         title: {
           text: symbol
        },
         navigation: {
            buttonOptions: {
                enabled: true
            }
        },

        legend: {
                    align: 'right',
                    verticalAlign: 'top',
                    layout: 'vertical',
                    x: 0,
                    y: 100
                },
        xAxis: {
                    categories: category_data.reverse(),
                    labels: {
                                rotation: -30,
                                align: 'right'
                            },
                    tickInterval: 5,
                    tickLength: 5,
                },
        yAxis: [{
            min:low,
            tickInterval: 5,
            labels: {
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            title: {
                text: 'PRICE',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            }
        }, {
            min: 0,
            tickInterval: 80,
            title: {
                text: 'VOLUME',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            labels: {
                format: '{value}M',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            opposite: true
        }],
         series: [{
            name: symbol,
            type: 'area',
            zoomType: 'x',
            panning: true,
            panKey: 'shift',
            data: price,
            color:'#e8175d'

        }, {
            name:  ' Volume',
            type: 'column',
            color: "white",
            yAxis: 1,
            data: volume,
             tooltip: {
                valueSuffix: 'M'
            }
        }]
    })

    }


    function WithinSTR(n)
    {
    		if(n < 10)
    		{
    			return('0'+n);
    		}

    		else
    		{
        	return (n.toString());
    		}
    }



    </script>


    <?php
     }

    }
    ?>





    <script>

    function multiply(x,y)
    {
      var result = x*y;
      return result;
    }
    </script>

    <script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <script src="js/main.js"></script>

  </body>

</html>
