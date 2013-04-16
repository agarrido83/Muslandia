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
						 "23 - Proxima jornada a disputar.\n".
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

		// Hallo la jornada actual
		$query = "SELECT * FROM LIGA_JornadaActual";
		if($result = $mysqli->query($query)) {
			$j_actual = $result->fetch_assoc();
		}
		// Libero $result
		$result->free();
		
		// Busco la jornada anterior
		if($j_actual["id_jornada"] > 1) {
			$query = "SELECT * FROM LIGA_Calendario WHERE id_jornada = ".($j_actual["id_jornada"]-1)." ORDER BY id_partida";
			if($result = $mysqli->query($query)) {
				$texto = "ULTIMA JORNADA DISPUTADA (".($j_actual["id_jornada"]-1)."):\n".
						 "PAREJAS   V1   V2\n\n";
				$contador = 0;
				while($row = $result->fetch_assoc()) {
					$texto .= $this->nombresPareja($row["local"])."    ".$row["vaca1_local"]."     ".$row["vaca2_local"]."\n".
							  $this->nombresPareja($row["visitante"])."    ".$row["vaca1_visitante"]."     ".$row["vaca2_visitante"]."\n".
							  "-----------------\n".
							  "GANADOR: ".(($row["pareja_ganadora"]=='L')?($this->nombresPareja($row["local"])):($this->nombresPareja($row["visitante"])))."\n";
					$contador++;
					if($contador == 1){
						$texto .= "\n\n";
					}
				}
				$this->replyOrFalse($texto);
			}
			// Libero $result
			$result->free();
		} else {
			$this->replyOrFalse("No hay ninguna jornada disputada.");
		}
		// Cierro la conexión con la Base de Datos
		$mysqli->close();
	}
	private function nodo22()
	{
		// Hago la conexión a la Base de Datos
		$mysqli = new mysqli('mysql.hostinger.es','u414170863_agg83','agarrido83','u414170863_mus');
		if($mysqli->connect_error) {
			die('Error de Conexión ('.$mysqli->connect_errno.') '.$mysqli->connect_error);
		}

		// Hallo la jornada actual
		$query = "SELECT * FROM LIGA_JornadaActual";
		if($result = $mysqli->query($query)) {
			$j_actual = $result->fetch_assoc();
		}
		// Libero $result
		$result->free();
		
		// Busco la jornada actual
		$query = "SELECT * FROM LIGA_Calendario WHERE id_jornada = ".$j_actual["id_jornada"]." ORDER BY id_partida";
		if($result = $mysqli->query($query)) {
			$texto = "JORNADA ACTUAL (".$j_actual["id_jornada"]."):\n".
					 "PAREJAS   V1   V2\n\n";
			$contador = 0;
			while($row = $result->fetch_assoc()) {
				if($row["pareja_ganadora"]<>'-') {
					$texto .= $this->nombresPareja($row["local"])."    ".$row["vaca1_local"]."     ".$row["vaca2_local"]."\n".
							  $this->nombresPareja($row["visitante"])."    ".$row["vaca1_visitante"]."     ".$row["vaca2_visitante"]."\n".
							  "-----------------\n".
							  "GANADOR: ".(($row["pareja_ganadora"]=='L')?($this->nombresPareja($row["local"])):($this->nombresPareja($row["visitante"])))."\n";
					$contador++;
					if($contador == 1) {
						$texto .= "\n\n";
					}
				} else {
					$texto .= $this->nombresPareja($row["local"])."\n".
							  $this->nombresPareja($row["visitante"])."\n".
							  "-----------------\n".
							  "PARTIDA AUN SIN JUGAR.\n";
					$contador++;
					if($contador == 1) {
						$texto .= "\n\n";
					}
				}
			}
			if($contador == 0) {
				$texto = "Actualmente no hay ninguna jornada en curso.\n";
			}
			$this->replyOrFalse($texto);
		}
		// Libero $result
		$result->free();
		
		// Cierro la conexión con la Base de Datos
		$mysqli->close();
	}
	private function nodo23()
	{
		// Hago la conexión a la Base de Datos
		$mysqli = new mysqli('mysql.hostinger.es','u414170863_agg83','agarrido83','u414170863_mus');
		if($mysqli->connect_error) {
			die('Error de Conexión ('.$mysqli->connect_errno.') '.$mysqli->connect_error);
		}

		// Hallo la jornada actual
		$query = "SELECT * FROM LIGA_JornadaActual";
		if($result = $mysqli->query($query)) {
			$j_actual = $result->fetch_assoc();
		}
		// Libero $result
		$result->free();
		
		// Busco la jornada siguiente
		if($j_actual["id_jornada"] < 6) {
			$query = "SELECT * FROM LIGA_Calendario WHERE id_jornada = ".($j_actual["id_jornada"]+1)." ORDER BY id_partida";
			if($result = $mysqli->query($query)) {
				$texto = "PROXIMA JORNADA (".($j_actual["id_jornada"]+1)."):\n".
						 "PAREJAS\n\n";
				$contador = 0;
				while($row = $result->fetch_assoc()) {
					$texto .= $this->nombresPareja($row["local"])."\n".
							  $this->nombresPareja($row["visitante"])."\n";
					$contador++;
					if($contador == 1){
						$texto .= "\n";
					}
				}
				$this->replyOrFalse($texto);
			}
			// Libero $result
			$result->free();
		} else {
			$this->replyOrFalse("No hay mas jornadas por disputar.");
		}
		// Cierro la conexión con la Base de Datos
		$mysqli->close();
	}
	private function nodo24()
	{
		// Hago la conexión a la Base de Datos
		$mysqli = new mysqli('mysql.hostinger.es','u414170863_agg83','agarrido83','u414170863_mus');
		if($mysqli->connect_error) {
			die('Error de Conexión ('.$mysqli->connect_errno.') '.$mysqli->connect_error);
		}

		// Extraigo los datos de la clasificación
		$query = "SELECT * FROM LIGA_Clasificacion ORDER BY posicion";
		if($result = $mysqli->query($query)) {
			$texto = "                CLASIFICACION            \n".
					 "POS   PAREJAS   PJ   JG   PxB   PTOS\n\n";
			$contador = 0;
			while($row = $result->fetch_assoc()) {
				$texto .= $row["posicion"]."        ".$this->nombresPareja($row["id_pareja"])."     ".$row["partidas_jugadas"]."     ".
						  $row["juegos_ganados"]."      ".$row["puntos_por_bonificacion"]."        ".$row["puntos"]."\n";
				$contador++;
				if($contador == 4){
					$texto .= "\n";
				}
			}
			$this->replyOrFalse($texto);
		}
		// Libero $result
		$result->free();
		
		// Cierro la conexión con la Base de Datos
		$mysqli->close();
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
	
	private function nombresPareja($id_pareja_)
	{	
		// Hago la conexión a la Base de Datos
		$mysqli = new mysqli('mysql.hostinger.es','u414170863_agg83','agarrido83','u414170863_mus');
		if($mysqli->connect_error) {
			die('Error de Conexión ('.$mysqli->connect_errno.') '.$mysqli->connect_error);
		}
		
		// Hallo los nombres de la pareja correspondiente a la id dada
		$nom = "";
		$query = "SELECT nombres FROM Parejas WHERE id_pareja = ".$id_pareja_;
		if($result = $mysqli->query($query)) {
			$nom = $result->fetch_assoc();
		}
		// Libero $result
		$result->free();
		
		// Cierro la conexión con la Base de Datos
		$mysqli->close();	
		
		return $nom["nombres"];
	}
}

// Create a new SBApp on dev.spotbros.com and copy-paste your SBCode and key
$muslandiaSBCode = "A6VMN66";
$muslandiaKey = "beeb902a3195c51ae702de30e789d4ac9db5dc70be76abb5bec7c4e3c21a1d06";
$muslandia=new Muslandia($muslandiaSBCode,$muslandiaKey);
$muslandia->serveRequest($_GET["params"]);
?>
