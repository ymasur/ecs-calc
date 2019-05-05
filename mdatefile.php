<?php
/*
	mdatefile.php
	-------------
	2019.05.04 YM: test les tableau avec empty()
	2016.03.06 Yves Masur (ymasur@microclub.ch)
	
	Ensemble de fonctions pour afficher des données de températures
	stockées dans des fichiers au nommage selon YYMMdata.txt, avec
	YY = année (sans le siècle)
	MM = mois de l'enregistrement
	Les données sont au format ASCII tabulé, une ligne par enregistrement. 
	Les champs sont:
	- la date
	- le temps
	- le % d'utilisation de la pompe
	- température retour de panneau solaire
	- température de l'accumulation solaire
	- température de l'eau chaude sanitaire
	Exemple de ligne:
	16/02/10	12:20:00	100	12.9	15.9	47.3
*/

// positionne le timezone au chargement du fichier
date_default_timezone_set('Europe/London');


/*
	function get_day($y, $m, $d)
	----------------------------
	Lit un fichier de nom au format: YYMMdata.txt.
	L'année est donnée sur 2 digits (2016 -> 16). Le siècle est ajouté
	aux données affichées: 16/01/22 -> 2016/01/22
	Exemple: get_day("16", "01", "22");
	Affiche les données filtrées sur le jour. Le séparateur termine chaque ligne.
	retour: 
	- Si le fichier est trouvé, texte des données du jour demandé
	- Sinon, message d'erreur
	
*/ 
$plain_hours = true;	//trie les heures pleines
function get_day($y, $m, $d) 
{
	$s = "";	// string de travail pour composer le résultat
	$century = "20";
	$line = "";
	$dfile = "./" . $y . $m . "data.txt";
	$dmatch = "/" . $m . "/" . $d;  //ex. "/01/22";
	global $plain_hours;
	$filter_plain_hour = ":00:00";	//ex. "14:00:00"
	
	if (file_exists($dfile))	// Q: fichier existant?
	{							// R: oui, traiter
		$file = fopen($dfile,"r");
//		$s = $s . "<h3>filtre: " . $y . $dmatch . "<br/></h3>";
		while (!feof($file)) //on parcourt toutes les lignes
		{
			$line = fgets($file,100);	// lire une ligne (max 100 char)
			if ($line === false) 		// terminé? sortir
				break;	
			if (($plain_hours==true) && (strpos($line, $filter_plain_hour)==false)) 
				continue;		// ne livrer que les heures pleines
			if (strpos($line, $dmatch) != false)	//Q: masque OK?
			{										//R: oui, conserver
				$s = $s . $century . $line;	//ajoute siècle et séparateur
			}
		} 
		fclose($file);   
	}
	else						// R: non, indiquer erreur
	{
		$s = $s . "Pas de fichier pour " . $y . "/" . $m . "/" . $d;
	}
	return $s;
}

/*	Effectue une recherche de donnée pour aujourd'hui
*/
function getToday()
{
	//date_default_timezone_set('Europe/London');
	// il faut avoir la bonne timezone (assumé par fichier php principal)
	$DateY = date("y");
	$Datem = date("m");
	$Dated = date("d");
	$text = get_day($DateY, $Datem, $Dated);
	return $text;
}

/*	dataInTable($text)
	------------------
	retourne une série de données ASCII au format balisé
	pour un tableau:
	- une ligne de texte génère un ligne de tableau
	- un TAB génère une colonne
*/
function dataInTable($text)
{
	$text = "<tr><td>" . $text;
	$tab_td = str_replace("\t", "</td><td>", $text);
	$text = str_replace("\n", "</td></tr> <tr><td>", $tab_td);
	
	return $text . "</td></tr>";
}

// injecte les données du jour au format tableau
function showToday()
{
	echo dataInTable(getToday());
}

/*
	dateSelect()
	------------
	Crée 3 listes déroulantes permettant de sélectionner
	une date: année, mois, jour. 
	La date du jour est sélectionnée par défaut.
*/
function dateSelect($noDay=false)
{
  // Variable qui ajoutera l'attribut selected de la liste déroulante
  $selected = '';
    // Créer la sélection de l'année (variable 'yyyy')
  $s = '<select  name="yyyy">\n';
  // date passée en paramètre?
  if (empty($_GET['yyyy'])) 
	  $d = (int)date('Y');
  else 
	  $d = (int)$_GET['yyyy'];
  
  for($i=2014; $i<=date('Y'); $i++)
  {
    // L'année est-elle à sélectionner ?
    if($i == $d)
      $selected = ' selected';
   // création de la ligne d'option
    $s = $s . '<option value="'. $i .'"'. $selected .'>'. $i .'</option>\n';
    // Remise à zéro de $selected
    $selected='';
  }
  $s = $s . "</select>\n";

  //Mois (variable 'mm')
  $s = $s . '<select  name="mm">\n';
  if (empty($_GET['mm'])) 
	  $d = (int)date('m');
  else 
	  $d = (int)$_GET['mm'];  

  for($i=1; $i<=12; $i++)
  {
    // Le mois est il le mois à sélectionner ?
    if($i == $d)
      $selected = ' selected';
    // création de la ligne d'option
    $s = $s . '<option value="'. $i .'"'. $selected .'>'. $i .'</option>\n';
    // Remise à zéro de $selected
    $selected='';
  }
  $s = $s . "</select>\n";
  
 if($noDay==false)
  {  
	//Jour (variable 'dd')
  $s = $s . '<select  name="dd">';
  if (empty($_GET['dd'])) 
	  $d = (int)date('d');
  else 
	  $d = (int)$_GET['dd'];  

 
	  for($i=1; $i<=31; $i++)
	  {
		// Le jour est il le jour courant ?
		if($i == $d)
		  $selected = ' selected';
		// création de la ligne d'option
		$s = $s . '<option value="'. $i .'"'. $selected .'>'. $i .'</option>\n';
		// Remise à zéro de $selected
		$selected='';
	  }
	  $s = $s . "</select>\n";
  }
  
  
  return $s;
}


/*	showDataDay()
	-------------
	Traite les paramètres du formulaire année/mois/jour :
	si aucun paramètre n'est donné, montre le jour actuel
	sinon lit la date, la formatte pour get_day() et injecte
	les valeurs dans la table.
*/
function showDataDay()
{
	global $plain_hours;
	if (isset($_GET['filter']) && $_GET['filter'] == "1" ) $plain_hours = false;
	if (empty($_GET['yyyy'])) {
		 showToday();
		return;
	}
	
	$y = (int)$_GET['yyyy'];
	$m = (int)$_GET['mm'];
	$d = (int)$_GET['dd'];
	if ($y > 2000)  {
		$yy = $y - 2000;
	} else {
		$yy = $y;
	}
	$mm = sprintf("%02d", $m);
	$dd = sprintf("%02d", $d);
	
	//echo "data pour: " . $yy . $mm . $dd;
	$data = get_day($yy, $mm, $dd);
	echo dataInTable($data);
	return;
}

/*	getDataDay($element)
	--------------------
	Traite les paramètres du formulaire année/mois/jour :
	si aucun paramètre n'est donné, rend le jour actuel
	sinon lit la date, la formatte pour get_day()
	Les données sont rendues selon l'élément de la colonne:
		- date (normalement pas utilisé)
		- time
		- pompe - le % d'utilisation de la pompe
		- t0 - température retour de panneau solaire
		- t1 - température de l'accumulation solaire
		- t2 - température de l'eau chaude sanitaire
		Exemple de ligne:
		16/02/10	12:20:00	100	12.9	15.9	47.3	
*/
function getDataDay($element)
{
	$text = "";
	$data = "";
	global $plain_hours;

	if (isset($_GET['filter']) && $_GET['filter'] == "1" ) $plain_hours = false;
	if (empty($_GET['yyyy'])) 
	{
		$text = getToday();
	}
	else
	{
		$y = (int)$_GET['yyyy'];
		$m = (int)$_GET['mm'];
		$d = (int)$_GET['dd'];
		if ($y > 2000)  {
			$yy = $y - 2000;
		} else {
			$yy = $y;
		}
		$mm = sprintf("%02d", $m);
		$dd = sprintf("%02d", $d);
		
		//echo "text pour: " . $yy . $mm . $dd;
		$text = get_day($yy, $mm, $dd);
	}
	
	// lines in array
	$line_array = explode("\r", $text);
	//print_r($line_array);

	// les datas sont brut. On filtre pour l'élément choisi.
	foreach($line_array as $line)
	{	
		$parts = explode("\t", $line);
		//print_r($parts); echo "</br>";
		if (!isset($parts[4])) continue;

		switch($element)
		{
			case "time":
				$data = $data . "'" . $parts[1] . "'" . ", ";
			break;

			case "pompe":
				$data = $data . "'" . $parts[2] . "'" . ", ";
				break;
			break;

			case "t0":
				$data = $data . "'" . $parts[3] . "'" . ", ";
				break;
			break;

			case "t1":
				$data = $data . "'" . $parts[4] . "'" . ", ";
			break;

			case "t2":
				$data = $data . "'" . $parts[5] . "'" . ", ";			
			break;

			default:
				echo "error in element choice!</br>";

		}
	}


	return $data;
}

?>
