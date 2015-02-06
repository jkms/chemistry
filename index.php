<?php

$latexbin = '/usr/local/texlive/2014/bin/x86_64-linux/latex';
$latexpath = "latex";
$imagepath = "images";
$ds = '/';

function randombond() {
	if (rand(0, 1)) {
		$string = "-";
	} else {
		$string = "=";
	}
	return $string;
}

function randomring($sides=5) {

	$string = "*$sides(";
	$randomline = rand(1,$sides-2);
	for ($x = 0; $x < $sides; $x++) {
		$string .= randombond();
		if ($x == $randomline) {
			$string .= randomline(rand(2,5));
		}
	}
	$string .= ')';
	return $string;
}

function randomline($length=3, $angle=30) {
	$string = "(";
	for ($x = 0; $x < $length; $x++) {
		if ($x % 2 == 0) {$modifier = "-";} else {$modifier = "+";}
		$string .= randombond() . "[::$modifier"."$angle]";
	}
	$string .= ")";
	return $string;
}

echo "<!DOCTYPE HTML>
<html>
	<head>
		<title>Chemical Stuff</title>
		<style>
			body {
				margin: 0px;
				padding: 0px;
			}
		</style>
	</head>
	<body>";


echo "Let's draw some chemicals:";
echo "<br>";

if (isset($_POST['chemical']) and isset($_POST['submit'])) {
        $chemical=$_POST['chemical'];
} else {
        $chemical='-[:30]-[:-30]-[:30]';
}

if (isset($_POST['random_ring'])) {
	$chemical=randomring(rand(3,8));
}



$latex = "\documentclass[crop]{standalone}
\\usepackage{chemfig}
\\begin{document}
% Content here

\\chemfig{ $chemical }

\\end{document}

";

$fileout = $latexpath ."$ds"."temp.tex";
file_put_contents($fileout, $latex);

exec("$latexbin --output-directory=$latexpath"."$ds $fileout");
exec("convert $latexpath"."$ds"."temp.dvi $imagepath"."$ds"."temp.png");
echo "<img src=$imagepath/temp.png>";

echo "\n
<form action=\"index.php\" method=\"post\">
	Give me some input:
	<input type=\"time\" name=\"chemical\" value=\"$chemical\">
	<input id=\"submit\" name=\"submit\" type=\"submit\" value=\"Submit\"><br>
	$randomtext<br>
	<input id=\"random_ring\" name=\"random_ring\" type=\"submit\" value=\"random_ring\">
</form>

<br>
<br>
Documentation is <a href=\"http://mirrors.ibiblio.org/CTAN/macros/latex/contrib/chemfig/chemfig_doc_en.pdf\">here</a>
<br><br>
Example 1: <b>J-[:-30]O-[:30]H-[:-30]N-[:30]*6(-R=U-L=E-S=)</b>
<br><br>
Example 2: <b>*6((=O)-N(-CH_3)-*5(-N=-N(-CH_3)-=)--(=O)-N(-H_3C)-)</b>
<br><br>
Example 3: <b>*6(-(<[::-120]H)(*6(-(-[::-20]H_3C)(-[::-70]H_3C)-O-(*6(-=(--[:30]-[:-30]-[:30]-[:-30]CH_3)-=(-OH)-=))--(<:[::-120]H)-))--=(-CH_3)--)</b>
<br><br>


	</body>
</html>";

?>
