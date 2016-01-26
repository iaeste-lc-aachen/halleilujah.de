<?php

/*!
 * Copyright © 2016 Rainer Rillke <rillke@wikipedia.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 */

if ( ! isset( $_REQUEST['torch'] ) ) {
	ob_end_clean();
	header( 'HTTP/1.0 404 Not Found' );
	echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL /registrations.php was not found on this server.</p>
<p>Additionally, a 404 Not Found
error was encountered while trying to use an ErrorDocument to handle the request.</p>
</body></html>
';
	exit();
}

require_once 'FormDefinition.php';
require_once 'EventPDO.php';

header( 'Content-Type: text/html; charset=utf-8' );
header( 'x-content-type-options: nosniff' );
header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
header( 'Pragma: no-cache' ); // HTTP 1.0
?>
<!DOCTYPE HTML>
<!--
	Twenty by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">

<title>Registrations - HalLeiLujah - IAESTE Halle Leipzig Weekend</title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="generator"
	content="Twenty by HTML5 UP | html5up.net | @n33co | Creative Commons Attribution 3.0 Unported | http://creativecommons.org/licenses/by/3.0/" />
<meta name="author" content="IAESTE LC Halle, IAESTE Team Leipzig" />
<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
<link rel="shortcut icon" href="favicon.ico" />
<link rel="stylesheet" href="assets/css/main.css" />
<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
<style>
th {
	font-weight: bold;
}

tr {
	border-bottom: 3px dashed #DDD;
}
</style>
</head>

<body class="index">
	<div id="page-wrapper">

		<!-- Header -->
		<header id="header">
			<h1 id="logo">
				<a href="./">HalLeiLujah</a> <small><span>by <a
						href="http://iaeste-halle.de">LC Halle</a>, Team Leipzig
				</span></small>
			</h1>
			<nav id="nav">
				<ul>
					<li class="current"><a href="./">welcome</a></li>
					<li class="submenu"><a href="#">jump to</a>
						<ul>
							<li><a href="./#what">what</a></li>
							<li><a href="./#when">when</a></li>
							<li><a href="./#where">where</a></li>
							<li><a href="./#whatsinside">how much &amp; what's inside</a></li>
							<li><a href="./#programme">programme</a></li>
							<li><a href="./#curriculum">curriculum</a></li>
							<li><a href="./#disclaimer">summary &amp; disclaimer</a></li>
						</ul></li>
					<li><a href="impressum.html" class="button">contact &amp; imprint</a></li>
				</ul>
			</nav>
		</header>

		<!-- Main -->
		<article id="main">

			<header class="special container">
				<span class="icon fa-table"></span>
				<h2>Registrations for HalLeiLujah</h2>
			</header>
<?php

class Registrations {

	private $registrationForm;

	public function __construct() {
		$this->registrationForm = FormDefinition::get();
	}

	public function dispatchRequest() {
		require_once 'EventConfig.php';

		if ( ! isset( $_REQUEST['torch'] ) ) {
			ob_end_clean();
			header( 'HTTP/1.0 404 Not Found' );
			exit();
		}
		try {
			$pdo = new EventPDO();
		} catch ( PDOException $e ) {
			echo 'Bummer! Our database is currently unavailable. Please try again later.';
			return;
		}
		$ep = EventConfig::getInstance();
		if ( isset( $_POST['pass'] ) && isset( $_POST['user'] ) &&
			 ( sha1( $_POST['pass'] ) === $ep->getAccessPass() ) &&
			 ( $_POST['user'] === $ep->getAccessUser() ) ) {
			$this->showData( $pdo );
			return;
		}
		$this->displayForm( $pdo );
	}

	private function displayForm( $pdo ) {
		echo '<section class="wrapper style4 special container"><div class="content"><form method="post"><input type="hidden" name="torch" value="1" /><input type="text" name="user" placeholder="user name" /><input name="pass" placeholder="password" type="password" /><button class="button">Some confusing button text</button></form>';
	}

	private function showData( $dbo ) {
		echo '<section class="wrapper style4 special"><div class="content"><table><tr>';
		$cols = 'id,firstname,lastname,gender,lc,homecountry,email,mobile,food,payment,comment';
		echo '<th>' . implode( '</th><th>', explode( ',', $cols ) ) . '</th>';
		echo '</tr>';

		// $pdo->exec('SET NAMES utf8');
		$sth = $dbo->prepare( 'SELECT ' . $cols . ' FROM registrations;' );
		$sth->execute();
		$result = $sth->fetchAll();
		foreach ( $result as $record ) {
			echo '<tr>';
			foreach ( explode( ',', $cols ) as $col ) {
				switch ( $col ) {
					case 'email' :
						echo '<td><a href="mailto:' .
							 htmlspecialchars( $record[$col], ENT_HTML5 ) . '">' .
							 htmlspecialchars( $record[$col], ENT_HTML5 ) .
							 '</a></td>';
						break;
					case 'mobile' :
						echo '<td><a href="tel:' .
							 htmlspecialchars( $record[$col], ENT_HTML5 ) . '">' .
							 htmlspecialchars( $record[$col], ENT_HTML5 ) .
							 '</a></td>';
						break;
					case 'gender' :
						switch ( $record[$col] ) {
							case 'female' :
								echo '<td><i class="fa fa-venus"></i></td>';
								break;
							case 'male' :
								echo '<td><i class="fa fa-mars"></i></td>';
								break;
							default :
								echo '<td><i class="fa fa-neuter"></i></td>';
						}
						break;
					default :
						echo '<td>' .
							 htmlspecialchars( $record[$col], ENT_HTML5 ) .
							 '</td>';
				}
			}
			echo '</tr>';
		}
		echo '</table>';
	}
}

( new Registrations() )->dispatchRequest();

?>

	</article>

		<!-- Footer -->
		<footer id="footer">

			<ul class="icons">
				<li><a
					href="https://twitter.com/share?via=IAESTE%20Germany&amp;text=IAESTE%20HalLeiLujah%20Weekend&amp;url=https%3A%2F%2Fiaeste.halle.rocks"
					target="_blank" title="Tweet about this weekend"
					class="icon circle fa-twitter"><span class="label">Twitter</span></a></li>
				<li><a
					href="https://www.facebook.com/sharer.php?u=https%3A%2F%2Fiaeste.halle.rocks&amp;t=IAESTE%20HalLeiLujah%20Weekend"
					target="_blank" title="Share on Facebook"
					class="icon circle fa-facebook"><span class="label">Facebook</span></a></li>
				<li><a
					href="https://plus.google.com/share?url=https%3A%2F%2Fiaeste.halle.rocks"
					target="_blank" title="Share on Google+"
					class="icon circle fa-google-plus"><span class="label">Google+</span></a></li>
			</ul>

			<ul class="copyright">
				<li><a href="datenschutz.html">Datenschutzerklärung</a></li>
				<li>Design: <a href="http://html5up.net">HTML5 UP</a>, <a href="http://creativecommons.org/licenses/by/3.0/">CC-By-SA</a></li>
			</ul>

		</footer>

	</div>

	<!-- Scripts -->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/jquery.dropotron.min.js"></script>
	<script src="assets/js/jquery.scrolly.min.js"></script>
	<script src="assets/js/jquery.scrollgress.min.js"></script>
	<script src="assets/js/skel.min.js"></script>
	<script src="assets/js/util.js"></script>
	<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
	<script src="assets/js/main.js"></script>
</body>

</html>
