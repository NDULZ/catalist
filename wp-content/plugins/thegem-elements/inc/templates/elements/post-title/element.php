<?php

class TheGem_Template_Element_Post_Title extends TheGem_Single_Post_Template_Element {
	public $show_in_loop = true;
	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_post_title';
	}

	public function is_loop_builder_template() {
		if ((!empty($GLOBALS['thegem_template_type']) && $GLOBALS['thegem_template_type'] == 'loop-item') || thegem_is_template_post('loop-item')) {
			return true;
		}

		return false;
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'alignment' => 'left',
			'max_width' => '',
			'text_tag' => $this->is_loop_builder_template() ? 'div' : 'h1',
			'text_style' => $this->is_loop_builder_template() ? 'title-h4' : '',
			'text_font_weight' => '',
			'text_font_style' => '',
			'text_letter_spacing' => $this->is_loop_builder_template() ? '0' : '',
			'text_transform' => $this->is_loop_builder_template() ? 'none' : '',
			'truncate_title' => '',
			'text_color' => '',
			'text_color_hover' => '',
			'use_custom_font_size' => '0',
			'custom_font_size' => '',
			'custom_line_height' => '',
			'custom_letter_spacing' => '',
			'custom_text_transform' => '',
			'use_custom_responsive_font' => '0',
			'custom_font_size_tablet' => '',
			'custom_line_height_tablet' => '',
			'custom_letter_spacing_tablet' => '',
			'custom_font_size_mobile' => '',
			'custom_line_height_mobile' => '',
			'custom_letter_spacing_mobile' => '',
			'use_custom_google_font' => '0',
			'custom_google_fonts' => '',
			'container_layout_tablet_padding_top' => '',
			'container_layout_tablet_padding_bottom' => '',
			'container_layout_tablet_padding_left' => '',
			'container_layout_tablet_padding_right' => '',
			'container_layout_mobile_padding_top' => '',
			'container_layout_mobile_padding_bottom' => '',
			'container_layout_mobile_padding_left' => '',
			'container_layout_mobile_padding_right' => '',
			'container_layout_tablet_margin_top' => '',
			'container_layout_tablet_margin_bottom' => '',
			'container_layout_tablet_margin_left' => '',
			'container_layout_tablet_margin_right' => '',
			'container_layout_mobile_margin_top' => '',
			'container_layout_mobile_margin_bottom' => '',
			'container_layout_mobile_margin_left' => '',
			'container_layout_mobile_margin_right' => '',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_responsive_options_extract(),
			$this->is_loop_builder_template() ? thegem_templates_dynamic_link_options_extract(): array()
		), $atts, 'thegem_te_post_title');

		// Init Title
		ob_start();
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$single_post = thegem_templates_init_post();

		if (empty($single_post)) {
			ob_end_clean();
			return thegem_templates_close_single_post($this->get_name(), $this->shortcode_settings(), '');
		}

		// Dynamic Link Output
		$is_link = false;
		$output_dynamic_link = $output_dynamic_link_target = $output_dynamic_link_title = $output_dynamic_link_rel = '';
		if (!empty($params['dynamic_link_type'])) {
			switch ($params['dynamic_link_type']) {
				case 'post':
					$is_link = true;
					$output_dynamic_link = get_permalink($single_post);
					$output_dynamic_link_target = '_self';
					break;
				case 'custom':
					$dynamic_link = vc_build_link($params['dynamic_link_custom']);
					if (!empty($dynamic_link['url'])) {
						$is_link = true;
						$output_dynamic_link = $dynamic_link['url'];
						$output_dynamic_link_target = $dynamic_link['target'];
						$output_dynamic_link_title = $dynamic_link['title'];
						$output_dynamic_link_rel = $dynamic_link['rel'];
					}
					break;
			}
		}
		$output_link_attributes = 'href='.esc_url($output_dynamic_link).' target='.esc_attr($output_dynamic_link_target).'';
		$output_link_attributes .= !empty($output_dynamic_link_title) ? ' title='.$output_dynamic_link_title.'' : '';
		$output_link_attributes .= !empty($output_dynamic_link_rel) ? ' rel='.$output_dynamic_link_rel.'' : '';

		// Layout Design Options
		$params['layout_design_wrap_class'] = '';
		if (!empty($atts['container_layout'])) {
			$custom_css_class = vc_shortcode_custom_css_class($atts['container_layout']);
			$params['layout_design_wrap_class'] = ' '.$custom_css_class;
			$params['layout_design_wrap_class'] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $params['layout_design_wrap_class'], 'thegem_te_post_title', $atts );
		}

		$params['text_styled'] = implode(' ', array(
			$params['text_style'],
			$params['text_font_weight']
		));

		$params['element_class'] = implode(' ', array(
			$params['element_class'],
			$params['layout_design_wrap_class'],
			thegem_templates_responsive_options_output($params)
		));

		?>

		<div <?php if (!empty($params['element_id'])): ?>id="<?= esc_attr($params['element_id']); ?>"<?php endif; ?> class="thegem-te-post-title <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">
			<<?= $params['text_tag'] ?> class="post-title <?= $params['text_styled'] ?>">
				<?php if ($is_link): ?><a <?= $output_link_attributes ?>><?php endif; ?>
					<span><?= get_the_title($single_post); ?></span>
				<?php if ($is_link): ?></a><?php endif; ?>
			</<?= $params['text_tag'] ?>>
		</div>

		<?php
		//Custom Styles
		$customize = '.thegem-te-post-title.'.$uniqid;
		$custom_css = '';
		$resolution = array('tablet', 'mobile');
		$behavior = array('normal', 'hover');
		$indents = array('padding', 'margin');
		$directions = array('top', 'bottom', 'left', 'right');

		// Layout Styles
		if (!empty($params['alignment'])) {
			$custom_css .= $customize.' {justify-content: ' . $params['alignment'] . '; text-align: ' . $params['alignment'] . ';}';
		}
		if (!empty($params['max_width'])) {
			$custom_css .= $customize.' .post-title {max-width: ' . $params['max_width'] . 'px;}';
		}

		// Text Styles
		if (!empty($params['text_letter_spacing']) || $params['text_letter_spacing'] == '0') {
			$custom_css .= $customize.' .post-title {letter-spacing: ' . $params['text_letter_spacing'] . 'px;}';
		}
		if ($params['text_transform'] != '') {
			$custom_css .= $customize.' .post-title {text-transform: ' . $params['text_transform'] . ';}';
		}
		if ($params['truncate_title'] != '') {
			$custom_css .= $customize.' .post-title span {max-height: initial; white-space: initial; display: -webkit-box; -webkit-line-clamp: ' . $params['truncate_title'] . '; line-clamp: ' . $params['truncate_title'] . '; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;}';
		}
		if (!empty($params['text_color'])) {
			$custom_css .= $customize.' .post-title {color: ' . $params['text_color'] . ';}';
			$custom_css .= $customize.' .post-title a {color: ' . $params['text_color'] . ';}';
		}
		if (!empty($params['text_color_hover'])) {
			$custom_css .= $customize.' .post-title a:hover {color: ' . $params['text_color_hover'] . ';}';
		}

		// Custom Font Styles
		if (!empty($params['use_custom_font_size'])) {
			if (!empty($params['custom_font_size'])) {
				$custom_css .= $customize.' .post-title {font-size: '.esc_attr($params['custom_font_size']).'px;}';
			}
			if (!empty($params['custom_line_height'])) {
				$custom_css .= $customize.' .post-title {line-height: '.esc_attr($params['custom_line_height']).'px;}';
			}
			if(!empty($params['custom_letter_spacing']) || strcmp($params['custom_letter_spacing'], '0') === 0) {
				$custom_css .= $customize.' .post-title {letter-spacing: '.esc_attr($params['custom_letter_spacing']).'px;}';
			}
			if (!empty($params['custom_text_transform'])) {
				$custom_css .= $customize.' .post-title {text-transform: '.esc_attr($params['custom_text_transform']).';}';
			}
		}
		if (!empty($params['use_custom_responsive_font'])) {
			if (!empty($params['custom_font_size_tablet'])) {
				$custom_css .= '@media screen and (max-width: 1023px) and (min-width: 768px) {
				    '.$customize.' .post-title {font-size: '.esc_attr($params['custom_font_size_tablet']).'px;
				}}';
			}
			if (!empty($params['custom_line_height_tablet'])) {
				$custom_css .= '@media screen and (max-width: 1023px) and (min-width: 768px) {
				    '.$customize.' .post-title {line-height: '.esc_attr($params['custom_line_height_tablet']).'px;
				}}';
			}
			if(!empty($params['custom_letter_spacing_tablet']) || strcmp($params['custom_letter_spacing_tablet'], '0') === 0 ) {
				$custom_css .= '@media screen and (max-width: 1023px) and (min-width: 768px) {
				    '.$customize.' .post-title {letter-spacing: '.esc_attr($params['custom_letter_spacing_tablet']).'px;
				}}';
			}
			if (!empty($params['custom_font_size_mobile'])) {
				$custom_css .= '@media screen and (max-width: 767px) {
				    '.$customize.' .post-title {font-size: '.esc_attr($params['custom_font_size_mobile']).'px;
				}}';
			}
			if (!empty($params['custom_line_height_mobile'])) {
				$custom_css .= '@media screen and (max-width: 767px) {
				    '.$customize.' .post-title {line-height: '.esc_attr($params['custom_line_height_mobile']).'px;
				}}';
			}
			if(!empty($params['custom_letter_spacing_mobile']) || strcmp($params['custom_letter_spacing_mobile'], '0') === 0) {
				$custom_css .= '@media screen and (max-width: 767px) {
				    '.$customize.' .post-title {letter-spacing: '.esc_attr($params['custom_letter_spacing_mobile']).'px;
				}}';
			}
		}
		if (!empty($params['use_custom_font_size']) && !empty($params['use_custom_google_font'])) {
			$font = thegem_font_parse($params['custom_google_fonts']);
			$custom_css .= $customize.' .post-title .light, '.$customize.' .post-title {'.esc_attr($font).'}';
		}

		// Container Layout Responsive
		foreach ($resolution as $res) {
			foreach ($indents as $ind) {
				foreach ($directions as $dir) {
					if (!empty($params['container_layout_'.$res.'_'.$ind.'_'.$dir]) || strcmp($params['container_layout_'.$res.'_'.$ind.'_'.$dir], '0') === 0) {
						$result = str_replace(' ', '', $params['container_layout_'.$res.'_'.$ind.'_'.$dir]);
						$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px';
						$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
						$custom_css .= '@media screen and ('.$width.') {'.$customize.' {'.$ind.'-'.$dir.':'.$result.$unit.' !important;}}';
					}
				}
			}
		}

		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));

		// Print custom css
		$css_output = '';
		if(!empty($custom_css)) {
			$css_output = '<style>'.$custom_css.'</style>';
		}

		$return_html = $css_output.$return_html;
		return thegem_templates_close_single_post($this->get_name(), $this->shortcode_settings(), $return_html);
	}

	public function set_layout_params() {
		$result = [];
		$group = __('General', 'thegem');

		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('General', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);

		$result[] = array(
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
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Max Width', 'thegem'),
			'param_name' => 'max_width',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('HTML Tag', 'thegem'),
			'param_name' => 'text_tag',
			'value' => array(
				__('H1', 'thegem') => 'h1',
				__('H2', 'thegem') => 'h2',
				__('H3', 'thegem') => 'h3',
				__('H4', 'thegem') => 'h4',
				__('H5', 'thegem') => 'h5',
				__('H6', 'thegem') => 'h6',
				__('p', 'thegem') => 'p',
				__('div', 'thegem') => 'div'
			),
			'std' => $this->is_loop_builder_template() ? 'div' : 'h1',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Style', 'thegem'),
			'param_name' => 'text_style',
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
			'std' => $this->is_loop_builder_template() ? 'title-h4' : '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Font weight', 'thegem'),
			'param_name' => 'text_font_weight',
			'value' => array(
				__('Default', 'thegem') => '',
				__('Thin', 'thegem') => 'light',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Transform', 'thegem'),
			'param_name' => 'text_transform',
			'value' => array(
				__('Default', 'thegem') => '',
				__('None', 'thegem') => 'none',
				__('Capitalize', 'thegem') => 'capitalize',
				__('Lowercase', 'thegem') => 'lowercase',
				__('Uppercase', 'thegem') => 'uppercase',
			),
			'std' => $this->is_loop_builder_template() ? 'none' : '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Letter Spacing', 'thegem'),
			'param_name' => 'text_letter_spacing',
			'std' => $this->is_loop_builder_template() ? '0' : '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Truncate Title (Lines)', 'thegem'),
			'param_name' => 'truncate_title',
			'std' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'description' => __('To style default blog title typography go to <a href="'.get_site_url().'/wp-admin/admin.php?page=thegem-theme-options#/typography/headings-and-body" target="_blank">Theme Options → Typography</a>.', 'thegem'),
			'group' => $group
		);

		return $result;
	}

	public function set_custom_font_params() {
		$result = array();
		$group = __('Style', 'thegem');

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color', 'thegem'),
			'param_name' => 'text_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		if ($this->is_loop_builder_template()) {
			$result[] = array(
				'type' => 'colorpicker',
				'heading' => __('Hover Text Color', 'thegem'),
				'param_name' => 'text_color_hover',
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => $group
			);
		}

		$result[] = array(
            'type' => 'checkbox',
            'heading' => __('Use custom font size?', 'thegem'),
            'param_name' => 'use_custom_font_size',
            'value' => array(__('Yes', 'thegem') => '1'),
            'group' => $group
        );

		$result[] = array(
            'type' => 'textfield',
            'heading' => __('Font size', 'thegem'),
            'param_name' => 'custom_font_size',
            'dependency' => array(
                'element' => 'use_custom_font_size',
                'value' => '1'
            ),
            'edit_field_class' => 'vc_column vc_col-sm-6',
            'group' => $group
        );

		$result[] = array(
            'type' => 'textfield',
            'heading' => __('Line height', 'thegem'),
            'param_name' => 'custom_line_height',
            'dependency' => array(
                'element' => 'use_custom_font_size',
                'value' => '1'
            ),
            'edit_field_class' => 'vc_column vc_col-sm-6',
            'group' => $group
        );

		$result[] = array(
            'type' => 'textfield',
            'heading' => __('Letter spacing', 'thegem'),
            'param_name' => 'custom_letter_spacing',
            'dependency' => array(
                'element' => 'use_custom_font_size',
                'value' => '1'
            ),
            'edit_field_class' => 'vc_column vc_col-sm-6',
            'group' => $group
        );

		$result[] = array(
            'type' => 'dropdown',
            'heading' => __('Text transform', 'thegem'),
            'param_name' => 'custom_text_transform',
            'value' => array(
                __('None', 'thegem') => 'none',
                __('Capitalize', 'thegem') => 'capitalize',
                __('Lowercase', 'thegem') => 'lowercase',
                __('Uppercase', 'thegem') => 'uppercase'
            ),
            'dependency' => array(
                'element' => 'use_custom_font_size',
                'value' => '1'
            ),
            'edit_field_class' => 'vc_column vc_col-sm-6',
            'group' => $group
        );

		$result[] = array(
            'type' => 'checkbox',
            'heading' => __('Responsive font size options', 'thegem'),
            'param_name' => 'use_custom_responsive_font',
            'value' => array(__('Yes', 'thegem') => '1'),
            'group' => $group
        );

		$result[] = array(
            'type' => 'textfield',
            'heading' => __('Font size tablet', 'thegem'),
            'param_name' => 'custom_font_size_tablet',
            'dependency' => array(
                'element' => 'use_custom_responsive_font',
                'value' => '1'
            ),
            'edit_field_class' => 'vc_column vc_col-sm-4',
            'group' => $group
        );

		$result[] = array(
            'type' => 'textfield',
            'heading' => __('Line height tablet', 'thegem'),
            'param_name' => 'custom_line_height_tablet',
            'dependency' => array(
                'element' => 'use_custom_responsive_font',
                'value' => '1'
            ),
            'edit_field_class' => 'vc_column vc_col-sm-4',
            'group' => $group
        );

		$result[] = array(
            'type' => 'textfield',
            'heading' => __('Letter spacing tablet', 'thegem'),
            'param_name' => 'custom_letter_spacing_tablet',
            'dependency' => array(
                'element' => 'use_custom_responsive_font',
                'value' => '1'
            ),
            'edit_field_class' => 'vc_column vc_col-sm-4',
            'group' => $group
        );

		$result[] = array(
            'type' => 'textfield',
            'heading' => __('Font size mobile', 'thegem'),
            'param_name' => 'custom_font_size_mobile',
            'dependency' => array(
                'element' => 'use_custom_responsive_font',
                'value' => '1'
            ),
            'edit_field_class' => 'vc_column vc_col-sm-4',
            'group' => $group
        );

		$result[] = array(
            'type' => 'textfield',
            'heading' => __('Line height mobile', 'thegem'),
            'param_name' => 'custom_line_height_mobile',
            'dependency' => array(
                'element' => 'use_custom_responsive_font',
                'value' => '1'
            ),
            'edit_field_class' => 'vc_column vc_col-sm-4',
            'group' => $group
        );

		$result[] = array(
            'type' => 'textfield',
            'heading' => __('Letter spacing mobile', 'thegem'),
            'param_name' => 'custom_letter_spacing_mobile',
            'dependency' => array(
                'element' => 'use_custom_responsive_font',
                'value' => '1'
            ),
            'edit_field_class' => 'vc_column vc_col-sm-4',
            'group' => $group
        );

		$result[] = array(
            'type' => 'checkbox',
            'heading' => __('Use Google fonts?', 'thegem'),
            'param_name' => 'use_custom_google_font',
            'value' => array(__('Yes', 'thegem') => '1'),
            'group' => $group
        );

		$result[] = array(
            'type' => 'google_fonts',
            'param_name' => 'custom_google_fonts',
            'value' => '',
            'settings' => array(
                'fields' => array(
                    'font_family_description' => esc_html__('Select font family.', 'thegem'),
                    'font_style_description' => esc_html__('Select font styling.', 'thegem'),
                ),
            ),
            'dependency' => array(
                'element' => 'use_custom_google_font',
                'value' => '1'
            ),
            'group' => $group
        );

		return $result;
	}

	public function set_design_options_params() {
		$result = array();
		$group = __('Design Options', 'thegem');

		$result[] = array(
			'type' => 'css_editor',
			'heading' => __('CSS box', 'thegem'),
			'param_name' => 'container_layout',
			'group' => $group
		);

		return $result;
	}

	public function extends_responsive_options_params() {
		$result = array();
		$group = __('Responsive Options', 'thegem');
		$resolution = array('tablet', 'mobile');
		$indents = array('padding', 'margin');
		$directions = array('top', 'bottom', 'left', 'right');

		foreach ($resolution as $res) {
			$result[] = array(
				'type' => 'thegem_delimeter_heading',
				'heading' => __(ucfirst($res), 'thegem'),
				'param_name' => $res.'_container_layout_heading',
				'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
				'group' => __($group, 'thegem')
			);

			foreach ($indents as $ind) {
				$result[] = array(
					'type' => 'thegem_delimeter_heading_two_level',
					'heading' => __($ind, 'thegem'),
					'param_name' => $res.'_'.$ind.'_container_layout_sub_heading',
					'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12 capitalize',
					'group' => __($group, 'thegem')
				);
				foreach ($directions as $dir) {
					$result[] = array(
						'type' => 'textfield',
						'heading' => __(ucfirst($dir), 'thegem'),
						'param_name' => 'container_layout_' . $res.'_'.$ind.'_'.$dir,
						'value' => '',
						'edit_field_class' => 'vc_column vc_col-sm-3 capitalize',
						'group' => __($group, 'thegem')
					);
				}
			}

			$result[] = array(
				'type' => 'thegem_delimeter_heading_two_level',
				'heading' => __('', 'thegem'),
				'param_name' => $res.'_description_layout_sub_heading',
				'edit_field_class' => 'vc_column vc_col-sm-12 no-top-padding',
				'description' => __('Note: px units are used by default. For % units add values with %, eg. 10%', 'thegem'),
				'group' => __($group, 'thegem')
			);
		}

		return $result;
	}

	public function shortcode_settings() {
		return array(
			'name' => __('Post Title', 'thegem'),
			'base' => 'thegem_te_post_title',
			'icon' => 'thegem-icon-wpb-ui-element-post-title',
			'category' => $this->is_template() ? __('Single Post Builder', 'thegem') : __('Single post', 'thegem'),
			'description' => __('Post Title (Single Post Builder)', 'thegem'),
			'params' => array_merge(

			    /* General - Layout */
				$this->set_layout_params(),

                /* Style - Custom Style */
				$this->set_custom_font_params(),

				/* Dynamic Link */
				$this->is_loop_builder_template() ? thegem_set_elements_dynamic_link() : array(),

				/* Extra Options */
				thegem_set_elements_extra_options( ),

				// Design Options
				$this->set_design_options_params(),

				/* Responsive Options */
				thegem_set_elements_responsive_options(),
				$this->extends_responsive_options_params()
			),
		);
	}
}

$templates_elements['thegem_te_post_title'] = new TheGem_Template_Element_Post_Title();
$templates_elements['thegem_te_post_title']->init();
