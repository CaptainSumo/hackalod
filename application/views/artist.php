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
		<script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.1/photoswipe.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.1/photoswipe-ui-default.min.js"></script>
</head>
<body>

	<?php if($rkdId) { ?>
			<div class="header">
				<h1 class="artist-name"><?php echo(urldecode($name))?></h1>
				<div class="button-container">
						<a onclick="goBack()"><span class="goback button">< Go Back</span></a>
						<a href="/index.php" class="back-link"><span class="goback button">Home</span></a>
				</div>
			</div>

			<div class="row">

				<div class="column">
					<table summary="Each row names a Nordic country and specifies its total area and land area, in square kilometers">
							<caption><?php echo(urldecode($name))?> - ID: <span><?php echo(urldecode($rkdId))?></caption>
							<tbody>
							<?php if(isset($rkdData['nationaliteit'])) {?>
									<tr><th scope="row">Nationaliteit</th>
									<td><?php echo(  is_array($rkdData['nationaliteit']) ? $rkdData['nationaliteit'][0] : $rkdData['nationaliteit']) ; ?></td>
									</tr>
							<?php }?>
							<?php if(isset($rkdData['geslacht'])) {?>
									<tr><th scope="row">Geslacht</th>
									<td><?php echo(  $rkdData['geslacht'] == 'm' ? 'Man': 'Vrouw') ?></td>
									</tr>
							<?php }?>
							<?php if(isset($rkdData['geboorteplaats'])) {?>
									<tr><th scope="row">Geboorteplaats</th>
									<td><?php echo(  is_array($rkdData['geboorteplaats']) ? @$rkdData['geboorteplaats'][0] : $rkdData['geboorteplaats']) ; ?></td>
									</tr>
							<?php }?>
							<?php if(isset($rkdData['geboortedatum_begin'])) {?>
									<tr><th scope="row">Geboortedatum</th>
									<td><?php echo(  is_array($rkdData['geboortedatum_begin']) ? @$rkdData['geboortedatum_begin'][0] : $rkdData['geboortedatum_begin']) ; ?></td>
									</tr>
							<?php }?>
							<?php if(isset($rkdData['sterfplaats'])) {?>
									<tr><th scope="row">Sterfplaats</th>
									<td><?php echo(  is_array($rkdData['sterfplaats']) ? @$rkdData['sterfplaats'][0] : $rkdData['sterfplaats']) ; ?></td>
									</tr>
							<?php }?>
							<?php if(isset($rkdData['sterfdatum_begin'])) {?>
									<tr><th scope="row">Sterfdatum</th>
									<td><?php echo(  is_array($rkdData['sterfdatum_begin']) ? @$rkdData['sterfdatum_begin'][0] : $rkdData['sterfdatum_begin']) ; ?></td>
									</tr>
							<?php }?>
									<tr><th scope="row">Website links</th>
										<td><a href="<?php print $wikiNl?>" target="_blank">Wikipedia (nl)</a>
												<a href="<?php print $wikiEn?>" target="_blank">Wikipedia (en)</a>
												<a href="<?php echo($rkdUri);?>" target="_blank">RKD</a></td>
									</tr>

                            <?php if(count($rkdImageData) > 0){?>
                                <tr><th scope="row">Bekende afbeelding</th>
                                    <td>
                                            <?php foreach($rkdImageData as $image){
                                                printf('<a href="%s">%s</a><br/>', $image['image_page'], $image['name'] );
                                            }?>


                                </tr>
                            <?php }?>
							</tbody>
					</table>
				</div>



		<?php }else { ?>
			<div class="header">
				<button onclick="goBack()" class="goback-artist button">Go Back</button>
				<h1 class="artist-name">Er is helaas geen RKDid & Wikidata voor deze kunstenaar</h1>
			</div>
		<?php }?>

		<div class="my-gallery column" itemscope itemtype="http://schema.org/ImageGallery">
		<?php
				foreach($rkdImageData as $image){
					$size = getimagesize($image['url_large']);
						printf('<figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
							<a href="%5$s" itemprop="contentUrl" data-size="%3$sx%4$s">
								<img src="%1$s" itemprop="thumbnail" alt="Image description" />
							</a>
							<figcaption itemprop="caption description">%2$s</figcaption>
						</figure>', $image['url'],$image['name'] , $size[0], $size[1], $image['url_large'], $image['image_page']);
				}
		?>
		</div>
	</div>

	<!-- <span>%2$s</span> -->

	<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

		  <div class="pswp__bg"></div>
		  <div class="pswp__scroll-wrap">

		      <div class="pswp__container">
		          <div class="pswp__item"></div>
		          <div class="pswp__item"></div>
		          <div class="pswp__item"></div>
		      </div>

		      <div class="pswp__ui pswp__ui--hidden">

		          <div class="pswp__top-bar">
		              <div class="pswp__counter"></div>
		              <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
		              <button class="pswp__button pswp__button--share" title="Share"></button>
		              <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
		              <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
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

</body>
</html>
