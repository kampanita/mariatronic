<?php
 $con = mysqli_connect('localhost','root','indarra','temperatura');
?>
<!DOCTYPE HTML>
<html>
<head>
 <meta charset="utf-8">
 <title>
 Create Google Charts
 </title>
 <script type="text/javascript" src="https://www.google.com/jsapi"></script>
 <script type="text/javascript">
 google.load("visualization", "1", {packages:["corechart"]});
 google.setOnLoadCallback(drawChart);
 function drawChart() {
 var data = google.visualization.arrayToDataTable([

 ['id', 'hora','temp'],
 <?php 
 $query = "SELECT string(id) as id,string(hora) as hora, temp FROM tempe order BY fecha desc,hora desc";

 $exec = mysqli_query($con,$query);
 while($row = mysqli_fetch_array($exec)){
 echo "['".$row['id']."',".$row['hora']."],".$row['temp']."],";
 }
 ?> 
 ]);

 var options = {
 title: 'Date wise visits'
 };
 var chart = new google.visualization.ColumnChart(document.getElementById("columnchart"));
 chart.draw(data, options);
 }
 </script>
</head>
<body>
 <h3>Column Chart</h3>
 <div id="columnchart" style="width: 900px; height: 500px;"></div>
</body>
</html>
