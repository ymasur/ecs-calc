<!DOCTYPE html>
<html><!-- ecllst.php 05.05.2019 -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>ECS (Data)</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<meta  content="Yves Masur"  name="author">
<?php 	include ("mdatefile.php"); ?> 
</head>
<body>
    <p  id="Titre"><h1>Data ECS (liste)</h1></p>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>	

<div class="container">
	<form  action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="get"> 
		<label><b>Data du:</b>
			<span  class="ddown"> <?php echo dateSelect(); ?> </span> 
		</label> 
		<input type="checkbox" name="filter" value="1" 
		<?php if (isset($_GET['filter']) && $_GET['filter'] == "1") echo "checked";?> > Tout
		<button type="submit" class="btn btn-success" >Afficher</button>
		<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>" class="btn btn-info" role="button">Aujourd'hui</a>
		<a href="chart.php" class="btn btn-info" role="button">Graphique</a>
		<a href="ecsreport.php" class="btn btn-info" role="button">Rapport</a>    
	</form>
</div>
	<div class="container" id=datavalues;">
    <table  class="table table-striped">
      <thead>
        <tr>
          <th>Date</th>
          <th>Heure</th>
          <th>Pompe</th>
          <th>Panneau</th>
          <th>Solaire</th>
          <th>Sanit.</th>
        </tr>
      </thead>
      <tbody>
        <?php echo showDataDay(); ?>
      </tbody>
    </table>
    </div>
  </body>
</html>
