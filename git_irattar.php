<?php

session_start();
$_SESSION['accept'] = 0; //sikeres dokumentum feltöltés

//_____________________ biztonsági id ellenőrzés (2 lépcsős)
			$validid = 0;
			$conn = new mysqli('szolgaltato','felh','jelszo','adatbazis');
			$result = $conn -> query("SELECT * FROM felhasznalo WHERE (((user = '".$_SESSION["user"]."')))");
			$belep = $result -> fetch_all();
			$conn->close();
			$conn = new mysqli('szolgaltato','felh','jelszo','adatbazis');
			$resultp = $conn -> query("SELECT * FROM jelszo_int ORDER BY ID");	
			$check = $resultp -> fetch_all();
			$conn->close();
			$ch = 0;
			foreach($check as $row){
				if($_SESSION['idnum'] == $check[$ch][0] && $belep[0][0] == $check[$ch][0]){
					$validid = 1;
				}
				$ch = $ch + 1;
			}
//_____________________________________________________bejelentkezés ellenőrzés
if($_SESSION['user'] == "" || $validid == 0){
	header('Location: mainpage.php');
	die();
}else{


$werr = 0; // jogosultság kiválasztását tároló változó. Hiba esetén $werr = 1



	
	// bejövő adatok tisztítása
	function test($str){
		$str = trim($str);
		$str = strip_tags($str);
		$str = stripslashes($str);
		return $str;
	}
	
	#______________________________________________________________________________________változóim
	$keresErr=$feltoltErr=$tipusErr=$targyErr=$nevErr=$keltErr=$ugyintErr="";
	$kermez=$iktszam=$ugyint=$mentszoveg=$mentszoveg1=$mentszoveg2=$feltoltszoveg="";
	$uindex=$iszam = 0;
	$tipus=[];
	
	
	#______________________________________________________________________________________1. form feldolgozása, fájl feltöltés
	if($_SERVER['REQUEST_METHOD']=="POST"){	
		if(isset($_POST['btn2'])){
			header('Location: mainpage.php');
		}elseif(isset($_POST['btn3'])){
	
			//_________________választottak ki jogosultat?
			$i5 = "0";$i6 = "0";$i7 = "0";$i8 = "0";$i9 = "0";$i10 = "0";$i11 = "0";$i12 = "0";
			if(!empty($_POST['lista'])){
				foreach($_POST['lista'] as $list){
					echo $lista;
					if($list == "kancellar"){$i5 = "1";}
					if($list == "igazgato"){$i6 = "1";}
					if($list == "titkar"){$i7 = "1";}
					if($list == "gr"){$i8 = "1";}
					if($list == "hr"){$i9 = "1";}
					if($list == "jogtanacsos"){$i10 = "1";}
					if($list == "tanugy"){$i11 = "1";}
					if($list == "kozos"){$i12 = "1";}
					if($list == "vezetoseg"){$i16 = "1";}
					if($list == "informatika"){$i17 = "1";}
					if($list == "project"){$i18 = "1";}
				}
			}else{$werr = 1;} // nincs jogosultság kiválasztva
			
			
			// Ha minden rendben mehet a feltöltés
			if($_FILES['file']['name'] != "" && $werr == 0){
				$type = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
				move_uploaded_file($_FILES['file']['tmp_name'],"uploads/".$_FILES['file']['name']);
					$i1 = $_FILES['file']['name'];
					$i2 = date("Y-M-d");
					$i3 = filesize($filename) . ' bytes';
					$i4 = $type;
					// előrébb tesztelve		
					$i13 = "";
					$i14 = $_SESSION["user"];
					$i15 = test($_POST['comment']);
					$conn = new mysqli('szolgaltato','felh','jelszo','adatbazis');
					$conn -> query("INSERT INTO doc (ID, fnev, date, size, type, kancellar, igazgato, titkar, gr, hr, jogtanacsos, tanugy, kozos, opt1, feltolto, comment, vezetoseg, informatika, project) 
									VALUES(null,'".$i1."','".$i2."','".$i3."','".$i4."','".$i5."','".$i6."','".$i7."','".$i8."','".$i9."','".$i10."','".$i11."','".$i12."','".$i13."','".$i14."','".$i15."','".$i16."','".$i17."','".$i18."')");
					$conn->close();
					$werr = 0;
					$_SESSION['accept'] = 1;
			}else{
					$feltoltszoveg="NEM VÁLASZTOTTÁL KI FÁJLT VAGY JOGOSULTAT!";
				 }	
		}else{
			if($_SESSION["err"] == 0){
				//$fileext = $_SESSION["formatum"];
				$feltoltszoveg="A FÁJL FORMÁTUMA NEM MEGFELELŐ!";
			}
		}
	}
	
?>

<!DOCTYPE_html>
<html lang="hu">
<head>
	<meta charset="UTF_8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/styleiktat.css">
	
</head>
<body>
<?php
	$conn = new mysqli('szolgaltato','felh','jelszo','adatbazis');
	$bcg = $conn -> query("SELECT * FROM felhasznalo WHERE (((user = '".$_SESSION["user"]."')))");
	$bcguser = $bcg -> fetch_all();
	$conn->close();
?>
<style type="text/css">
body {
	height: 100%;
	background-image: url(img/bgmain.jpg);
	background-repeat: no-repeat;
	background-size: cover;
	background-attachment: fixed;
}
</style>


	<form class="upload" name="myForm" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" enctype="multipart/form-data">	
	<div class="container-fluid">
	<div class="doboz">
		<?php if($_SESSION['accept'] == 1){?>
		<div class="row">
			<div class = "col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 justify-content-center">
				<img class = "acckep" src = "../img/accept.jpg" alt="saved">
			</div>
		</div>		
		<?php $_SESSION['accept'] = 0; } ?>
		<div class="row">
			<div class = "col-sm-12 col-md-12 col-lg-12 col-xl-12">
				<div class="jogosultalert0" style="margin-bottom: 10px;">Fájl feltöltő modul</div>
			</div>
		</div>	
		<div class="row">
			<div class = "col-sm-12 col-md-12 col-lg-6 col-xl-7">
				<div class="container-fluid">
					<div class="row">
						<div class = "col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<input style="float: left;" type="file" style="font-size: 1.4vw; " name="file">
						</div>
						<div class = "col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<div class="form-group">
								<label for="exampleFormControlTextarea1"></label>
								<textarea class="form-control" style="margin-top: 15px;" id="exampleFormControlTextarea1" rows="3" name = "comment" placeholder="Megjegyzés"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class = "col-sm-12 col-md-12 col-lg-3 col-xl-1 col-xxl-1">
				<label><input class="inputcsoport" type="checkbox" name="lista[]" value="kancellar"><div class="inputtext"> Kancellár</div></label>
				<label><input class="inputcsoport" type="checkbox" name="lista[]" value="igazgato"><div class="inputtext"> Igazgató</div></label>
			    <label><input class="inputcsoport" type="checkbox" name="lista[]" value="titkar"><div class="inputtext"> Titkárság</div></label>
			   	<label><input class="inputcsoport" type="checkbox" name="lista[]" value="gr"><div class="inputtext"> GR</div></label>
			</div>
			<div class = "col-sm-12 col-md-12 col-lg-3 col-xl-1 col-xxl-1">
				<label><input class="inputcsoport" type="checkbox" name="lista[]" value="hr"><div class="inputtext"> HR</div></label>
				<label><input class="inputcsoport" type="checkbox" name="lista[]" value="jogtanacsos"><div class="inputtext"> Jogász</div></label>
				<label><input class="inputcsoport" type="checkbox" name="lista[]" value="tanugy"><div class="inputtext"> Tanügy</div></label>
				<label><input class="inputcsoport" type="checkbox" name="lista[]" value="kozos"><div class="inputtext"> Közös</div></label>
			</div>
			<div class = "col-sm-12 col-md-12 col-lg-3 col-xl-1 col-xxl-1">
				<label><input class="inputcsoport" type="checkbox" name="lista[]" value="vezetoseg"><div class="inputtext"> Vezetőség</div></label>
				<label><input class="inputcsoport" type="checkbox" name="lista[]" value="informatika"><div class="inputtext"> Informatika</div></label>
				<label><input class="inputcsoport" type="checkbox" name="lista[]" value="project"><div class="inputtext"> Projekt</div></label>
			</div>
			<div class = "col-sm-12 col-md-12 col-lg-6 col-xl-2">
				<input class="btnsexit" onclick="nosave()" type="submit" value="VISSZA!" name = "btn2">
				<input class="btns" onclick="yessave()" type="submit" value="Feltöltöm!" name = "btn3">
			</div>
		</div>
		<div class="row">
			<div class = "col-sm-12 col-md-12 col-lg-12 col-xl-12">
				<div style="color: red;">Legalább egy jogosult kiválasztása szükséges a fájl feltöltéshez !</div>
			</div>
		</div>
		<div class="row">
			<div class = "col-sm-12 col-md-12 col-lg-12 col-xl-12">
				<div class="jogosultalert0">Dokumentum mappák</div>
			</div>
		</div>		
		<div class="row">
			<div class = "col-sm-12 col-md-12 col-lg-12 col-xl-12">
				<div class ="jogosultalert2"><?php echo $ment;?></div>
				<div class ="jogosultalert1"><?php echo $feltoltszoveg;?></div>
			</div>
		</div>	
	</div>
	</div>
	</form>
	
	<form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
	<div class="container-fluid">
	<div class="doboz">
	</form>
		
		<div class="soremel"></div>
		<div class="row">
			<div class = "col-sm-12 col-md-12 col-lg-6 col-xl-2 d-flex justify-content-center">
						<?php
							$conn = new mysqli('szolgaltato','felh','jelszo','adatbazis');
							$result = $conn -> query("SELECT * FROM felhasznalo WHERE (((user = '".$_SESSION["user"]."')))");
							$table = $result -> fetch_all();
							$conn->close();
						?>
							<?php
							if($table[0][5] == 1){
								echo '<div class = "kepmappa1"><a href="mappakancellar.php"><img src="../img/mappakancellar.jpg" alt="irat" width="150" height="150"></a></div>';
							}else{
								echo '<img style="margin-bottom: 10px;" src="../img/mappa.jpg" alt="irat" width="150" height="150">';
							}
							?>
			</div>			
			<div class = "col-sm-12 col-md-12 col-lg-6 col-xl-2 d-flex justify-content-center">				
							<?php
							if($table[0][6] == 1){
								echo '<div class = "kepmappa2"><a href="mappaigazgato.php"><img src="../img/mappaigazgato.jpg" alt="irat" width="150" height="150"></a></div>';
							}else{
								echo '<img style="margin-bottom: 10px;" src="../img/mappa.jpg" alt="irat" width="150" height="150">';
							}
							?>
			</div>			
			<div class = "col-sm-12 col-md-12 col-lg-6 col-xl-2 d-flex justify-content-center">	
							<?php
							if($table[0][7] == 1){
								echo '<div class = "kepmappa3"><a href="mappatitkarsag.php"><img src="../img/mappatitkarsag.jpg" alt="irat" width="150" height="150"></a></div>';
							}else{
								echo '<img style="margin-bottom: 10px;" src="../img/mappa.jpg" alt="irat" width="150" height="150">';		
							}
							?>
			</div>			
			<div class = "col-sm-12 col-md-12 col-lg-6 col-xl-2 d-flex justify-content-center">	
							<?php
							if($table[0][8] == 1){
								echo '<div class = "kepmappa4"><a href="mappagr.php"><img src="../img/mappagh.jpg" alt="irat" width="150" height="150"></a></div>';
							}else{
								echo '<img style="margin-bottom: 10px;" src="../img/mappa.jpg" alt="irat" width="150" height="150">';
							}
							?>
			</div>		
			<div class = "col-sm-12 col-md-12 col-lg-6 col-xl-2 d-flex justify-content-center">	
							<?php
							if($table[0][9] == 1){
								echo '<div class = "kepmappa5"><a href="mappahr.php"><img src="../img/mappahr.jpg" alt="irat" width="150" height="150"></a></div>';
							}else{
								echo '<img style="margin-bottom: 10px;" src="../img/mappa.jpg" alt="irat" width="150" height="150">';
							}
							?>
			</div>			
			<div class = "col-sm-12 col-md-12 col-lg-6 col-xl-2 d-flex justify-content-center">	
							<?php
							if($table[0][10] == 1){
								echo '<div class = "kepmappa6"><a href="mappajog.php"><img src="../img/mappajog.jpg" alt="irat" width="150" height="150"></a></div>';
							}else{
								echo '<img style="margin-bottom: 10px;" src="../img/mappa.jpg" alt="irat" width="150" height="150">';
							}
							?>
			</div>			
			<div class = "col-sm-12 col-md-12 col-lg-6 col-xl-2 d-flex justify-content-center">	
							<?php
							if($table[0][11] == 1){
								echo '<div class = "kepmappa7"><a href="mappatanugy.php"><img src="../img/mappatanugy.jpg" alt="irat" width="150" height="150"></a></div>';
							}else{
								echo '<img style="margin-bottom: 10px;" src="../img/mappa.jpg" alt="irat" width="150" height="150">';
							}
							?>
			</div>			
			<div class = "col-sm-12 col-md-12 col-lg-6 col-xl-2 d-flex justify-content-center">	
							<?php
							if($table[0][12] == 1){
								echo '<div class = "kepmappa8"><a href="mappakozos.php"><img src="../img/mappakozos.jpg" alt="irat" width="150" height="150"></a></div>';
							}else{
								echo '<img style="margin-bottom: 10px;" src="../img/mappa.jpg" alt="irat" width="150" height="150">';
							}
							?>
			</div>		
			<div class = "col-sm-12 col-md-12 col-lg-6 col-xl-2 d-flex justify-content-center">	
							<?php
							if($table[0][19] == 1){
								echo '<div class = "kepmappa9"><a href="mappavezetoseg.php"><img src="../img/mappavezetoseg.jpg" alt="irat" width="150" height="150"></a></div>';
							}else{
								echo '<img style="margin-bottom: 10px;" src="../img/mappa.jpg" alt="irat" width="150" height="150">';
							}
							?>
			</div>			
			<div class = "col-sm-12 col-md-12 col-lg-6 col-xl-2 d-flex justify-content-center">	
							<?php
							if($table[0][20] == 1){
								echo '<div class = "kepmappa10"><a href="mappainformatika.php"><img src="../img/mappainformatika.jpg" alt="irat" width="150" height="150"></a></div>';
							}else{
								echo '<img style="margin-bottom: 10px;" src="../img/mappa.jpg" alt="irat" width="150" height="150">';
							}
							?>
			</div>			
			<div class = "col-sm-12 col-md-12 col-lg-6 col-xl-2 d-flex justify-content-center">	
							<?php
							if($table[0][21] == 1){
								echo '<div class = "kepmappa11"><a href="mappaprojekt.php"><img src="../img/mappaprojekt.jpg" alt="irat" width="150" height="150"></a></div>';
							}else{
								echo '<img style="margin-bottom: 10px;" src="../img/mappa.jpg" alt="irat" width="150" height="150">';
							}
							?>
			</div>			
			<div class = "col-sm-12 col-md-12 col-lg-6 col-xl-2 d-flex justify-content-center">	
				<img style="margin-bottom: 10px;" src="../img/mappa.jpg" alt="irat" width="150" height="150">
			</div>			
		</div>	
	
	
		<style tpye="text/css">
			#helptext{
				text-align: left;
				color: blue;
				font-size: 24px;
				padding-left: 10px;
			}
		</style>
		
		
		
	</div>
	</div>
	
	<div class="soremel"></div>
	
	<div class="container-fluid">
	<div class="doboz"  style="background: rgba(255,255,255,.8);">
		<div class="row">
			<div class = "col-sm-12 col-md-12 col-lg-2 col-xl-2">
				<img src="../img/help.jpg" alt="irat" width="200" height="150">
			</div>
			<div class = "col-sm-12 col-md-12 col-lg-10 col-xl-10">
				<div id = "helptext">
					<p>OLDAL SEGÉD</p>
					<p>Az első táblában fájlokat tudsz feltölteni a szerverre. (1) A fájl kiválasztása gomb megnyomásával tallózhatsz 
					a saját gépeden kiválasztva a feltölteni kívánt fájlt. Jelöld ki a fájlt majd nyomd meg a Megnyitás gombot.(2) Írhatsz a kiválasztott fájlt kísérő megjegyzést 
					a szövegboxba. (3) Ki kell 
					jelölnöd kinek legyen jogosultsága (melyik mappába kerüljön) letölteni a fájlt. Több személy is kijelölhető egyszerre. (4) Kattints a Feltöltöm gombra.</p>
					<p>A második táblában mappákat találsz, azok a mappák érhetőek el számodra, amelyek meg vannak nevezve. Kattints a 
					kívánt mappára és megnyílik a tartalma.</p>
				</div>
			</div>
		</div>
	</div>
	</div>
			
	<div class="soremel"></div>



<script>
	function nosave() {
	  var x = document.forms["myForm"]["file"].value;
	  var y = document.forms["myForm"]["comment"].value;
	  if (x != "" && y != "") {
		  alert(" A KITÖLTÖTT ŰRLAP UTÁN NEM NYOMTÁL 'FELTÖLTÖM!' GOMBOT! ");
	  }		
	}
</script>

</body>
</html>
<?php } ?>