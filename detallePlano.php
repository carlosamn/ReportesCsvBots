<?php 

require_once "./Librerias/simpleXLSX.php";
include("Data.php");
include("connection.php");

$day = date('d');
$month = date('m');


$AllPaths = 'C:/Users/ProBook 6470b/Documents/mibot/Banco Ripley/Reporte_Base_preventivo_ripley/'.$month.'/'.$day.'/Preventiva/';

$listOfFiles = (glob($AllPaths."bot*"));

foreach ($listOfFiles as $key) {
	$mysql = new mysqli($host,$username, $password, $database);

	if (mysqli_connect_errno()) {
		printf("Error de conexión: %s\n", mysqli_connect_error());
		exit();
	}

	$mysql->query("TRUNCATE table base_reporte");
	$mysql->query("TRUNCATE table detalle_reporte");
	
	$list = (glob($key."/BOT*"));

	foreach ($list as $path ) {

		$mysql = new mysqli($host,$username, $password, $database);

		if (mysqli_connect_errno()) {
			printf("Error de conexión: %s\n", mysqli_connect_error());
			exit();
		}

		$instanceBase = new Data();
		$nameCampana = $instanceBase->NameCampana($path);
		$exportBase = $instanceBase->query($path, 1, $nameCampana);

		if ($mysql->query($exportBase) === TRUE) {
 			echo "New record created successfully on base";

 			$instanceDetalle = new Data();
 			$exportDetalle = $instanceDetalle->query($path, 2, $nameCampana);

 			if ($mysql->query($exportDetalle) === TRUE) {
 				echo "New record created successfully on detalle";
 			}
 			else {
  			echo "Error:"."<br>" . $mysql->error;
			}
		} else {
  			echo "Error:"."<br>" . $mysql->error;
		}

	}	

	$textoPlano="CAMPANA, RUT, FONO, GESTION, FECHA, HORA, RANGO, INTERES, DURACION\r\n";

	$mysql = new mysqli($host,$username, $password, $database);
	if (mysqli_connect_errno()) {
		printf("Error de conexión: %s\n", mysqli_connect_error());
		exit();
	}

	$view = 'select * from detalle';
	$reportView = $mysql->query($view);


	foreach ($reportView as $row) {
		$textoPlano .= $row['CAMPANA'].",".$row['rut'].",".$row['FONO'].",".$row['GESTION'].",".$row['FECHA'].",".$row['HORA'].",".$row['RANGO'].",".$row['interes'].",".$row['duracion']."\r\n";
	}

	$myFile = fopen($key."/detalle_.csv", "w");
	fwrite($myFile,$textoPlano);
	fclose($myFile);

	$mysql->close();

}

?>