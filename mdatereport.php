<?php
/*
	mdatereport.php
	-------------
	2019.05.05 YM : corrigé test isset(...)
	2018.01.27 Yves Masur (ymasur@microclub.ch)
	
	Ensemble de fonctions pour afficher des données de températures
	stockées dans des fichiers au nommage selon YYMMdata.report.txt, avec
	YY = année (sans le siècle)
	MM = mois de l'enregistrement
	Les données sont au format ASCII tabulé, une ligne par enregistrement. 
	Les champs sont:
	- le jour du mois
	- le fonctionnement solaire en [%]
	- la constante de temps calculée
	Puis, entre parenthèses:
	- température moyenne de l'accumulation solaire+sanitaire à 0H00
	- température moyenne à ~6h
	- température ambiante
	- le temps de la V6, denière valeur basse, en [h]
	Exemple de ligne:
	13	16.9	75	(30.10; 29.25; 18.80; 5.83)
*/

// positionne le timezone au chargement du fichier
date_default_timezone_set('Europe/London');


/*
	function get_month_report($y, $m)
	----------------------------
	Lit un fichier de nom au format: YYMMdata.calc.txt.
	L'année est donnée sur 2 digits (2016 -> 16). Le siècle est ajouté
	aux données affichées: 16/01/22 -> 2016/01/22
	Exemple: get_month_report("16", "01");
	Affiche les données filtrées sur le jour. Le séparateur termine chaque ligne.
	retour: 
	- Si le fichier est trouvé, texte des données du jour demandé
	- Sinon, message d'erreur
	
*/ 

function get_month_report($y, $m) 
{
	$s = "";	// string de travail pour composer le résultat
	$line = "vide";
	$dfile = "./" . $y . $m . "data.calc.txt";
	$i = 0;	
	if (file_exists($dfile))	// Q: fichier existant?
	{							// R: oui, traiter
		$file = fopen($dfile,"r");

		while (!feof($file)) //on parcourt toutes les lignes
		{
			$line = fgets($file,100);	// lire une ligne (max 100 char)
			if ($line === false) 		// terminé? sortir
				break;	
			If ($i > 0)			// pas la 1ère ligne (entête)
				$s = $s . $line;
			$i = $i + 1;
		} 
		fclose($file);   
	}
	else						// R: non, indiquer erreur
	{
		$s = $s . "Pas de fichier pour " . $y . "/" . $m;
	}
	//echo "get_month_report()" . $dfile . " - ". $i;
	return $s;
}


/* 	showMonthReport()
	-----------------
	Traite les paramètres du formulaire année/mois :
	si aucun paramètre n'est donné, montre les calculs
	jusqu'au jour actuel
	sinon lit la date, la formatte pour get_month_report() et injecte
	les valeurs dans la table.
*/
function show_month_report()
{
	if (! isset($_GET['yyyy']) )
	{
		$y = (int)date("y");
		$m = (int)date("m");
	}
	else
	{
		$y = (int)$_GET['yyyy'];
		$m = (int)$_GET['mm'];
	}
	
	if ($y > 2000)  
	{
		$yy = $y - 2000;
	} 
	else 
	{
		$yy = $y;
	}
	$mm = sprintf("%02d", $m);
	
	//echo "data pour: " . $yy . $mm . " \n";
	
	echo dataInTable(get_month_report($yy, $mm));
}
?>
