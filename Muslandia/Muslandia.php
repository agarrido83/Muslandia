<?php
require_once('../SBClientSDK/SBApp.php');

class Muslandia extends SBApp
{
	// M�todos protegidos
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
			$this->replyOrFalse("Hola ".$userName."! Bienvenido a MuslandiaApp!");		
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
	
	// M�todos privados
	
	/***************************************************************
	* compruebaEntrada
	*		@Def: Comprueba el comando que el usiario ha escrito.
	*		@Param:
	*			- $comando: Comando escrito por el usuario.
	***************************************************************/
	private function compruebaEntrada($comando)
	{
		switch (strtolower($comando)) {
			case "menu":
				$this->menuInicial();
				break;
			case "ayuda":
				$this->ayuda();
				break;
			case "1":
				$this->nodo1();
				break;
			case "2":
				$this->nodo2();
				break;	
			case "4":
				$this->creditos();
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
	/***************************************************************
	* menuInicial
	*		@Def: Muestra el men� inicial de la aplicaci�n.
	***************************************************************/
	private function menuInicial()
	{
		$texto = utf8_encode("En estos momentos se est� disputando el I Torneo de Mus.\n").
						 utf8_encode("�Qu� quieres consultar?\n\n").
						 utf8_encode("1 - Parejas participantes.\n").
						 utf8_encode("2 - LIGA.\n").
						 utf8_encode("3 - COPA.\n").
						 utf8_encode("4 - Cr�ditos\n\n").
						 utf8_encode("Escribe el n�mero correspondiente:");
	  $this->replyOrFalse($texto);
	}
	/***************************************************************
	* nodo1
	*		@Def: Muestra las parejas participantes en el torneo.
	***************************************************************/
	private function nodo1()
	{
		// Hago la conexi�n a la Base de Datos
		$mysqli = new mysqli('mysql.hostinger.es','u414170863_agg83','agarrido83','u414170863_mus');
		if($mysqli->connect_error) {
			die('Error de Conexi�n ('.$mysqli->connect_errno.') '.$mysqli->connect_error);
		}

		// Hago la consulta
		$query = "SELECT * FROM Parejas ORDER BY id_pareja";
		if($result = $mysqli->query($query)) {

			$texto = utf8_encode("Parejas participantes y categor�a en la que participan en LIGA:\n\n");
			$contador = 0;
			while($row = $result->fetch_assoc()) {
					$texto .= $row["id_pareja"]." ".$row["nombres"]." -> ".$row["categoria_en_liga"];
					$contador++;
					if($contador <> 6) {
						$texto .= "\n";
					}
			}
			
			// Mando el mensaje con el resultado
			$this->replyOrFalse($texto);
				
		}
		// Libero $result
		$result->free();
		// Cierro la conexi�n con la Base de Datos
		$mysqli->close();
	}
	/***************************************************************
	* nodo2
	*		@Def: Muestra las opciones de la LIGA.
	***************************************************************/
	private function nodo2()
	{
		$texto = utf8_encode("Opciones para la LIGA:\n\n").
						 utf8_encode("21 - �ltima jornada disputada.\n").
						 utf8_encode("22 - Jornada en curso.\n").
						 utf8_encode("23 - Pr�xima jornada a disputar.\n").
						 utf8_encode("24 - Clasificaci�n.\n\n").
 						 utf8_encode("Escribe el n�mero correspondiente:");
	  $this->replyOrFalse($texto);
	}
	/***************************************************************
	* nodo3
	*		@Def: Muestra las opciones de la COPA.
	***************************************************************/
	private function nodo3()
	{
		$texto = utf8_encode("Opciones para la COPA:\n\n").
						 utf8_encode("31 - Cuartos de Final.\n").
						 utf8_encode("32 - Semifinales.\n").
						 utf8_encode("33 - Final.\n").
						 utf8_encode("34 - Cuadro de Honor.\n\n").
 						 utf8_encode("Escribe el n�mero correspondiente:");
	  $this->replyOrFalse($texto);
	}
	/***************************************************************
	* nodo21
	*		@Def: Muestra los datos de la �ltima jornada disputada en
	*					LIGA.
	***************************************************************/
	private function nodo21()
	{
		// Hago la conexi�n a la Base de Datos
		$mysqli = new mysqli('mysql.hostinger.es','u414170863_agg83','agarrido83','u414170863_mus');
		if($mysqli->connect_error) {
			die('Error de Conexi�n ('.$mysqli->connect_errno.') '.$mysqli->connect_error);
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
				$texto = utf8_encode("�LTIMA JORNADA DISPUTADA (".($j_actual["id_jornada"]-1)."):\n").
						     utf8_encode("PAREJAS   V1   V2\n\n");
				$contador = 0;
				while($row = $result->fetch_assoc()) {
					$texto .= $this->nombresPareja($row["local"])."    ".$row["vaca1_local"]."     ".$row["vaca2_local"]."\n".
							  $this->nombresPareja($row["visitante"])."    ".$row["vaca1_visitante"]."     ".$row["vaca2_visitante"]."\n".
							  "-----------------\n".
							  "GANADOR: ".(($row["pareja_ganadora"]=='L')?($this->nombresPareja($row["local"])):($this->nombresPareja($row["visitante"])));
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
		// Cierro la conexi�n con la Base de Datos
		$mysqli->close();
	}
	/***************************************************************
	* nodo22
	*		@Def: Muestra los datos de la jornada que se est� disputando
	*					actualmente en la LIGA.
	***************************************************************/
	private function nodo22()
	{
		// Hago la conexi�n a la Base de Datos
		$mysqli = new mysqli('mysql.hostinger.es','u414170863_agg83','agarrido83','u414170863_mus');
		if($mysqli->connect_error) {
			die('Error de Conexi�n ('.$mysqli->connect_errno.') '.$mysqli->connect_error);
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
							  "GANADOR: ".(($row["pareja_ganadora"]=='L')?($this->nombresPareja($row["local"])):($this->nombresPareja($row["visitante"])));
					$contador++;
					if($contador == 1) {
						$texto .= "\n\n";
					}
				} else {
					$texto .= $this->nombresPareja($row["local"])."\n".
							  $this->nombresPareja($row["visitante"])."\n".
							  "-----------------\n".
							  utf8_encode("PARTIDA A�N SIN DISPUTAR.");
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
		
		// Cierro la conexi�n con la Base de Datos
		$mysqli->close();
	}
	/***************************************************************
	* nodo23
	*		@Def: Muestra los datos de la pr�xima jornada a disputar en
	*					LIGA.
	***************************************************************/
	private function nodo23()
	{
		// Hago la conexi�n a la Base de Datos
		$mysqli = new mysqli('mysql.hostinger.es','u414170863_agg83','agarrido83','u414170863_mus');
		if($mysqli->connect_error) {
			die('Error de Conexi�n ('.$mysqli->connect_errno.') '.$mysqli->connect_error);
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
				$texto = utf8_encode("PR�XIMA JORNADA (".($j_actual["id_jornada"]+1)."):\n").
						 		 utf8_encode("PAREJAS\n\n");
				$contador = 0;
				while($row = $result->fetch_assoc()) {
					$texto .= $this->nombresPareja($row["local"])."\n".
							      $this->nombresPareja($row["visitante"]);
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
			$this->replyOrFalse("No quedan jornadas por disputar.");
		}
		// Cierro la conexi�n con la Base de Datos
		$mysqli->close();
	}
	/***************************************************************
	* nodo24
	*		@Def: Muestra la clasificaci�n de la LIGA
	***************************************************************/
	private function nodo24()
	{
		// Hago la conexi�n a la Base de Datos
		$mysqli = new mysqli('mysql.hostinger.es','u414170863_agg83','agarrido83','u414170863_mus');
		if($mysqli->connect_error) {
			die('Error de Conexi�n ('.$mysqli->connect_errno.') '.$mysqli->connect_error);
		}

		// Extraigo los datos de la clasificaci�n
		$query = "SELECT * FROM LIGA_Clasificacion ORDER BY posicion";
		if($result = $mysqli->query($query)) {
			$texto = utf8_encode("                CLASIFICACI�N            \n").
					 		 utf8_encode("POS   PAREJAS   PJ   JG   PxB   PTOS\n\n");
			$contador = 0;
			while($row = $result->fetch_assoc()) {
				$texto .= $row["posicion"]."        ".$this->nombresPareja($row["id_pareja"])."   _".$row["partidas_jugadas"]."_     ".
						      $row["juegos_ganados"]."    ".$row["puntos_por_bonificacion"]."       ".$row["puntos"];
				$contador++;
				if($contador <> 4){
					$texto .= "\n";
				} 
			}
			$this->replyOrFalse($texto);
		}
		// Libero $result
		$result->free();
		
		// Cierro la conexi�n con la Base de Datos
		$mysqli->close();
	}
	/***************************************************************
	* nodo31
	*		@Def: Muestra los datos de los cuartos de final de la COPA
	***************************************************************/
	private function nodo31()
	{
		// Hago la conexi�n a la Base de Datos
		$mysqli = new mysqli('mysql.hostinger.es','u414170863_agg83','agarrido83','u414170863_mus');
		if($mysqli->connect_error) {
			die('Error de Conexi�n ('.$mysqli->connect_errno.') '.$mysqli->connect_error);
		}

		// Hago la consulta...
		$query = "SELECT * FROM COPA_Calendario WHERE id_eliminatoria like 'CF%' ORDER BY id_eliminatoria";
		if($result = $mysqli->query($query)) {
			$texto = "CUARTOS DE FINAL\n".
							 "--------------------\n\n";
			$contador = 0;
			while($row = $result->fetch_assoc()) {
				$texto .= "CF".($contador+1)."\n";
				if($row["local"] <> 0 and $row["visitante"] <> 0 and $row["pareja_ganadora"] <> '-') {
					$texto .= $this->nombresPareja($row["local"])."    ".$row["vaca1_local"]."     ".$row["vaca2_local"]."\n".
							  	  $this->nombresPareja($row["visitante"])."    ".$row["vaca1_visitante"]."     ".$row["vaca2_visitante"]."\n".
							  		"-----------------\n".
							  		"GANADOR: ".(($row["pareja_ganadora"]=='L')?($this->nombresPareja($row["local"])):($this->nombresPareja($row["visitante"])));
					$contador++;
					if($contador <> 4) {
						$texto .= "\n\n";
					}
				} else if ($row["local"] == 0 or $row["visitante"] == 0 and $row["pareja_ganadora"] <> '-') {
					if ($row["local"] == 0) {
						$texto .= $this->nombresPareja($row["visitante"])."\n";
					} else {
						$texto .= $this->nombresPareja($row["local"])."\n";
					}
					$texto .=		"-----------------\n".
							  			"CLASIFICADO DIRECTAMENTE A SEMIFINALES.";
					$contador++;
					if($contador <> 4) {
						$texto .= "\n\n";
					}
				} else if ($row["local"] == 0 or $row["visitante"] == 0 and $row["pareja_ganadora"] <> '-') {
					if ($row["local"] == 0) {
						$texto .= $this->nombresPareja($row["visitante"])."\n";
					} else {
						$texto .= $this->nombresPareja($row["local"])."\n";
					}
					$texto .=		"-----------------\n".
							  			"CLASIFICADO DIRECTAMENTE A SEMIFINALES.";
					$contador++;
					if($contador <> 2) {
						$texto .= "\n\n";
					}
				} else {
					$texto .= $this->nombresPareja($row["local"])."\n".
							  		$this->nombresPareja($row["visitante"])."\n".
							  		"-----------------\n".
							  		utf8_encode("PARTIDA A�N SIN JUGAR.");
					$contador++;
					if($contador <> 4) {
						$texto .= "\n\n";
					}
				}
			}
		}
		$this->replyOrFalse($texto);
		
		// Libero $result
		$result->free();
		
		// Cierro la conexi�n con la Base de Datos
		$mysqli->close();
	}
  /***************************************************************
	* nodo32
	*		@Def: Muestra los datos de la semifinal de la COPA
	***************************************************************/
	private function nodo32()
	{
		// Hago la conexi�n a la Base de Datos
		$mysqli = new mysqli('mysql.hostinger.es','u414170863_agg83','agarrido83','u414170863_mus');
		if($mysqli->connect_error) {
			die('Error de Conexi�n ('.$mysqli->connect_errno.') '.$mysqli->connect_error);
		}

		// Hago la consulta...
		$query = "SELECT * FROM COPA_Calendario WHERE id_eliminatoria like 'SF%' ORDER BY id_eliminatoria";
		if($result = $mysqli->query($query)) {
			$texto = "SEMIFINALES\n".
 							 "--------------\n\n";
			$contador = 0;
			while($row = $result->fetch_assoc()) {
				$texto .= "SF".($contador+1)."\n";
				if($row["local"] <> 0 and $row["visitante"] <> 0 and $row["pareja_ganadora"] <> '-') {
					$texto .= $this->nombresPareja($row["local"])."    ".$row["vaca1_local"]."     ".$row["vaca2_local"]."\n".
							  	  $this->nombresPareja($row["visitante"])."    ".$row["vaca1_visitante"]."     ".$row["vaca2_visitante"]."\n".
							  		"-----------------\n".
							  		"GANADOR: ".(($row["pareja_ganadora"]=='L')?($this->nombresPareja($row["local"])):($this->nombresPareja($row["visitante"])));
					$contador++;
					if($contador <> 2) {
						$texto .= "\n\n";
					}
				} else if ($row["local"] == 0 or $row["visitante"] == 0 and $row["pareja_ganadora"] == '-') {
					if ($row["local"] == 0) {
						$texto .= $this->nombresPareja($row["visitante"])."\n";
					} else {
						$texto .= $this->nombresPareja($row["local"])."\n";
					}
					$texto .=		"-----------------\n".
							  			"ESPERANDO CONTRINCANTE...";
					$contador++;
					if($contador <> 2) {
						$texto .= "\n\n";
					}
				} else if ($row["local"] == 0 and $row["visitante"] == 0 and $row["pareja_ganadora"] == '-') {
					$texto .=	utf8_encode("NADIE CLASIFICADO A�N.");
				} else {
					$texto .= $this->nombresPareja($row["local"])."\n".
							  		$this->nombresPareja($row["visitante"])."\n".
							  		"-----------------\n".
							  		utf8_encode("PARTIDA A�N SIN JUGAR.");
					$contador++;
					if($contador <> 2) {
						$texto .= "\n\n";
					}
				}
			}
		}
		$this->replyOrFalse($texto);
		
		// Libero $result
		$result->free();
		
		// Cierro la conexi�n con la Base de Datos
		$mysqli->close();
	}
	/***************************************************************
	* nodo33
	*		@Def: Muestra los datos de la final de COPA
	***************************************************************/
	private function nodo33()
	{
		// Hago la conexi�n a la Base de Datos
		$mysqli = new mysqli('mysql.hostinger.es','u414170863_agg83','agarrido83','u414170863_mus');
		if($mysqli->connect_error) {
			die('Error de Conexi�n ('.$mysqli->connect_errno.') '.$mysqli->connect_error);
		}

		// Hago la consulta...
		$query = "SELECT * FROM COPA_Calendario WHERE id_eliminatoria = 'FIN'";
		if($result = $mysqli->query($query)) {
			$texto = "FINAL\n".
							 "-------\n\n";
			while($row = $result->fetch_assoc()) {
				if($row["local"] <> 0 and $row["visitante"] <> 0 and $row["pareja_ganadora"] <> '-') {
					$texto .= $this->nombresPareja($row["local"])."    ".$row["vaca1_local"]."     ".$row["vaca2_local"]."\n".
							  	  $this->nombresPareja($row["visitante"])."    ".$row["vaca1_visitante"]."     ".$row["vaca2_visitante"]."\n".
							  		"-----------------\n".
							  		"GANADOR: ".(($row["pareja_ganadora"]=='L')?($this->nombresPareja($row["local"])):($this->nombresPareja($row["visitante"])));
				} else if ($row["local"] == 0 and $row["visitante"] == 0 and $row["pareja_ganadora"] == '-') {
					$texto .=	utf8_encode("NADIE CLASIFICADO A�N.");
				} else if ($row["local"] == 0 or $row["visitante"] == 0 and $row["pareja_ganadora"] == '-') {
					if ($row["local"] == 0) {
						$texto .= $this->nombresPareja($row["visitante"])."\n";
					} else {
						$texto .= $this->nombresPareja($row["local"])."\n";
					}
					$texto .=		"-----------------\n".
							  			"ESPERANDO CONTRINCANTE...";
				} else {
					$texto .= $this->nombresPareja($row["local"])."\n".
							  		$this->nombresPareja($row["visitante"])."\n".
							  		"-----------------\n".
							  		utf8_encode("PARTIDA A�N SIN JUGAR.");
				}
			}
		}
		$this->replyOrFalse($texto);
		
		// Libero $result
		$result->free();
		
		// Cierro la conexi�n con la Base de Datos
		$mysqli->close();
	}
	/***************************************************************
	* nodo34
	*		@Def: Muestra el Cuadro de Honor de la COPA
	***************************************************************/
	private function nodo34()
	{
		$texto = "CUADRO DE HONOR\n".
			 			 "--------------------\n\n".
						 utf8_encode("A�N NO HA TERMINADO LA COPA.");
		$this->replyOrFalse($texto);
	}
	/***************************************************************
	* ayuda
	*		@Def: Funci�n que muestra los comandos disponibles en la
	*					aplicaci�n.
	***************************************************************/
	private function ayuda()
	{
		$texto = utf8_encode("COMANDOS DISPONIBLES\n").
			 			 utf8_encode("-------------------------\n\n").
						 utf8_encode("'menu' -> Men� principal.\n").
						 utf8_encode("'1' -> Parejas del Torneo.\n").
						 utf8_encode("'2' -> Men� de LIGA\n").
						 utf8_encode("'21' -> �ltima jornada de LIGA.\n").
						 utf8_encode("'22' -> Jornada actual de LIGA.\n").						 
						 utf8_encode("'23' -> Pr�xima jornada de LIGA.\n").
						 utf8_encode( "'24' -> Clasificaci�n de la LIGA.\n").
						 utf8_encode("'3' -> Men� de COPA\n").
						 utf8_encode("'31' -> Cuartos de Final de COPA.\n").
						 utf8_encode("'32' -> Semifinal de COPA.\n").
						 utf8_encode("'33' -> Final de COPA.\n").
						 utf8_encode("'34' -> Cuadro de Honor de la COPA.\n").
						 utf8_encode("'4' -> Informaci�n de los cr�ditos.\n").
						 utf8_encode("'ayuda' -> Muestra esta ayuda.\n");
		$this->replyOrFalse($texto);
	}
	/***************************************************************
	* creditos
	*		@Def: Funci�n que muestra la informaci�n de los cr�ditos de 
	*					la aplicaci�n.
	***************************************************************/
	private function creditos()
	{
		$texto = utf8_encode("MuslandiaApp.\n").
						 utf8_encode("Versi�n 1.0.\n\n").
					   utf8_encode("SBApp desarrollada por...\n").
						 utf8_encode("Antonio Garrido Gonz�lez.\n\n").
						 utf8_encode("Abril de 2013.");
		$this->replyOrFalse($texto);
	}
	/***************************************************************
	* nombresPareja
	*		@Def: Muestra los nombres asociados a un id de pareja.
	*		@Param:
	*			- $id_pareja: Id de la pareja.
	*		@Return: Los nombres asociados a la pareja con la id dada.
	***************************************************************/
	private function nombresPareja($id_pareja_)
	{	
		// Hago la conexi�n a la Base de Datos
		$mysqli = new mysqli('mysql.hostinger.es','u414170863_agg83','agarrido83','u414170863_mus');
		if($mysqli->connect_error) {
			die('Error de Conexi�n ('.$mysqli->connect_errno.') '.$mysqli->connect_error);
		}
		
		// Hallo los nombres de la pareja correspondiente a la id dada
		$nom = "";
		$query = "SELECT nombres FROM Parejas WHERE id_pareja = ".$id_pareja_;
		if($result = $mysqli->query($query)) {
			$nom = $result->fetch_assoc();
		}
		// Libero $result
		$result->free();
		
		// Cierro la conexi�n con la Base de Datos
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
