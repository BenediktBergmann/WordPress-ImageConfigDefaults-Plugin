<?php
/**
 * Plugin Name: Image Config Defaults
 * Plugin URI:  https://github.com/BenediktBergmann/WordPress-ImageConfigDefaults-Plugin
 * Description: Adds default configuration to images when added to a blog post within gutenberg editor. It will load the configuration of the option page (image_default_align and image_default_link_type).
 * Version:     1.0.0
 * Author:      Benedikt Bergmann
 * Author URI:  https://benediktbergmann.eu
 * Text Domain: Auto-Anchor 
 * License:     GPL3
 */

	add_action( 'admin_head-post.php', 'imageConfigDefaults_SetImageDefaultSettings' );
	add_action( 'admin_head-post-new.php', 'imageConfigDefaults_SetImageDefaultSettings' );
	function imageConfigDefaults_SetImageDefaultSettings() {
		$alignment = get_option( 'image_default_align' );
		$link = get_option( 'image_default_link_type' );
	?>
		<script>
		function imageConfigDefaults_setImageDefaultSettings(settings, name) {
			if (name !== "core/image" || !settings.attributes) {
				return settings;
			}
			settings.attributes.linkDestination.default = "<?php echo $link ?>";
			settings.attributes.align.default = "<?php echo $alignment ?>";

			return settings;
		}

		wp.hooks.addFilter(
			"blocks.registerBlockType",
			"imageConfigDefaults/setImageDefaultSettings",
			imageConfigDefaults_setImageDefaultSettings
		);
		</script>
	<?php
	}
?>
