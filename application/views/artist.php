<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Overzicht van <?php echo($name) ?></title>
    <link rel="stylesheet" type="text/css" href="/assets/css/site.css">
</head>
<body>

    <h1> <?php echo(urldecode($name))?></h1>
    <dl>
        <dt>RKD Artist Id</dt>
        <dd><?php echo(urldecode($rkdId))?></dd>
    </dl>
    <dl>
        <dt>RKD Artist Page</dt>
        <dd><a href="<?php echo($rkdUri);?>" target="_blank"><?php echo(urldecode($name))?></a></dd>
    </dl>





</body>
</html>
