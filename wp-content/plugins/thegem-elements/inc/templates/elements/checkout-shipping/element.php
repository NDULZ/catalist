<?php

class TheGem_Template_Element_Checkout_Shipping extends TheGem_Checkout_Template_Element
{
	
	public function __construct(){
	}
	
	public function get_name(){
		return 'thegem_te_checkout_shipping';
	}
	
	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'layout' => '',
			'heading' => '1',
			'heading_alignment' => 'left',
			'heading_text_style' => '',
			'heading_font_weight' => 'light',
			'heading_letter_spacing' => '',
			'heading_text_transform' => '',
			'heading_text_color' => '',
			'heading_spacing_desktop' => '',
			'heading_spacing_tablet' => '',
			'heading_spacing_mobile' => '',
			'label_text_color' => '',
			'input_text_color' => '',
			'input_background_color' => '',
			'input_border_color' => '',
			'input_border_color_error' => '',
			'input_placeholder_color' => '',
			'input_border_radius' => '',
			'input_checkbox_border_radius' => '',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_responsive_options_extract()
		), $atts, 'thegem_te_checkout_shipping');
		
		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-') . rand(1, 9999);
  
		$params['element_class'] = implode(' ', array($params['element_class'], $params['layout'], thegem_templates_responsive_options_output($params)));
		$title_classes = implode(' ', array('checkout-shipping-title', $params['heading_text_style'], $params['heading_font_weight']));
		$title_tag = empty($params['heading_text_style']) ? 'h3' : 'div';
  
		ob_start();
		$checkout = WC()->checkout();
  
		?>

        <div <?php if (!empty($params['element_id'])): ?>id="<?= esc_attr($params['element_id']); ?>"<?php endif; ?>
             class="thegem-te-checkout-shipping <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">
            
            <div class="woocommerce-shipping-fields">
				<?php if (true === WC()->cart->needs_shipping_address()) : ?>
			
                    <?php if (!empty($params['heading'])) : ?>
                        <<?= $title_tag; ?> id="ship-to-different-address-title" class="<?= $title_classes; ?>"><?php esc_html_e( 'Different address?', 'thegem' ); ?></<?= $title_tag; ?>>
                    <?php endif; ?>

                    <p id="ship-to-different-address" class="form-row">
                        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                            <input id="ship-to-different-address-checkbox"
                                   class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox gem-checkbox" <?php checked(apply_filters('woocommerce_ship_to_different_address_checked', 'shipping' === get_option('woocommerce_ship_to_destination') ? 1 : 0), 1); ?>
                                   type="checkbox" name="ship_to_different_address" value="1"/>
                            <span><?php esc_html_e('Ship to a different address?', 'woocommerce'); ?></span>
                        </label>
                    </p>

                    <div class="shipping_address">
						<?php do_action('woocommerce_before_checkout_shipping_form', $checkout); ?>

                        <div class="woocommerce-shipping-fields__field-wrapper">
							<?php
							$fields = $checkout->get_checkout_fields('shipping');
							
							foreach ($fields as $key => $field) {
								woocommerce_form_field($key, $field, $checkout->get_value($key));
							}
							?>
                            <div class="clear"></div>
                        </div>
						
						<?php do_action('woocommerce_after_checkout_shipping_form', $checkout); ?>
                    </div>
				
				<?php endif; ?>
            </div>
            <div class="woocommerce-additional-fields">
				<?php do_action('woocommerce_before_order_notes', $checkout); ?>
				
				<?php if (apply_filters('woocommerce_enable_order_notes_field', 'yes' === get_option('woocommerce_enable_order_comments', 'yes'))) : ?>
			
                    <?php if ( ! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only() ) : ?>
                        <?php if (!empty($params['heading'])) : ?>
                            <<?= $title_tag; ?> class="<?= $title_classes; ?>"><?php esc_html_e( 'Additional information', 'woocommerce' ); ?></<?= $title_tag; ?>>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="woocommerce-additional-fields__field-wrapper">
						<?php foreach ($checkout->get_checkout_fields('order') as $key => $field) : ?>
							<?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
						<?php endforeach; ?>
                    </div>
        
				<?php endif; ?>
				
				<?php do_action('woocommerce_after_order_notes', $checkout); ?>
            </div>
        </div>
		
		<?php
		//Custom Styles
		$customize = '.thegem-te-checkout-shipping.'.$uniqid;
		$custom_css = '';
		$resolution = array('desktop', 'tablet', 'mobile');
		$directions = array('top', 'bottom', 'left', 'right');
		
		// Heading Styles
		if (!empty($params['heading_alignment'])) {
			$custom_css .= $customize.' .checkout-shipping-title {text-align: ' . $params['heading_alignment'] . ';}';
		}
		if (!empty($params['heading_letter_spacing'])) {
			$custom_css .= $customize.' .checkout-shipping-title {letter-spacing: ' . $params['heading_letter_spacing'] . 'px;}';
		}
		if (!empty($params['heading_text_transform'])) {
			$custom_css .= $customize.' .checkout-shipping-title {text-transform: ' . $params['heading_text_transform'] . ';}';
		}
		if (!empty($params['heading_text_color'])) {
			$custom_css .= $customize.' .checkout-shipping-title {color: ' . $params['heading_text_color'] . ';}';
		}
		foreach ($resolution as $res) {
			if (!empty($params['heading_spacing_'.$res]) || strcmp($params['heading_spacing_'.$res], '0') === 0) {
				$result = str_replace(' ', '', $params['heading_spacing_'.$res]);
				$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px';
				if ($res == 'desktop') {
					$custom_css .= $customize.' .checkout-shipping-title {margin-bottom:'.$result.$unit.' !important;}';
				} else {
					$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
					$custom_css .= '@media screen and ('.$width.') {'.$customize.' .checkout-shipping-title {margin-bottom:'.$result.$unit.' !important;}}';
				}
			}
		}
		
		// Form Styles
		if (!empty($params['input_text_color'])) {
			$custom_css .= $customize.' .form-row input.input-text {color: ' . $params['input_text_color'] . ' !important;}';
			$custom_css .= $customize.' .form-row textarea.input-text {color: ' . $params['input_text_color'] . ' !important;}';
			$custom_css .= $customize.' .form-row .select2-selection__rendered {color: ' . $params['input_text_color'] . ' !important;}';
			$custom_css .= $customize.' .form-row .select2-selection__arrow {color: ' . $params['input_text_color'] . ' !important;}';
			$custom_css .= $customize.' .form-row .checkbox-sign:before {color: ' . $params['input_text_color'] . ' !important;}';
		}
		if (!empty($params['input_background_color'])) {
			$custom_css .= $customize.' .form-row input.input-text {background-color: ' . $params['input_background_color'] . ' !important;}';
			$custom_css .= $customize.' .form-row textarea.input-text {background-color: ' . $params['input_background_color'] . ' !important;}';
			$custom_css .= $customize.' .form-row .select2-selection__rendered {background-color: ' . $params['input_background_color'] . ' !important;}';
			$custom_css .= $customize.' .form-row .checkbox-sign {background-color: ' . $params['input_background_color'] . ' !important;}';
		}
		if (!empty($params['input_border_color'])) {
			$custom_css .= $customize.' .form-row input.input-text {border-color: ' . $params['input_border_color'] . ' !important;}';
			$custom_css .= $customize.' .form-row textarea.input-text {border-color: ' . $params['input_border_color'] . ' !important;}';
			$custom_css .= $customize.' .form-row .select2-selection--single {border-color: ' . $params['input_border_color'] . ' !important;}';
			$custom_css .= $customize.' .form-row .checkbox-sign {border-color: ' . $params['input_border_color'] . ' !important;}';
		}
		if (!empty($params['input_border_color_error'])) {
			$custom_css .= $customize.' .form-row.woocommerce-invalid input.input-text {border-color: ' . $params['input_border_color_error'] . ' !important;}';
			$custom_css .= $customize.' .form-row.woocommerce-invalid textarea.input-text {border-color: ' . $params['input_border_color_error'] . ' !important;}';
			$custom_css .= $customize.' .form-row label abbr {color: ' . $params['input_border_color_error'] . ' !important;}';
		}
		if (!empty($params['input_placeholder_color'])) {
			$custom_css .= $customize.' .form-row .select2-selection__placeholder {color: ' . $params['input_placeholder_color'] . ' !important;}';
			$custom_css .= $customize.' .form-row input.input-text::placeholder {color: ' . $params['input_placeholder_color'] . ' !important;}';
			$custom_css .= $customize.' .form-row textarea.input-text::placeholder {color: ' . $params['input_placeholder_color'] . ' !important;}';
		}
		if (!empty($params['label_text_color'])) {
			$custom_css .= $customize.' .form-row label {color: ' . $params['label_text_color'] . ' !important;}';
			$custom_css .= $customize.' .form-row.create-account span {color: ' . $params['label_text_color'] . ' !important;}';
		}
		if (!empty($params['input_border_radius']) || $params['input_border_radius'] == 0) {
			$custom_css .= $customize.' .form-row input.input-text {border-radius: ' . $params['input_border_radius'] . 'px !important;}';
			$custom_css .= $customize.' .form-row textarea.input-text {border-radius: ' . $params['input_border_radius'] . 'px !important;}';
			$custom_css .= $customize.' .form-row .select2-selection--single {border-radius: ' . $params['input_border_radius'] . 'px !important;}';
			$custom_css .= $customize.' .form-row .select2-selection__rendered {border-radius: ' . $params['input_border_radius'] . 'px !important;}';
		}
		if (!empty($params['input_checkbox_border_radius']) || $params['input_checkbox_border_radius'] == 0) {
			$custom_css .= $customize.' .form-row .checkbox-sign {border-radius: ' . $params['input_checkbox_border_radius'] . 'px !important;}';
		}
		
		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
		
		// Print custom css
		$css_output = '';
		if(!empty($custom_css)) {
			$css_output = '<style>'.$custom_css.'</style>';
		}
		
		$return_html = $css_output.$return_html;
		return $return_html;
	}
	
	public function set_layout_params() {
		$result = array();
		$group = __('General', 'thegem');
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('General', 'thegem'),
			'param_name' => 'general_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Layout', 'thegem'),
			'param_name' => 'layout',
			'value' => array_merge(array(
					__('Default', 'thegem') => '',
					__('Compact', 'thegem') => 'compact',
				)
			),
			'std' => '',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		return $result;
	}
	
	public function set_heading_params() {
		$resolutions = array('desktop', 'tablet', 'mobile');
		$result = array();
		$group = __('General', 'thegem');
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Heading', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Heading', 'thegem'),
			'param_name' => 'heading',
			'value' => array(__('Show', 'thegem') => '1'),
			'std' => '1',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Alignment', 'thegem'),
			'param_name' => 'heading_alignment',
			'value' => array_merge(array(
					__('Left', 'thegem') => 'left',
					__('Center', 'thegem') => 'center',
					__('Right', 'thegem') => 'right',
				)
			),
			'std' => 'left',
			'dependency' => array(
				'element' => 'heading',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Style', 'thegem'),
			'param_name' => 'heading_text_style',
			'value' => array(
				__('Default', 'thegem') => '',
				__('Title H1', 'thegem') => 'title-h1',
				__('Title H2', 'thegem') => 'title-h2',
				__('Title H3', 'thegem') => 'title-h3',
				__('Title H4', 'thegem') => 'title-h4',
				__('Title H5', 'thegem') => 'title-h5',
				__('Title H6', 'thegem') => 'title-h6',
				__('Title xLarge', 'thegem') => 'title-xlarge',
				__('Styled Subtitle', 'thegem') => 'styled-subtitle',
				__('Main Menu', 'thegem') => 'title-main-menu',
				__('Body', 'thegem') => 'text-body',
				__('Tiny Body', 'thegem') => 'text-body-tiny',
			),
			'std' => '',
			'dependency' => array(
				'element' => 'heading',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Font weight', 'thegem'),
			'param_name' => 'heading_font_weight',
			'value' => array(
				__('Default', 'thegem') => '',
				__('Thin', 'thegem') => 'light',
			),
			'std' => 'light',
			'dependency' => array(
				'element' => 'heading',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Letter Spacing', 'thegem'),
			'param_name' => 'heading_letter_spacing',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'heading',
				'value' => '1'
			),
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Transform', 'thegem'),
			'param_name' => 'heading_text_transform',
			'value' => array(
				__('Default', 'thegem') => '',
				__('None', 'thegem') => 'none',
				__('Capitalize', 'thegem') => 'capitalize',
				__('Lowercase', 'thegem') => 'lowercase',
				__('Uppercase', 'thegem') => 'uppercase',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'heading',
				'value' => '1'
			),
			'group' => $group
		);
		
		foreach ($resolutions as $res) {
			$result[] = array(
				'type' => 'textfield',
				'heading' => __('Bottom Spacing ('.$res.')', 'thegem'),
				'param_name' => 'heading_spacing_'.$res,
				'value' => '',
				'dependency' => array(
					'element' => 'heading',
					'value' => '1'
				),
				'edit_field_class' => 'vc_column vc_col-sm-4',
				'group' => $group
			);
		}
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color', 'thegem'),
			'param_name' => 'heading_text_color',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'dependency' => array(
				'element' => 'heading',
				'value' => '1'
			),
			'group' => $group
		);
		
		return $result;
	}
	
	public function set_form_params() {
		$group = __('General', 'thegem');
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Form', 'thegem'),
			'param_name' => 'delimiter_heading_description',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Input Text Color', 'thegem'),
			'param_name' => 'input_text_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Input Background Color', 'thegem'),
			'param_name' => 'input_background_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Input Border Color', 'thegem'),
			'param_name' => 'input_border_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Input Border Color Error', 'thegem'),
			'param_name' => 'input_border_color_error',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Label Text Color', 'thegem'),
			'param_name' => 'label_text_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Input Placeholder Color', 'thegem'),
			'param_name' => 'input_placeholder_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Border Radius', 'thegem'),
			'param_name' => 'input_border_radius',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Checkbox Border Radius', 'thegem'),
			'param_name' => 'input_checkbox_border_radius',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		return $result;
	}
	
	public function shortcode_settings(){
		return array(
			'name' => __('Shipping Details', 'thegem'),
            'base' => 'thegem_te_checkout_shipping',
            'icon' => 'thegem-icon-wpb-ui-element-checkout-shipping',
            'category' => __('Checkout Builder', 'thegem'),
            'description' => __('Shipping Details (Checkout Builder)', 'thegem'),
			'params' => array_merge(
			
			    /* General - Layout */
				$this->set_layout_params(),
				
				/* General - Heading */
				$this->set_heading_params(),
				
				/* General - Content */
				$this->set_form_params(),
				
				/* Extra Options */
				thegem_set_elements_extra_options(),
				
				/* Responsive Options */
				thegem_set_elements_responsive_options()
			),
		);
	}
}

$templates_elements['thegem_te_checkout_shipping'] = new TheGem_Template_Element_Checkout_Shipping();
$templates_elements['thegem_te_checkout_shipping']->init();
