<?php
		define("DB_HOST", "77.104.152.148");
		define("DB_USER", "jhonlyb2_wpcp");
		define("DB_PASS", "-(0S8ABJ1p");
		define("DB_NAME", "jhonlyb2_wpcp");
class Conexion 
{
	    
	 static $instancia=null;
	 public $dblink;
	 function __construct()
	{
		$this->dblink= mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
		if (!$this->dblink) {
			echo "Error a conectar con conexion a base de datos";
		}
	}
	 public static function getInstancia()
	 {
	 	if(self::$instancia==null)
	 	{
	 		self::$instancia= new Conexion();
	 	}
	 	return self::$instancia->dblink;
	 }
	 public static function Ejecutar($sql)
	 {
	 	$dblink=self::getInstancia();
	 	if (mysqli_query($dblink, $sql)) {
    return true;
} else {
   return false;
}
	 }
	public static function consulta($query)
	{
		$dblink=self::getInstancia();
		$resultado= mysqli_query($dblink,$query);
		$rs= array();
		$contador=0;
		while ($fila= mysqli_fetch_object($resultado)) {
			$rs[$contador]=$fila;
			$contador++;
		}
		return $rs;
	}
	function __destruct()
	{
		mysqli_close($this->dblink);
	}
	
}