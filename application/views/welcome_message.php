<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Kunststromingen</title>
		<script src="./assets/js/d3.js" charset="utf-8"></script>
		<script src="./assets/js/d3.layout.cloud.js"></script>
		<script src="./assets/js/d3.wordcloud.js"></script>
		<script src="./assets/js/main.js"></script>
    <script src="./index.php/data/kunststroming"></script>
    <link rel="stylesheet" type="text/css" href="./assets/css/site.css">
		<link href="https://fonts.googleapis.com/css?family=Oswald:500" rel="stylesheet">
</head>
<body>

	<div class="header">

		<form method="get" id="search" action="/">
		    <fieldset>
		        <legend class="visually-hidden">Zoeken door alles</legend>
		        <input name="trefwoord" title="Zoeken" type="text" class="search-input" placeholder="Zoeken door alle kunststromingen">
						<button type="button" name="button"></button>
			  </fieldset>
		</form>

	</div>

	<div id='wordcloud'></div>

	<script>
		d3.wordcloud()
			.size([1500, 1500])
			.fill(d3.scale.ordinal().range(["#B9CA64", "#DB7681", "#BF313D", "#E0D18A", "#4D4D4D"]))
			.words(words)
			.onwordclick(function(d, i) {
				if (d.href) { window.location = d.href; }
			})
			.start();
	</script>


</body>
</html>
