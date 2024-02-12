<?php

class TheGem_Template_Element_Product_SizeGuide extends TheGem_Product_Template_Element {

	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_product_size_guide';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'source' => 'default',
			'size_guide' => '',
			'title' => 'Size guide',
			'alignment' => 'left',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_design_options_extract('single-product')
        ), $atts, 'thegem_te_product_size_guide');
		
		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-product-size-guide', $params);
		
		// Init Sizeguide
		ob_start();
		$product = thegem_templates_init_product();
		if (empty($product)) {
			ob_end_clean();
			return thegem_templates_close_product($this->get_name(), $this->shortcode_settings(), '');
		}

        if ($params['source'] == 'default') {
	        $image_src = thegem_get_option('size_guide_image');
	        $title = thegem_get_option('size_guide_text');
        } else {
	        $image_id = preg_replace( '/[^\d]/', '', $params['size_guide'] );
	        $image_src = wp_get_attachment_image_src( $image_id, 'full' );
	        if ( ! empty( $image_src[0] ) ) {
		        $image_src = $image_src[0];
	        }
	        $title = $params['title'];
        }
		
		if (empty($image_src)) {
			ob_end_clean();
			return thegem_templates_close_product($this->get_name(), $this->shortcode_settings(), '');
		}

		?>

		<div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
             class="thegem-te-product-size-guide <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>"
			<?= thegem_data_editor_attribute($uniqid . '-editor') ?>>
            
            <div class="product-size-guide"><a href="<?= esc_url($image_src); ?>" class="fancybox"><?= esc_html($title); ?></a></div>
		</div>

		<?php
		//Custom Styles
		$customize = '.thegem-te-product-size-guide.'.$uniqid;
		
		if (!empty($params['alignment'])) {
			$custom_css .= $customize.' .product-size-guide a {justify-content: ' . $params['alignment'] . '; text-align: ' . $params['alignment'] . ';}';
		}
		
		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));

		// Print custom css
		$css_output = '';
		if(!empty($custom_css)) {
			$css_output = '<style>'.$custom_css.'</style>';
		}

		$return_html = $css_output.$return_html;
		return thegem_templates_close_product($this->get_name(), $this->shortcode_settings(), $return_html);
	}

	public function shortcode_settings() {

		return array(
			'name' => __('Product Size Guide', 'thegem'),
			'base' => 'thegem_te_product_size_guide',
			'icon' => 'thegem-icon-wpb-ui-element-product-size_guide',
			'category' => __('Single Product Builder', 'thegem'),
			'description' => __('Product Size Guide (Product Builder)', 'thegem'),
			'params' => array_merge(

			    /* General - Layout */
				array(
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Layout', 'thegem'),
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Source', 'thegem'),
						'param_name' => 'source',
						'value' => array_merge(array(
                                __('Default from Theme Options', 'thegem') => 'default',
                                __('Custom Image', 'thegem') => 'custom',
                            )
						),
						'std' => 'default',
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'attach_image',
						'heading' => esc_html__( 'Image', 'thegem' ),
						'param_name' => 'size_guide',
						'value' => '',
						'description' => esc_html__( 'Select image from media library.', 'thegem' ),
						'dependency' => array(
							'element' => 'source',
							'value' => 'custom'
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General'
					),
					array(
						"type" => "textfield",
						'heading' => __('Title', 'thegem'),
						'param_name' => 'title',
						'std' => 'Size guide',
						'dependency' => array(
							'element' => 'source',
							'value' => 'custom'
						),
						"edit_field_class" => "vc_column vc_col-sm-12",
						'group' => 'General'
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Alignment', 'thegem'),
						'param_name' => 'alignment',
						'value' => array_merge(array(
								__('Left', 'thegem') => 'left',
								__('Center', 'thegem') => 'center',
								__('Right', 'thegem') => 'right',
							)
						),
						'std' => 'left',
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
				),
				
				/* Extra Options */
				thegem_set_elements_extra_options(),
				
				/* Flex Options */
				thegem_set_elements_design_options('single-product')
			),
		);
	}
}

$templates_elements['thegem_te_product_size_guide'] = new TheGem_Template_Element_Product_SizeGuide();
$templates_elements['thegem_te_product_size_guide']->init();
