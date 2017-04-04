<?php

echo $before_widget;

if ( $title ) {
	echo $before_title . $title . $after_title;
}

?>
<ul class="clearfix">

<?php
	for ( $i = 1; $i <= $custom_count; $i++ ) :
		$name = isset($instance["custom_name"][$i]) ? $instance["custom_name"][$i] : '';
		$icon = isset($instance["custom_icon"][$i]) ? $instance["custom_icon"][$i] : '';
		$link = isset($instance["custom_url"][$i]) ? $instance["custom_url"][$i] : '#';
?>
		<li>
			<a href="<?php echo esc_url( $link ) ?>" rel="nofollow">
				<span class="icon before <?php echo esc_attr( wpv_get_icon_type( $icon ) ) ?>" data-icon-type="<?php echo esc_attr( $icon ) ?>"><?php wpv_icon( $icon )?></span><span class="content"><?php echo $name?></span><span class="icon after"><?php wpv_icon( 'arrow-right1' ) ?></span>
			</a>
		</li>
<?php endfor ?>
</ul>

<?php
	echo $after_widget;
