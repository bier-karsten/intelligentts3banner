<?php
/* 
<-- Don't remove this Copyright section! Diesen Copyrightbereich nicht entfernen -->
Title: Intelligent Teamspeak3-Banner
Author/Autor: Adrianhes
Code by/Code von: Adrianhes
Website/Webseite: https://adrianhes.de
Contact/Kontakt: kontakt@adrianhes.de
License: Public usage allowed if this Copyrightsection isn't removed.
Lizenz: Der öffentliche Gebrauch ist erlaubt, wenn der Copyrightbereich nicht verändert wurde.
<-- Copyright End -->
*/

		//Wenn der Server nicht erreichbar ist, wird der Banner nicht funktionieren!
    //lade das Framework
	require_once("teamspeak3/libraries/TeamSpeak3/TeamSpeak3.php");
		//Serverquery Variablen
		$serverquery_username = "serveradmin"; 				//Serverquery login username
    $serverquery_pass = ""; 							//Serverquery login password
    $serverip = ""; 									//Teamspeak 3 server IP, only IP not port
    $serverquery_port = "10011"; 									//Serverquery port
    $serverport = "9987"; 												//Teamspeak 3 server port
    $pdo = new PDO('mysql:host=SERVER;dbname=DB', 'U', 'PASSWORD'); 		//Datenbankverbindung für Nachrichten im Banner
		$sql = "SELECT * FROM ts ORDER BY id DESC LIMIT 1"; 																										//Letzter Eintrag aus Datenbank
    foreach ($pdo->query($sql) as $row) {
   	$infomsg = $row['msg']; 																																								//Erste Nachricht in Variable
};

$pdo2 = new PDO('mysql:host=SERVER;dbname=DB', 'U', 'PASSWORD'); 				//Zweite Datenbankverbindung für zweite Nachricht im Banner
    $sql2 = "SELECT * FROM ts2 ORDER BY id DESC LIMIT 1";
    foreach ($pdo2->query($sql2) as $row2) {
   $infomsg2 = $row2['msg']; 																																								//Zweite Nachricht in Variable
};


    
    try {
        //Serverquery connection mit Variablen
        $ts3_VirtualServer = TeamSpeak3::factory("serverquery://$serverquery_username:$serverquery_pass@$serverip:$serverquery_port/?server_port=$serverport");
			
			
        $nutzer = $ts3_VirtualServer->virtualserver_clientsonline - "1"; 				//Belegte Slots in Variable
        $snames = $ts3_VirtualServer->virtualserver_name; 											//Servername in Variable
        $chn = "Channel: ".$ts3_VirtualServer->virtualserver_channelsonline; 		//Channelzahl in Variable
      
          if ($nutzer < "2") {
  $word = "Es ist "; 							//Wenn weniger als zwei Nutzer online = "Es ist"
} else {
  $word = "Es sind "; 						//Wenn mehr als ein Nutzer online = "Es sind"
} if ($nutzer < "1") {
			
					$word = "Es sind "; 		//Wenn kein Nutzer online = "Es sind"
					}
        
        $blabla 					= $word. $nutzer." von ".$ts3_VirtualServer->virtualserver_maxclients." Nutzern online."; //"Es sind/Es ist" + "Onlinenutzerzahl" von "Slots"
				$uptime 					= $ts3_VirtualServer->virtualserver_uptime; 	//Server uptime
				$init 						= $uptime; 																		//Umrechnung der Uptime (angegeben in Sekunden) in Menschenzeit
				$days 						= floor($init / 86400); 											//Ein Tag = 86400 Sekunden also "Sekunden / 86400 = Tag/e"
				$hours 						= floor(($init / 3600) % 24); 								//Eine Stunde = 3600 Sekunden also "Sekunden / 3600 = Stunde/n"
				$minutes 					= floor(($init / 60) % 60); 									//Eine Minute = 60 Sekunden also "Sekunden / 60 = Minute/n"
				$seconds 					= $init % 60;
				$uptimeoutput 		= "ist online seit ".$days." Tagen, ".$hours." Stunden und ".$minutes." Minuten"; 		//Uptime in Menschenzeit in Variable
				$currentDateTime 	= date('d.m.Y H:i'); 																																	//Aktuelles Datum mit Uhrzeit (d=Tag, m=Monat, Y=Jahr, H=Stunde, i=Minute)
    }
    
    catch(Exception $e){
        									// bei Fehlern wird der Server als offline angezeigt
		$error = "Offline"; 		//Wenn der Server nicht erreichbar ist, wird der Banner nicht funktionieren!
    }
?>
<?php
$im 			= imagecreatetruecolor(800, 150); 								//Maße des Bildes
$rot 			= imagecolorallocate($im, 255,0,0); 							//Rote Textfarbe
$gruen 		= imagecolorallocate($im, 0, 255, 0); 						//Grüne Textfarbe
$blau 		= imagecolorallocate($im, 0, 0, 255); 						//Blaue Textfarbe
$gelb 		= imagecolorallocate($im, 255, 255, 0); 					//Gelb Textfarbe
$tuerkis 	= imagecolorallocate($im, 0, 200, 255); 					//Türkise Textfarbe
$pink 		= imagecolorallocate($im, 255, 0, 255); 					//Pinke Textfarbe
$orange 	= imagecolorallocate($im, 255, 127, 0); 					//Orange Textfarbe
$lila 		= imagecolorallocate($im, 127, 0, 255); 					//Lila Textfarbe
$weiss 		= imagecolorallocate($im, 255, 255, 255); 				//Weisse Textfarbe
																														//imagestring($im, ["5, 10, 5," = POSITION]   ["$snames" = TEXT], ["$gelb"] = FARBE);
imagestring($im, 5, 10, 5,   $snames, $gelb); 							//Servername
imagestring($im, 5, 10, 30,  $blabla, $orange); 						//Nutzer
imagestring($im, 5, 10, 60,  $infomsg, $weiss); 						//Erste Nachricht
imagestring($im, 5, 10, 90,  $infomsg2, $weiss); 						//Zweite Nachricht
imagestring($im, 5, 350, 30,  $chn, $pink); 								//Channelanzahl
imagestring($im, 5, 300, 5,  $uptimeoutput, $tuerkis); 			//Uptime des Servers
imagestring($im, 5, 630, 120,  $currentDateTime, $gruen); 	//Aktuelles Datum und Uhrzeit

// Die Content-Type-Kopfzeile senden, in diesem Fall image/jpeg
header('Content-Type: image/jpeg');

// Das Bild ausgeben
imagejpeg($im);

// Den Speicher freigeben
imagedestroy($im);
?>
