<?php
error_reporting(E_ALL | E_STRICT);
header("Content-Type: text/html; charset=utf-8"); // Anweisung an den Browser, welcher Zeichensatz verwendet werden soll.
ini_set('auto_detect_line_endings', true);


/* Copyright 2014, Jan Stieler */


//Vars

$filename = 'visitenkarte';
$dirname = 'visitenkarten';
$filetypeCSV = '.csv';
$filetypeTXT = '.txt';
$show_email = false; // Bestimmt, ob Email angezeigt (true) oder nicht angezeigt (false) werden soll
$show_website = true; // Bestimmt, ob Website-URL angezeigt werden soll
$dir = __DIR__;
$pre = "-";


//Funktionen
function entry (){
	global $filename, $filetypeCSV, $filetypeTXT, $dirname;

	// Einlesen und Splitten der Datei:
	$count_open = fopen ('counter'.$filetypeTXT, 'r+'); // Öffnen des Counter-Files
	if ($count_open) { // Erfolgreich?
		$count_get = fgets ($count_open); // Auslesen
		fseek($count_open, 0, SEEK_SET);  // Zeiger wieder auf Anfang
		$count_new = str_pad($count_get+1, 2 ,'0', STR_PAD_LEFT); // Counter erhöhen
	
		fwrite ($count_open, $count_new); // Reinschreiben
		fclose ($count_open);
	
		$path = $dirname . '/' . $filename . '_' . $_POST['name'] . '-' . $_POST['surname'] . $filetypeCSV;
		$dir = dirname($path); // Verzeichnis für neuen Eintrag anlegen
		if (!file_exists($dir) and !is_dir($dir)) {
		    mkdir($dir);         
		}
		
		$open = fopen ($path, 'w+');    //Die Einträge speichern
		$stringtitle = 'name , surname , position , email , mobile , number , fax , street , streetnumber , zip , city';
		$string = $_POST['name'] . ',' . $_POST['surname'] . ',' . $_POST['position'] . ',' . $_POST['email'] . ',' . $_POST['mobile']. ',' . $_POST['number']. ',' . $_POST['fax']. ',' . $_POST['street']. ',' . $_POST['streetnumber']. ',' . $_POST['zip']. ',' . $_POST['city'];
		fwrite ($open, $stringtitle . "\n" . $string);
		fclose ($open);
	}
}

function validate() {
	// Wenn auf 'Eintragen' geklickt wurde, prüfe das Formular 
	if(isset($_POST['submit']) AND $_POST['submit']=='abschicken'){ 
		// Fehlerarray erzeugen 
		$errors = array(); 	 
		// Prüfen, ob Name und Email nicht leer sind 
		if(!isset($_POST['name'], $_POST['surname'], $_POST['position'], $_POST['email'], $_POST['street'], $_POST['streetnumber'], $_POST['zip'], $_POST['city'])){
             $errors[] = "Bitte benutzen sie unser Formular."; 
        }
        else{
			if(trim($_POST['name'])==''){
				$errors[] = "Bitte geben Sie ihren Namen ein.";
			}
			if(trim($_POST['email'])==''){
				$errors[] = "Bitte geben Sie ihre Email-Adresse ein.";
			}
		}
	} 
	// Wenn das Formular geprüft wurde und kein Fehler gefunden wurde verarbeite die Daten
	if(isset($errors) AND !count($errors)){ 
		if (function_exists('entry')){
			entry ();
			echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Der Beitrag wurde eingetragen.</div>';
		}
		else {
			echo '<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Warnung! </strong>Es ist ein Fehler aufgetreten.</div>';
		}
	} 
	// Beim ersten Aufruf oder beim Finden eines Fehlers wird das Formular angezeigt
	else { 
		// Wurde bei der Formularprüfung ein Fehler gefunden wird er über dem Formular ausgegeben
		if(isset($errors)){ 
			echo '<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Warnung! </strong>Ihre Daten konnten nicht verarbeitet werden. <br><ul>'; 
			foreach($errors as $error)
			echo '<li>'.$error.'</li>'; 
			echo '</ul></div>'; 
		}
	}
}
?>
<!doctype html>
<html lang="de">
	<head>
		<meta name="content-type" content="text/html; charset=utf-8" />
		<title>Gästebuch</title>
		<link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
		<script src="js/modernizr.min.js"></script>
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
		<style>
		
			.form-control,
			.btn {
				-webkit-border-radius: 0;
				-moz-border-radius: 0;
				border-radius: 0;
			}
			
			.well {
				border-radius: 0;
			}
			
			form {
				margin-bottom: 15px;
			}
						
			.aria-show {
				position:absolute;
				left:-10000px;
				top:auto;
				width:1px;
				height:1px;
				overflow:hidden;
			}			
			
			.card_container {
				position: relative;
				width: 450px;
				height: 281px;
				z-index: 1;
				margin-top: 60px;
				margin-bottom: 61px;
				-webkit-perspective: 1000px;
				-moz-perspective: 1000px;
				-o-perspective: 1000px;
				perspective: 1000px;
			}
						
			.card {
				width:100%;
				height:100%;
				border: 1px solid #D6D6D6;
				-webkit-transform-style: preserve-3d;
				-webkit-transition: all 1.0s linear;
				-moz-transform-style: preserve-3d;
				-moz-transition: all 1.0s linear;
				-o-transform-style: preserve-3d;
				-o-transition: all 1.0s linear;
				transform-style: preserve-3d;
				transition: all 1.0s linear;
				display:block;
				color: #fff;
				-webkit-box-shadow: 2px 2px 10px 0 #aaa;
				box-shadow: 2px 2px 10px 0 #aaa;
				cursor: pointer;
			}
			
			.card .logo {
				padding-top: 21px;
			}
			
			.card .name {
				margin-top: 20px;
			}
			
			.card .name output {
				display: inline;
			}
			
			.card .position {
				margin-top: -9px;
			}
			
			.card .position {
				display: inline;
			}
			
			.card output {
				padding-top: 0;
				font-size: inherit;
				vertical-align: middle;
				margin-right: 5px;
				color: inherit;
			}
			
			.card #outputStreet {
				float: left;
			}
			
			.card #outputzip {
				float: left;
			}			
			
			#switch {
				display: none;
			}
						
			input:checked + .card{
				-webkit-transform: rotateY(180deg);
				-moz-transform: rotateY(180deg);
				-o-transform: rotateY(180deg);
				transform: rotateY(180deg);
				-webkit-box-shadow: 2px 2px 10px 0 #aaa;
				box-shadow: -2px 2px 10px 0 #aaa;
			} 
			
			.face {
				position: absolute;
				width: 100%;
				height: 100%;
				-webkit-backface-visibility: hidden;
				-moz-backface-visibility: hidden;
				-o-backface-visibility: hidden;
				backface-visibility: hidden;
				color:#000;
			}
			
			.face.back {
				display: block;
				-webkit-transform: rotateY(180deg);
				-webkit-box-sizing: border-box;
				-moz-transform: rotateY(180deg);
				-moz-box-sizing: border-box;
				-o-transform: rotateY(180deg);
				-o-box-sizing: border-box;
				transform: rotateY(180deg);
				box-sizing: border-box;
				padding: 10px;
			}
		</style>
		<script>
		$(document).ready(function() {			
			$('.switch').on('click',function () {
				var btn = $(this);
				btn.button('toggle');
			
				if(btn.hasClass('active')) {
					$('#switch').prop('checked', true);
					btn.text('Vorderseite');
				} else {
					$('#switch').removeAttr('checked');
					btn.text('Rückseite');
				}
			});
			
			$('button[name="delete"]').on('click', function(){
				$('output').val('');
			});
			
			$('input#InputName').clone().prepend('h2.name');

			$('#InputName').on('keyup keydown', function(){
				$('#OutputName').text($(this).val());
			});
			$('#InputSurname').on('keyup keydown', function(){
				$('#OutputSurname').text($(this).val());
			});
			$('#InputPosition').on('keyup keydown', function(){
				$('#OutputPosition').text($(this).val());
			});
			$('#InputEmail').on('keyup keydown', function(){
				$('#OutputEmail').text($(this).val());
			});
			$('#InputMobile').on('keyup keydown', function(){
				$('#OutputMobile').text($(this).val());
			});
			$('#InputNumber').on('keyup keydown', function(){
				$('#OutputNumber').text($(this).val());
			});
			$('#InputFax').on('keyup keydown', function(){
				$('#OutputFax').text($(this).val());
			});
			$('#InputStreet').on('keyup keydown', function(){
				$('#OutputStreet').text($(this).val());
			});
			$('#InputStreetnumber').on('keyup keydown', function(){
				$('#OutputStreetnumber').text($(this).val());
			});
			$('#InputZip').on('keyup keydown', function(){
				$('#OutputZip').text($(this).val());
			});
			$('#InputCity').on('keyup keydown', function(){
				$('#OutputCity').text($(this).val());
			});
		});
		</script>
	<body class="container">
		<div class="col-md-6">	
			<h1>Dateneintrag</h1>
			<!-- START OF FORM -->			
			<form role="form" action="" method="post" class="row">			
				<div class="form-group col-md-12">
					<label class="aria-show" for="InputName">Ihr Name</label>
					<?php 
						// Stellt die Email-Adresse wieder her, wenn ein Fehler auftrat 
						if(isset($_POST['name'])){
							echo '<input type="text" name="name" class="form-control" id="InputName" placeholder="Ihr Name" value="'.htmlentities($_POST['name'], ENT_QUOTES).'">';
						}
						
						else{
							echo '<input type="text" name="name" class="form-control" id="InputName" placeholder="Ihr Name">';
						} 
					?> 
				</div>
				<div class="form-group col-md-12">
					<label class="aria-show" for="InputSurname">Ihr Nachname</label>
					<?php 
						// Stellt die Email-Adresse wieder her, wenn ein Fehler auftrat 
						if(isset($_POST['surname'])){
							echo '<input type="text" name="surname" class="form-control" id="InputSurname" placeholder="Ihr Nachname" value="'.htmlentities($_POST['surname'], ENT_QUOTES).'">';
						}
						
						else{
							echo '<input type="text" name="surname" class="form-control" id="InputSurname" placeholder="Ihr Nachname">';
						} 
					?> 
				</div>
				<div class="form-group col-md-12">
					<label class="aria-show" for="InputPosition">Ihre Position</label>
					<?php 
						// Stellt die Email-Adresse wieder her, wenn ein Fehler auftrat 
						if(isset($_POST['position'])){
							echo '<input type="text" name="position" class="form-control" id="InputPosition" placeholder="Ihre Position" value="'.htmlentities($_POST['position'], ENT_QUOTES).'">';
						}
						
						else{
							echo '<input type="text" name="position" class="form-control" id="InputPosition" placeholder="Ihre Position">';
						} 
					?> 
				</div>
				<div class="form-group col-md-12">
					<label class="aria-show" for="InputEmail">Ihre E-Mail Adresse</label>
					<?php 
						// Stellt die Email-Adresse wieder her, wenn ein Fehler auftrat 
						if(isset($_POST['email'])){
							echo '<input type="email" name="email" class="form-control" id="InputEmail" placeholder="Ihre E-Mail Adresse" value="'.htmlentities($_POST['email'], ENT_QUOTES).'">'; 
						}
						
						else{
							echo '<input type="email" name="email" class="form-control" id="InputEmail" placeholder="Ihre E-Mail Adresse">';
						} 
					?> 
				</div>
				<div class="form-group col-md-12">
					<label class="aria-show" for="InputMobile">Ihre Mobilnummer</label>
					<?php 
						// Stellt die Email-Adresse wieder her, wenn ein Fehler auftrat 
						if(isset($_POST['mobile'])){
							echo '<input type="tel" name="mobile" class="form-control" id="InputMobile" placeholder="Ihre Mobilnummer" value="'.htmlentities($_POST['mobile'], ENT_QUOTES).'">'; 
						}
						
						else{
							echo '<input type="tel" name="mobile" class="form-control" id="InputMobile" placeholder="Ihre Mobilnummer">';
						} 
					?>
				</div>
				<div class="form-group col-md-12">
					<label class="aria-show" for="InputNumber">Ihre telefonnummer</label>
					<?php 
						// Stellt die Email-Adresse wieder her, wenn ein Fehler auftrat 
						if(isset($_POST['number'])){
							echo '<input type="tel" name="number" class="form-control" id="InputNumber" placeholder="Ihre Telefonnummer" value="'.htmlentities($_POST['number'], ENT_QUOTES).'">'; 
						}
						
						else{
							echo '<input type="tel" name="number" class="form-control" id="InputNumber" placeholder="Ihre Telefonnummer">';
						} 
					?>
				</div>
				<div class="form-group col-md-12">
					<label class="aria-show" for="InputFax">Ihre Faxnumber</label>
					<?php 
						// Stellt die Email-Adresse wieder her, wenn ein Fehler auftrat 
						if(isset($_POST['fax'])){
							echo '<input type="tel" name="fax" class="form-control" id="InputFax" placeholder="Ihre Faxnummer" value="'.htmlentities($_POST['fax'], ENT_QUOTES).'">'; 
						}
						
						else{
							echo '<input type="tel" name="fax" class="form-control" id="InputFax" placeholder="Ihre Faxnummber">';
						} 
					?>
				</div>
				<div class="form-group col-md-9">
					<label class="aria-show" for="InputStreet">Ihr Straßenname</label>
					<?php 
						// Stellt die Email-Adresse wieder her, wenn ein Fehler auftrat 
						if(isset($_POST['street'])){
							echo '<input type="text" name="street" class="form-control" id="InputStreet" placeholder="Ihre Straßennamen" value="'.htmlentities($_POST['street'], ENT_QUOTES).'">'; 
						}
						
						else{
							echo '<input type="text" name="street" class="form-control" id="InputStreet" placeholder="Ihre Straßennamen">';
						} 
					?>
				</div>
				<div class="form-group col-md-3">
					<label class="aria-show" for="InputStreetnumber">Ihre Hausnr.</label>
					<?php 
						// Stellt die Email-Adresse wieder her, wenn ein Fehler auftrat 
						if(isset($_POST['streetnumber'])){
							echo '<input type="text" name="streetnumber" class="form-control" id="InputStreetnumber" placeholder="Ihre Hausnr." value="'.htmlentities($_POST['streetnumber'], ENT_QUOTES).'">'; 
						}
						
						else{
							echo '<input type="text" name="streetnumber" class="form-control" id="InputStreetnumber" placeholder="Ihre Hausnr.">';
						} 
					?>
				</div>
				<div class="form-group col-md-4">
					<label class="aria-show" for="InputZip">Ihre Hausnummer</label>
					<?php 
						// Stellt die Email-Adresse wieder her, wenn ein Fehler auftrat 
						if(isset($_POST['zip'])){
							echo '<input type="number" name="zip" class="form-control" id="InputZip" placeholder="Ihre Postleitzahl" value="'.htmlentities($_POST['zip'], ENT_QUOTES).'">'; 
						}
						
						else{
							echo '<input type="number" name="zip" class="form-control" id="InputZip" placeholder="Ihre Postleitzahl">';
						} 
					?>
				</div>
				<div class="form-group col-md-8">
					<label class="aria-show" for="InputCity">Ihr Wohnort</label>
					<?php 
						// Stellt die Email-Adresse wieder her, wenn ein Fehler auftrat 
						if(isset($_POST['streetnumber'])){
							echo '<input type="int" name="city" class="form-control" id="InputCity" placeholder="Ihr Wohnort" value="'.htmlentities($_POST['city'], ENT_QUOTES).'">'; 
						}
						
						else{
							echo '<input type="int" name="city" class="form-control" id="InputCity" placeholder="Ihr Wohnort">';
						} 
					?>
				</div>
				<button type="submit" class="btn btn-success" name="submit" value="abschicken" style="margin-left:15px;">Eintrag abschicken</button>
				<button type="reset" class="btn btn-danger pull-right" name="delete" style="margin-right: 15px;">Eintrag löschen</button>
			</form>
			<!-- END OF FORM -->
			<?php
				if (isset($_POST['submit']) AND $_POST['submit']=='abschicken'){
					if (function_exists('validate')){
					 	validate ();
					}
					else {
						echo '<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Warning! </strong>Das Formular wurde nicht validiert.</div>';
					}
				}
			?>

		<!-- END OF GUESTBOOK-CODE -->
		</div>
		<div class="col-md-6">
			<h1>Visitenkartenvorschau</h1>
			<div class=card_container>
				<input type="checkbox" name="switch" id="switch">
				<label class="card" for="switch">
					<div class="front face">
						<img class="logo center-block" src="http://placehold.it/150x150/FF0000/000&text=Logo">
						<output class="name text-center" for="InputName" id="OutputName"></output><output for="InputSurname" id="OutputSurname"></output>
						<output class="position text-center" id="OutputPosition"></output>
					</div>
					<div class="back face">
						<output for="InputEmail" id="OutputEmail"></output>
						<output for="InputMobile" id="OutputMobile"></output>
						<output for="InputNumber" id="OutputNumber"></output>
						<output for="InputFax" id="OutputFax"></output>
						<output for="InputStreet" id="OutputStreet"></output>
						<output for="InputStreetnumber" id="OutputStreetnumber"></output>
						<output for="InputZip" id="OutputZip"></output>
						<output for="InputCity" id="OutputCity"></output>
					</div>
				</label>
			</div>
			<button class="btn btn-default switch" name="switch">Rückseite</button>
		</div>
	</body>
</html>