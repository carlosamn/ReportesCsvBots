<?php
class Data
{
	public $values;
	public $query;
	
	function query($path,$sheet,$nameCampana)
	{
		
		$xlsx = simpleXLSX::parse($path);
		$workSheet = $xlsx->rows($sheet);

		foreach ($workSheet  as $fields ) {

			if($fields[0] == 'CAMPANA' || $fields[0] == 'RUT' ){
			continue;
			}
		else {
			if($sheet == 1){

				$time = new DateTime($fields[5]);
				$time = $time->format('H:i:s');
				$date = new DateTime($fields[4]);
				$date = $date->format('d-m-Y');
				$this->values .= "('$nameCampana','$fields[1]','$fields[2]','$fields[3]','$date', '$time', '$fields[6]', '$fields[7]', '$fields[8]'),";
			}else{
				$time = new DateTime($fields[4]);
				$time = $time->format('H:i:s');
				$date = new DateTime($fields[3]);
				$date = $date->format('d-m-Y');
				$this->values .= "('$fields[0]','$fields[1]','$fields[2]','$date', '$time', '$fields[5]' , '$fields[6]', '$nameCampana'),";
				}
		
			}
		}
		

		$this->values = substr($this->values,0,strlen($this->values)-1);
		if($sheet == 1){
			$this->query = ('insert into base_reporte 
			(CAMPANA,RUT,FONO,MEJOR_GESTION,FECHA,HORA,MEJOR_RANGO,CANTIDAD,INTERES) 
			values'. $this->values .'');
		} else {
			$this->query = ('insert into detalle_reporte 
			(RUT,FONO,GESTION,FECHA,HORA,RANGO,DURACION,CAMPANA) 
			values'. $this->values .'');
			}
		return $this->query;
	}

	function NameCampana($path) {
		$Name;

		if(fnmatch("*BOT 1 GRUPO 1*", $path)){
			$Name = "BOT 1 GRUPO 1";
		}else if (fnmatch("*BOT 1 GRUPO 2*", $path)){
			$Name = "BOT 1 GRUPO 2";
		}else if (fnmatch("*BOT 1 GRUPO 3*", $path)){
			$Name = "BOT 1 GRUPO 3";
		}else if (fnmatch("*BOT 2 GRUPO 1*", $path)){
			$Name = "BOT 2 GRUPO 1";
		}else if (fnmatch("*BOT 2 GRUPO 2*", $path)){
			$Name = "BOT 2 GRUPO 2";
		}else if (fnmatch("*BOT 2 GRUPO 3*", $path)){
			$Name = "BOT 2 GRUPO 3";
		}else{
			$Name = "Not defined yet";
		}
		return $Name;
	}
}
?>
