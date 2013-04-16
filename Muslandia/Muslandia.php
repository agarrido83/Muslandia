<?php
require_once('../SBClientSDK/SBApp.php');
class Muslandia extends SBApp
{
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
			menu();
		}
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
			$this->replyOrFalse("Has dicho: ".$messageText);
		}
	}
}

function menu()
{
	$texto= "En estos momentos se está disputando el I Torneo de Mus.\n";
	$texto.= "¿Qué quieres consultar?\n\n";
	$texto.= "1 - Parejas participantes.\n";
	$texto.= "2 - LIGA\n";
	$texto.= "3 - COPA";
	$this->ReplyOrFalse($texto);
}
	
// Create a new SBApp on dev.spotbros.com and copy-paste your SBCode and key
$muslandiaSBCode = "[SBCode]";
$muslandiaKey = "[key]";
$muslandia=new Muslandia($muslandiaSBCode,$muslandiaKey);
$muslandia->serveRequest($_GET["params"]);
?>
