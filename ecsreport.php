<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>ECS (Report)</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<meta  content="Yves Masur"  name="author">
<?php 	
    include ("mdatefile.php"); 
		include ("mdatereport.php");
?> 
</head>
<body>
    <p  id="Titre"><h1>Data ECS month report</h1></p>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>	

<div class="container">
	<form  action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="get"> 
		<label><b>Data du:</b>
			<span  class="ddown"> <?php echo dateSelect(true); ?> </span> 
		</label> 
		<button type="submit" class="btn btn-success" >Afficher</button>
		<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>" class="btn btn-info" role="button">Ce mois</a>
		<a href="ecslst.php" class="btn btn-info" role="button">Datas</a>
    <a href="chart.php" class="btn btn-info" role="button">Graphique</a>
	</form>
</div>
	<div class="container" id=datavalues;">
    <table  class="table table-striped">
      <thead>
        <tr>
          <th>Jour</th>
          <th>Solaire [%]</th>
          <th>Cst de temps [h]</th>
          <th>(V0; V6; Vamb; t)</th>
        </tr>
      </thead>
      <tbody>
        <?php echo show_month_report(); ?>
      </tbody>
    </table>
    </div>
  </body>
</html>
