<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo($name);?></title>
        <script src="/assets/js/jquery-3.3.1.js" charset="utf-8"></script>
		<script src="/assets/js/d3.js" charset="utf-8"></script>
		<script src="/assets/js/d3.layout.cloud.js"></script>
		<script src="/assets/js/d3.wordcloud.js"></script>
		<script src="/assets/js/main.js"></script>
    <script src="/index.php/data/kunstenaars/<?php echo($code.'/'.$name);?>"></script>
    <link rel="stylesheet" type="text/css" href="/assets/css/site.css">
		<link href="https://fonts.googleapis.com/css?family=Oswald:500" rel="stylesheet">
</head>
<body>

	<div class="header">
		<fieldset>
        <legend class="visually-hidden">Zoeken door alles</legend>
        <input  name="trefwoord" id="trefwoord" title="Zoeken" type="text" class="search-input" placeholder="Zoeken door alle kunststromingen">
				<button type="button" name="button" class="search-button button" id="searchbtn"></button>
	  </fieldset>
    <span id="matches"></span>

		<div class="button-container">
				<a onclick="goBack()"><span class="goback button">< Go Back</span></a>
				<a href="/index.php" class="back-link"><span class="goback button">Home</span></a>
		</div>

				<h1><?php echo($name);?></h1>
	</div>

	<div class="extra-container">
		<a href="<?php print $wikiNl?>" target="_blank" class="extra-link"><span>Wikipedia (Nederlands)</span></a> -
		<a href="<?php print $wikiEn?>" target="_blank" class="extra-link"><span>Wikipedia (Engels)</span></a>
	</div>

	<div id='wordcloud'></div>


	<script>

        loadTagCloud = function(words) {
            dimensions = Math.sqrt(words.length) * 160;
            dimensions = Math.max(100, dimensions);
            dimensions = Math.min(1200, dimensions);
            d3.wordcloud()
                .size([parseInt(dimensions*1.25), dimensions])
                .fill(d3.scale.ordinal().range(["#B9CA64", "#DB7681", "#BF313D", "#E0D18A", "#4D4D4D"]))
                .words(words)
                .onwordclick(function(d, i) {
                    if (d.href) { window.location = d.href; }
                })
                .start();
        };

        updateTagCloud = function(words){
            dimensions = Math.sqrt(words.length) * 160;
            dimensions = Math.max(100, dimensions);
            dimensions = Math.min(1200, dimensions);
            //Ugly hack to reset wordcloud
            $("#wordcloud").html('');
            d3.wordcloud()
                .size([parseInt(dimensions*1.25), dimensions])
                .fill(d3.scale.ordinal().range(["#B9CA64", "#DB7681", "#BF313D", "#E0D18A", "#4D4D4D"]))
                .words(words)
                .onwordclick(function(d, i) {
                    if (d.href) { window.location = d.href; }
                })
                .start();
        };

        function fetchData(){

            searchVal = $('#trefwoord').val();
            if(searchVal) {

                //Full search. This is slow
                $.getJSON("./index.php/data/kunststroming/" + searchVal, function (data) {
                    $('#matches').html('<ul></ul>');

                    $.each(data.artists[0], function (index, value) {
                        $('#matches ul').append('<li><a href="' + value.url + '">' + value.name + '</a></li>');
                    });

                    updateTagCloud(data.movements);
                });
            }
            else{
                $('#matches').html('<ul></ul>');
                //getEverything
                $.getJSON("./index.php/data/kunststroming", function(data){
                    loadTagCloud(data);
                });
            }

        }

        $( document ).ready(function() {
            $( "#searchbtn" ).click(function() {
                sterm = $('#trefwoord').val();
                location.href =  '/index.php?st=' + encodeURIComponent(sterm);
            });
            fetchData();
        });

	</script>

	<script>
        dimensions = Math.sqrt(words.length) * 160;
        dimensions = Math.max(100, dimensions);
        dimensions = Math.min(1000, dimensions);


		d3.wordcloud()
			.size([dimensions, dimensions])
			.fill(d3.scale.ordinal().range(["#B9CA64", "#DB7681", "#BF313D", "#E0D18A", "#4D4D4D"]))
			.words(words)
			.onwordclick(function(d, i) {
				if (d.href) { window.location = d.href; }
			})
			.start();
	</script>


</body>
</html>
