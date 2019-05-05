<!DOCTYPE html>
<html><!-- chart.php 05.05.2019 -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>ECS chart</title>

    <!-- Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="./js/bootstrap.min.js"></script>
   
    <link href="./css/bootstrap.min.css" rel="stylesheet">

	  <meta  content="Yves Masur"  name="author">
    <?php 	
      include ("mdatefile.php"); 
      $plain_hours = false; // by default, all points
    ?> 
</head>
<body>
    <p  id="Titre"><h1>Data ECS (graphique)</h1></p>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="./js/bootstrap.min.js"></script>

    <!-- Chart part -->
    <script src="./js/Chart.min.js"></script>

<div class="container">
	<form  action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="get"> 
		<label><b>Data du:</b>
		  <span  class="ddown"> <?php echo dateSelect(); ?> </span> 
		</label> 
		<button type="submit" class="btn btn-success" >Afficher</button>
		<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>" class="btn btn-info" role="button">Aujourd'hui</a>
		<a href="ecslst.php" class="btn btn-info" role="button">Datas</a>
    <a href="ecsreport.php" class="btn btn-info" role="button">Rapport</a>
	</form>
</div>

<!--    Chart       -->
<div>
    <canvas id="ECSChart"></canvas>
    <script>
    var ctx = document.getElementById('ECSChart');
    var ECSChart = new Chart(ctx, 
    {
      type: 'line',
      data: 
      {
        labels: [<?php echo(getDataDay('time')); ?>],
        datasets: 
        [
          {
            label : 'Pompe',
            borderColor: 'rgb(0, 190, 0)',
            fill: false,
            tension: 0,         
            data: [<?php echo(getDataDay('pompe')); ?>],
          },
          {
            label: 'Panneaux',
            borderColor: 'rgb(50,50, 50)',
            fill: false,
            data: [<?php echo(getDataDay('t0')); ?>],
          },         {
            label: 'Eau solaire',
            borderColor: 'rgb(255, 50, 50)',
            fill: false,
            data: [<?php echo(getDataDay('t1')); ?>],
          },
          {
            label: 'Eau sanitaire',
            borderColor: 'rgb(50, 50, 190)',
            fill: false,
            data: [<?php echo(getDataDay('t2')); ?>],
          },          
        ],
      }
    });
    </script>
</div>

  </body>
</html>
