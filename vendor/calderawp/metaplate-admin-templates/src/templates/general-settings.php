		<div class="metaplate-config-group">
			<label><?php _e( 'Name', 'metaplate' ); ?></label>
			<input type="text" name="name" value="{{name}}" data-sync="#metaplate-name-title" id="metaplate-name" required>
		</div>
		
		<div class="metaplate-config-group">
			<label><?php _e( 'Slug', 'metaplate' ); ?></label>
			<input type="text" name="slug" value="{{slug}}" data-format="slug" data-sync=".metaplate-subline" data-master="#metaplate-name" id="metaplate-slug" required>
		</div>
		
		<div class="metaplate-config-group">
			<label><?php _e( 'Autoload', 'metaplate' ); ?></label>
			<label><input data-live-sync="true" type="checkbox" name="content_type_specific" value="1" id="metaplate-content_type_specific" {{#if content_type_specific}}checked="checked"{{/if}}> <?php _e('Enable', 'metaplate'); ?></label>			
		</div>

		{{#if content_type_specific}}
			<div class="metaplate-config-group">
				<label><?php _e( 'Page Types', 'metaplate' ); ?></label>
				<select name="page_type" data-live-sync="true">
					<option value="single" {{#is page_type value="single"}}selected="selected"{{/is}}><?php _e( 'Single', 'metaplate' ); ?></option>
					<option value="archive" {{#is page_type value="archive"}}selected="selected"{{/is}}><?php _e( 'Archive (list)', 'metaplate' ); ?></option>
					<option value="both" {{#is page_type value="both"}}selected="selected"{{/is}}><?php _e( 'Both', 'metaplate' ); ?></option>
				</select>
			</div>
			<div class="metaplate-config-group">
				<label><?php _e( 'Placement', 'metaplate' ); ?></label>
				<select name="placement" data-live-sync="true">
					<option value="append" {{#is placement value="append"}}selected="selected"{{/is}}><?php _e( 'After Content', 'metaplate' ); ?></option>
					<option value="prepend" {{#is placement value="prepend"}}selected="selected"{{/is}}><?php _e( 'Before Content', 'metaplate' ); ?></option>
					<option value="replace" {{#is placement value="replace"}}selected="selected"{{/is}}><?php _e( 'Replace Content', 'metaplate' ); ?></option>
				</select>
				{{#is placement value="replace"}}<p class="description"><?php _e( 'Use \{{content}} in the template to indicate placement of original content.', 'metaplate' ); ?></p>{{/is}}
			</div>

			<div class="metaplate-config-group">
				<label style="float:left;"><?php _e( 'Post Types', 'metaplate' ); ?></label>
				<div style="margin-left: 180px; padding-top: 6px;">
				<?php
				$post_types = get_post_types( array( 'public' => true ), 'objects' );
				foreach($post_types as $type){?>
				<label style="display:block; margin-bottom:4px;"><input type="checkbox" data-live-sync="true" name="post_type[<?php echo $type->name; ?>]" value="1" {{#if post_type/<?php echo $type->name; ?>}}checked="checked"{{/if}}> <?php echo $type->label; ?></label>
				<?php } ?>
				</div>
			</div>
		{{else}}
			<h4><?php _e( 'Rendering Code', 'metaplate' ); ?> <small class="description"><?php _e( 'template tags', 'metaplate' ); ?></small></h4>
			<div class="metaplate-config-group">
				<label><?php _e( 'Template Function:', 'metaplate' ); ?></label>
				<code style="display: inline-block; padding: 10px;">&lt;?php echo caldera_metaplate_render( '{{id}}' ); ?&gt;</code></p>
			</div>
			<div class="metaplate-config-group">
				<label><?php _e( 'Shortcode:', 'metaplate' ); ?></label>
				<code style="display: inline-block; padding: 10px;">[caldera_metaplate id="{{id}}"]</code></p>
			</div>
		{{/if}}


		<?php
		/*
		if( class_exists( 'Custom_Field_Suite' ) ){
			foreach($post_types as $type){
				$fields = array();
				$args = array(
					'posts_per_page' 	=>	1,
					'post_type'			=>	$type->name,
					'post_status'		=>	'publish'
				);
				$posts = get_posts( $args );
				if( !empty( $posts ) ){
					$field_info = CFS()->get_field_info( false, $posts[0]->ID );
					foreach( $field_info as $field ){
						if( empty( $field['parent_id'] ) ){
							$fields[$field['id']][] = $field['name'];
						}else{
							$fields[$field['parent_id']][] = $field['name'];
						}
					}
				}
				echo '{{#if post_type/' . $type->name . '}}';
				foreach ($fields as $key => $value) {
					if( count( $value ) > 1 ){
						$parent = array_shift( $value );
						echo '<div class="metaplate-autocomplete-out-entry-mustache" data-slug="#each ' . $parent . '" data-label="#each ' . $parent . '">#each ' . $parent . '</div>';
						foreach( $value as $part ){
							echo '<div class="metaplate-autocomplete-in-entry-mustache" data-parent="' . $parent . '" data-slug="' . $part . '" data-label="' . $part . '">' . $part . '</div>';
						}
					}else{
						echo '<div class="metaplate-autocomplete-out-entry-mustache" data-slug="' . $value[0] . '" data-label="' . $value[0] . '">' . $value[0] . '</div>';
					}
				};
				echo '{{/if}}';
			}
		};			
		*/
		?>
