<html>
<head></head>


<body><h1>Added Stocks</h1>

  <?php


  $gaming = ["ATVI","EA",	"GME",	"GLUU",	"ZNGA",	"NTES",	"TTWO",	"NTDOY"];
  $tech = ["MSFT",	"NVDA","JAKK",	"MAT","KIDBQ",	"HAS",	"DG"];
  $banks = ["BAC",	"FITB",	"GS",	"USB",	"BK",	"CPB",	"V", "WFC"];
 $fashion = ["RL","SHOO","UFI","GPS",	"LB",	"LULU","TGT",	"ANF",	"AEO",	"UA",	"NKE",	"UFI",	"GIL"];
  $vehicle = ["THO",	"TM",	"WGO",	"HMC",	"F",	"FCAU",	"GM",	"TSLA","GPC"];
$education = ["LRN",	"GPX",	"CHGG",	"GHC","EDU",	"TEDU",	"ATGE",	"BPI",	"ONE",	"STRA","UTI"];
$commodity = ["NEW",	"PTR",	"SHLX",		"VLO",		"BP",		"UUU",	"CLF"];

  //$restaurant = [ "TXRH",	"CAKE",	"CBRL",	"BDL",	"SBUX",	"PZZA",	"RRGB", "AMZN",	"UNFI",	"HAIN",	"SFM",	"NGVC",	"KR"];
  $restaurant = ["SFM",	"NGVC",	"KR"];


  $conn = new mysqli("localhost", "root", "", "stockinvestment");
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

       $bankarrayLength = count($restaurant);
       $i = 0;
       while ($i < $bankarrayLength)
       {

         $url = "https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=" . $restaurant[$i] . "&apikey=6NY6T4WCXM9DCPYC";
         $jsonData = file_get_contents($url);
         $jsonData = json_decode($jsonData,true);

         //$open = $jsonData['Global Quote']['02. open'];
         $close = $jsonData['Global Quote']['05. price'];
         //$high = $jsonData['Global Quote']['03. high'];
         //$low = $jsonData['Global Quote']['04. low'];
         $volume = $jsonData['Global Quote']['06. volume'];
         $percentageChange = $jsonData['Global Quote']['10. change percent'];

         $query = "INSERT into restaurantnew (symbol, price, percentChange, volume)
         VALUES ('$restaurant[$i]', '$close', '$percentageChange','$volume')";

         $result = mysqli_query($conn,$query);


         $i++;
       }






   ?>
















</body>
</html>
