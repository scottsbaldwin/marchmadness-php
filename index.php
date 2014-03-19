<?php
session_cache_limiter("none");
session_name("marchmadness");
session_start();
//session_register("mm_auth","mm_personId");

require_once("db.php");

//$db = mysql_connect("db387.perfora.net", "dbo165651394", "s36CuXnh");
//mysql_select_db("db165651394",$db);

//$db = mysql_connect("localhost", "root", "root");
//mysql_select_db("ssb",$db);

$dbconfig = $GLOBALS["dbconfig"];
$db = mysql_connect($dbconfig["host"], $dbconfig["username"], $dbconfig["password"]);
mysql_select_db($dbconfig["dbname"],$db);

// perform the login process
if ($_POST['login'] && $_POST['loginPersonId'] && $_POST['loginBirthDay']) {
	$loginPersonId = $_POST['loginPersonId'];
	$loginBirthDay = $_POST['loginBirthDay'];

	$sql = "select * from familymember where personId = $loginPersonId and birthDate = '$loginBirthDay'";
	$result = mysql_query($sql);
	if ($result && mysql_num_rows($result) == 1) {
		$auth = 1;
		$_SESSION['mm_auth'] = 1; // this tells the session that we have a valid login
		$_SESSION['mm_personId'] = $loginPersonId; // this is the current logged in person
	} else {
		// Person not found!
		$auth = 0;
		$_SESSION['mm_auth'] = 0;
		$_SESSION['mm_personId'] = 0;
		$authMessage = "Your birthday was incorrect. Please try again.";
	}
} else {
	$auth = $_SESSION['mm_auth'];
}


if ($_POST['logout'] || $_GET['logout']) {
  session_destroy();
  $auth = 0;
  Header("Location: /marchmadness");
}

function getNameOptions($selection = "") {
	$sql = "
	SELECT
	f.personId,
	case when prefersMiddleName = 1 then middleName else firstName end as firstName,
	lastName,
	birthDate
	FROM familymember f join familywithmember fwm on f.personId=fwm.personId and fwm.familyId=2
	where birthDate > '1940-01-01' 
	order by birthDate asc
	";

	$result = mysql_query($sql);
	$options = "";
	while ($result && $myrow = mysql_fetch_array($result)) {
		$sel = ($selection && $selection == $myrow["personId"]) ? " selected" : "";
		$options .= sprintf("<option value=\"%s\"%s>%s</option>\n", $myrow["personId"], $sel, $myrow["firstName"]);
	}
	return $options;
}

function showLogin($errMsg = "") { 
	include("./login.php");
}

function showBrackets() { 
	include("./brackets-new.php");
}

?>
<html>
<head>
<title>March Madness</title>
<link type="text/css" href="css/start/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js"></script>

<style>
body, td { font-family: arial; font-size: 9px; }
#loginBody { font-family: arial; font-size: 12px; }
td.scoreCell { font-size: 10px; background-color: white; }
td.scoreCellAlt { font-size: 10px; background-color: #efefef; }
td.header { font-size: 11px; font-weight: bold; text-align: left; background-color: #dddddd; }
a { color: #990000; text-decoration: none; }
a:hover { color: #990000; text-decoration: underline; }

.espnLink { text-transform: none; text-decoration: underline; color: #45577a; }
.espnLink:hover { text-transform: none; text-decoration: underline; color: #45577a;background-color: #eeeeee; }

input, select { font-family: Arial; font-size: 12px; }
.error { color: red; }
.label { width: 75px; }
.submitBtn { border: 1px solid #45577a; background-color: #efefef; }

.team64,.team32,.team16,.team8,.team4,.team2,.team1 { text-transform: uppercase; margin: 2px; background-color: #ffffff; width: 140px; border: 1px solid #666666; }

#brackets { display: none; }
#scoreTable { display: block; }
#trashTalk { font-size: 10pt; }

.scoreBoard { border: 1px solid black; margin-top: 5px; margin-bottom: 5px; }
ul#icons {margin: 0; padding: 0;}
ul#icons li {margin: 2px; position: relative; padding: 4px 0; cursor: pointer; float: left; list-style: none;}
ul#icons a.ui-icon {float: left; margin: 0 4px;}

.gameWinner { background-color:#ACDD4A; }
.gameLoser { background-color:pink; }
.gameNotPlayed { background-color:#eeeeee; }
</style>
</head>
<body>
<?php
// if there is not a valid login, show the login page
if (!$_SESSION['mm_auth']) {
	showLogin($authMessage);
} else {
	showBrackets();
}
?>
</body>
</html>
