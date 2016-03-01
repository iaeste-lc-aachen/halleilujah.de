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
ob_start();

require_once 'Registration.php';

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

<title>Registration - HalLeiLujah - IAESTE Halle Leipzig Weekend</title>
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
.label {
	display: inline;
	padding: .2em .6em .3em;
	font-size: 75%;
	font-weight: bold;
	line-height: 1;
	color: #fff;
	text-align: center;
	white-space: nowrap;
	vertical-align: baseline;
}

a.label:hover, a.label:focus {
	color: #fff;
	text-decoration: none;
	cursor: pointer;
}

.label:empty {
	display: none;
}

.btn .label {
	position: relative;
	top: -1px;
}

.label-default {
	background-color: #83d3c9;
}

.label-default[href]:hover, .label-default[href]:focus {
	background-color: #5e5e5e;
}

.label-primary {
	background-color: #337ab7;
}

.label-primary[href]:hover, .label-primary[href]:focus {
	background-color: #286090;
}

.label-success {
	background-color: #5cb85c;
}

.label-success[href]:hover, .label-success[href]:focus {
	background-color: #449d44;
}

.label-info {
	background-color: #83d3c9;
}

.label-info[href]:hover, .label-info[href]:focus {
	background-color: #31b0d5;
}

.label-warning {
	background-color: #f0ad4e;
}

.label-warning[href]:hover, .label-warning[href]:focus {
	background-color: #ec971f;
}

.label-danger {
	background-color: #d9534f;
}

.label-danger[href]:hover, .label-danger[href]:focus {
	background-color: #c9302c;
}

.badge {
	display: inline-block;
	min-width: 10px;
	padding: 3px 7px;
	font-size: 12px;
	font-weight: bold;
	line-height: 1;
	color: #fff;
	text-align: center;
	white-space: nowrap;
	vertical-align: middle;
	background-color: #777;
	border-radius: 10px;
}

.badge:empty {
	display: none;
}

.btn .badge {
	position: relative;
	top: -1px;
}

.btn-xs .badge, .btn-group-xs>.btn .badge {
	top: 0;
	padding: 1px 5px;
}

a.badge:hover, a.badge:focus {
	color: #fff;
	text-decoration: none;
	cursor: pointer;
}

.list-group-item.active>.badge, .nav-pills>.active>a>.badge {
	color: #337ab7;
	background-color: #fff;
}

.list-group-item>.badge {
	float: right;
}

.list-group-item>.badge+.badge {
	margin-right: 5px;
}

.nav-pills>li>a>.badge {
	margin-left: 3px;
}

.bootstrap-tagsinput {
	background-color: #fff;
	border: 1px solid #ccc;
	padding: 0.75em;
	margin-bottom: 10px;
	color: #555;
	vertical-align: middle;
	max-width: 100%;
	line-height: 22px;
	cursor: text;
	text-align: left;
}

.bootstrap-tagsinput input {
	border: none;
	box-shadow: none;
	outline: none;
	background-color: transparent;
	padding: 0;
	margin: 0;
	width: auto !important;
	max-width: inherit;
	line-height: 2em;
}

.bootstrap-tagsinput input:focus {
	border: none;
	box-shadow: none;
}

.bootstrap-tagsinput .tag {
	margin-right: 2px;
	color: white;
}

.bootstrap-tagsinput .tag [data-role="remove"] {
	margin-left: 8px;
	cursor: pointer;
}

.bootstrap-tagsinput .tag [data-role="remove"]:after {
	content: "x";
	padding: 0px 2px;
}

.bootstrap-tagsinput .tag [data-role="remove"]:hover {
	box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px
		rgba(0, 0, 0, 0.05);
}

.bootstrap-tagsinput .tag [data-role="remove"]:hover:active {
	box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
}

.bootstrap-tagsinput input, .bootstrap-tagsinput label {
	display: inline-block;
	line-height: 1em;
}

.typeahead-wrapper {
	display: block;
	margin: 50px 0;
}

.tt-menu {
	background-color: #fff;
	border: 1px solid #000;
	text-align: left;
}

.tt-suggestion.tt-cursor {
	background-color: #ccc;
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
				<span class="icon fa-pencil-square-o"></span>
				<h2>Registration for HalLeiLujah</h2>
			</header>

			<!-- One -->
			<section class="wrapper style4 special container 75%">

				<!-- Content -->
				<div class="content">
<?php

( new Registration() )->dispatchRequest();

?>
	</div>

			</section>

			<header id="contact" class="special container">
				<span class="icon fa-envelope"></span>
				<h2>Get In Touch</h2>
				<p>
					e-Mail us: <a
						href="mailto:iaeste@uni-halle.de?subject=halleiluja%20contactform">iaeste@uni-halle.de</a>
					or use the <a href="impressum.html#contact">contact form</a>
				</p>
			</header>

		</article>

		<!-- Footer -->
		<footer id="footer">

			<ul class="icons">
				<li><a
					href="https://twitter.com/share?via=IAESTE%20Germany&amp;text=IAESTE%20HalLeiLujah%20Weekend&amp;url=https%3A%2F%2Fwww.halleilujah.de"
					target="_blank" title="Tweet about this weekend"
					class="icon circle fa-twitter"><span class="label">Twitter</span></a></li>
				<li><a
					href="https://www.facebook.com/sharer.php?u=https%3A%2F%2Fwww.halleilujah.de&amp;t=IAESTE%20HalLeiLujah%20Weekend"
					target="_blank" title="Share on Facebook"
					class="icon circle fa-facebook"><span class="label">Facebook</span></a></li>
				<li><a
					href="https://plus.google.com/share?url=https%3A%2F%2Fwww.halleilujah.de"
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
	<script>
$('form').submit(function(e) {
	var msg = '';
	$(this).find('input').each(function(i, el) {
		var $el = $(el);
		if (!$el.attr('required')) return;
		switch ($el.attr('type')) {
			case 'email':
			case 'text':
				if ($el.val().length < 1) {
					msg += 'Missing: ' + $el.attr('name') + ' ';
				}
				break;
			case 'checkbox':
				if (!$el[0].checked) {
					msg += 'Must be checked: ' + $el.attr('name') + ' ';
				}
				break;
		}
	});
	if (msg) {
		alert( 'Some data are missing. Please have a look over the form again and add missing data:\n' + msg );
		e.preventDefault();
	}
});
	</script>
	<script src="assets/js/jquery.dropotron.min.js"></script>
	<script src="assets/js/jquery.scrolly.min.js"></script>
	<script src="assets/js/jquery.scrollgress.min.js"></script>
	<script src="assets/js/skel.min.js"></script>
	<script src="assets/js/util.js"></script>
	<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
	<script src="assets/js/main.js"></script>
	<script src="assets/js/typeahead.bundle.min.js"></script>
	<script src="assets/js/bootstrap-tagsinput.min.js"></script>
	<script>
$('.tagsinput').each(function() {
	var $i = $(this);
	var d = $i.data('tags').split(',');
	d = $.map(d, function(val) {
		return { name: val };
	});
	var food = new Bloodhound({
	  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
	  queryTokenizer: Bloodhound.tokenizers.whitespace,
	  local: d
	});
	food.initialize();

	$i.tagsinput({
	  typeaheadjs: {
		 name: 'food',
		 displayKey: 'name',
		 valueKey: 'name',
		 source: food.ttAdapter()
	  }
	});
});
	</script>
</body>

</html>
