<?php
/**
 * @package Vihv Google Maps
 */
/*
Plugin Name: Vihv Google Maps
Description: kml based widget for displaing your data on embedded google map. You can edit your map on google maps site, or manually, keep kml hosted on google servers or on your own - all up to you. Simple, but effective and full functional.
Version: 2.1
Author: Vigorous Hive
Author URI: http://vihv.org/store
Plugin URI: http://vihv.org/store/v/our-plugins/vihv-google-maps/
License: MIT
*/
class TVihvGoogleMapsWidget extends WP_Widget {
	public function __construct() {
		parent::__construct(
	 		'vihv_google_maps_widget', // Base ID
			'Vihv Google Map', // Name
			array( 'description' => __( 'kml based widget for displaing your data on embedded google map.' ), ) // Args
		);
		add_shortcode('vihv-google-map', array($this, 'shortcode'));
		wp_enqueue_script('vihv-google-map-api', "http://maps.googleapis.com/maps/api/js?key=AIzaSyCTfeBRAv3YAwD-uRhZUv93kK4cXUZgFMY&sensor=false");
		wp_register_script('vihv-google-map', plugins_url()."/vihv-google-maps/js/vihv-google-maps.js", array('jquery','vihv-google-map-api'));
		wp_enqueue_script('vihv-google-map');
		wp_register_style('vihv-google-maps-css', plugins_url()."/vihv-google-maps/css/default.css");
	}

	public function widget( $args, $instance ) {
		wp_enqueue_style('vihv-google-maps-css');
		echo $args['before_widget'];
		?>
		<div 
			id='container-<?php echo $args['widget_id'];?>' 
			class="vihv-google-map-container"
			></div>
		<script>
			new TVihvGoogleMap(<?php echo "'".$instance['latitude']."','".$instance['longitude']."','".$instance['zoom']."','".htmlspecialchars_decode($instance['kml'])."'"; ?>,'container-<?php echo $args['widget_id'];?>');
		</script>	
		<?php
				//var_dump($args);
		echo $args['after_widget'];
	}

	public function shortcode($args) {
		ob_start();
		$this->widget(array('widget_id'=>'vihv-google-map-shortcode-'.md5(implode('',$args))), $args);
		return ob_get_clean();
	}
	
	function getFields() {
		return array(
			array('name'=>'latitude','title'=>'Latitude'),
			array('name'=>'longitude','title'=>'Longitude'),
			array('name'=>'zoom','title'=>'Zoom'),
			array('name'=>'kml','title'=>'link to Kml','help'=>'http://vihv.org/store/v/our-plugins/vihv-google-maps'),
		);
	}
	
 	public function form( $instance ) {
		foreach($this->getFields() as $field) { 
			?>
			<p>
				<label for="<?php echo $field['name'];?>"><?php echo $field['title'];?></label>
				<?php if($field['help']) { ?>
				<small><a href="<?php echo $field['help']?>">What's this?</a></small>
				<?php } ?>
				<input 
					id='<?php echo $this->get_field_id($field['name']);?>'
					type="text" 
					name="<?php echo $this->get_field_name($field['name']);?>" 
					value='<?php echo $instance[$field['name']]?>'/>
			</p>
			<?php
		}
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		foreach($this->getFields() as $field) {
			$instance[$field['name']] = htmlspecialchars($new_instance[$field['name']], ENT_QUOTES);
		}
		return $instance;
	}
}

add_action( 'widgets_init', function(){
     register_widget( 'TVihvGoogleMapsWidget' );
});

