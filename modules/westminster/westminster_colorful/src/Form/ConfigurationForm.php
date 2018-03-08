<?php

  namespace Drupal\westminster_colorful\Form;

  use Drupal\Core\Form\ConfigFormBase;
  use Drupal\Core\Form\FormStateInterface;

  Class ConfigurationForm extends ConfigFormBase {

    const CSS_SELECTORS = [
      'page' => [
        'background' => '.content-wrapper, html',
        'text' => '.content-wrapper,.system-modules label,.system-modules-uninstall label',
      ],
      'box' => [
        'background' => '.box-body,.tab-content,.login-box-body',
        'text' => '.box-body,.tab-content',
        'link' => '.box-body a:not(.btn),.tab-content a',
        'link_hover' => '.box-body a:hover:not(.btn),.box-body a:active:not(.btn),.box-body a:focus:not(.btn),.tab-content a:hover,.tab-content a:active,.tab-content a:focus',
        'border' => 'details,td,th',
        'table_row_alternate' => '.table-striped>tbody>tr:nth-of-type(odd)',
        'table_cell_active' => 'td.is-active,.nav-tabs-custom>.nav-tabs>li.active>a',
      ],
      'vertical_tabs' => [
        'background' => '.vertical-tabs',
        'text' => '.vertical-tabs,.vertical-tabs__menu-item-title',
        'link' => '.vertical-tabs a',
        'link_hover' => '.vertical-tabs a:hover,.vertical-tabs a:active,.vertical-tabs a:focus',
        'border' => '.vertical-tabs,.vertical-tabs__menu,.vertical-tabs__menu-item',
        'tab' => '.vertical-tabs__menu-item',
        'tab_selected' => '.vertical-tabs__menu-item.is-selected',
      ],
      'breadcrumb' => [
        'text' => '.breadcrumb>li>a',
        'spacer' => '.breadcrumb>li+li:before',
        'active' => '.breadcrumb>.active',
      ],
      'top_menu' => [
        'background' => '.main-header>.navbar,.main-header>.logo',
        'text' => '.main-header>.logo,.main-header>.navbar .navbar-nav>li>a,.main-header>.navbar>.sidebar-toggle',
        'border' => '.main-header>.navbar .navbar-nav>li>a,.main-header>.navbar>.sidebar-toggle,.main-header>.logo',
        'hover' => '.main-header>.navbar .navbar-nav>li>a:hover,.main-header>.navbar>.sidebar-toggle:hover',
      ],
      'left_menu' => [
        'background' => '.main-sidebar, .wrapper',
        'text' => '.sidebar a',
        'background_active' => '.sidebar-menu>li.active>a',
        'border_active' => '.sidebar-menu>li.active>a',
        'text_active' => '.sidebar-menu>li.active>a',
        'hover' => '.sidebar-menu>li.active>a:hover,.sidebar-menu>li>a:hover',
      ],
      'left_menu_dropdown' => [
        'background' => '.sidebar-menu>li .treeview-menu',
        'text' => '.treeview-menu.menu-open>li>a',
      ],
      'footer' => [
        'background' => '.main-footer',
        'text' => '.main-footer',
        'svg' => '.zd-svg-logo',
        'link' => '.main-footer a',
        'link_hover' => '.main-footer a:active,.main-footer a:focus,.main-footer a:hover',
      ],
      'button' => [
        'default' => '.btn-default',
        'default_hover' => '.btn-default:hover,.btn-default:active,.btn-default:focus',
        'primary' => '.btn-primary',
        'primary_hover' => '.btn-primary:hover,.btn-primary:active,.btn-primary:focus',
        'success' => '.btn-success',
        'success_hover' => '.btn-success:hover,.btn-success:active,.btn-success:focus',
        'info' => '.btn-info',
        'info_hover' => '.btn-info:hover,.btn-info:active,.btn-info:focus',
        'danger' => '.btn-danger',
        'danger_hover' => '.btn-danger:hover,.btn-danger:active,.btn-danger:focus',
        'warning' => '.btn-warning',
        'warning_hover' => '.btn-warning:hover,.btn-warning:active,.btn-warning:focus',
      ],
      'form_inputs' => [
        'background' => '.form-control,.select2 span',
        'text' => '.form-control,.select2 span',
        'checkbox' => 'input[type="checkbox"]',
      ],
    ];

    public function buildForm(array $form, FormStateInterface $form_state) {
      $config = $this->config('westminster_colorful.configuration');
      $pageColors = $config->get('page');
      $boxColors = $config->get('box');
      $verticalTab = $config->get('vertical_tabs');
      $breadcrumb = $config->get('breadcrumb');
      $topMenu = $config->get('top_menu');
      $leftMenu = $config->get('left_menu');
      $leftMenuDropdown = $config->get('left_menu_dropdown');
      $footer = $config->get('footer');
      $button = $config->get('button');
      $formInputs = $config->get('form_inputs');

      $form['colors'] = [
        '#type' => 'vertical_tabs',
        '#default_tab' => 'edit-page',
      ];
      $form['page'] = [
        '#type' => 'details',
        '#title' => t('Page'),
        '#group' => 'colors',
      ];
      $form['box'] = [
        '#type' => 'details',
        '#title' => t('Box'),
        '#group' => 'colors',
      ];
      $form['vertical_tabs'] = [
        '#type' => 'details',
        '#title' => t('Vertical Tabs'),
        '#group' => 'colors'
      ];
      $form['breadcrumb'] = [
        '#type' => 'details',
        '#title' => t('Breadcrumbs'),
        '#group' => 'colors',
      ];
      $form['top_menu'] = [
        '#type' => 'details',
        '#title' => t('Top Menu'),
        '#group' => 'colors',
      ];
      $form['left_menu'] = [
        '#type' => 'details',
        '#title' => t('Left Menu'),
        '#group' => 'colors',
      ];
      $form['left_menu_dropdown'] = [
        '#type' => 'details',
        '#title' => t('Left Menu - Dropdown'),
        '#group' => 'colors',
      ];
      $form['footer'] = [
        '#type' => 'details',
        '#title' => t('Footer'),
        '#group' => 'colors',
      ];
      $form['button'] = [
        '#type' => 'details',
        '#title' => t('Buttons'),
        '#group' => 'colors',
      ];
      $form['form_inputs'] = [
        '#type' => 'details',
        '#title' => t('Form Inputs'),
        '#group' => 'colors',
      ];
      $form['page']['page_background'] = [
        '#type' => 'color',
        '#title' => t('Background Color'),
        '#required' => TRUE,
        '#default_value' => $pageColors['background']['color'],
      ];
      $form['page']['page_text'] = [
        '#type' => 'color',
        '#title' => t('Text Color'),
        '#required' => TRUE,
        '#default_value' => $pageColors['text']['color'],
      ];
      $form['box']['box_background'] = [
        '#type' => 'color',
        '#title' => t('Background Color'),
        '#required' => TRUE,
        '#default_value' => $boxColors['background']['color'],
      ];
      $form['box']['box_table_row_alternate'] = [
        '#type' => 'color',
        '#title' => t('Alternating Table Row Color'),
        '#required' => TRUE,
        '#default_value' => $boxColors['table_row_alternate']['color'],
      ];
      $form['box']['box_table_cell_active'] = [
        '#type' => 'color',
        '#title' => t('Active Table Cell Color'),
        '#required' => TRUE,
        '#default_value' => $boxColors['table_cell_active']['color'],
      ];
      $form['box']['box_text'] = [
        '#type' => 'color',
        '#title' => t('Text Color'),
        '#required' => TRUE,
        '#default_value' => $boxColors['text']['color'],
      ];
      $form['box']['box_link'] = [
        '#type' => 'color',
        '#title' => t('Link Color'),
        '#required' => TRUE,
        '#default_value' => $boxColors['link']['color'],
      ];
      $form['box']['box_link_hover'] = [
        '#type' => 'color',
        '#title' => t('Link Hover Color'),
        '#required' => TRUE,
        '#default_value' => $boxColors['link_hover']['color'],
      ];
      $form['box']['box_border'] = [
        '#type' => 'color',
        '#title' => t('Border Color'),
        '#required' => TRUE,
        '#default_value' => $boxColors['border']['color'],
      ];
      $form['vertical_tabs']['vertical_tabs_background'] = [
        '#type' => 'color',
        '#title' => t('Background Color'),
        '#required' => TRUE,
        '#default_value' => $verticalTab['background']['color'],
      ];
      $form['vertical_tabs']['vertical_tabs_text'] = [
        '#type' => 'color',
        '#title' => t('Text Color'),
        '#required' => TRUE,
        '#default_value' => $verticalTab['text']['color'],
      ];
      $form['vertical_tabs']['vertical_tabs_link'] = [
        '#type' => 'color',
        '#title' => t('Link Color'),
        '#required' => TRUE,
        '#default_value' => $verticalTab['link']['color'],
      ];
      $form['vertical_tabs']['vertical_tabs_link_hover'] = [
        '#type' => 'color',
        '#title' => t('Link Hover Color'),
        '#required' => TRUE,
        '#default_value' => $verticalTab['link_hover']['color'],
      ];
      $form['vertical_tabs']['vertical_tabs_border'] = [
        '#type' => 'color',
        '#title' => t('Border Color'),
        '#required' => TRUE,
        '#default_value' => $verticalTab['border']['color'],
      ];
      $form['vertical_tabs']['vertical_tabs_tab'] = [
        '#type' => 'color',
        '#title' => t('Non-Selected Tab Color'),
        '#required' => TRUE,
        '#default_value' => $verticalTab['tab']['color'],
      ];
      $form['vertical_tabs']['vertical_tabs_tab_selected'] = [
        '#type' => 'color',
        '#title' => t('Selected Tab Color'),
        '#required' => TRUE,
        '#default_value' => $verticalTab['tab_selected']['color'],
      ];
      $form['breadcrumb']['breadcrumb_text'] = [
        '#type' => 'color',
        '#title' => t('Text Color'),
        '#required' => TRUE,
        '#default_value' => $breadcrumb['text']['color'],
      ];
      $form['breadcrumb']['breadcrumb_spacer'] = [
        '#type' => 'color',
        '#title' => t('Spacer Color'),
        '#required' => TRUE,
        '#default_value' => $breadcrumb['spacer']['color'],
      ];
      $form['breadcrumb']['breadcrumb_active_text'] = [
        '#type' => 'color',
        '#title' => t('Active Breadcrumb Color'),
        '#required' => TRUE,
        '#default_value' => $breadcrumb['active']['text']['color'],
      ];
      $form['top_menu']['top_menu_background_color'] = [
        '#type' => 'color',
        '#title' => t('Background Color'),
        '#required' => TRUE,
        '#default_value' => $topMenu['background']['color'],
      ];
      $form['top_menu']['top_menu_text_color'] = [
        '#type' => 'color',
        '#title' => t('Text Color'),
        '#required' => TRUE,
        '#default_value' => $topMenu['text']['color'],
      ];
      $form['top_menu']['top_menu_border_color'] = [
        '#type' => 'color',
        '#title' => t('Border Color'),
        '#required' => TRUE,
        '#default_value' => $topMenu['border']['color'],
      ];
      $form['top_menu']['top_menu_hover'] = [
        '#type' => 'details',
        '#title' => t('Hover'),
        '#group' => 'top_menu',
        '#collapsible' => TRUE,
      ];
      $form['top_menu']['top_menu_hover']['top_menu_text_hover_text_color'] = [
        '#type' => 'color',
        '#title' => t('Text Hover Color'),
        '#required' => TRUE,
        '#default_value' => $topMenu['hover']['color']['text'],
      ];
      $form['top_menu']['top_menu_hover']['top_menu_text_hover_background_color'] = [
        '#type' => 'color',
        '#title' => t('Text Hover Background Color'),
        '#required' => TRUE,
        '#default_value' => $topMenu['hover']['color']['background'],
      ];
      $form['left_menu']['left_menu_background_color'] = [
        '#type' => 'color',
        '#title' => t('Background'),
        '#required' => TRUE,
        '#default_value' => $leftMenu['background']['color'],
      ];
      $form['left_menu']['left_menu_text_color'] = [
        '#type' => 'color',
        '#title' => t('Text'),
        '#required' => TRUE,
        '#default_value' => $leftMenu['text']['color'],
      ];
      $form['left_menu']['left_menu_selected'] = [
        '#type' => 'details',
        '#title' => t('Selected Item'),
        '#group' => 'left_menu',
        '#collapsible' => TRUE
      ];
      $form['left_menu']['left_menu_hover'] = [
        '#type' => 'details',
        '#title' => t('Hover'),
        '#group' => 'left_menu',
        '#collapsible' => TRUE
      ];
      $form['left_menu']['left_menu_selected']['left_menu_background_active_color'] = [
        '#type' => 'color',
        '#title' => t('Background Color'),
        '#required' => TRUE,
        '#default_value' => $leftMenu['background_active']['color'],
      ];
      $form['left_menu']['left_menu_selected']['left_menu_border_active'] = [
        '#type' => 'color',
        '#title' => t('Border Color'),
        '#required' => TRUE,
        '#default_value' => $leftMenu['border_active']['color'],
      ];
      $form['left_menu']['left_menu_selected']['left_menu_text_active'] = [
        '#type' => 'color',
        '#title' => t('Text Color'),
        '#required' => TRUE,
        '#default_value' => $leftMenu['text_active']['color'],
      ];
      $form['left_menu']['left_menu_hover']['left_menu_background_hover'] = [
        '#type' => 'color',
        '#title' => t('Background Hover Color'),
        '#required' => TRUE,
        '#default_value' => $leftMenu['hover']['color']['background'],

      ];
      $form['left_menu']['left_menu_hover']['left_menu_text_hover'] = [
        '#type' => 'color',
        '#title' => t('Text Hover Color'),
        '#required' => TRUE,
        '#default_value' => $leftMenu['hover']['color']['text'],
      ];
      $form['left_menu']['left_menu_hover']['left_menu_border_hover'] = [
        '#type' => 'color',
        '#title' => t('Border Hover Color'),
        '#required' => TRUE,
        '#default_value' => $leftMenu['hover']['color']['border'],
      ];
      $form['left_menu_dropdown']['left_menu_dropdown_background'] = [
        '#type' => 'color',
        '#title' => t('Background Color'),
        '#required' => TRUE,
        '#default_value' => $leftMenuDropdown['background']['color'],
      ];
      $form['left_menu_dropdown']['left_menu_dropdown_text'] = [
        '#type' => 'color',
        '#title' => t('Text Color'),
        '#required' => TRUE,
        '#default_value' => $leftMenuDropdown['text']['color'],
      ];
      $form['footer']['footer_background'] = [
        '#type' => 'color',
        '#title' => t('Background Color'),
        '#required' => TRUE,
        '#default_value' => $footer['background']['color'],
      ];
      $form['footer']['footer_text'] = [
        '#type' => 'color',
        '#title' => t('Text Color'),
        '#required' => TRUE,
        '#default_value' => $footer['text']['color'],
      ];
      $form['footer']['link'] = [
        '#type' => 'color',
        '#title' => t('Link Color'),
        '#required' => TRUE,
        '#default_value' => $footer['link']['color'],
      ];
      $form['footer']['link_hover'] = [
        '#type' => 'color',
        '#title' => t('Link Hover Color'),
        '#required' => TRUE,
        '#default_value' => $footer['link_hover']['color'],
      ];
      $form['button']['button_default'] = [
        '#type' => 'details',
        '#title' => t('Default'),
        '#group' => 'button',
        '#collapsible' => TRUE,
      ];
      $form['button']['button_primary'] = [
        '#type' => 'details',
        '#title' => t('Primary'),
        '#group' => 'button',
        '#collapsible' => TRUE,
      ];
      $form['button']['button_success'] = [
        '#type' => 'details',
        '#title' => t('Success'),
        '#group' => 'button',
        '#collapsible' => TRUE,
      ];
      $form['button']['button_info'] = [
        '#type' => 'details',
        '#title' => t('Info'),
        '#group' => 'button',
        '#collapsible' => TRUE,
      ];
      $form['button']['button_danger'] = [
        '#type' => 'details',
        '#title' => t('Danger'),
        '#group' => 'button',
        '#collapsible' => TRUE,
      ];
      $form['button']['button_warning'] = [
        '#type' => 'details',
        '#title' => t('Warning'),
        '#group' => 'button',
        '#collapsible' => TRUE,
      ];
      $form['button']['button_default']['button_default_background'] = [
        '#type' => 'color',
        '#title' => t('Background'),
        '#required' => TRUE,
        '#default_value' => $button['default']['background']['color'],
      ];
      $form['button']['button_default']['button_default_background_hover'] = [
        '#type' => 'color',
        '#title' => t('Background Hover Color'),
        '#required' => TRUE,
        '#default_value' => $button['default']['background']['hover'],
      ];
      $form['button']['button_default']['button_default_border'] = [
        '#type' => 'color',
        '#title' => t('Border Color'),
        '#required' => TRUE,
        '#default_value' => $button['default']['border']['color'],
      ];
      $form['button']['button_default']['button_default_border_hover'] = [
        '#type' => 'color',
        '#title' => t('Border Hover Color'),
        '#required' => TRUE,
        '#default_value' => $button['default']['border']['hover'],
      ];
      $form['button']['button_default']['button_default_text'] = [
        '#type' => 'color',
        '#title' => t('Text Color'),
        '#required' => TRUE,
        '#default_value' => $button['default']['text']['color'],
      ];
      $form['button']['button_default']['button_default_text_hover'] = [
        '#type' => 'color',
        '#title' => t('Text Hover Color'),
        '#required' => TRUE,
        '#default_value' => $button['default']['text']['hover'],
      ];

      $form['button']['button_primary']['button_primary_background'] = [
        '#type' => 'color',
        '#title' => t('Background Color'),
        '#required' => TRUE,
        '#default_value' => $button['primary']['background']['color'],
      ];
      $form['button']['button_primary']['button_primary_background_hover'] = [
        '#type' => 'color',
        '#title' => t('Background Hover Color'),
        '#required' => TRUE,
        '#default_value' => $button['primary']['background']['hover'],
      ];
      $form['button']['button_primary']['button_primary_border'] = [
        '#type' => 'color',
        '#title' => t('Border Color'),
        '#required' => TRUE,
        '#default_value' => $button['primary']['border']['color'],
      ];
      $form['button']['button_primary']['button_primary_border_hover'] = [
        '#type' => 'color',
        '#title' => t('Border Hover Color'),
        '#required' => TRUE,
        '#default_value' => $button['primary']['border']['hover'],
      ];
      $form['button']['button_primary']['button_primary_text'] = [
        '#type' => 'color',
        '#title' => t('Text Color'),
        '#required' => TRUE,
        '#default_value' => $button['primary']['text']['color'],
      ];
      $form['button']['button_primary']['button_primary_text_hover'] = [
        '#type' => 'color',
        '#title' => t('Text Hover Color'),
        '#required' => TRUE,
        '#default_value' => $button['primary']['text']['hover'],
      ];

      $form['button']['button_success']['button_success_background'] = [
        '#type' => 'color',
        '#title' => t('Background Color'),
        '#required' => TRUE,
        '#default_value' => $button['success']['background']['color'],
      ];
      $form['button']['button_success']['button_success_background_hover'] = [
        '#type' => 'color',
        '#title' => t('Background Hover Color'),
        '#required' => TRUE,
        '#default_value' => $button['success']['background']['hover'],
      ];
      $form['button']['button_success']['button_success_border'] = [
        '#type' => 'color',
        '#title' => t('Border Color'),
        '#required' => TRUE,
        '#default_value' => $button['success']['border']['color'],
      ];
      $form['button']['button_success']['button_success_border_hover'] = [
        '#type' => 'color',
        '#title' => t('Border Hover Color'),
        '#required' => TRUE,
        '#default_value' => $button['success']['border']['hover'],
      ];
      $form['button']['button_success']['button_success_text'] = [
        '#type' => 'color',
        '#title' => t('Text Color'),
        '#required' => TRUE,
        '#default_value' => $button['success']['text']['color'],
      ];
      $form['button']['button_success']['button_success_text_hover'] = [
        '#type' => 'color',
        '#title' => t('Text Hover Color'),
        '#required' => TRUE,
        '#default_value' => $button['success']['text']['hover'],
      ];

      $form['button']['button_info']['button_info_background'] = [
        '#type' => 'color',
        '#title' => t('Background Color'),
        '#required' => TRUE,
        '#default_value' => $button['info']['background']['color'],
      ];
      $form['button']['button_info']['button_info_background_hover'] = [
        '#type' => 'color',
        '#title' => t('Background Hover Color'),
        '#required' => TRUE,
        '#default_value' => $button['info']['background']['hover'],
      ];
      $form['button']['button_info']['button_info_border'] = [
        '#type' => 'color',
        '#title' => t('Border Color'),
        '#required' => TRUE,
        '#default_value' => $button['info']['border']['color'],
      ];
      $form['button']['button_info']['button_info_border_hover'] = [
        '#type' => 'color',
        '#title' => t('Border Hover Color'),
        '#required' => TRUE,
        '#default_value' => $button['info']['border']['hover'],
      ];
      $form['button']['button_info']['button_info_text'] = [
        '#type' => 'color',
        '#title' => t('Text Color'),
        '#required' => TRUE,
        '#default_value' => $button['info']['text']['color'],
      ];
      $form['button']['button_info']['button_info_text_hover'] = [
        '#type' => 'color',
        '#title' => t('Text Hover Color'),
        '#required' => TRUE,
        '#default_value' => $button['info']['text']['hover'],
      ];

      $form['button']['button_danger']['button_danger_background'] = [
        '#type' => 'color',
        '#title' => t('Background Color'),
        '#required' => TRUE,
        '#default_value' => $button['danger']['background']['color'],
      ];
      $form['button']['button_danger']['button_danger_background_hover'] = [
        '#type' => 'color',
        '#title' => t('Background Hover Color'),
        '#required' => TRUE,
        '#default_value' => $button['danger']['background']['hover'],
      ];
      $form['button']['button_danger']['button_danger_border'] = [
        '#type' => 'color',
        '#title' => t('Border Color'),
        '#required' => TRUE,
        '#default_value' => $button['danger']['border']['color'],
      ];
      $form['button']['button_danger']['button_danger_border_hover'] = [
        '#type' => 'color',
        '#title' => t('Border Hover Color'),
        '#required' => TRUE,
        '#default_value' => $button['danger']['border']['hover'],
      ];
      $form['button']['button_danger']['button_danger_text'] = [
        '#type' => 'color',
        '#title' => t('Text Color'),
        '#required' => TRUE,
        '#default_value' => $button['danger']['text']['color'],
      ];
      $form['button']['button_danger']['button_danger_text_hover'] = [
        '#type' => 'color',
        '#title' => t('Text Hover Color'),
        '#required' => TRUE,
        '#default_value' => $button['danger']['text']['hover'],
      ];

      $form['button']['button_warning']['button_warning_background'] = [
        '#type' => 'color',
        '#title' => t('Background Color'),
        '#required' => TRUE,
        '#default_value' => $button['warning']['background']['color'],
      ];
      $form['button']['button_warning']['button_warning_background_hover'] = [
        '#type' => 'color',
        '#title' => t('Background Hover Color'),
        '#required' => TRUE,
        '#default_value' => $button['warning']['background']['hover'],
      ];
      $form['button']['button_warning']['button_warning_border'] = [
        '#type' => 'color',
        '#title' => t('Border Color'),
        '#required' => TRUE,
        '#default_value' => $button['warning']['border']['color'],
      ];
      $form['button']['button_warning']['button_warning_border_hover'] = [
        '#type' => 'color',
        '#title' => t('Border Hover Color'),
        '#required' => TRUE,
        '#default_value' => $button['warning']['border']['hover'],
      ];
      $form['button']['button_warning']['button_warning_text'] = [
        '#type' => 'color',
        '#title' => t('Text Color'),
        '#required' => TRUE,
        '#default_value' => $button['warning']['text']['color'],
      ];
      $form['button']['button_warning']['button_warning_text_hover'] = [
        '#type' => 'color',
        '#title' => t('Text Hover Color'),
        '#required' => TRUE,
        '#default_value' => $button['warning']['text']['hover'],
      ];
      $form['form_inputs']['form_inputs_background'] = [
        '#type' => 'color',
        '#title' => t('Background Color'),
        '#required' => TRUE,
        '#default_value' => $formInputs['background']['color'],
      ];
      $form['form_inputs']['form_inputs_text'] = [
        '#type' => 'color',
        '#title' => t('Text Color'),
        '#required' => TRUE,
        '#default_value' => $formInputs['text']['color'],
      ];

      return parent::buildForm($form, $form_state);
    }

    protected function getEditableConfigNames() {
      return [
        'westminster_colorful.configuration',
      ];
    }

    public function getFormId() {
      return 'westminster_colorful_configuration';
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
      $configFactory = $this->configFactory->getEditable('westminster_colorful.configuration');
      $pageColors = $configFactory->get('page');
      $boxColors = $configFactory->get('box');
      $verticalTab = $configFactory->get('vertical_tabs');
      $breadcrumb = $configFactory->get('breadcrumb');
      $topMenu = $configFactory->get('top_menu');
      $leftMenu = $configFactory->get('left_menu');
      $leftMenuDropdown = $configFactory->get('left_menu_dropdown');
      $footer = $configFactory->get('footer');
      $button = $configFactory->get('button');
      $formInputs = $configFactory->get('form_inputs');
      $pageColors['background']['color'] = $form_state->getValue('page_background');
      $pageColors['text']['color'] = $form_state->getValue('page_text');
      $boxColors['background']['color'] = $form_state->getValue('box_background');
      $boxColors['table_row_alternate']['color'] = $form_state->getValue('box_table_row_alternate');
      $boxColors['table_cell_active']['color'] = $form_state->getValue('box_table_cell_active');
      $boxColors['text']['color'] = $form_state->getValue('box_text');
      $boxColors['link']['color'] = $form_state->getValue('box_link');
      $boxColors['link_hover']['color'] = $form_state->getValue('box_link_hover');
      $boxColors['border']['color'] = $form_state->getValue('box_border');
      $verticalTab['background']['color'] = $form_state->getValue('vertical_tabs_background');
      $verticalTab['text']['color'] = $form_state->getValue('vertical_tabs_text');
      $verticalTab['link']['color'] = $form_state->getValue('vertical_tabs_link');
      $verticalTab['link_hover']['color'] = $form_state->getValue('vertical_tabs_link_hover');
      $verticalTab['border']['color'] = $form_state->getValue('vertical_tabs_border');
      $verticalTab['tab']['color'] = $form_state->getValue('vertical_tabs_tab');
      $verticalTab['tab_selected']['color'] = $form_state->getValue('vertical_tabs_tab_selected');
      $breadcrumb['text']['color'] = $form_state->getValue('breadcrumb_text');
      $breadcrumb['spacer']['color'] = $form_state->getValue('breadcrumb_spacer');
      $breadcrumb['active']['text']['color'] = $form_state->getValue('breadcrumb_active_text');
      $topMenu['background']['color'] = $form_state->getValue('top_menu_background_color');
      $topMenu['text']['color'] = $form_state->getValue('top_menu_text_color');
      $topMenu['border']['color'] = $form_state->getValue('top_menu_border_color');
      $topMenu['hover']['color']['text'] = $form_state->getValue('top_menu_text_hover_text_color');
      $topMenu['hover']['color']['background'] = $form_state->getValue('top_menu_text_hover_background_color');
      $leftMenu['background']['color'] = $form_state->getValue('left_menu_background_color');
      $leftMenu['text']['color'] = $form_state->getValue('left_menu_text_color');
      $leftMenu['background_active']['color'] = $form_state->getValue('left_menu_background_active_color');
      $leftMenu['border_active']['color'] = $form_state->getValue('left_menu_border_active');
      $leftMenu['text_active']['color'] = $form_state->getValue('left_menu_text_active');
      $leftMenu['hover']['color']['background'] = $form_state->getValue('left_menu_background_hover');
      $leftMenu['hover']['color']['text'] = $form_state->getValue('left_menu_text_hover');
      $leftMenu['hover']['color']['border'] = $form_state->getValue('left_menu_border_hover');
      $leftMenuDropdown['background']['color'] = $form_state->getValue('left_menu_dropdown_background');
      $leftMenuDropdown['text']['color'] = $form_state->getValue('left_menu_dropdown_text');
      $footer['background']['color'] = $form_state->getValue('footer_background');
      $footer['text']['color'] = $form_state->getValue('footer_text');
      $footer['link']['color'] = $form_state->getValue('footer_link');
      $footer['link_hover']['color'] = $form_state->getValue('footer_link_hover');
      $button['default']['background']['color'] = $form_state->getValue('button_default_background');
      $button['default']['background']['hover'] = $form_state->getValue('button_default_background_hover');
      $button['default']['border']['color'] = $form_state->getValue('button_default_border');
      $button['default']['border']['hover'] = $form_state->getValue('button_default_border_hover');
      $button['default']['text']['color'] = $form_state->getValue('button_default_text');
      $button['default']['text']['hover'] = $form_state->getValue('button_default_text_hover');
      $button['primary']['background']['color'] = $form_state->getValue('button_primary_background');
      $button['primary']['background']['hover'] = $form_state->getValue('button_primary_background_hover');
      $button['primary']['border']['color'] = $form_state->getValue('button_primary_border');
      $button['primary']['border']['hover'] = $form_state->getValue('button_primary_border_hover');
      $button['primary']['text']['color'] = $form_state->getValue('button_primary_text');
      $button['primary']['text']['hover'] = $form_state->getValue('button_primary_text_hover');
      $button['success']['background']['color'] = $form_state->getValue('button_success_background');
      $button['success']['background']['hover'] = $form_state->getValue('button_success_background_hover');
      $button['success']['border']['color'] = $form_state->getValue('button_success_border');
      $button['success']['border']['hover'] = $form_state->getValue('button_success_border_hover');
      $button['success']['text']['color'] = $form_state->getValue('button_success_text');
      $button['success']['text']['hover'] = $form_state->getValue('button_success_text_hover');
      $button['info']['background']['color'] = $form_state->getValue('button_info_background');
      $button['info']['background']['hover'] = $form_state->getValue('button_info_background_hover');
      $button['info']['border']['color'] = $form_state->getValue('button_info_border');
      $button['info']['border']['hover'] = $form_state->getValue('button_info_border_hover');
      $button['info']['text']['color'] = $form_state->getValue('button_info_text');
      $button['info']['text']['hover'] = $form_state->getValue('button_info_text_hover');
      $button['danger']['background']['color'] = $form_state->getValue('button_danger_background');
      $button['danger']['background']['hover'] = $form_state->getValue('button_danger_background_hover');
      $button['danger']['border']['color'] = $form_state->getValue('button_danger_border');
      $button['danger']['border']['hover'] = $form_state->getValue('button_danger_border_hover');
      $button['danger']['text']['color'] = $form_state->getValue('button_danger_text');
      $button['danger']['text']['hover'] = $form_state->getValue('button_danger_text_hover');
      $button['warning']['background']['color'] = $form_state->getValue('button_warning_background');
      $button['warning']['background']['hover'] = $form_state->getValue('button_warning_background_hover');
      $button['warning']['border']['color'] = $form_state->getValue('button_warning_border');
      $button['warning']['border']['hover'] = $form_state->getValue('button_warning_border_hover');
      $button['warning']['text']['color'] = $form_state->getValue('button_warning_text');
      $button['warning']['text']['hover'] = $form_state->getValue('button_warning_text_hover');
      $formInputs['background']['color'] = $form_state->getValue('form_inputs_background');
      $formInputs['text']['color'] = $form_state->getValue('form_inputs_text');

      $configFactory->set('page', $pageColors);
      $configFactory->set('box', $boxColors);
      $configFactory->set('vertical_tabs', $verticalTab);
      $configFactory->set('breadcrumb', $breadcrumb);
      $configFactory->set('top_menu', $topMenu);
      $configFactory->set('left_menu', $leftMenu);
      $configFactory->set('left_menu_dropdown', $leftMenuDropdown);
      $configFactory->set('footer', $footer);
      $configFactory->set('button', $button);
      $configFactory->set('form_inputs', $formInputs);
      $configFactory->save();

      $this->createCSSFile();
      parent::submitForm($form, $form_state);
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {

    }

    public function createCSSFile() {
      $filepath = drupal_get_path('module', 'westminster_colorful');
      $configFactory = $this->configFactory->getEditable('westminster_colorful.configuration');
      $pageColors = $configFactory->get('page');
      $boxColors = $configFactory->get('box');
      $verticalTab = $configFactory->get('vertical_tabs');
      $breadcrumb = $configFactory->get('breadcrumb');
      $topMenu = $configFactory->get('top_menu');
      $leftMenu = $configFactory->get('left_menu');
      $leftMenuDropdown = $configFactory->get('left_menu_dropdown');
      $footer = $configFactory->get('footer');
      $button = $configFactory->get('button');
      $formInputs = $configFactory->get('form_inputs');
      $css = self::CSS_SELECTORS['page']['background'].'{background-color:'.$pageColors['background']['color'].' !important;}'
              .self::CSS_SELECTORS['page']['text'].'{color:'.$pageColors['text']['color'].' !important;}'
              .self::CSS_SELECTORS['box']['background'].'{background-color:'.$boxColors['background']['color'].' !important;}'
              .self::CSS_SELECTORS['box']['table_row_alternate'].'{background-color:'.$boxColors['table_row_alternate']['color'].' !important;}'
              .self::CSS_SELECTORS['box']['table_cell_active'].'{background-color:'.$boxColors['table_cell_active']['color'].' !important;}'
              .self::CSS_SELECTORS['box']['text'].'{color:'.$boxColors['text']['color'].' !important;}'
              .self::CSS_SELECTORS['box']['link'].'{color:'.$boxColors['link']['color'].' !important;}'
              .self::CSS_SELECTORS['box']['link_hover'].'{color:'.$boxColors['link_hover']['color'].' !important;}'
              .self::CSS_SELECTORS['box']['border'].'{border-color:'.$boxColors['border']['color'].' !important;}'
              .self::CSS_SELECTORS['vertical_tabs']['background'].'{background-color:'.$verticalTab['background']['color'].' !important;}'
              .self::CSS_SELECTORS['vertical_tabs']['text'].'{color:'.$verticalTab['text']['color'].' !important;}'
              .self::CSS_SELECTORS['vertical_tabs']['link'].'{color:'.$verticalTab['link']['color'].' !important;}'
              .self::CSS_SELECTORS['vertical_tabs']['link_hover'].'{color:'.$verticalTab['link_hover']['color'].' !important;}'
              .self::CSS_SELECTORS['vertical_tabs']['border'].'{border-color:'.$verticalTab['border']['color'].' !important;}'
              .self::CSS_SELECTORS['vertical_tabs']['tab'].'{background-color:'.$verticalTab['tab']['color'].' !important;}'
              .self::CSS_SELECTORS['vertical_tabs']['tab_selected'].'{background-color:'.$verticalTab['tab_selected']['color'].' !important;}'
              .self::CSS_SELECTORS['breadcrumb']['text'].'{color:'.$breadcrumb['text']['color'].' !important;}'
              .self::CSS_SELECTORS['breadcrumb']['spacer'].'{color:'.$breadcrumb['spacer']['color'].' !important;}'
              .self::CSS_SELECTORS['breadcrumb']['active'].'{color:'.$breadcrumb['active']['text']['color'].' !important;}'
              .self::CSS_SELECTORS['top_menu']['background'].'{background-color:'.$topMenu['background']['color'].' !important;}'
              .self::CSS_SELECTORS['top_menu']['text'].'{color:'.$topMenu['text']['color'].' !important;}'
              .self::CSS_SELECTORS['top_menu']['border'].'{border-color:'.$topMenu['border']['color'].' !important;}'
              .self::CSS_SELECTORS['top_menu']['hover'].'{color:'.$topMenu['hover']['color']['text'].' !important;background-color:'.$topMenu['hover']['color']['background'].' !important;}'
              .self::CSS_SELECTORS['left_menu']['background'].'{background-color:'.$leftMenu['background']['color'].' !important;}'
              .self::CSS_SELECTORS['left_menu']['text'].'{color:'.$leftMenu['text']['color'].' !important;}'
              .self::CSS_SELECTORS['left_menu']['background_active'].'{background-color:'.$leftMenu['background_active']['color'].' !important;}'
              .self::CSS_SELECTORS['left_menu']['border_active'].'{border-color:'.$leftMenu['border_active']['color'].' !important;}'
              .self::CSS_SELECTORS['left_menu']['text_active'].'{color:'.$leftMenu['text_active']['color'].' !important;}'
              .self::CSS_SELECTORS['left_menu']['hover'].'{color:'.$leftMenu['hover']['color']['text'].' !important;background-color:'.$leftMenu['hover']['color']['background'].' !important;border-color:'.$leftMenu['hover']['color']['border'].' !important;}'
              .self::CSS_SELECTORS['left_menu_dropdown']['background'].'{background-color:'.$leftMenuDropdown['background']['color'].' !important;}'
              .self::CSS_SELECTORS['left_menu_dropdown']['text'].'{color:'.$leftMenuDropdown['text']['color'].' !important;}'
              .self::CSS_SELECTORS['footer']['background'].'{background-color:'.$footer['background']['color'].' !important;}'
              .self::CSS_SELECTORS['footer']['text'].'{color:'.$footer['text']['color'].' !important;}'
              .self::CSS_SELECTORS['footer']['svg'].'{fill:'.$footer['text']['color'].' !important;}'
              .self::CSS_SELECTORS['footer']['link'].'{color:'.$footer['link']['color'].' !important;}'
              .self::CSS_SELECTORS['footer']['link_hover'].'{color:'.$footer['link_hover']['color'].' !important;}'
              .self::CSS_SELECTORS['button']['default'].'{color:'.$button['default']['text']['color'].'!important;background-color:'.$button['default']['background']['color'].' !important;border-color:'.$button['default']['border']['color'].' !important;}'
              .self::CSS_SELECTORS['button']['default_hover'].'{color:'.$button['default']['text']['hover'].' !important;background-color:'.$button['default']['background']['hover'].' !important;border-color:'.$button['default']['border']['hover'].' !important;}'
              .self::CSS_SELECTORS['button']['primary'].'{color:'.$button['primary']['text']['color'].'!important;background-color:'.$button['primary']['background']['color'].' !important;border-color:'.$button['primary']['border']['color'].' !important;}'
              .self::CSS_SELECTORS['button']['primary_hover'].'{color:'.$button['primary']['text']['hover'].' !important;background-color:'.$button['primary']['background']['hover'].' !important;border-color:'.$button['primary']['border']['hover'].' !important;}'
              .self::CSS_SELECTORS['button']['success'].'{color:'.$button['success']['text']['color'].'!important;background-color:'.$button['success']['background']['color'].' !important;border-color:'.$button['success']['border']['color'].' !important;}'
              .self::CSS_SELECTORS['button']['success_hover'].'{color:'.$button['success']['text']['hover'].' !important;background-color:'.$button['success']['background']['hover'].' !important;border-color:'.$button['success']['border']['hover'].' !important;}'
              .self::CSS_SELECTORS['button']['info'].'{color:'.$button['info']['text']['color'].'!important;background-color:'.$button['info']['background']['color'].' !important;border-color:'.$button['info']['border']['color'].' !important;}'
              .self::CSS_SELECTORS['button']['info_hover'].'{color:'.$button['info']['text']['hover'].' !important;background-color:'.$button['info']['background']['hover'].' !important;border-color:'.$button['info']['border']['hover'].' !important;}'
              .self::CSS_SELECTORS['button']['danger'].'{color:'.$button['danger']['text']['color'].'!important;background-color:'.$button['danger']['background']['color'].' !important;border-color:'.$button['danger']['border']['color'].' !important;}'
              .self::CSS_SELECTORS['button']['danger_hover'].'{color:'.$button['danger']['text']['hover'].' !important;background-color:'.$button['danger']['background']['hover'].' !important;border-color:'.$button['danger']['border']['hover'].' !important;}'
              .self::CSS_SELECTORS['button']['warning'].'{color:'.$button['warning']['text']['color'].'!important;background-color:'.$button['warning']['background']['color'].' !important;border-color:'.$button['warning']['border']['color'].' !important;}'
              .self::CSS_SELECTORS['button']['warning_hover'].'{color:'.$button['warning']['text']['hover'].' !important;background-color:'.$button['warning']['background']['hover'].' !important;border-color:'.$button['warning']['border']['hover'].' !important;}'
              .self::CSS_SELECTORS['form_inputs']['background'].'{background-color:'.$formInputs['background']['color'].' !important;}'
              .self::CSS_SELECTORS['form_inputs']['text'].'{color:'.$formInputs['text']['color'].' !important;}'
              .self::CSS_SELECTORS['form_inputs']['checkbox'].'{box-shadow:0 0 2px -1px '.$formInputs['background']['color'].' !important;}';
      file_put_contents($filepath.'/css/westminster-colorful.css', $css);
      drupal_flush_all_caches();
    }

  }
