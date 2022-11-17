<?php
$login='PEPITO';
$password='CONTRASENA';

$CONEXION = conectar_mysqli_bd();

$sql = " SELECT * ";
$sql.= " FROM usr_usuarios ";
$sql.= " WHERE ";
$sql.= " usr_usuarios.activo = 1 AND ";
$sql.= " (";
$sql.= " usr_usuarios.login=? OR ";
$sql.= " usr_usuarios.login=? ";
$sql.= " ) AND ";
$sql.= " usr_usuarios.password=? ";

$Datos_	=	array(
	"sss",
	addslashes($login),
	utf8_decode($login),
	addslashes($password),
);

$arrayUsuario = consulta_mysqli_BD_($sql, $Datos_, $CONEXION);
echo "<pre>".print_R($arrayUsuario, true)."</pre>";
die();

//-----------------------------------------------------

	function conectar_mysqli_bd(){
		$conexion = mysqli_connect(SQL_HOST, SQL_USER, SQL_PWD, SQL_DB);

		if (!$conexion) {
			die('Error de Conexión (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
		}
		return $conexion;
	}
//-----------------------------------------------------
	function generaCadenaBind($Datos_){
		$Cadena_Bind='';
		foreach($Datos_ as $indice=>$dato){
			if ($indice==0){
				$Cadena_Bind.='"'.$dato.'", ';
			}else{
				$Cadena_Bind.='$Variable_'.$indice.', ';
			}
		}
		//Elimino ultima coma
		$Cadena_Bind=rtrim($Cadena_Bind, ", ");
		return $Cadena_Bind;
	}
//-----------------------------------------------------
	function consulta_mysqli_BD_($sql, $Datos_, $Conexion){
		$Cadena_Bind=generaCadenaBind($Datos_);
	
		$stmt = $Conexion->prepare($sql); 
		//Asiganacion Valores a Variables
		foreach($Datos_ as $indice=>$dato){
			if ($indice>0){
				${"Variable_" . $indice} =	$dato;
				echo "Variable_".$indice."=".${"Variable_" . $indice}."<br>";
			}
		}

		$stmt->bind_param($Cadena_Bind);  //->Así NO funciona
		//$stmt->bind_param("sss", $Variable_1, $Variable_2, $Variable_3);  //->Así si funciona

		$stmt->execute();
		$result = $stmt->get_result();
		$user = $result->fetch_assoc();
		echo "Resultado 2: <pre>".print_R($user, true)."<br>"; 		
		die('mato el proceso');
	}
//-----------------------------------------------------
?>
