<?php
require_once('../SBClientSDK/SBApp.php');

class Muslandia extends SBApp
{
	// Métodos protegidos
	protected function onError($errorType_)
	{
		error_log($errorType_);
	}
	protected function onNewVote(SBUser $user_,$newVote_,$oldRating_,$newRating_)
	{
		$this->replyOrFalse("Gracias por votarme con ".$newVote_." estrella(s) :)");
	}	
	protected function onNewContactSubscription(SBUser $user_)
	{
		if(($userName = $user_->getSBUserNameOrFalse()))
		{
			$this->replyOrFalse("Hola ".$userName."! Bienvenido a Muslandia!");		
		}	
		$this->replyOrFalse("Escribe 'menu' para ver las opciones disponibles...");
	}	
	protected function onNewContactUnSubscription(SBUser $user_)
	{
		if(($userName = $user_->getSBUserNameOrFalse()))
		{
			error_log($userName." se ha ido...");
		}
	}	
	protected function onNewMessage(SBMessage $msg_)
	{
		if(($messageText = $msg_->getSBMessageTextOrFalse()))
		{
			$this->compruebaEntrada($messageText);			
		}
	}
	
	// Métodos privados
	private function compruebaEntrada($comando)
	{
		switch (strtolower($comando)) {
			case "menu":
				$this->menuInicial();
				break;
			case "1":
				$this->nodo1();
				break;
			case "2":
				$this->nodo2();
				break;	
			case "3":
				$this->nodo3();
				break;
			case "21":
				$this->nodo21();
				break;
			case "22":
				$this->nodo22();
				break;
			case "23":
				$this->nodo23();
				break;
			case "24":
				$this->nodo24();
				break;
			case "31":
				$this->nodo31();
				break;
			case "32":
				$this->nodo32();
				break;
			case "33":
				$this->nodo33();
				break;
			case "34":
				$this->nodo34();
				break;								
			default:
				$this->replyOrFalse("Comando incorrecto. Escriba 'ayuda' para ver los comandos disponibles...");	
		}
	}
	
	private function menuInicial()
	{
		$texto = "En estos momentos se esta disputando el I Torneo de Mus.\n".
						 "Que quieres consultar?\n\n".
						 "1 - Parejas participantes.\n".
						 "2 - LIGA.\n".
						 "3 - COPA.\n\n".
						 "Escribe el numero correspondiente:";
	  $this->replyOrFalse($texto);
	}
	
	private function nodo1()
	{
		// Hago la conexión a la Base de Datos
		$mysqli = new mysqli('mysql.hostinger.es','u414170863_agg83','agarrido83','u414170863_mus');
		if($mysqli->connect_error) {
			die('Error de Conexión ('.$mysqli->connect_errno.') '.$mysqli->connect_error);
		}

		// Hago la consulta
		$query = "SELECT * FROM Parejas ORDER BY id_pareja";
		if($result = $mysqli->query($query)) {

			$texto = "Parejas participantes y categoria en la que participan en LIGA:\n\n";
			while($row = $result->fetch_assoc()) {
					$texto .= $row["id_pareja"]." ".$row["nombres"]." -> ".$row["categoria_en_liga"]."\n";
			}
			
			// Mando el mensaje con el resultado
			$this->replyOrFalse($texto);
				
		}
		// Libero $result
		$result->free();
		// Cierro la conexión con la Base de Datos
		$mysqli->close();
	}
	private function nodo2()
	{
		$texto = "Opciones para la LIGA:\n\n".
						 "21 - Ultima jornada disputada.\n".
						 "22 - Jornada en curso.\n".
						 "23 - Proxima jornada.\n".
						 "24 - Clasificacion.\n\n".
 						 "Escribe el numero correspondiente:";
	  $this->replyOrFalse($texto);
	}
	private function nodo3()
	{
		$texto = "Opciones para la COPA:\n\n".
						 "31 - Cuartos de Final.\n".
						 "32 - Semifinales.\n".
						 "33 - Final.\n".
						 "34 - Cuadro de Honor.\n\n".
 						 "Escribe el numero correspondiente:";
	  $this->replyOrFalse($texto);
	}
	private function nodo21()
	{
		// Hago la conexión a la Base de Datos
		$mysqli = new mysqli('mysql.hostinger.es','u414170863_agg83','agarrido83','u414170863_mus');
		if($mysqli->connect_error) {
			die('Error de Conexión ('.$mysqli->connect_errno.') '.$mysqli->connect_error);
		}

		// Hago la consulta
		$query = "SELECT * FROM LIGA_JornadaActual";
		if($result = $mysqli->query($query)) {
			$j_actual = $result->feth_assoc();
			$this->replyOrFalse($j_actual["id_jornada"]);
		}
			/*
			$texto = "Parejas participantes y categoria en la que participan en LIGA:\n\n";
			while($row = $result->fetch_assoc()) {
					$texto .= $row["id_pareja"]." ".$row["nombres"]." -> ".$row["categoria_en_liga"]."\n";
			}*/
	}
	private function nodo22()
	{
		;
	}
	private function nodo23()
	{
		;
	}
	private function nodo24()
	{
		;
	}
	private function nodo31()
	{
		;
	}
	private function nodo32()
	{
		;
	}
	private function nodo33()
	{
		;
	}
	private function nodo34()
	{
		;
	}

}

// Create a new SBApp on dev.spotbros.com and copy-paste your SBCode and key
$muslandiaSBCode = "A6VMN66";
$muslandiaKey = "beeb902a3195c51ae702de30e789d4ac9db5dc70be76abb5bec7c4e3c21a1d06";
$muslandia=new Muslandia($muslandiaSBCode,$muslandiaKey);
$muslandia->serveRequest($_GET["params"]);
?>
