<?php

$media = WpvTemplates::get_sermon_media();

extract($media);

$media_icons = array(
	'video' => 'youtube',
	'audio' => 'theme-earphones',
	'document' => 'file-pdf',
	'link' => 'theme-link',
);

foreach ($media_links as $slug => $link): ?>
	<?php
		$params = '';
		if($slug == 'document' || $slug == 'link') {
			$params .= ' target="_blank"';
		} else {
			$params .= ' class="vamtam-lightbox"';

			if(preg_match('/youtu\.?be|dailymotion|vimeo/', $link)) {
				$params .= ' data-iframe="true"';
			}
		}
	?>
	<a href="<?php echo $link ?>" <?php echo $params ?>><?php echo wpv_shortcode_icon(array(
		'name' => $media_icons[$slug],
	)) ?></a>
<?php endforeach ?>
<div class="hidden">
	<?php foreach ($media_inline as $id => $content): ?>
		<div id="<?php echo esc_attr($id) ?>" class="wpv-sermon-inline-media">
			<?php echo $content ?>
		</div>
	<?php endforeach ?>
</div>