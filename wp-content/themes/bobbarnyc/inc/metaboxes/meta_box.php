<?php
//include( get_template_directory() . '/inc/metaboxes/meta_box.php' ); ADD TO YOU FUNCTIONS.PHP
// metaboxes directory constant
define( 'CUSTOM_METABOXES_DIR', get_template_directory_uri().'/inc/metaboxes' );

/**
 * recives data about a form field and spits out the proper html
 *
 * @param	array					$field			array with various bits of information about the field
 * @param	string|int|bool|array	$meta			the saved data for this field
 * @param	array					$repeatable		if is this for a repeatable field, contains parant id and the current integar
 *
 * @return	string									html for the field
 */
function custom_meta_box_field( $field, $meta = null, $repeatable = null ) {
	if ( ! ( $field || is_array( $field ) ) )
		return;
	
	// get field data
	$value = isset( $field['value'] ) ? $field['value'] : null;
	$title = isset( $field['title'] ) ? $field['title'] : null;
	$type = isset( $field['type'] ) ? $field['type'] : null;
	$label = isset( $field['label'] ) ? $field['label'] : null;
	$desc = isset( $field['desc'] ) ? '<span class="description">' . $field['desc'] . '</span>' : null;
	$place = isset( $field['place'] ) ? $field['place'] : null;
	$size = isset( $field['size'] ) ? $field['size'] : null;
	$width = isset( $field['width'] ) ? $field['width'] : null;
	$rows = isset( $field['rows'] ) ? $field['rows'] : null;
	$cols = isset( $field['cols'] ) ? $field['cols'] : null;
	$post_type = isset( $field['post_type'] ) ? $field['post_type'] : null;
	$post_cat = isset( $field['post_cat'] ) ? $field['post_cat'] : null;
	$options = isset( $field['options'] ) ? $field['options'] : null;
	$settings = isset( $field['settings'] ) ? $field['settings'] : null;
	$repeatable_fields = isset( $field['repeatable_fields'] ) ? $field['repeatable_fields'] : null;
	$group_fields = isset( $field['group_fields'] ) ? $field['group_fields'] : null;
	$default_color = isset( $field['default_color'] ) ? $field['default_color'] : null;
	$multiple = isset( $field['multiple'] ) ? $field['multiple'] : null;
	
	// the id and name for each field
	$id = $name = isset( $field['id'] ) ? $field['id'] : null;
	if ( $repeatable ) {
		$name = $repeatable[0] . '[' . $repeatable[1] . '][' . $id .']';
		$id = $repeatable[0] . '_' . $repeatable[1] . '_' . $id;
	}
	switch( $type ) {
		// basic
		case 'text':
		case 'tel':
		case 'email':
		default:
			echo '<input type="' . $type . '" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="' . esc_attr( $meta ) . '" class="regular-text" style="width:'.esc_attr( $width ).';" size="30" /><br>'. $desc;
		break;
		case 'soundcloud':
			$consumer_key = '7d60b57f78e0968a839f9c896b79517a';
			echo '<input type="text" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="' . esc_attr( $meta ) . '" class="regular-text soundcloud" style="width:'.esc_attr( $width ).';" size="30" /> <a rel="36" class="button stream-url" href="#">Get Stream URL</a><br>'. $desc;
		break;
		case 'single_text':
			echo '<input type="' . $type . '" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="' . esc_attr( $meta ) . '" class="regular-text" style="width:'.esc_attr( $width ).';" size="30" />';
		break;
		case 'url':
			echo '<input type="' . $type . '" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="' . esc_url( $meta ) . '" class="regular-text" style="' . esc_attr( $width ) . '" size="30" />
					<br />' . $desc;
		break;
		case 'number':
			echo '<input type="' . $type . '" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="' . intval( $meta ) . '" class="regular-text" style="' . esc_attr( $width ) . '" size="30" />
					<br />' . $desc;
		break;
		// textarea
		case 'textarea':
			wp_editor( $meta, esc_attr( $id ), array(
			    'wpautop'       => true,
			    'media_buttons' => true,
			    'textarea_name' => esc_attr( $name ),
			    'textarea_rows' => 10,
			    'teeny'         => true
			) );			
			// echo '<textarea name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" cols="' . esc_attr( $cols ) . '" rows="' . esc_attr( $rows ) . '" style="' . esc_attr( $width ) . '" class="theEditor">' . esc_textarea( $meta ) . '</textarea><div>' . $desc,'</div>';
		break;
		// editor
		case 'editor':
			echo wp_editor( $meta, $id, $settings ) . '<br />' . $desc;
		break;
		// checkbox
		case 'checkbox':
			echo '<input type="checkbox" name="'.esc_attr($name).'" id="'.esc_attr( $id ).'" ',$meta ? ' checked="checked"' : '',' />
					<label for="' . esc_attr( $id ) . '">' . $desc . '</label>';
		break;
		// checkbox
		case 'value_checkbox':
			echo '<input type="checkbox" name="'.esc_attr($name).'" id="'.esc_attr( $id ).'" ',$meta ? ' checked="checked"' : '',' value="'.esc_attr($value).'"/>
					<label for="' . esc_attr( $id ) . '">' . $desc . '</label>';
		break;
		// select, chosen
		case 'select':
		case 'chosen':
			echo '<select name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '"' , $type == 'chosen' ? ' class="chosen"' : '' , isset( $multiple ) && $multiple == true ? ' multiple="multiple"' : '' , '>
					<option value="">Select One</option>'; // Select One
			foreach ( $options as $option )
				echo '<option' . selected( $meta, $option['value'], false ) . ' value="' . $option['value'] . '">' . $option['label'] . '</option>';
			echo '</select><br />' . $desc;
		break;
		// radio
		case 'radio':
			echo '<ul class="meta_box_items">';
			foreach ( $options as $option )
				echo '<li><input type="radio" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '-' . $option['value'] . '" value="' . $option['value'] . '" ' . checked( $meta, $option['value'], false ) . ' />
						<label for="' . esc_attr( $id ) . '-' . $option['value'] . '">' . $option['label'] . '</label></li>';
			echo '</ul>' . $desc;
		break;
		// checkbox_group
		case 'checkbox_group':
			echo '<ul class="meta_box_items">';
			foreach ( $options as $option )
				echo '<li><input type="checkbox" value="' . $option['value'] . '" name="' . esc_attr( $name ) . '[]" id="' . esc_attr( $id ) . '-' . $option['value'] . '"' , is_array( $meta ) && in_array( $option['value'], $meta ) ? ' checked="checked"' : '' , ' /> 
						<label for="' . esc_attr( $id ) . '-' . $option['value'] . '">' . $option['label'] . '</label></li>';
			echo '</ul>' . $desc;
		break;
		// color
		case 'color':
	       echo '<input type="text" name="'.esc_attr( $name ).'" id="colorpicker-'.$field['id'].'" value="'.$meta.'" '.($default_color ? 'data-default-color="'.$default_color.'"' : '').' />
	       	<br />' . $desc;
	       echo '<script type="text/javascript">
	       	jQuery(function($){
	       		var colorOptions = {
	       		    defaultColor: true,
	       		    palettes: false
	       		};
	       		jQuery("#colorpicker-'.$field['id'].'").wpColorPicker(colorOptions);
	       	});
	       	</script>';
	   break;
		// post_select, post_chosen
		case 'post_select':
		case 'post_list':
		case 'post_chosen':
			echo '<select data-placeholder="'.(isset($multiple) && $multiple == true ? 'Select Posts' : 'Select One').'" name="' . esc_attr( $name ) . '[]" id="' . esc_attr( $id ) . '"' , $type == 'post_chosen' ? ' class="chosen"' : '' , isset( $multiple ) && $multiple == true ? ' multiple' : '' , '>
					<option value=""></option>'; // Select One
			if( isset($post_cat) ):
				$category = get_posts( array( 'category_name' => $post_cat, 'posts_per_page' => -1, 'orderby' => 'name', 'order' => 'ASC' ) );
				foreach ( $category as $item ):
					echo '<option value="'.$item->ID.'"'.selected( is_array( $meta ) && in_array( $item->ID, $meta ), true, false ).'>'.$item->post_title.'</option>';
				endforeach;
				$post_type_object = get_post_type_object( $post_cat );
				$postsurl = admin_url( 'edit.php' );
			endif;
			
			if( isset($post_type) ):
				$posts = get_posts( array( 'post_type' => $post_type, 'posts_per_page' => -1, 'orderby' => 'name', 'order' => 'ASC' ) );
				foreach ( $posts as $item ):
					echo '<option value="'.$item->ID.'"'.selected( is_array( $meta ) && in_array( $item->ID, $meta ), true, false ).'>'.$item->post_title.'</option>';
				endforeach;
				$post_type_object = get_post_type_object( $post_type );
				$postsurl = admin_url( 'edit.php?post_type='.$post_type );
			endif;
			
			echo '</select> &nbsp;<span class="description"><a href="'.$postsurl.'">Manage '.$post_type_object->label.'</a></span>' . $desc;
		break;
		// post_checkboxes
		case 'post_checkboxes':
			$posts = get_posts( array( 'post_type' => $post_type, 'posts_per_page' => -1 ) );
			echo '<ul class="meta_box_items">';
			foreach ( $posts as $item ) 
				echo '<li><input type="checkbox" value="' . $item->ID . '" name="' . esc_attr( $name ) . '[]" id="' . esc_attr( $id ) . '-' . $item->ID . '"' , is_array( $meta ) && in_array( $item->ID, $meta ) ? ' checked="checked"' : '' , ' />
						<label for="' . esc_attr( $id ) . '-' . $item->ID . '">' . $item->post_title . '</label></li>';
			$post_type_object = get_post_type_object( $post_type );
			echo '</ul> ' . $desc , ' &nbsp;<span class="description"><a href="' . admin_url( 'edit.php?post_type=' . $post_type . '">Manage ' . $post_type_object->label ) . '</a></span>';
		break;
		// post_drop_sort
		case 'post_drop_sort':
			//areas
			$post_type_object = get_post_type_object( $post_type );
			echo '<p>' . $desc . ' &nbsp;<span class="description"><a href="' . admin_url( 'edit.php?post_type=' . $post_type . '">Manage ' . $post_type_object->label ) . '</a></span></p><div class="post_drop_sort_areas">';
			foreach ( $areas as $area ) {
				echo '<ul id="area-' . $area['id']  . '" class="sort_list">
						<li class="post_drop_sort_area_name">' . $area['label'] . '</li>';
						if ( is_array( $meta ) ) {
							$items = explode( ',', $meta[$area['id']] );
							foreach ( $items as $item ) {
								$output = $display == 'thumbnail' ? get_the_post_thumbnail( $item, array( 204, 30 ) ) : get_the_title( $item ); 
								echo '<li id="' . $item . '">' . $output . '</li>';
							}
						}
				echo '</ul>
					<input type="hidden" name="' . esc_attr( $name ) . '[' . $area['id'] . ']" 
					class="store-area-' . $area['id'] . '" 
					value="' , $meta ? $meta[$area['id']] : '' , '" />';
			}
			echo '</div>';
			// source
			$exclude = null;
			if ( !empty( $meta ) ) {
				$exclude = implode( ',', $meta ); // because each ID is in a unique key
				$exclude = explode( ',', $exclude ); // put all the ID's back into a single array
			}
			$posts = get_posts( array( 'post_type' => $post_type, 'posts_per_page' => -1, 'post__not_in' => $exclude ) );
			echo '<ul class="post_drop_sort_source sort_list">
					<li class="post_drop_sort_area_name">Available ' . $label . '</li>';
			foreach ( $posts as $item ) {
				$output = $display == 'thumbnail' ? get_the_post_thumbnail( $item->ID, array( 204, 30 ) ) : get_the_title( $item->ID ); 
				echo '<li id="' . $item->ID . '">' . $output . '</li>';
			}
			echo '</ul>';
		break;
		// tax_select
		case 'tax_select':
			echo '<select name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '">
					<option value="">Select One</option>'; // Select One
			$terms = get_terms( $id, 'get=all' );
			$post_terms = wp_get_object_terms( get_the_ID(), $id );
			$taxonomy = get_taxonomy( $id );
			$selected = $post_terms ? $taxonomy->hierarchical ? $post_terms[0]->term_id : $post_terms[0]->slug : null;
			foreach ( $terms as $term ) {
				$term_value = $taxonomy->hierarchical ? $term->term_id : $term->slug;
				echo '<option value="' . $term_value . '"' . selected( $selected, $term_value, false ) . '>' . $term->name . '</option>'; 
			}
			echo '</select> &nbsp;<span class="description"><a href="'.get_bloginfo( 'url' ) . '/wp-admin/edit-tags.php?taxonomy=' . $id . '">Manage ' . $taxonomy->label . '</a></span>
				<br />' . $desc;
		break;
		// tax_checkboxes
		case 'tax_checkboxes':
			$terms = get_terms( $id, 'get=all' );
			$post_terms = wp_get_object_terms( get_the_ID(), $id );
			$taxonomy = get_taxonomy( $id );
			$checked = $post_terms ? $taxonomy->hierarchical ? $post_terms[0]->term_id : $post_terms[0]->slug : null;
			foreach ( $terms as $term ) {
				$term_value = $taxonomy->hierarchical ? $term->term_id : $term->slug;
				echo '<input type="checkbox" value="' . $term_value . '" name="' . $id . '[]" id="term-' . $term_value . '"' . checked( $checked, $term_value, false ) . ' /> <label for="term-' . $term_value . '">' . $term->name . '</label><br />';
			}
			echo '<span class="description">' . $field['desc'] . ' <a href="'.get_bloginfo( 'url' ) . '/wp-admin/edit-tags.php?taxonomy=' . $id . '&post_type=' . $page . '">Manage ' . $taxonomy->label . '</a></span>';
		break;
		// date
		case 'date':
			echo '<input type="text" class="rg-datepicker" name="'.esc_attr( $name ).'" id="'.$id.'-'.$repeatable[0].'" value="'.esc_attr( $meta ).'" style="width:'.esc_attr( $width ).';" size="30" />
					<br />' . $desc;
		break;
		//release date
		case 'releasedate':
			echo '<input type="text" class="rg-release-datepicker" name="'.esc_attr( $name ).'" id="'.$id.'-'.$repeatable[0].'" value="'.esc_attr( $meta ).'" style="width:'.esc_attr( $width ).';" size="30" />
					<br />' . $desc;
		break;
		//fromdate
		case 'fromdate':
			echo '<input type="text" name="'.esc_attr( $name ).'" id="'.esc_attr( $id ).'" value="'.esc_attr( $meta ).'" class="datepicker_from" style="width:'.esc_attr( $width ).';" size="30" />';
		break;
		//todate
		case 'todate':
			echo '<input type="text" name="'.esc_attr( $name ).'" id="'.esc_attr( $id ).'" value="'.esc_attr( $meta ).'" class="datepicker_to" style="width:'.esc_attr( $width ).';" size="30" />';
		break;
		//timepicker
		case 'timepicker':
			echo '<input type="text" name="'.esc_attr( $name ).'" id="'.$id.$repeatable[0].'" value="'.esc_attr( $meta ).'" class="rg-timepicker" style="width:'.esc_attr( $width ).';" size="30" /><br />' . $desc;
		break;
		// slider
		case 'slider':
		$value = $meta != '' ? intval( $meta ) : '0';
			echo '<div id="' . esc_attr( $id ) . '-slider"></div>
					<input type="text" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="' . $value . '" size="5" />
					<br />' . $desc;
		break;
		// image
		case 'image':
			$image = CUSTOM_METABOXES_DIR . '/images/image.png';	
			echo '<div class="meta_box_image">
					<span class="meta_box_default_image" style="display:none">' . $image . '</span>';
			if ( $meta ) {
				$image = wp_get_attachment_image_src( intval( $meta ), 'medium' );
			}
			echo '<input name="'.$name.'" type="hidden" class="meta_box_upload_image" value="' . intval( $meta ) . '" style="width:'.$width.';" />
	      	     <img src="' . esc_attr( $image[0] ) . '" class="meta_box_preview_image" alt="'.$image->post_title.'" style="width:150px;" /><br/>
	      		 <a href="#" class="meta_box_upload_image_button button" rel="' . get_the_ID() . '">Choose Image</a>
	      		 <small><a href="#" class="meta_box_clear_image_button">Remove Image</a></small>
	      	   </div>' . $desc;
		break;
		// file
		case 'file':		
			$iconClass = 'meta_box_file';
			if ( $meta ) $iconClass .= ' checked';
			echo	'<div class="meta_box_file_stuff"><input name="' . esc_attr( $name ) . '" type="hidden" class="meta_box_upload_file" value="' . esc_url( $meta ) . '" />
						<span class="' . $iconClass . '"></span>
						<span class="meta_box_filename">' . esc_url( $meta ) . '</span>
							<a href="#" class="meta_box_upload_image_button button" rel="' . get_the_ID() . '">Choose File</a>
							<small>&nbsp;<a href="#" class="meta_box_clear_file_button">Remove File</a></small></div>
							<br clear="all" />' . $desc;
		break;
		// repeatable
		case 'repeatable':
			echo '<table id="' . esc_attr( $id ) . '-repeatable" class="meta_box_repeatable" cellspacing="0">
				<thead>
					<tr>
						<th><span class="sort_label"></span></th>
						<th>Fields</th>
						<th><a class="meta_box_repeatable_add" href="#"></a></th>
					</tr>
				</thead>
				<tbody>';
			$i = 0;
			// create an empty array
			if ( $meta == '' || $meta == array() ) {
				$keys = wp_list_pluck( $repeatable_fields, 'id' );
				$meta = array ( array_fill_keys( $keys, null ) );
			}
			$meta = array_values( $meta );
			foreach( $meta as $row ) {
				echo '<tr><td colspan="3">';
				echo '<div class="btns"><a class="meta_box_repeatable_remove" href="#"></a></div>';
				foreach ( $repeatable_fields as $repeatable_field ) {
					if ( ! array_key_exists( $repeatable_field['id'], $meta[$i] ) )
						$meta[$i][$repeatable_field['id']] = null;
					echo '<div class="r-row" id="'.$repeatable_field['id'].'">';
						echo '<label>' . $repeatable_field['label']  . '</label>';
						echo custom_meta_box_field( $repeatable_field, $meta[$i][$repeatable_field['id']], array( $id, $i ) );
					echo '</div>';
				} // end each field
				echo '</td></tr>';
				$i++;
			} // end each row
			echo '</tbody>';
			echo '</table>
				' . $desc;
		break;
		// group of fields
		case 'input_group':
			echo '<table id="' . esc_attr( $id ) . '-group" class="meta_box_group" cellspacing="0">
				<tbody>';
			$i = 0;
			if ( $meta == '' || $meta == array() ) {
				$keys = wp_list_pluck( $group_fields, 'id' );
				$meta = array ( array_fill_keys( $keys, null ) );
			}
			$meta = array_values( $meta );
			foreach( $meta as $row ) {
				echo '<tr><td colspan="3">';
				foreach ( $group_fields as $group_field ) {
					if ( ! array_key_exists( $group_field['id'], $meta[$i] ) )
						$meta[$i][$group_field['id']] = null;
					echo '<div class="r-row" id="'.$group_field['id'].'">';
					echo '<label>' . $group_field['label']  . '</label>';
					echo custom_meta_box_field( $group_field, $meta[$i][$group_field['id']], array( $id, $i ) );
					echo '</div>';
				} // end each field
				echo '</td></tr>';
				$i++;
			} // end each row
			echo '</tbody>';
			echo '</table>
				' . $desc;
		break;

	} //end switch
		
}


/**
 * Finds any item in any level of an array
 *
 * @param	string	$needle 	field type to look for
 * @param	array	$haystack	an array to search the type in
 *
 * @return	bool				whether or not the type is in the provided array
 */
function meta_box_find_field_type( $needle, $haystack ) {
	foreach ( $haystack as $h )
		if ( isset( $h['type'] ) && $h['type'] == 'repeatable' )
			return meta_box_find_field_type( $needle, $h['repeatable_fields'] );
		elseif ( ( isset( $h['type'] ) && $h['type'] == $needle ) || ( isset( $h['repeatable_type'] ) && $h['repeatable_type'] == $needle ) )
			return true;
	return false;
}

/**
 * Find repeatable
 *
 * This function does almost the same exact thing that the above function 
 * does, except we're exclusively looking for the repeatable field. The 
 * reason is that we need a way to look for other fields nested within a 
 * repeatable, but also need a way to stop at repeatable being true. 
 * Hopefully I'll find a better way to do this later.
 *
 * @param	string	$needle 	field type to look for
 * @param	array	$haystack	an array to search the type in
 *
 * @return	bool				whether or not the type is in the provided array
 */
function meta_box_find_repeatable( $needle = 'repeatable', $haystack ) {
	foreach ( $haystack as $h )
		if ( isset( $h['type'] ) && $h['type'] == $needle )
			return true;
	return false;
}

/**
 * sanitize boolean inputs
 */
function meta_box_santitize_boolean( $string ) {
	if ( ! isset( $string ) || $string != 1 || $string != true )
		return false;
	else
		return true;
}

/**
 * outputs properly sanitized data
 *
 * @param	string	$string		the string to run through a validation function
 * @param	string	$function	the validation function
 *
 * @return						a validated string
 */
function meta_box_sanitize( $string, $function = 'sanitize_text_field' ) {
	switch ( $function ) {
		case 'intval':
			return intval( $string );
		case 'absint':
			return absint( $string );
		case 'wp_kses_post':
			return wp_kses_post( $string );
		case 'wp_kses_data':
			return wp_kses_data( $string );
		case 'esc_url_raw':
			return esc_url_raw( $string );
		case 'is_email':
			return is_email( $string );
		case 'sanitize_title':
			return sanitize_title( $string );
		case 'santitize_boolean':
			return santitize_boolean( $string );
		case 'sanitize_text_field':
			return sanitize_text_field( $string );
		default:
			return $string;
	}
}

/**
 * Map a multideminsional array
 *
 * @param	string	$func		the function to map
 * @param	array	$meta		a multidimensional array
 * @param	array	$sanitizer	a matching multidimensional array of sanitizers
 *
 * @return	array				new array, fully mapped with the provided arrays
 */
function meta_box_array_map_r( $func, $meta, $sanitizer ) {
		
	$newMeta = array();
	$meta = array_values( $meta );
	
	foreach( $meta as $key => $array ) {
		if ( $array == '' )
			continue;
		/**
		 * some values are stored as array, we only want multidimensional ones
		 */
		if ( ! is_array( $array ) ) {
			return array_map( $func, $meta, (array)$sanitizer );
			break;
		}
		/**
		 * the sanitizer will have all of the fields, but the item may only 
		 * have valeus for a few, remove the ones we don't have from the santizer
		 */
		$keys = array_keys( $array );
		$newSanitizer = $sanitizer;
		if ( is_array( $sanitizer ) ) {
			foreach( $newSanitizer as $sanitizerKey => $value )
				if ( ! in_array( $sanitizerKey, $keys ) )
					unset( $newSanitizer[$sanitizerKey] );
		}
		/**
		 * run the function as deep as the array goes
		 */
		foreach( $array as $arrayKey => $arrayValue )
			if ( is_array( $arrayValue ) )
				$array[$arrayKey] = meta_box_array_map_r( $func, $arrayValue, $newSanitizer[$arrayKey] );
		
		$array = array_map( $func, $array, $newSanitizer );
		$newMeta[$key] = array_combine( $keys, array_values( $array ) );
	}
	return $newMeta;
}

/**
 * takes in a few peices of data and creates a custom meta box
 *
 * @param	string			$id			meta box id
 * @param	string			$title		title
 * @param	array			$fields		array of each field the box should include
 * @param	string|array	$page		post type to add meta box to
 */
class Custom_Add_Meta_Box {
	
	var $id;
	var $title;
	var $fields;
	var $page;
	
    public function __construct( $id, $title, $fields, $page ) {
		$this->id = $id;
		$this->title = $title;
		$this->fields = $fields;
		$this->page = $page;
		
		if( ! is_array( $this->page ) )
			$this->page = array( $this->page );
		
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_head',  array( $this, 'admin_head' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_box' ) );
		add_action( 'save_post',  array( $this, 'save_box' ));
    }
	
	/**
	 * enqueue necessary scripts and styles
	 */
	function admin_enqueue_scripts() {
		global $pagenow;
		if ( in_array( $pagenow, array( 'post-new.php', 'post.php' ) ) && in_array( get_post_type(), $this->page ) ) {
			// js
			$deps = array( 'jquery' );
			if ( meta_box_find_field_type( 'date', $this->fields ) || meta_box_find_field_type( 'releasedate', $this->fields ) || meta_box_find_field_type( 'input_group', $this->fields ) || meta_box_find_field_type( 'repeatable', $this->fields ) || meta_box_find_field_type( 'timepicker', $this->fields ) )
				$deps[] = 'jquery-ui-datepicker';
			if ( meta_box_find_field_type( 'slider', $this->fields ) || meta_box_find_field_type( 'timepicker', $this->fields ) )
				$deps[] = 'jquery-ui-slider';
			if ( meta_box_find_field_type( 'timepicker', $this->fields ) )
				wp_enqueue_script( 'rg-timepicker', get_template_directory_uri(). '/inc/metaboxes/js/jquery.timepicker.1.4.5.js', array( 'jquery' ) );
			if ( meta_box_find_field_type( 'color', $this->fields ) )
				wp_enqueue_script('wp-color-picker');
			if ( meta_box_find_field_type( 'chosen', $this->fields ) || meta_box_find_field_type( 'post_chosen', $this->fields ) )
				$deps[] = 'chosen';
				wp_enqueue_script( 'chosen', get_template_directory_uri(). '/inc/metaboxes/js/chosen.js', array( 'jquery' ) );
				wp_enqueue_style( 'chosen', get_template_directory_uri(). '/inc/metaboxes/css/chosen.css' );
			
			if ( in_array( true, array( 
				meta_box_find_field_type( 'releasedate', $this->fields ),
				meta_box_find_field_type( 'date', $this->fields ),
				meta_box_find_field_type( 'timepicker', $this->fields ),
				meta_box_find_field_type( 'slider', $this->fields ),
				meta_box_find_field_type( 'color', $this->fields ),
				meta_box_find_field_type( 'chosen', $this->fields ),
				meta_box_find_field_type( 'post_chosen', $this->fields ),
				meta_box_find_repeatable( 'repeatable', $this->fields ),
				meta_box_find_repeatable( 'input_group', $this->fields ),
				meta_box_find_field_type( 'image', $this->fields ),
				meta_box_find_field_type( 'file', $this->fields ),
				meta_box_find_field_type( 'text', $this->fields )
			) ) )
				wp_enqueue_script( 'meta_box', get_template_directory_uri(). '/inc/metaboxes/js/custom-admin.js', $deps );
			
			// css
			$deps = array();
			wp_register_style( 'jqueryui', get_template_directory_uri(). '/inc/metaboxes/css/jqueryui.css' );
			if ( meta_box_find_field_type( 'date', $this->fields ) || meta_box_find_field_type( 'releasedate', $this->fields ) || meta_box_find_field_type( 'slider', $this->fields ) || meta_box_find_field_type( 'input_group', $this->fields ) || meta_box_find_field_type( 'timepicker', $this->fields ) || meta_box_find_field_type( 'repeatable', $this->fields ) )
				$deps[] = 'jqueryui';
			if ( meta_box_find_field_type( 'color', $this->fields ) )
				wp_enqueue_style('wp-color-picker');
			wp_enqueue_style( 'meta_box', get_template_directory_uri(). '/inc/metaboxes/css/meta_box.css', $deps );
		}
	}
	
	/**
	 * adds scripts to the head for special fields with extra js requirements
	 */
	function admin_head() {
		if ( in_array( get_post_type(), $this->page ) && ( meta_box_find_field_type( 'date', $this->fields ) || meta_box_find_field_type( 'slider', $this->fields ) || meta_box_find_field_type( 'releasedate', $this->fields ) || meta_box_find_field_type( 'timepicker', $this->fields ) || meta_box_find_field_type( 'input_group', $this->fields ) || meta_box_find_field_type( 'repeatable', $this->fields ) ) ) {
		
			echo '<script type="text/javascript">
						jQuery(function($) {';
			foreach ( $this->fields as $field ) {
				switch( $field['type'] ) {
					// date
					case 'date' :
						echo '$( "input.rg-datepicker" ).datepicker({ dateFormat: "DD - M \u2022 d" });';
					break;
					//release
					case 'releasedate' :
						echo '$( "input.rg-release-datepicker" ).datepicker({ dateFormat: "M yy" });';
					break;
					//repeatable
					case 'repeatable' :
						echo '$("input.rg-datepicker").each(function(){ 
								$(this).datepicker({ dateFormat: "MM d" });
							  });
							  
							  $("input.rg-datepicker").each(function(){ 
								$(this).timepicker({ timeFormat: "hh:mm tt" });
							  });';
					break;
					// tofromdate
					case 'input_group' :
						echo '$( "input.datepicker_from" ).datepicker({
							    defaultDate: "+1w",
							    dateFormat: "MM d, yy",
							    changeMonth: true,
							    numberOfMonths: 3,
							    onClose: function( selectedDate ) {
							      $( "input.datepicker_to" ).datepicker( "option", "minDate", selectedDate );
							    }
							  });
							  $( "input.datepicker_to" ).datepicker({
							    defaultDate: "+1w",
							    dateFormat: "MM d, yy",
							    changeMonth: true,
							    numberOfMonths: 3,
							    onClose: function( selectedDate ) {
							      $( "input.datepicker_from" ).datepicker( "option", "maxDate", selectedDate );
							    }
							  });';
					break;
					// slider
					case 'slider' :
					$value = get_post_meta( get_the_ID(), $field['id'], true );
					if ( $value == '' )
						$value = $field['min'];
					echo '
							$( "#' . $field['id'] . '-slider" ).slider({
								value: ' . $value . ',
								min: ' . $field['min'] . ',
								max: ' . $field['max'] . ',
								step: ' . $field['step'] . ',
								slide: function( event, ui ) {
									$( "#' . $field['id'] . '" ).val( ui.value );
								}
							});';
					break;
					// timepicker
					case 'timepicker' :
						echo '$("input#'.$field['id'].'").timepicker({timeFormat: "ga", step: 60});';
					break;
				}
			}
			
			echo '});
				</script>';
		
		}
	}
	
	/**
	 * adds the meta box for every post type in $page
	 */
	function add_box() {
		foreach ( $this->page as $page ) {
			add_meta_box( $this->id, $this->title, array( $this, 'meta_box_callback' ), $page, 'normal', 'high' );
		}
	}
	
	/**
	 * outputs the meta box
	 */
	function meta_box_callback() {
		// Use nonce for verification
		wp_nonce_field( 'custom_meta_box_nonce_action', 'custom_meta_box_nonce_field' );
		
		// Begin the field table and loop
		echo '<table class="form-table meta_box">';
		foreach ( $this->fields as $field) {
			if ( $field['type'] == 'section' ) {
				echo '<tr id="'.$field['id'].'_row">
						<td colspan="2">
							<h2>' . $field['label'] . '</h2>
						</td>
					</tr>';
			} else {
				echo '<tr id="'.$field['id'].'_row">';
						// echo '<th style="width:15%"><label for="' . $field['id'] . '">' . $field['label'] . '</label></th>';
						echo '<td>';
						echo '<div class="lhold"><label for="' . $field['id'] . '">' . $field['label'] . '</label></div>';
						
						$meta = get_post_meta( get_the_ID(), $field['id'], true);
						echo custom_meta_box_field( $field, $meta );
						
				echo '</td>
					</tr>';
			}
		} // end foreach
		echo '</table>'; // end table
	}
	
	/**
	 * saves the captured data
	 */
	function save_box( $post_id ) {
		$post_type = get_post_type();
		
		// verify nonce
		if ( ! isset( $_POST['custom_meta_box_nonce_field'] ) )
			return $post_id;
		if ( ! ( in_array( $post_type, $this->page ) || wp_verify_nonce( $_POST['custom_meta_box_nonce_field'],  'custom_meta_box_nonce_action' ) ) ) 
			return $post_id;
		// check autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;
		// check permissions
		if ( ! current_user_can( 'edit_page', $post_id ) )
			return $post_id;
		
		// loop through fields and save the data
		foreach ( $this->fields as $field ) {
			if( $field['type'] == 'section' ) {
				$sanitizer = null;
				continue;
			}
			if( in_array( $field['type'], array( 'tax_select', 'tax_checkboxes' ) ) ) {
				// save taxonomies
				if ( isset( $_POST[$field['id']] ) ) {
					$term = $_POST[$field['id']];
					wp_set_object_terms( $post_id, $term, $field['id'] );
				}
			}
			else {
				// save the rest
				$new = false;
				$old = get_post_meta( $post_id, $field['id'], true );
				if ( isset( $_POST[$field['id']] ) )
					$new = $_POST[$field['id']];
				if ( isset( $new ) && '' == $new && $old ) {
					delete_post_meta( $post_id, $field['id'], $old );
				} elseif ( isset( $new ) && $new != $old ) {
					$sanitizer = isset( $field['sanitizer'] ) ? $field['sanitizer'] : '';
					if ( is_array( $new ) )
						$new = meta_box_array_map_r( 'meta_box_sanitize', $new, $sanitizer );
					else
						$new = meta_box_sanitize( $new, $sanitizer );
					update_post_meta( $post_id, $field['id'], $new );
				}
			}
		} // end foreach
	}
	
}