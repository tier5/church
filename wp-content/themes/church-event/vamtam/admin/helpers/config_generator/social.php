<?php
/*
 * social share icons
 */

$contexts = array(
	'post'      => __('Post', 'church-event'),
	'page'      => __('Page', 'church-event'),
	'portfolio' => __('Portfolio', 'church-event'),
	'product'   => __('Product', 'church-event'),
	'lightbox'  => __('Lightbox', 'church-event'),
	'tribe'     => __('Tribe Events', 'church-event'),
);

$networks = array(
	'twitter'    => 'https://abs.twimg.com/favicons/favicon.png',
	'facebook'   => 'https://www.facebook.com/favicon.ico',
	'googleplus' => '//ssl.gstatic.com/s2/oz/images/faviconr2.ico',
	'pinterest'  => 'https://www.pinterest.com/favicon.ico',
	'ess' => '',
);

?>

<div class="wpv-config-row social clearfix">
	<div class="rtitle">
		<h4><?php echo $name?></h4>

		<?php wpv_description('social', $desc) ?>
	</div>

	<div class="rcontent">
		<table cellspacing="5px">
			<thead>
				<th>&nbsp;</th>
				<?php foreach($networks as $network=>$image): ?>
					<th>
						<?php if ( $network === 'ess' ) : ?>
							Easy Social Share Buttons
						<?php else : ?>
							<img src="<?php echo $image?>" alt="<?php echo ucfirst($network)?>" width="16" height="16"/>
						<?php endif ?>
					</th>
				<?php endforeach ?>
			</thead>
			<tbody>
				<?php foreach($contexts as $context=>$context_translation): ?>
					<tr>
						<th><?php echo $context_translation ?></th>
						<?php foreach($networks as $network=>$image): ?>
							<td>
								<?php if ( $context !== 'lightbox' || $network !== 'ess' ) : ?>
									<input type="checkbox" name="share-<?php echo $context.'-'.$network?>" <?php checked(wpv_get_option("share-$context-$network"), true)?> value="true" class="<?php wpv_static($value)?>" />
								<?php endif ?>
							</td>
						<?php endforeach ?>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>