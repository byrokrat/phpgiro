<?php
/**
 * View autogiro files in browser
 * @package itbz\phpautogiro
 */


/**
 * Autoload classes
 * @param string $class
 * @return void
 * @ignore
 */
function __autoload($class){
	$fname = str_replace('_', DIRECTORY_SEPARATOR, $class);
	$fname .= '.php';
	if ( is_readable($fname) ) {
		include $fname;
	}
}

/*
    ok, många grejer är bull här
    
    det första är att inga PhpGiro klasser borde läsa från filsystemet
        de borde bara ta en sträng som den är!
*/

$factory = new PhpGiro_Factory();


if ( !empty($_FILES) and isset($_FILES['file']['tmp_name']) and is_file($_FILES['file']['tmp_name']) ) {
	$a = $factory->parse($_FILES['file']['tmp_name']);
} else {
	$a = false;
}

?>
<!DOCTYPE html>
<html lang="sv">
<head>
<meta charset="utf-8">
<title>PhpGiro: AG viewer</title>
<style>
#wrapp {
	width: 600px;
}
#foot {
	margin:20px;
	text-align:center;
	font: normal 10pt Helvetica, sans-serif;
	color:#777;
}
h1 {
	font:bold 34pt Helvetica, sans-serif;
	text-align:center;
}
h2 {
	font-family: Helvetica, sans-serif;
}
h3 {
	margin-top:40px;
}
hr {
	border:0;
	border-top: solid 1px #888;
	margin:20px 0 20px 0;
}
.post, .error {
	margin: 20px 0 20px 0;
	padding: 10px;
	background-color:#eee;
}
span {
	margin-bottom:3px;
	display:block;
}

.sub {border-left: solid 2px #777;padding-left:10px;margin:10px 0 10px 10px;}

.name, .statusMsg, .periodMsg, .sourceMsg, .refCodeDesc, .channelDesc {
	font-style: italic;
}

.ref {
	font-weight: bold;
}

.amount, .betNr {
	text-decoration:underline;
}

.amount:after {
	content:" kr";
}
</style>
</head>

<body>
<div id="wrapp">

<h1>BANK viewer</h1>
<hr>
<form enctype="multipart/form-data" action="." method="POST">
	<input name="file" type="file">
	<input type="submit" value="Analyze">
</form>
<hr>
<?php if ( $a ) { ?>

<h2><?php echo $_FILES['file']['name'];?></h2>

<?php if ( $a->hasError() ) {
	echo "<pre>";	
	foreach ( $a->getErrors() as $err ) {
		echo htmlspecialchars($err)."\n";
	}
	echo "</pre>";
} ?>

<?php
	while ( $s = $a->getSection() ) {
		@print("<h3>Layout: ".$s['layout']." ".$s['layoutName']."</h3>");

		foreach ( $s as $key => $row ) {
			if ( is_array($row) ) continue;
			if ( $key == "layout" ) continue;
			if ( $key == "layoutName" ) continue;
			if ( $key == "errors" ) continue;
			if ( $key == "posts" ) continue;
			echo "<p>$key: $row</p>";
		}

		if ( isset($s['errors']) and is_array($s['errors']) ) {
			echo "<h4>Errors:</h4>";
			foreach ( $s['errors'] as $err ) {
				echo '<div class="error">';
				if ( is_array($err) ) {
					foreach ( $err as $key => $val ) {
						$val = htmlspecialchars($val);
						echo '<span class="'.$key.'">'.$key.': '.$val.'</span>';
					}
				} else {
					echo '<span>'.htmlspecialchars($err).'</span>';
				}
				echo"</div>";
			}
		}

		if ( isset($s['posts']) and is_array($s['posts']) ) {
			echo "<h4>Posts:</h4>";
			foreach ( $s['posts'] as $post ) {
				echo '<div class="post">';
				foreach ( $post as $key => $val ) {
					if ( is_array($val) ) {
						echo '<div class="sub">';
						echo "<b>$key: </b><br/>";	
						foreach ( $val as $sKey => $sVal ) {
							if ( is_array($sVal) ) {
								echo '<div class="sub">';
								foreach ( $sVal as $ssKey => $ssVal ) {
									echo '<span class="'.$ssKey.'">'.$ssKey.': '.$ssVal.'</span>';
								}
								echo "</div>";
							} else {
								echo '<span>'.$sVal.'</span>';
							}
						}
						echo '</div>';
					} else {
						echo '<span class="'.$key.'">'.$key.': '.$val.'</span>';
					}
				}
				echo"</div>";
			}
		}
	}
?>

<?php } ?>

<hr>

<div id="foot">
Powered by: <img src="phpgiro.png" height="25" align="center">
</div>
</div>
</body>
</html>
