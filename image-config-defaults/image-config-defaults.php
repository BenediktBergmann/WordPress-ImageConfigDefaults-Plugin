<?php
/**
 * Plugin Name: Image Config Defaults
 * Plugin URI:  https://github.com/BenediktBergmann/WordPress-ImageConfigDefaults-Plugin
 * Description: Adds default configuration to images when added to a blog post within gutenberg editor. It will load the configuration of the option page (image_default_align and image_default_link_type).
 * Version:     1.1.0
 * Author:      Benedikt Bergmann
 * Author URI:  https://benediktbergmann.eu
 * Text Domain: Auto-Anchor 
 * License:     GPL3
 */

	add_action( 'admin_head-post.php', 'imageConfigDefaults_SetImageDefaultSettings' );
	add_action( 'admin_head-post-new.php', 'imageConfigDefaults_SetImageDefaultSettings' );
	function imageConfigDefaults_SetImageDefaultSettings() {
		$alignment = get_option( 'image_default_align' );
		switch ($alignment) {
			case "left":
			case "right":
			case "center":
				break;
			default:
				$alignment = "none";
				break;
		}

		$link = get_option( 'image_default_link_type' );
		switch ($link) {
			case "file":
			case "attachment":
				$link = "attachment";
				break;
			case "media":
			case "media file":
			case "media-file":
				$link = "media";
				break;
			default:
				$link = "none";
				break;
		}

		$options = get_option( 'imageConfigDefaults_plugin_options' );
		$caption = $options['caption'];

	?>
		<script>
		function imageConfigDefaults_setImageDefaultSettings(settings, name) {
			if (name !== "core/image" || !settings.attributes) {
				return settings;
			}

			debugger;

			settings.attributes.linkDestination.default = "<?php echo $link ?>";
			settings.attributes.align.default = "<?php echo $alignment ?>";

			if(!settings.attributes.caption){
				settings.attributes.caption = {};
			}
			settings.attributes.caption.default = "<?php echo $caption ?>";

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

	/* Create Settings page */
	function imageConfigDefaults_add_settings_page() {
		add_options_page( 'Image Config Default Plugin Settings', 'Image Config Default', 'manage_options', 'imageConfigDefaults', 'imageConfigDefaults_render_plugin_settings_page' );
	}
	add_action( 'admin_menu', 'imageConfigDefaults_add_settings_page' );

	function imageConfigDefaults_render_plugin_settings_page() {
		?>
		<h2>Image Config Default Plugin Settings</h2>
		<form action="options.php" method="post">
			<?php 
			settings_fields( 'imageConfigDefaults_plugin_options' );
			do_settings_sections( 'imageConfigDefaults_plugin' ); ?>
			<input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
		</form>
		<?php
	}

	function imageConfigDefaults_register_settings() {
		register_setting( 'imageConfigDefaults_plugin_options', 'imageConfigDefaults_plugin_options');
		add_settings_section( 'default_settings', 'Image default settings', 'imageConfigDefaults_plugin_section_text', 'imageConfigDefaults_plugin' );
	
		add_settings_field( 'imageConfigDefaults_plugin_setting_align', 'image_default_align', 'imageConfigDefaults_plugin_setting_align', 'imageConfigDefaults_plugin', 'default_settings' );
		add_settings_field( 'imageConfigDefaults_plugin_setting_link_type', 'image_default_link_type', 'imageConfigDefaults_plugin_setting_link_type', 'imageConfigDefaults_plugin', 'default_settings' );
		add_settings_field( 'imageConfigDefaults_plugin_setting_caption', 'Default caption', 'imageConfigDefaults_plugin_setting_caption', 'imageConfigDefaults_plugin', 'default_settings' );
	}
	add_action( 'admin_init', 'imageConfigDefaults_register_settings' );

	function imageConfigDefaults_plugin_section_text() {
		?>
			<p>Here you can set all the options for using the Image config Defaults plugin</p>
		<?php
	}
	
	function imageConfigDefaults_plugin_setting_caption() {
		$options = get_option( 'imageConfigDefaults_plugin_options' );
		?>
			<input id="imageConfigDefaults_plugin_setting_caption" name="imageConfigDefaults_plugin_options[caption]" type="text" value="<?php echo esc_attr( $options['caption'] ); ?>" />
        <?php
	}

	function imageConfigDefaults_plugin_setting_align() {
		$align = get_option( 'image_default_align' );
		?>
			<input id="imageConfigDefaults_plugin_setting_align" name="image_default_align" type="text" value="<?php echo esc_attr( $align ); ?>" />
        <?php
	}

	function imageConfigDefaults_plugin_setting_link_type() {
		$linktype = get_option( 'image_default_link_type' );
		?>
			<input id="imageConfigDefaults_plugin_setting_link_type" name="image_default_link_type" type="text" value="<?php echo esc_attr( $linktype ); ?>" />
        <?php
	}
?>