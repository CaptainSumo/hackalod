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

    <?php if($rkdId) { ?>
        <dl>
            <dt>RKD Artist Id</dt>
            <dd><?php echo(urldecode($rkdId))?></dd>
            <dt>RKD Artist Page</dt>
            <dd><a href="<?php echo($rkdUri);?>" target="_blank"><?php echo(urldecode($name))?></a></dd>
       <?php if(isset($rkdData['nationaliteit'])) {?>
                <dt>Nationaliteit</dt>
                <dd><?php echo(  is_array($rkdData['nationaliteit']) ? $rkdData['nationaliteit'][0] : $rkdData['nationaliteit']) ; ?></dd>
        <?php }?>
        <?php if(isset($rkdData['geslacht'])) {?>
                <dt>Geslacht</dt>
                <dd><?php echo(  $rkdData['geslacht'] == 'm' ? 'Man': 'Vrouw') ?></dd>
        <?php }?>
            <?php if(isset($rkdData['geboorteplaats'])) {?>
                <dt>Geboorteplaats</dt>
                <dd><?php echo(  is_array($rkdData['geboorteplaats']) ? $rkdData['geboorteplaats'][0] : $rkdData['geboorteplaats']) ; ?></dd>
            <?php }?>
            <?php if(isset($rkdData['geboortedatum_begin'])) {?>
                <dt>Geboortedatum</dt>
                <dd><?php echo(  is_array($rkdData['geboortedatum_begin']) ? $rkdData['geboortedatum_begin'][0] : $rkdData['geboortedatum_begin']) ; ?></dd>
            <?php }?>
            <?php if(isset($rkdData['sterfplaats'])) {?>
                <dt>Sterfplaats</dt>
                <dd><?php echo(  is_array($rkdData['sterfplaats']) ? $rkdData['sterfplaats'][0] : $rkdData['sterfplaats']) ; ?></dd>
            <?php }?>
            <?php if(isset($rkdData['sterfdatum_begin'])) {?>
                <dt>Sterfdatum</dt>
                <dd><?php echo(  is_array($rkdData['sterfdatum_begin']) ? $rkdData['sterfdatum_begin'][0] : $rkdData['sterfdatum_begin']) ; ?></dd>
            <?php }?>

        </dl>

    <?php }else { ?>
        <h3 class="warning" >Er is helaas geen RKDid ik Wikidata voor deze kunstenaar</h3>
    <?php }?>


        <ul>
            <?php
                foreach($rkdImageData as $image){
                    printf('<li><img src="%1$s" title="%2$s" alt="%2$s" />%2$s</li>', $image['url'],$image['name'] );
                }
            ?>
        </ul>



</body>
</html>
