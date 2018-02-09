<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>
		<script src="./assets/js/d3.js" charset="utf-8"></script>
		<script src="./assets/js/d3.layout.cloud.js"></script>
		<script src="./assets/js/d3.wordcloud.js"></script>
		<script src="./assets/js/example.words.js"></script>
    <link rel="stylesheet" type="text/css" href="./assets/css/site.css">
</head>
<body>

<div id="container">

	<div id='wordcloud'></div>

	<script>
		d3.wordcloud()
			.size([800, 500])
			.fill(d3.scale.ordinal().range(["#884400", "#448800", "#888800", "#444400"]))
			.words(words)
			.onwordclick(function(d, i) {
				if (d.href) { window.location = d.href; }
			})
			.start();
	</script>

	<h1>Welcome to CodeIgniter!</h1>

	<div id="body">
		<p>The page you are looking at is being generated dynamically by CodeIgniter.</p>

		<p>If you would like to edit this page you'll find it located at:</p>
		<code>application/views/welcome_message.php</code>

		<p>The corresponding controller for this page is found at:</p>
		<code>application/controllers/Welcome.php</code>

		<p>If you are exploring CodeIgniter for the very first time, you should start by reading the <a href="user_guide/">User Guide</a>.</p>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>

</body>
</html>
