<?php
error_reporting(-1);
ini_set('display_errors', 'On');

define( 'ROOT', __DIR__ );

require_once 'app/class-api.php';
require_once 'app/class-lala.php';


if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	$data = array();

	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);

	$api = new Ai_Api( $_GET, $data );
	echo $api->api();

	die();
}
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Lala CLasifier -- A.I. Assisted Classification</title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="build/css/lala.bundle.css">
</head>
<body>
<header class="navbar bg-primary-dark text-light">
	<section class="navbar-section">
		<a href="#" class="btn btn-link"><i class="fa fa-book" aria-hidden="true"></i> Docs</a>
		<a href="#" class="btn btn-link"><i class="fa fa-github" aria-hidden="true"></i> GitHub</a>
	</section>
	<section class="navbar-center m-2">
		<b class="navbar-brand logo-text m-2">A.I. Assisted</b>
		<i class="fa fa-3x fa-eercast" aria-hidden="true"></i>
		<b class="navbar-brand logo-text m-2">Classification</b>
	</section>
	<section class="navbar-section">
		<a href="#" class="btn btn-link"><i class="fa fa-connectdevelop" aria-hidden="true"></i> Train</a>
		<a href="#" class="btn btn-link"><i class="fa fa-eercast" aria-hidden="true"></i> Predict</a>
	</section>
</header>
<div id="lala_app"><div class="container">
		<div class="columns">
			<div class="column parallax col-3 centered">
				<div class="parallax-top-left"></div>
				<div class="parallax-top-right"></div>
				<div class="parallax-bottom-left"></div>
				<div class="parallax-bottom-right"></div>
				<div class="parallax-content">
					<div class="parallax-front centered">
						<h1 class="text-primary text-center text-comfortaa text-lala" style="position: absolute; display: block; width: 100%;"><b>Lala</b><span class="text-light">Clasifier</span></h1>
					</div>
					<div class="parallax-back">
						<img src="assets/lala_clasifier.png" width="250" class="img-responsive rounded">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="bg-primary-dark">
		<div class="container grid-lg">
			<div class="columns">
				<div class="column col-12">
					<h2 class="text-comfortaa text-center text-light p-2"><i class="fa fa-random" aria-hidden="true"></i> Random Feedback</h2>
				</div>
				<div class="column col-12">
					<live-clasifier></live-clasifier>
				</div>
				<div class="column col-12">
					<p class="text-comfortaa text-left text-justify text-light p-2">
						<small>
							<i class="fa fa-info-circle" aria-hidden="true"></i> <b>Notes:</b>
							<i>You are presented with a random feedback item, you can rate it, and help Lala do better classifications.</i>
						</small>
					</p>
				</div>
				<div class="column col-12">
					<predict-clasifier></predict-clasifier>
				</div>
				<div class="column col-12">
					<p class="text-comfortaa text-left text-justify text-light p-2">
						<small>
							<i class="fa fa-info-circle" aria-hidden="true"></i> <b>Notes:</b>
							<i>For the presented random feedback you can ask Lala to predict a label.</i>
						</small>
					</p>
				</div>
			</div>
		</div>
	</div>
	<div class="bg-gray">
		<div class="container grid-lg">
			<train-clasifier></train-clasifier>
		</div>
	</div>
</div>
<div class="bg-primary-dark text-light">
	<div class="container" style="margin-top: 25px;">
		<div class="columns">
			<div class="column col-12 m-2 p-2 text-center">
				<b class="text-comfortaa">Powered by: </b>
				<img src="assets/tehnology.png" style="display: inline-block;">
				<small class="text-comfortaa">Author @ Bogdan Preda</small>
			</div>
		</div>
	</div>
</div>
<script type="application/javascript" src="build/js/lala.bundle.js"></script>
</body>
</html>

