<html>
<head>
<title>March Madness</title>
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

.team64 { text-transform: uppercase; margin: 2px; background-color: #ffffff; width: 140px; border: 1px solid #666666; }
.team32 { text-transform: uppercase; margin: 2px; background-color: #ffffff; width: 140px; border: 1px solid #666666; }
.team16 { text-transform: uppercase; margin: 2px; background-color: #ffffff; width: 140px; border: 1px solid #666666; }
.team8 { text-transform: uppercase; margin: 2px; background-color: #ffffff; width: 140px; border: 1px solid #666666; }
.team4 { text-transform: uppercase; margin: 2px; background-color: #ffffff; width: 140px; border: 1px solid #666666; }
.team2 { text-transform: uppercase; margin: 2px; background-color: #ffffff; width: 140px; border: 1px solid #666666; }
.team1 { text-transform: uppercase; margin: 2px; background-color: #ffffff; width: 140px; border: 1px solid #666666; }

#brackets { display: none; }
#scoreTable { display: block; }
</style>
</head>
<body>

<?php
include("./teams.php");
include("./MarchMadness.class.php");
$mmObj = new MarchMadness();
if ($_GET['personId']) {
	print "<!-- $_GET['personId'] -->\n";
	print $mmObj->getcheerboard($_GET['personId']);
} else {
	print "Could not load your cheer board. Please close the window and try again.";
}
?>
<div><a href="javascript: window.close();">Close Window</a></div>
</body>
</html>
