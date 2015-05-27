<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Ad Offense Email System</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<?php

require '/mail/PHPMailerAutoload.php';

$mail = new PHPMailer;

echo "<CENTER>";
echo "<div id='container'>";
echo "<div id='main'>";
echo "<table border ='1' class='table'><tr><th class='table-left' rowspan='3'>";
if(isset($_GET["logout"])){
	echo "";
}
elseif(isset($_SESSION['login'])){
	if($_SESSION['login'] == "superadmin"){
		echo "<p>User Options:<br />";
		echo "<a class href='adoffense.php?logout=set'>Logout</a></p>";
	}
	elseif($_SESSION['login'] == "contactsadmin"){
		echo "<p>User Options:<br />";
		echo "<a class href='adoffense.php?logout=set'>Logout</a></p>";
	}
	else{
		echo "<p>User Options:<br />";
		echo "<a class href='adoffense.php?logout=set'>Logout</a></p>";
	}
}
elseif(isset($_POST["login"]) && isset($_POST["password"])){
	if(empty($_POST["login"]) || empty($_POST["password"])){
		echo "";
	}
	else{
		$login = $_POST["login"];
		$password = $_POST["password"];
		$users = file_get_contents("users.txt");
		$usersarray = explode(",",$users);
		$userssearch = $login . "," . $password;
		if(strpos($users,$userssearch) !== FALSE){
			echo "<p>User Options:<br />";
			echo "<a class href='adoffense.php?logout=set'>Logout</a></p>";
		}
		else{
			echo "";
		}
	}
}
else{
	echo "";
}
echo "</th><th class='table-hf'>";
echo "<h1>Ad Offense Email & Ticket System</h1>";
echo "</th></tr><tr><td class='table-mid'>";

if(isset($_GET["logout"])){
	logout();
	echo "<p>You are now logged out!</p>";
	echo "<hr />";
	echo "<p><a href='index.html'>Return to Login Page</a></p>";
}
elseif(isset($_POST['adoffenseform'])){
	if(empty($_POST['priority']) || empty($_POST['type']) || empty($_POST['viewed']) || empty($_POST['sites']) || empty($_POST['action']) || empty($_POST['sender']) || empty($_POST['ssp']) || empty($_POST['network'])){
		echo "<p>Must fill out all required fields!</p>";
		$priority = $_POST['priority'];
		$type = $_POST['type'];
		$viewed = $_POST['viewed'];
		$sites = $_POST['sites'];
		$action = $_POST['action'];
		$source = $_POST['source'];
		$offender = $_POST['offender'];
		$advertiser = $_POST['advertiser'];
		$blockurl = $_POST['blockurl'];
		$sender = $_POST['sender'];
		reloadAdOffenseForm($priority,$type,$viewed,$sites,$action,$source,$offender,$advertiser,$blockurl,$sender);
	}
	else{
		//$datafields =  array ("helpdesk_ticket" =>array(),"custom_field" =>array());
		//$datafields['helpdesk_ticket']['email'] = "aq@intermarkets.net";
	
		$jsondata = array ();
		$jsondata['helpdesk_ticket[email]'] = "aq@intermarkets.net";
	
		$priority = $_POST['priority'];
		$priority2 = "";
		switch ($priority) {
		  case "Low":
			$priority2 = 1;
			break;
		  case "Medium":
			$priority2 = 2;
			break;
		  case "High":
			$priority2 = 3;
			break;
		  default:
			$priority2 = 4;
		}
		//$datafields['helpdesk_ticket']['priority'] = $priority2;
		$jsondata['helpdesk_ticket[priority]'] = $priority2;
		
		$viewed = $_POST['viewed'];
		//$datafields['helpdesk_ticket']['custom_field']['viewed_80079'] = $viewed;
		$jsondata['helpdesk_ticket[custom_field[viewed_80079]]'] = $viewed;
		
		$sender = clean_input($_POST['sender']);
		
		$htmlbody = array();
		$textbody = array();
		
		$sites = $_POST['sites'];
		array_push($htmlbody,"Site: $sites");
		//$datafields['helpdesk_ticket']['custom_field']['observed_at_80079'] = $sites;
		$jsondata['helpdesk_ticket[custom_field[observed_at_80079]]'] = $sites;
		
		$type = $_POST['type'];
		array_push($htmlbody,"Offense type: $type");
		//$datafields['helpdesk_ticket']['ticket_type'] = $type;
		$jsondata['helpdesk_ticket[ticket_type]'] = $type;
		
		$ticketsubject = "Ad Offense - " . $sites . " - $type" . " ad";
		//$datafields['helpdesk_ticket']['subject'] = $ticketsubject;
		$jsondata['helpdesk_ticket[subject]'] = $ticketsubject;
		
		if(isset($_FILES['attachment1'])){
			if(empty($_FILES["attachment1"]["name"])){
				$attachment1 = "";
			}
			else{
				$attachment1 = $_FILES["attachment1"]["name"];
				$name = "attachment1";
				$att1response = uploadAndSaveFile($name);
				
				if(stripos($att1response,"not") == FALSE){
					$jsondata['helpdesk_ticket[attachments][][resource]'] = "@" . "C:/xampp/htdocs/adoffense/upload/" . $attachment1;
				}
				
				echo $att1response;
			}
		}
		
		if(isset($_FILES['attachment2'])){
			if(empty($_FILES["attachment2"]["name"])){
				$attachment2 = "";
			}
			else{
				$attachment2 = $_FILES["attachment2"]["name"];
				$name = "attachment2";
				$att2response = uploadAndSaveFile($name);
				echo $att2response;
			}
		}
		
		if(isset($_FILES['attachment3'])){
			if(empty($_FILES["attachment3"]["name"])){
				$attachment3 = "";
			}
			else{
				$attachment3 = $_FILES["attachment3"]["name"];
				$name = "attachment3";
				$att3response = uploadAndSaveFile($name);
				echo $att3response;
			}
		}
		
		if(isset($_FILES['attachment4'])){
			if(empty($_FILES["attachment4"]["name"])){
				$attachment4 = "";
			}
			else{
				$attachment4 = $_FILES["attachment4"]["name"];
				$name = "attachment4";
				$att4response = uploadAndSaveFile($name);
				echo $att4response;
			}
		}
		
		if(isset($_FILES['attachment5'])){
			if(empty($_FILES["attachment5"]["name"])){
				$attachment5 = "";
			}
			else{
				$attachment5 = $_FILES["attachment5"]["name"];
				$name = "attachment5";
				$att5response = uploadAndSaveFile($name);
				echo $att5response;
			}
		}
		
		if(isset($_POST['advertiser'])){
			if(empty($_POST['advertiser'])){
			}
			else{
				$advertiser = clean_input($_POST['advertiser']);
				array_push($htmlbody,"Advertiser/Product: $advertiser");
				//$datafields['helpdesk_ticket']['custom_field']['advertiserproduct_80079'] = $advertiser;
				$jsondata['helpdesk_ticket[custom_field[advertiserproduct_80079]]'] = $advertiser;
			}
		}
		
		if(isset($_POST['offender'])){
			if(empty($_POST['offender'])){
			}
			else{
				$offender = clean_input($_POST['offender']);
				array_push($htmlbody,"Offending party: $offender");
				//$datafields['helpdesk_ticket']['custom_field']['offender_80079'] = $offender;
				$jsondata['helpdesk_ticket[custom_field[offender_80079]]'] = $offender;
			}
		}
		
		if(isset($_POST['source'])){
			if(empty($_POST['source'])){
			}
			else{
				$source = $_POST['source'];
				array_push($htmlbody,"Vendor source: $source");
				//$datafields['helpdesk_ticket']['custom_field']['vendor_source_80079'] = $source;
				$jsondata['helpdesk_ticket[custom_field[vendor_source_80079]]'] = $source;
			}
		}
		
		$action = $_POST['action'];
		array_push($htmlbody,"Action needed: $action");
		//$datafields['helpdesk_ticket']['custom_field']['needs_to_be_blocked_on_80079'] = $action;
		$jsondata['helpdesk_ticket[custom_field[needs_to_be_blocked_on_80079]]'] = $action;
		
		if(isset($_POST['blockurl'])){
			if(empty($_POST['blockurl'])){
			}
			else{
				$blockurl = $_POST['blockurl'];
				array_push($htmlbody,"Block URL: $blockurl");
				//$datafields['helpdesk_ticket']['custom_field']['block_url_80079'] = $blockurl;
				$jsondata['helpdesk_ticket[custom_field[block_url_80079]]'] = $blockurl;
			}
		}
		
		if(isset($_POST['notes'])){
			if(empty($_POST['notes'])){
				$notes = "";
			}
			else{
				$notes = clean_input($_POST['notes']);
				array_push($htmlbody,"Notes: $notes");
				//$datafields['helpdesk_ticket']['description'] = $notes;
				$jsondata['helpdesk_ticket[description]'] = $notes;
			}
		}
		
		if(isset($_POST['adcode'])){
			if(empty($_POST['adcode'])){
			}
			else{
				$adcode = $_POST['adcode'];
				array_push($htmlbody,"Ad Code: $adcode");
				//$datafields['helpdesk_ticket']['custom_field']['ad_code_80079'] = $adcode;
				$jsondata['helpdesk_ticket[custom_field[ad_code_80079]]'] = $adcode;
			}
		}
	
		$ssp = $_POST['ssp'];
		$ssp2 = $ssp;
		$sspaddresses = array();

		if(isset($_POST['ssp'])){
			if(array_search("none",$_POST['ssp']) !== FALSE && count($ssp) > 1){
				echo "<p>Cannot select None and another Value at the same time!</p>";
				$priority = $_POST['priority'];
				$type = $_POST['type'];
				$viewed = $_POST['viewed'];
				$sites = $_POST['sites'];
				$action = $_POST['action'];
				$source = $_POST['source'];
				$offender = $_POST['offender'];
				$advertiser = $_POST['advertiser'];
				$blockurl = $_POST['blockurl'];
				$sender = $_POST['sender'];
				reloadAdOffenseForm($priority,$type,$viewed,$sites,$action,$source,$offender,$advertiser,$blockurl,$sender);
			}
			elseif(empty($_POST['ssp']) || array_search("none",$_POST['ssp']) !== FALSE){
				$sspaddresses = "";
			}
			else{
				$ssps = file_get_contents("ssp.txt");
				$sspsarray = explode(";",$ssps);
				
				for($x=0; $x<count($ssp); $x++){
					foreach($ssp2 as $value){
						$ssppos1 = stripos($ssps,$value);
						
						$ssplen = strlen($value);
						$ssppos2 = $ssppos1 + $ssplen +1;
						
						while(current($sspsarray) !== $value){
							next($sspsarray);
						}
						
						$str2 = next($sspsarray);
						$len2 = strlen($str2);
						reset($sspsarray);
						
						array_push($sspaddresses,substr($ssps,$ssppos2,$len2));
						array_shift($ssp2);
					}
				}
			}
		}
		
		$network = $_POST['network'];
		$network2 = $network;
		$networkaddresses = array();
		
		if(isset($_POST['network'])){
			if(array_search("none",$_POST['network']) !== FALSE && count($network) > 1){
				echo "<p>Cannot select None and another Value at the same time!</p>";
				$priority = $_POST['priority'];
				$type = $_POST['type'];
				$viewed = $_POST['viewed'];
				$sites = $_POST['sites'];
				$action = $_POST['action'];
				$source = $_POST['source'];
				$offender = $_POST['offender'];
				$advertiser = $_POST['advertiser'];
				$blockurl = $_POST['blockurl'];
				$sender = $_POST['sender'];
				reloadAdOffenseForm($priority,$type,$viewed,$sites,$action,$source,$offender,$advertiser,$blockurl,$sender);
			}
			elseif(empty($_POST['network']) || array_search("none",$_POST['network']) !== FALSE){
				$networkaddresses = "";
			}
			else{
				$networks = file_get_contents("networks.txt");
				$networksarray = explode(";",$networks);
				
				for($x=0; $x<count($network); $x++){
					foreach($network2 as $value){
						$networkpos1 = stripos($networks,$value);
						
						$networklen = strlen($value);
						$networkpos2 = $networkpos1 + $networklen +1;
						
						while(current($networksarray) !== $value){
							next($networksarray);
						}
						
						$str3 = next($networksarray);
						$len3 = strlen($str3);
						reset($networksarray);
						
						array_push($networkaddresses,substr($networks,$networkpos2,$len3));
						array_shift($network2);
					}
				}
			}
		}
		$ticket_num = sendFreshdesk($jsondata);
		
		if($ticket_num !== FALSE){
			$emailsubject = "Ad Offense - " . $sites . " - $type" . " ad - Ticket ID: $ticket_num";
		}
		else{
			$emailsubject = "Ad Offense - " . $sites . " - $type" . " ad - Ticket ID: Not Created!";
		}
		
		sendMail($sspaddresses,$networkaddresses,$attachment1,$attachment2,$attachment3,$attachment4,$attachment5,$emailsubject,$htmlbody,$sender,$type);
		adOffenseForm();
	}
}
elseif(isset($_SESSION['login'])){
	if($_SESSION['login'] == "superadmin"){
	//loads superadmin panel
	}
	elseif($_SESSION['login'] == "contactsadmin"){
		if(isset($_GET['contactsadmin'])){
			if(isset($_GET['updateSSP'])){
				if(isset($_GET['changeSSP'])){
					if(empty($_GET['ssp']) || empty($_GET['email'])){
						echo "<p>All fields must be filled out!</p>";
						contactsAdmin();
					}
					else{
						$sspname = $_GET['sspname'];//original name
						$sspemail = $_GET['sspemail'];//original email
						$ssp = $_GET['ssp'];
						$email = $_GET['email'];
						$sspstring = file_get_contents("ssp.txt");
						$ssparray = explode(";",$sspstring);
						
						if(array_search($ssp,$ssparray) !== FALSE){//will change email only
							function changeSSPEmail($value){
								global $sspemail;
								global $email;
								if ($value===$sspemail){
									return $email;
								}
								return $value;
							}
							$ssparray2 = array_map("changeSSPEmail",$ssparray);
							$sspstring2 = implode(";",$ssparray2);
							file_put_contents("ssp.txt",$sspstring2,LOCK_EX);
							
							echo "<p>You have successfully changed the $sspname email!</p>";
							contactsAdmin();
						}
						else{//will change name and email of ssp
							function changeSSPName($value){
								global $sspname;
								global $ssp;
								if ($value===$sspname){
									return $ssp;
								}
								return $value;
							}
							
							function changeSSPEmail($value){
								global $sspemail;
								global $email;
								if ($value===$sspemail){
									return $email;
								}
								return $value;
							}
							$ssparray2 = array_map("changeSSPEmail",$ssparray);
							$ssparray3 = array_map("changeSSPName",$ssparray2);
							$sspstring2 = implode(";",$ssparray3);
							file_put_contents("ssp.txt",$sspstring2,LOCK_EX);
							
							echo "<p>You have successfully changed the $sspname name and email!</p>";
							contactsAdmin();
						}
					}
				}
				else{
					$sspname = $_GET['ssp'];
					$ssp = file_get_contents("ssp.txt");
					$ssparray = explode(";",$ssp);
					
					while(current($ssparray) !== $sspname){
							next($ssparray);
					}
					$sspemail = next($ssparray);
					
					echo "<form action='adoffense.php' method='get'>";
					echo "<p><b>Change SSP Contact:</b></p>";
					echo "<label for='ssp'>SSP Name: </label><input type='text' value='$sspname' name='ssp' /><label 	for='email'>SSP Email: </label><input type='email' value='$sspemail' name='email' />";
					echo "<input type='hidden' value='set' name='changeSSP' />";
					echo "<input type='hidden' value='set' name='updateSSP' />";
					echo "<input type='hidden' value='set' name='contactsadmin' />";
					echo "<input type='hidden' value='$sspname' name='sspname' />";
					echo "<input type='hidden' value='$sspemail' name='sspemail' />";
					echo "<input type='submit' value='Change'>";
					echo "</form>";
					echo "<hr />";
					contactsAdmin();
				}
			}
			elseif(isset($_GET['updateNetwork'])){
				if(isset($_GET['changeNetwork'])){
					if(empty($_GET['network']) || empty($_GET['email'])){
						echo "<p>All fields must be filled out!</p>";
						contactsAdmin();
					}
					else{
						$networkname = $_GET['networkname'];//original name
						$networkemail = $_GET['networkemail'];//original email
						$network = $_GET['network'];
						$email = $_GET['email'];
						$networkstring = file_get_contents("networks.txt");
						$networkarray = explode(";",$networkstring);
						
						if(array_search($network,$networkarray) !== FALSE){//will change email only
							function changeNetworkEmail($value){
								global $networkemail;
								global $email;
								if ($value===$networkemail){
									return $email;
								}
								return $value;
							}
							$networkarray2 = array_map("changeNetworkEmail",$networkarray);
							$networkstring2 = implode(";",$networkarray2);
							file_put_contents("networks.txt",$networkstring2,LOCK_EX);
							
							echo "<p>You have successfully changed the $networkname email!</p>";
							contactsAdmin();
						}
						else{//will change name and email of ssp
							function changeNetworkName($value){
								global $networkname;
								global $network;
								if ($value===$networkname){
									return $network;
								}
								return $value;
							}
							
							function changeNetworkEmail($value){
								global $networkemail;
								global $email;
								if ($value===$networkemail){
									return $email;
								}
								return $value;
							}
							$networkarray2 = array_map("changeNetworkEmail",$networkarray);
							$networkarray3 = array_map("changeNetworkName",$networkarray2);
							$networkstring2 = implode(";",$networkarray3);
							file_put_contents("networks.txt",$networkstring2,LOCK_EX);
							
							echo "<p>You have successfully changed the $networkname name and email!</p>";
							contactsAdmin();
						}
					}
				}
				else{
					$networkname = $_GET['network'];
					$network = file_get_contents("networks.txt");
					$networkarray = explode(";",$network);
					
					while(current($networkarray) !== $networkname){
							next($networkarray);
					}
					$networkemail = next($networkarray);
					
					echo "<form action='adoffense.php' method='get'>";
					echo "<p><b>Change Network Contact:</b></p>";
					echo "<label for='network'>Network Name: </label><input type='text' value='$networkname' name='network' /><label 	for='email'>Network Email: </label><input type='email' value='$networkemail' name='email' />";
					echo "<input type='hidden' value='set' name='changeNetwork' />";
					echo "<input type='hidden' value='set' name='updateNetwork' />";
					echo "<input type='hidden' value='set' name='contactsadmin' />";
					echo "<input type='hidden' value='$networkname' name='networkname' />";
					echo "<input type='hidden' value='$networkemail' name='networkemail' />";
					echo "<input type='submit' value='Change'>";
					echo "</form>";
					echo "<hr />";
					contactsAdmin();
				}
			}
			elseif(isset($_GET['addSSP'])){
				if(empty($_GET['ssp']) || empty($_GET['email'])){
					echo "<p>All fields must be filled out!</p>";
					contactsAdmin();
				}
				else{
					$ssp = $_GET['ssp'];
					$email = $_GET['email'];
					$sspstring = file_get_contents("ssp.txt");
					$ssparray = explode(';',$sspstring);
					array_push($ssparray,$ssp,$email);
					$sspstring2 = implode(';',$ssparray);
					file_put_contents("ssp.txt",$sspstring2,LOCK_EX);
					
					echo "<p>You have successfully added $ssp!</p>";
					contactsAdmin();
				}
			}
			else{
				if(empty($_GET['network']) || empty($_GET['email'])){
					echo "<p>All fields must be filled out!</p>";
					contactsAdmin();
				}
				else{
					$network = $_GET['network'];
					$email = $_GET['email'];
					$networkstring = file_get_contents("networks.txt");
					$networkarray = explode(';',$networkstring);
					array_push($networkarray,$network,$email);
					$networkstring2 = implode(';',$networkarray);
					file_put_contents("networks.txt",$networkstring2,LOCK_EX);
					
					echo "<p>You have successfully added $network!</p>";
					contactsAdmin();
				}
			}
		}
		else{
			contactsAdmin();
		}
	}
	else{
		adOffenseForm();
	}
}
elseif(isset($_POST["login"]) && isset($_POST["password"])){
	if(empty($_POST["login"]) || empty($_POST["password"])){
		echo "<p>Please enter both Username and Password.</p>";
		echo "<hr />";
		echo "<p><a href='index.html'>Return to Login Page</a></p>";
	}
	else{
		$login = $_POST["login"];
		$password = $_POST["password"];
		$users = file_get_contents("users.txt");
		$usersarray = explode(",",$users);
		$userssearch = $login . "," . $password;
		if(strpos($users,$userssearch) !== FALSE){
			if($login == "superadmin"){
				$_SESSION['login']=$login;
				
			}
			elseif($login == "contactsadmin"){
				$_SESSION['login']=$login;
				contactsAdmin();
			}
			else{
				$_SESSION['login']=$login;
				adOffenseForm();
			}
		}
		else{
			echo "<p>Invalid Username or Password!</p>";
			echo "<hr />";
			echo "<p><a href='index.html'>Return to Login Page</a></p>";
		}
	}
}
else{
echo "<p>You must be logged in to view this page!</p>";
echo "<hr />";
echo "<p><a href='index.html'>Return to Login Page</a></p>";
}

echo "</td></tr><tr><td class='table-hf'>";
echo "Ad Operations Email & Ticket System Copyright © 2014 Intermarkets, Inc. All rights reserved.";
echo "</td></tr>";
echo "</div>";
echo "</div>";
echo "<CENTER>";

function uploadAndSaveFile($name){
	$allowedExts = array("gif", "jpeg", "jpg", "png", "PNG", "txt", "doc", "docx", "odt");
	$temp = explode(".", $_FILES[$name]["name"]);
	$extension = end($temp);

	if ((($_FILES[$name]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") || ($_FILES[$name]["type"] == "application/vnd.oasis.opendocument.text") || ($_FILES[$name]["type"] == "application/msword") || ($_FILES[$name]["type"] == "text/plain") || ($_FILES[$name]["type"] == "image/gif") || ($_FILES[$name]["type"] == "image/jpeg") || ($_FILES[$name]["type"] == "image/jpg") || ($_FILES[$name]["type"] == "image/pjpeg") || ($_FILES[$name]["type"] == "image/x-png") || ($_FILES[$name]["type"] == "image/png")) && ($_FILES[$name]["size"] < 500000) && in_array($extension, $allowedExts)){
		
		if ($_FILES[$name]["error"] > 0){
			$response = "<p>File " . $_FILES[$name]["name"] . " did not upload properly!</p>";
			return $response;
		}
		else{
			//echo "Upload: " . $_FILES[$name]["name"] . "<br>";
			//echo "Type: " . $_FILES[$name]["type"] . "<br>";
			//echo "Size: " . ($_FILES[$name]["size"] / 1024) . " kB<br>";
			//echo "Temp file: " . $_FILES[$name]["tmp_name"] . "<br>";
			move_uploaded_file($_FILES[$name]["tmp_name"],
			"upload/" . $_FILES[$name]["name"]);
			
			$response = "<p>File " . $_FILES[$name]["name"] . " uploaded properly!</p>";
			return $response;
			}
	}
	else{
		$response = "<p>File " . $_FILES[$name]["name"] . " did not upload properly!</p>";
		return $response;
	}
}

function sendMail($sspaddresses,$networkaddresses,$attachment1,$attachment2,$attachment3,$attachment4,$attachment5,$emailsubject,$htmlbody,$sender,$type){
$mail = new PHPMailer;

	$mail->isSMTP();
	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'intermarketsaq@gmail.com';
	$mail->Password = 'rsg2014!b';
	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;
	
	$mail->addReplyTo('aq@intermarkets.net', 'Ad Quality');
	$mail->From = 'intermarketsaq@gmail.com';
	$mail->FromName = 'Ad Quality';
	if(isset($sspaddresses) && isset($networkaddresses)){
		if(empty($sspaddresses) && empty($networkaddresses)){
			$mail->addAddress('gspeach@intermarkets.net');
		}
		elseif(empty($networkaddresses)){
			foreach($sspaddresses as $value){
				$mail->addAddress($value);
			}
			$mail->addCC('gspeach@intermarkets.net');
		}
		elseif(empty($sspaddresses)){
			foreach($networkaddresses as $value){
				$mail->addAddress($value);
			}
			$mail->addCC('gspeach@intermarkets.net');
			//$mail->addCC('cdolle@intermarkets.net');
		}
		else{
			foreach($sspaddresses as $value){
				$mail->addAddress($value);
			}
			
			foreach($networkaddresses as $value){
				$mail->addAddress($value);
			}
			$mail->addCC('gspeach@intermarkets.net');
			//$mail->addCC('cdolle@intermarkets.net');
		}
	}
	
	$mail->WordWrap = 50;
	if(isset($attachment1)){
		if(empty($attachment1)){
		}
		else{
			$mail->addattachment("upload/" . $attachment1); 
		}
	}
	if(isset($attachment2)){
		if(empty($attachment2)){
		}
		else{
			$mail->addattachment("upload/" . $attachment2); 
		}
	}
	if(isset($attachment3)){
		if(empty($attachment3)){
		}
		else{
			$mail->addattachment("upload/" . $attachment3); 
		}
	}
	if(isset($attachment4)){
		if(empty($attachment4)){
		}
		else{
			$mail->addattachment("upload/" . $attachment4); 
		}
	}
	if(isset($attachment5)){
		if(empty($attachment5)){
		}
		else{
			$mail->addattachment("upload/" . $attachment5); 
		}
	}
	$mail->isHTML(false);
	
	$mail->Subject = $emailsubject;
	if($type == "Audio" || $type == "Malware"){
		$mail->Priority = 1;
	}
	
	$bodyhtmlstring = "";
	foreach($htmlbody as $value){
		$bodyhtmlstring .= $value . "\n\n";
	}
	
	$mail->Body = $bodyhtmlstring . "This email was sent on " . date("h:i:sa") . " " . date("Y/m/d") . " by " . $sender . " using the Intermarkets Ad Offense Email System.";
	//$mail->AltBody = $bodyhtmlstring . "<br />" . "This email was sent on " . date("Y/m/d") . " by " . $sender . " using the Intermarkets Ad Offense Email System.";
	
	if(!$mail->send()){
		echo "<p>Message could not be sent!</p>";
		echo "<p>Mailer Error: </p>" . $mail->ErrorInfo;
	} 
	else{
		echo "<p>Message has been sent.</p>";
	}
	
}

/*
function curl(){
curl -u ssnow@intermarkets.net:rsg2013a -H "Content-Type: application/json" -X POST -d '{ "helpdesk_ticket": { "description": "Details about the issue...", "subject": "Support Needed...", "email": "tom@outerspace.com", "priority": 1, "status": 2 }}' http://imkaq.freshdesk.com/helpdesk/tickets.json
}
*/


function sendFreshdesk($jsondata){

//$email="gspeach@intermarkets.net";
//$password="rsg2013a";
$apikey="M1mXj25Lpv9bCNERGwug";
$password="X";

//$url = 'http://imkaq.freshdesk.com/helpdesk/tickets.json';
$url = 'http://msdfreshdesk.freshdesk.com/helpdesk/tickets/204.json';

/*
$jsondata = array (
	'helpdesk_ticket[email]' => 'john@freshdesk.com',
	'helpdesk_ticket[subject]' => 'test',
	'helpdesk_ticket[description]' => 'testing description content',
	'helpdesk_ticket[attachments][][resource]' =>  "@" . "C:/xampp/htdocs/adoffense/upload/ad.PNG"
);
*/

$header[] = "Content-type: multipart/form-data";

/*
$jsondata= json_encode($datafields);

$header[] = "Content-type: application/json";
*/

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_POST, true);
//curl_setopt($ch, CURLOPT_POSTFIELDS,$datafields);
curl_setopt($ch, CURLOPT_POSTFIELDS,$jsondata);

curl_setopt($ch, CURLOPT_USERPWD, "$apikey:$password");
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_HEADER, false);
// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$server_output = curl_exec ($ch);

$response = json_decode($server_output);

if(isset($response)){
	$ticket_num = $response->helpdesk_ticket->display_id;
}


//echo "RESPONSE:<br/>".var_dump($response);

	if(empty($ticket_num)){
		curl_close ($ch);
		echo "<p>Ticket was not created successfully!</p>";
		echo "RESPONSE:<br/>".var_dump($response);
		return FALSE;
	}
	else{
		curl_close ($ch);
		echo "<p>Ticket ID: $ticket_num was created successfully!</p>";
		echo "RESPONSE:<br/>".var_dump($response);
		return $ticket_num;
	}


}

function logout(){
	session_destroy();
	unset($_SESSION['login']);
}

function clean_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}

function contactsAdmin(){
	//update ssp form
	echo "<form action='adoffense.php' method='get'>";
	echo "<p><b>Update SSP Contacts:</b></p>";
	echo "<select name='ssp'>";
	echo ssp();
	echo "</select><br /><br />";
	echo "<input type='hidden' value='set' name='updateSSP' />";
	echo "<input type='hidden' value='set' name='contactsadmin' />";
	echo "<input type='submit' value='Update'>";
	echo "</form>";
	//update networks form
	echo "<form action='adoffense.php' method='get'>";
	echo "<p><b>Update Network Contacts:</b></p>";
	echo "<select name='network'>";
	echo networks();
	echo "</select><br /><br />";
	echo "<input type='hidden' value='set' name='updateNetwork' />";
	echo "<input type='hidden' value='set' name='contactsadmin' />";
	echo "<input type='submit' value='Update'>";
	echo "</form>";
	//add new ssp form
	echo "<form action='adoffense.php' method='get'>";
	echo "<p><b>Add SSP Contact:</b></p>";
	echo "<label for='ssp'>SSP Name: </label><input type='text' name='ssp' /><label for='email'>SSP Email: </label><input type='email' name='email' />";
	echo "<input type='hidden' value='set' name='addSSP' />";
	echo "<input type='hidden' value='set' name='contactsadmin' />";
	echo "<input type='submit' value='Add'>";
	echo "</form>";
	//add new networks form
	echo "<form action='adoffense.php' method='get'>";
	echo "<p><b>Add Network Contact:</b></p>";
	echo "<label for='network'>Network Name: </label><input type='text' name='network' /><label for='email'>Network Email: </label><input type='email' name='email' />";
	echo "<input type='hidden' value='set' name='addNetwork' />";
	echo "<input type='hidden' value='set' name='contactsadmin' />";
	echo "<input type='submit' value='Add'>";
	echo "</form>";
}

function reloadAdOffenseForm($priority,$type,$viewed,$sites,$action,$source,$offender,$advertiser,$blockurl,$sender){
	echo "<h3>Ad Offense Form</h3>";
	echo "<form action='adoffense.php' enctype='multipart/form-data' method='post'>";
	echo "<p><font color = 'Crimson'>*Required Fields</font></p>";
	//Priority of offense
	echo required() . "<label for='priority'>Priority: </label><br />";
	echo "<select name='priority'>";
	echo "<option value='$priority' selected>$priority</option>";
	echo "<option value='Low'>Low</option>";
	echo "<option value='Medium'>Medium</option>";
	echo "<option value='High'>High</option>";
	echo "<option value='Urgent'>Urgent</option>"; 
	echo "</select><br /><br />";
	//offense type
	echo required() . "<label for='type'>Offense Type: </label><br />";
	echo "<select name='type'>";
	echo "<option value='$type' selected>$type</option>";
	echo offenseType();
	echo "</select><br /><br />";
	//where ad was viewed
	echo required() . "<label for='viewed'>Viewed: </label><br />";
	echo "<select name='viewed'>";
	echo "<option value='$viewed' selected>$viewed</option>";
	echo viewed();
	echo "</select><br /><br />";
	//publisher sites
	echo required() . "<label for='sites'>Site: </label><br />";
	echo "<select name='sites'>";
	echo "<option value='$sites' selected>$sites</option>";
	echo sites();
	echo "</select><br /><br />";
	//block actions
	echo required() . "<label for='action'>Action Needed: </label><br />";
	echo "<select name='action'>";
	echo "<option value='$action' selected>$action</option>";
	echo blockAction();
	echo "</select><br /><br />";
	//vendor sources
	echo "<label for='source'>Vendor Source: </label><br />";
	echo "<select name='source'>";
	echo "<option value='$source' selected>$source</option>";
	echo vendor();
	echo "</select><br /><br />";
	//offender
	echo "<label for='offender'>Offender: </label><br /><input type='text' name='offender' value='$offender' /><button type='button' onclick='offenderHelp()'>Need Help?</button><br /><br />";
	//advertiser or product description
	echo "<label for='advertiser'>Advertiser/Product: </label><br /><input type='text' value='$advertiser' name='advertiser' /><button type='button' onclick='advertiserHelp()'>Need Help?</button><br /><br />";
	//the block url
	echo "<label for='blockurl'>Block URL: </label><br /><input type='text' value='$blockurl' name='blockurl' /><br /><br />";
	//adcode
	echo "<label for='adcode'>Ad Code: </label><br /><textarea rows='10' cols = '50' maxlength='20000' name='adcode'></textarea><br /><br />";
	//attachments
	echo "Attachments: <button type='button' onclick='attachmentHelp()'>Need Help?</button><br /><br />";
	echo "<label for ='attachment1'>Select Attachment 1(Ticket): </label><input type='file' name='attachment1'><br />";
	echo "<label for ='attachment2'>Select Attachment 2: </label><input type='file' name='attachment2'><br />";
	echo "<label for ='attachment3'>Select Attachment 3: </label><input type='file' name='attachment3'><br />";
	echo "<label for ='attachment4'>Select Attachment 4: </label><input type='file' name='attachment4'><br />";
	echo "<label for ='attachment5'>Select Attachment 5: </label><input type='file' name='attachment5'><br /><br />";
	echo "<label for='notes'>Notes(100 Character Limit): </label><br /><textarea rows='10' cols = '50' maxlength='100' name='notes'></textarea><br /><br />";
	echo required() . "<label for='sender'>Sender(Your Name): </label><br /><input type='text' name='sender' value='$sender'/>";
	//email form
	echo "<h3>Email Form</h3>";
	//ssp
	echo required() . "<label for='ssp'>SSP's(Hold Ctrl or Shift for multiple selections): </label><br />";
	echo "<select multiple name='ssp[]' size='5'>";
	echo "<option value='none' selected>None</option>";
	echo ssp();
	echo "</select><br /><br />";
	//ad networks
	echo required() . "<label for'network'>Ad Network's(Hold Ctrl or Shift for multiple selections): </label><br />";
	echo "<select multiple name='network[]'size='10'>";
	echo "<option value='none' selected>None</option>";
	echo networks();
	echo "</select><br /><br />";
	echo "<input type='hidden' name='adoffenseform' value='set'>";
	echo "<input type='submit' value='Submit'>";
	echo "<input type='reset' value='Clear'>";
	echo "</form>";
}

function adOffenseForm(){
	echo "<h3>Ad Offense Form</h3>";
	echo "<form action='adoffense.php' enctype='multipart/form-data' method='post'>";
	echo "<p><font color = 'Crimson'>*Required Fields</font></p>";
	//Priority of offense
	echo required() . "<label for='priority'>Priority: </label><br />";
	echo "<select name='priority'>";
	echo "<option value='Low'>Low</option>";
	echo "<option value='Medium'>Medium</option>";
	echo "<option value='High'>High</option>";
	echo "<option value='Urgent'>Urgent</option>"; 
	echo "</select><br /><br />";
	//offense type
	echo required() . "<label for='type'>Offense Type: </label><br />";
	echo "<select name='type'>";
	echo offenseType();
	echo "</select><br /><br />";
	//where ad was viewed
	echo required() . "<label for='viewed'>Viewed: </label><br />";
	echo "<select name='viewed'>";
	echo viewed();
	echo "</select><br /><br />";
	//publisher sites
	echo required() . "<label for='sites'>Site: </label><br />";
	echo "<select name='sites'>";
	echo sites();
	echo "</select><br /><br />";
	//block actions
	echo required() . "<label for='action'>Action Needed: </label><br />";
	echo "<select name='action'>";
	echo blockAction();
	echo "</select><br /><br />";
	//vendor sources
	echo "<label for='source'>Vendor Source: </label><br />";
	echo "<select name='source'>";
	echo vendor();
	echo "</select><br /><br />";
	//offender
	echo "<label for='offender'>Offender: </label><br /><input type='text' name='offender' /><button type='button' onclick='offenderHelp()'>Need Help?</button><br /><br />";
	//advertiser or product description
	echo "<label for='advertiser'>Advertiser/Product: </label><br /><input type='text' name='advertiser' /><button type='button' onclick='advertiserHelp()'>Need Help?</button><br /><br />";
	//the block url
	echo "<label for='blockurl'>Block URL: </label><br /><input type='text' name='blockurl' /><br /><br />";
	//adcode
	echo "<label for='adcode'>Ad Code: </label><br /><textarea rows='10' cols = '50' maxlength='20000' name='adcode'></textarea><br /><br />";
	//attachments
	echo "Attachments: <button type='button' onclick='attachmentHelp()'>Need Help?</button><br /><br />";
	echo "<label for ='attachment1'>Select Attachment 1(Ticket): </label><input type='file' name='attachment1'><br />";
	echo "<label for ='attachment2'>Select Attachment 2: </label><input type='file' name='attachment2'><br />";
	echo "<label for ='attachment3'>Select Attachment 3: </label><input type='file' name='attachment3'><br />";
	echo "<label for ='attachment4'>Select Attachment 4: </label><input type='file' name='attachment4'><br />";
	echo "<label for ='attachment5'>Select Attachment 5: </label><input type='file' name='attachment5'><br /><br />";
	echo "<label for='notes'>Notes(100 Character Limit): </label><br /><textarea rows='10' cols = '50' maxlength='100' name='notes'></textarea><br /><br />";
	echo required() . "<label for='sender'>Sender(Your Name): </label><br /><input type='text' name='sender' />";
	//email form
	echo "<h3>Email Form</h3>";
	//ssp
	echo required() . "<label for='ssp'>SSP's(Hold Ctrl or Shift for multiple selections): </label><br />";
	echo "<select multiple name='ssp[]' size='5'>";
	echo "<option value='none' selected>None</option>";
	echo ssp();
	echo "</select><br /><br />";
	//ad networks
	echo required() . "<label for'network'>Ad Network's(Hold Ctrl or Shift for multiple selections): </label><br />";
	echo "<select multiple name='network[]'size='10'>";
	echo "<option value='none' selected>None</option>";
	echo networks();
	echo "</select><br /><br />";
	echo "<input type='hidden' name='adoffenseform' value='set'>";
	echo "<input type='submit' value='Submit'>";
	echo "<input type='reset' value='Clear'>";
	echo "</form>";
}

function required(){
echo "<font color = 'Crimson'>*</font>";
}

function adminPanel(){

}

function ssp(){
	$ssp = file_get_contents("ssp.txt");
	$ssparray = explode(";",$ssp);
	asort($ssparray);
	foreach($ssparray as $value){
		if($value === "" || strpbrk($value,"@") !== FALSE){
		
		}
		else{
			echo "<option value='$value'>$value</option>";
		}
	}
}

function networks(){
	$networks = file_get_contents("networks.txt");
	$networksarray = explode(";",$networks);
	asort($networksarray);
	foreach($networksarray as $value){
		if($value === "" || strpbrk($value,"@") !== FALSE){
		
		}
		else{
			echo "<option value='$value'>$value</option>";
		}
	}
}

function offenseType(){
	$offense = file_get_contents("offensetype.txt");
	$offensearray = explode(",",$offense);
	asort($offensearray);
	foreach($offensearray as $value){
		echo "<option value='$value'>$value</option>";
	}
}

function sites(){
	$sites = file_get_contents("sites.txt");
	$sitesarray = explode(",",$sites);
	asort($sitesarray);
	foreach($sitesarray as $value){
		echo "<option value='$value'>$value</option>";
	}
}

function viewed(){
	$viewed = file_get_contents("viewed.txt");
	$viewedarray = explode(",",$viewed);
	asort($viewedarray);
	foreach($viewedarray as $value){
		echo "<option value='$value'>$value</option>";
	}
}

function blockAction(){
	$blockaction = file_get_contents("blockaction.txt");
	$blockactionarray = explode(",",$blockaction);
	asort($blockactionarray);
	foreach($blockactionarray as $value){
		echo "<option value='$value'>$value</option>";
	}
}

function vendor(){

	$vendorsource = file_get_contents("vendorsource.txt");
	$vendorsourcearray = explode(",",$vendorsource);
	asort($vendorsourcearray);
	foreach($vendorsourcearray as $value){
		echo "<option value='$value'>$value</option>";
	}
}

?>
<script>
function attachmentHelp() {
    alert("Supported file types: .gif, .jpeg, .jpg, .png, .txt, .doc, .docx, .odt\nData limit: 500kB\nAttachment 1 is the only attachment that will post to freshdesk.");
}

function offenderHelp() {
    alert("Enter in the offending party. e.g. MathTag, Genome, ValueClick...");
}

function advertiserHelp() {
    alert("Enter in the advertiser or product. e.g. Chrysler, Disney, Lysol...");
}
</script>
</body>
</html>