<?php
/**
 * Main admin class.
 *
 * @package calderawp\metaplate\admin
 * @author    David Cramer <david@digilab.co.za>
 * @license   GPL-2.0+
 * @link
 * @copyright 2014 David Cramer
 */

namespace calderawp\metaplate\admin;


use calderawp\metaplate\core\data;
use calderawp\metaplate\core\init;

/**
 * Class settings
 * @package calderawp\metaplate\admin
 */
class settings extends init {


	/**
	 * Start up
	 */
	public function __construct(){

		// save config
		add_action( 'wp_ajax_mtpt_save_config', array( $this, 'save_config') );
		// creat new
		add_action( 'wp_ajax_mtpt_create_metaplate', array( $this, 'create_new_metaplate') );
		// delete
		add_action( 'wp_ajax_mtpt_delete_metaplate', array( $this, 'delete_metaplate') );

	}


	/**
	 * saves a config
	 */
	private function update_settings($config){

		if( isset( $config['metaplate-setup'] ) && !wp_verify_nonce( $config['metaplate-setup'], 'metaplate' ) ){
			wp_send_json_error( $config );
		}

		$metaplates = $this->get_registry();
		if( isset( $config['id'] ) && !empty( $metaplates[ $config['id'] ] ) ){
			$new_value = array();
			$old_value = data::get_metaplate( $config[ 'id' ] );
			if ( is_string( $old_value ) ) {
				$old_value = (array) json_decode( $old_value );
			}
			$fields = array( 'id', 'name', 'slug', 'content_type_specific', '_current_tab' );
			foreach( $fields as $field ) {
				if ( isset( $config[ $field ] ) ) {
					if ( 'content_type_specific' !== $field ) {
						$new_value[ $field ] = strip_tags( $config[ $field ] );
						if ( isset( $old_value[ $field ] ) ) {
							$new_value[ $field ] = $old_value[ $field ];
						}
						else {
							wp_send_json_error( $config );
							die();
						}
					} else {
						$new_value[ $field ] = (bool) $config[ $field ];
					}

				}
				else {
					if ( isset( $old_value[ $field ] ) ) {
						$new_value[ $field ] = $old_value[ $field ];
					}

				}

			}

			// add search form to registery
			if( !empty( $config['search_form'] ) ){
				$new_value['search_form'] = $config['search_form'];
			}
			$this->update_registry( $new_value, $config['id'] );

		}

		if ( ! $config[ 'id'] && isset( $config[ 'slug'] ) ) {
			$config[ 'id' ] = data::get_metaplate_id_by_slug( $config[ 'slug' ] );
		}
		$update = update_option( $config['id'], $config );
		if ( $update ) {
			wp_send_json_success( $config );
		}

		wp_send_json_error( $config );
		die();


	}

	/**
	 * saves a config
	 */
	public function save_config(){

		if( empty( $_POST['metaplate-setup'] ) || !wp_verify_nonce( $_POST['metaplate-setup'], 'metaplate' ) ){
			if( empty( $_POST['config'] ) ){
				return;
			}
		}
		// define default
		$config = array();
		if( !empty( $_POST['metaplate-setup'] ) && empty( $_POST['config'] ) ){
			$config = stripslashes_deep( $_POST );

			$this->update_settings( $config );

			wp_redirect( '?page=metaplate&updated=true' );
			exit;
		}

		if( !empty( $_POST['config'] ) ){
			$config = json_decode( stripslashes_deep( $_POST['config'] ), true );
			$this->update_settings( $config );
			wp_send_json_success( $config );

		}

		// nope
		wp_send_json_error( $config );

	}

	/**
	 * Deletes a block
	 */
	public function delete_metaplate(){

		$search_blocks = $this->get_registry();
		if( isset( $search_blocks[ $_POST['block'] ] ) ){
			delete_option( $search_blocks[$_POST['block']]['id'] );

			unset( $search_blocks[ $_POST['block'] ] );
			update_option( '_metaplates_registry', $search_blocks );

			wp_send_json_success( $_POST );
		}

		wp_send_json_error( $_POST );

	}
	/**
	 * create new metaplate
	 */
	public function create_new_metaplate(){

		$metaplates = $this->get_registry();
		if( empty( $metaplates ) ){
			$metaplates = array();
		}

		$metaplate_id = uniqid('MTPT').rand(100,999);
		if( !isset( $metaplates[ $metaplate_id ] ) ){
			$data = $this->validate_sanitize_new();
			if ( is_array( $data ) ) {
				$new_metaplate = array(
					'id'           => $metaplate_id,
					'_current_tab' => '#metaplate-panel-general'
				);
				$new_metaplate = array_merge( $new_metaplate, $data );

				update_option( $metaplate_id, $new_metaplate );
				$this->update_registry( $new_metaplate, $metaplate_id );

				// end
				wp_send_json_success( $new_metaplate );
				die();
			}

		}

		wp_send_json_error();

	}

	/**
	 * Validate and sanitize metaplate options coming from POST data.
	 *
	 * @return array|bool
	 */
	private function validate_sanitize_new() {
		$fields = array( 'name', 'slug', 'content_type_specific' );
		foreach( $fields as $field ) {
			if ( ! isset( $_POST[ $field ] ) ) {
				return false;

			}

			if ( 'content_type_specific' !== $field ) {
				$save[ $field ] = strip_tags( $_POST[ $field ] );
			}


		}

		$content_type_specific = $_POST[ 'content_type_specific'];
		if ( ! in_array( $content_type_specific, array( 'true', 'false' ) ) ) {
			return false;
		}

		if ( 'false' === $_POST[ 'content_type_specific']  ) {
			$save[ 'content_type_specific' ] = false;
		} else {
			$save[ 'content_type_specific' ] = true;
		}

		return $save;


	}

	/**
	 * Get current metaplates
	 *
	 * @return array|bool
	 */
	private function get_registry() {
		return data::get_registry();

	}

	/**
	 * Update registry of metaplates
	 *
	 * Note: Does not save the metaplate itself.
	 *
	 * @param array $new_value The new item to add.
	 * @param string $id Id of new item to add.
	 *
	 * @return bool
	 */
	private function update_registry( $new_value, $id ) {
		return data::update_registry( $new_value, $id );

	}


} 
