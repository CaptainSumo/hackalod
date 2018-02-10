<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Overzicht van <?php echo($name) ?></title>
    <link rel="stylesheet" type="text/css" href="/assets/css/site.css">
		<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Oswald:500" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.1/default-skin/default-skin.min.css">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.1/photoswipe.min.css">
		<script src="/assets/js/jquery-3.3.1.min.js"></script>
		<script src="/assets/js/main.js"></script>
</head>
<body>

			<div class="header">
				<h1 class="artist-name"><?php echo(urldecode($name))?></h1>
				<div class="button-container">
						<a onclick="goBack()"><span class="goback button">< Go Back</span></a>
						<a href="/index.php" class="back-link"><span class="goback button">Home</span></a>
				</div>
			</div>

            <h1><?php echo($name) ?></h1>
            <img src="<?php echo($image_url); ?>"

	</div>


	</div>

</body>
</html>
