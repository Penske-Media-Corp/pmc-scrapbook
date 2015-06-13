<?php
/*
Plugin Name: PMC Scrapbook
Description: Scrapbook for new features, plugins, themes, etc.
Version: 1.0.0
Author: Gabriel Koen, PMC
Author URI: http://pmc.com/
*/

add_action( 'init', function() {

	$labels = array(
		'name'                => _x( 'Scrapbook Items', 'Post Type General Name', 'pmc-scrapbook' ),
		'singular_name'       => _x( 'Scrapbook', 'Post Type Singular Name', 'pmc-scrapbook' ),
		'menu_name'           => __( 'Scrapbook', 'pmc-scrapbook' ),
		'parent_item_colon'   => __( 'Parent Item:', 'pmc-scrapbook' ),
		'all_items'           => __( 'All Items', 'pmc-scrapbook' ),
		'view_item'           => __( 'View Item', 'pmc-scrapbook' ),
		'add_new_item'        => __( 'Add New Item', 'pmc-scrapbook' ),
		'add_new'             => __( 'Add New', 'pmc-scrapbook' ),
		'edit_item'           => __( 'Edit Item', 'pmc-scrapbook' ),
		'update_item'         => __( 'Update Item', 'pmc-scrapbook' ),
		'search_items'        => __( 'Search Item', 'pmc-scrapbook' ),
		'not_found'           => __( 'Not found', 'pmc-scrapbook' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'pmc-scrapbook' ),
	);
	$rewrite = array(
		'slug'                => 'scrapbook',
		'with_front'          => true,
		'pages'               => true,
		'feeds'               => true,
	);
	$args = array(
		'label'               => __( 'pmc-scrapbook', 'pmc-scrapbook' ),
		'description'         => __( 'New features, plugins, and themes.', 'pmc-scrapbook' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
		'taxonomies'          => array( 'post_tag' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite,
		'capability_type'     => 'post',
	);
	register_post_type( 'pmc-scrapbook', $args );

} );


function pmc_scrapbook_post_custom_field_definitions() {
	$scrapbook_group = 'scrapbook_details';

	$custom_fields = array(
		'pmc_scrapbook_jira_url'  => array(
			'label' => 'JIRA URL',
			'group' => $scrapbook_group,
		),

		'pmc_scrapbook_bitbucket_url'      => array(
			'label' => 'Bitbucket URL',
			'group' => $scrapbook_group,
		),

		'pmc_scrapbook_github_url'      => array(
			'label' => 'Github URL',
			'group' => $scrapbook_group,
		),

		'pmc_scrapbook_wporg_plugin_url'      => array(
			'label' => 'WP.org plugin URL',
			'group' => $scrapbook_group,
		),

		'pmc_scrapbook_url'      => array(
			'label' => 'Link URL',
			'group' => $scrapbook_group,
		),

		'pmc_scrapbook_technical_lead'         => array(
			'label' => 'Technical Lead',
			'group' => $scrapbook_group,
			'field_type' => 'select',
			'values' => array(
				'',
				'Amit Sannad',
				'Corey Gilmore',
				'Hau Vong',
				'Amit Gupta',
				'Adaeze Esiobu',
				'Vicky Biswas',
				'Gabriel Koen',
			),
		),

		'pmc_scrapbook_agency'         => array(
			'label' => 'Agency',
			'group' => $scrapbook_group,
			'field_type' => 'select',
			'values' => array(
				'',
				'10up',
				'Oomph',
				'Crowd Favorite',
				'Code and Theory',
			),
		),

		'pmc_scrapbook_project_manager'         => array(
			'label' => 'Project Manager',
			'group' => $scrapbook_group,
			'field_type' => 'select',
			'values' => array(
				'',
				'Christina Yeoh',
				'Derek Ramsay',
			),
		),

		'pmc_scrapbook_product_manager'         => array(
			'label' => 'Product Manager',
			'group' => $scrapbook_group,
			'field_type' => 'select',
			'values' => array(
				'',
				'Nick Soriano',
				'Zareh Shabani',
				'Ian Larson',
			),
		),

		'pmc_scrapbook_design_lead'         => array(
			'label' => 'Design Lead',
			'group' => $scrapbook_group,
			'field_type' => 'select',
			'values' => array(
				'',
				'Nelson Anderson',
				'Robb Rice',
			),
		),

		'pmc_scrapbook_seo_manager'         => array(
			'label' => 'SEO Manager',
			'group' => $scrapbook_group,
			'field_type' => 'select',
			'values' => array(
				'',
				'Ankur Vakil',
				'Mario Con',
				'Debra Krein',
			),
		),

		'pmc_scrapbook_tags'      => array(
			'label'             => 'Tags',
			'group'             => $scrapbook_group,
			'field_type'        => 'taxonomy_radio',
			'taxonomy'          => 'post_tag',
			'multiple' => true,
			'display_callback'  => 'pmc_scrapbook_post__display_taxonomy_callback',
		),

	);

	return $custom_fields;

}

/**
 *
 * @param  string $field_slug the slug/id of the field
 * @param  object $field the field object
 * @param  string $object_type what object type is the field associated with
 * @param  int $object_id the ID of the current object
 * @param  string $value the value of the field
 * @return void
 */
function pmc_scrapbook_post__display_taxonomy_callback( $field_slug, $field, $object_type, $object_id, $value ) {
	global $custom_metadata_manager;

	if( is_array($value) && !empty($value) )
		$value = $value[0];
	else
		$value = '';

	$cloneable = (isset($field->multiple) && $field->multiple);
	$readonly_str = ($field->readonly) ? 'readonly="readonly" ' : '';

	if (!in_array($object_type, $custom_metadata_manager->_non_post_types))
		global $post;

	$field_id = $field_slug.'[]';

	$terms = get_terms( $field->taxonomy, array('hide_empty' => false));

	$term_whitelist = array(
		'411',
		'advertising',
		'analytics',
		'awardsline',
		'aws',
		'bgr',
		'deadline',
		'engineering',
		'events and conferences',
		'feature',
		'hollywoodlife',
		'migration',
		'open source',
		'plugin',
		'pmc corporate',
		'pmc studios',
		'redesign',
		'stratogen',
		'tvline',
		'variety insight',
		'variety latino',
		'variety',
		'wordpress.com vip',
	);

	foreach ( $terms as $key => $term ) {
		if ( false === array_search( strtolower( $term->name ), $term_whitelist ) ) {
			unset($terms[$key]);
		}
	}

	?>
	<div class="custom-metadata-field <?php echo $field->field_type ?>">
		<label for="<?php echo $field_slug; ?>"><?php echo $field->label; ?></label>
		<div class="<?php echo $field_slug ?><?php echo ( $cloneable ) ? ' cloneable' : ''; ?>" id="<?php echo $field_slug ?>">

			<?php
			foreach ( $terms as $term ) : ?>
				<label class="selectit"><input name="<?php echo esc_attr($field_id); ?>" type="checkbox" id="<?php echo esc_attr($term->slug); ?>" value="<?php echo esc_attr($term->slug); ?>" <?php checked( has_tag( intval( $term->term_id ), $GLOBAL['post'] ) ) ?>> <?php echo esc_html($term->name); ?></label>
			<?php endforeach; ?>
			</select>
		</div>
	</div>

	<?php
}

add_action( 'admin_init', function() {
	$scrapbook_group = 'scrapbook_details';
	$custom_fields = pmc_scrapbook_post_custom_field_definitions();

	if( function_exists( 'x_add_metadata_field' ) && function_exists( 'x_add_metadata_group' ) ) {
		x_add_metadata_group( $scrapbook_group, 'pmc-scrapbook', array(
			'label' => 'Details'
		));

		foreach( $custom_fields as $field => $opts ) {
			if( !empty($opts) ) {
				x_add_metadata_field( $field, 'pmc-scrapbook', $opts );
			}
		}
	}

} );

add_filter( 'the_content', function( $content ) {
	if ( 'pmc-scrapbook' !== get_post_type( $GLOBALS['post'] ) ) {
		return $content;
	}

	$custom_fields = pmc_scrapbook_post_custom_field_definitions();
	$post_meta = get_metadata( 'post', $GLOBALS['post']->ID );

	$data = '';
	foreach ( $post_meta as $meta_key => $meta_value ) {
		if ( 'pmc_scrapbook_tags' === $meta_key ) {
			continue;
		}

		if ( isset($custom_fields[$meta_key]) ) {
			$label = $custom_fields[$meta_key]['label'];
			$values = ( isset($custom_fields[$meta_key]['values']) ) ? $custom_fields[$meta_key]['values'] : array();
			$value = $meta_value[0];

			if ( $value == intval($value) && isset($values[$value]) ) {
				$value = $values[$value];
			}

			if ( parse_url( $value, PHP_URL_SCHEME ) ) {
				$data .= '<li><span class="scrapbook-title">' . esc_html( $label ) . ':</span> <a href="' . esc_url( $value ) . '">' . esc_html( $value ) . '</a></li>';
			} else {
				$data .= '<li><span class="scrapbook-title">' . esc_html( $label ) . ':</span> ' . esc_html( $value ) . '</li>';
			}
		}
	}

	if ( $data ) {
		$data = '<ul class="pmc-scrapbook">' . $data . '</ul>';
	}

	return $content . $data;
} );

//EOF