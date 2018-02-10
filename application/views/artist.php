<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Overzicht van <?php echo($name) ?></title>
    <link rel="stylesheet" type="text/css" href="/assets/css/site.css">
		<script src="/assets/js/main.js"></script>
		<script src="/assets/js/photoswipe.js"></script>
		<script src="/assets/js/photoswipe-ui-default.js"></script>
</head>
<body>

	<div class="header">
		<button onclick="goBack()" class="goback-artist button">Go Back</button>
		<h1 class="artist-name"> <?php echo(urldecode($name))?></h1>
	</div>

	<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

    <!-- Background of PhotoSwipe.
         It's a separate element as animating opacity is faster than rgba(). -->
    <div class="pswp__bg"></div>

    <!-- Slides wrapper with overflow:hidden. -->
    <div class="pswp__scroll-wrap">

        <!-- Container that holds slides.
            PhotoSwipe keeps only 3 of them in the DOM to save memory.
            Don't modify these 3 pswp__item elements, data is added later on. -->
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
        <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">

                <!--  Controls are self-explanatory. Order can be changed. -->

                <div class="pswp__counter"></div>

                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                <button class="pswp__button pswp__button--share" title="Share"></button>

                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                <!-- element will get class pswp__preloader--active when preloader is running -->
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                      <div class="pswp__preloader__cut">
                        <div class="pswp__preloader__donut"></div>
                      </div>
                    </div>
                </div>
            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div>
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>

            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>

            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>

        </div>

    </div>

</div>



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
