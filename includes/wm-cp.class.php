<?php
/**
* Widget WM Child Post Classes Plugin Loader
*
* Loader
* @author Repon Hossain
* @package WM Child Post
* @version 1.01
*/

/**
* Loader shortcode class
* @package WM Child Post
* @subpackage includes
*/
class WM_childpost {

	/**
	 * Plugin Loader init
	 * @static
	 * @since 1.01
	 */
	public static function init() {
		self::wm_hooks();
	}

	/**
	* hook popup dailogue box
	* @static
	* @since 1.01
	*/
	public static function wm_hooks () {
		add_action( 'admin_footer', array(  __CLASS__ , 'wm_shortcode_popup' ) );
	}

	/**
	* WM Child Post popup dailogue box
	* @static
	* @since 1.01
	*/
	public static function wm_shortcode_popup() { ?>
		<div id="wm-container" style="display:none">
			<div id="wm-child-post" >
				<h4 class="hndle wm-title">Insert Shortcode (WM Child Post)</h4>
				<div class="wm-content">

					<table class="widefat wm-table">
						<tr class="paddtr">
							<th width="30%"><label for="wm-groups"><?php _e('Post Title:', 'wm-child-post' ) ?></label></th>
							<td>
								<input type="text" id="wm-title" size="55" value="" />
							</td>
						</tr>

						<tr class="paddtr">
							<th width="30%"><label for="wm-groups"><?php _e('Post Groups:', 'wm-child-post' ) ?></label></th>
							<td>
								<?php self::child_post_groups() ?>
								
								<input type="submit" class="button tagadd" id="wm-add" value="<?php _e('Add', 'wm-child-post' ) ?>" />

								<div id="wm-btn-groups"></div>
								<input type="hidden" id="groups-val" value="" />
							</td>
						</tr>

						<tr>
							<th></th>
							<td><label><input id="wm-show-excerpt" type="checkbox" name="" /> <?php _e('Show Excerpt', 'wm-child-post' ) ?></label></td>
						</tr>

						<tr>
							<th><label for="wm-showposts"><?php _e('Show Posts:', 'wm-child-post') ?></label></th>
							<td><input id="wm-showposts" type="text" name="" value="" /></td>
						</tr>

						<tr>
							<th></th>
							<td><label><input id="wm-faq" type="checkbox" name="" /> <?php _e('FAQ', 'wm-child-post') ?></label></td>
						</tr>

						<tr class="bottom paddtr">
							<th></th>
							<td>
								<input id="wm-insert" type="submit" class="button button-hero button-primary" value="<?php _e('Insert', 'wm-child-post') ?>" />
								<input type="submit" class="wm-close-btn button button-hero button-primary" value="<?php _e('Cancel', 'wm-child-post') ?>" />
							</td>
						</tr>
					</table>

				</div><!-- wm-content end here -->
			</div><!-- wm-child-post end here -->
		</div><!--wm-container end here -->
	<?php }

	/**
	* child post groups select option
	* @static
	* @since 1.01
	*/
	public static function child_post_groups () {
		//Get Child Groups terms
		$groups = get_terms( 'child-group');

		//print select option if groups terms found
		if ( count($groups) > 0 ) :
			echo '<select id="wm-groups">';
			foreach ( $groups as $group ) { echo ('<option value="' . $group->slug . '">' . $group->name . '</option>'); }
			echo '</select>';
		endif;
	}
	
}
?>