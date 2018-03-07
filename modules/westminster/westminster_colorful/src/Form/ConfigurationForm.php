<?php

  namespace Drupal\westminster_colorful\Form;

  use Drupal\Core\Form\ConfigFormBase;
  use Drupal\Core\Form\FormStateInterface;

  Class ConfigurationForm extends ConfigFormBase {

    const CSS_SELECTORS = [
      'page' => [
        'background' => '.content-wrapper',
        'text' => '.content-wrapper',
      ],
      'box' => [
        'background' => '.box-body,.tab-content',
        'text' => '.box-body,.tab-content',
        'link' => '.box-body a,.tab-content a',
        'link_hover' => '.box-body a:hover,.box-body a:active,.box-body a:focus,.tab-content a:hover,.tab-content a:active,.tab-content a:focus',
        'border' => 'details'
      ],
      'vertical_tabs' => [
        'background' => '.vertical-tabs',
        'text' => '.vertical-tabs',
        'link' => '.vertical-tabs a',
        'link_hover' => '.vertical-tabs a:hover,.vertical-tabs a:active,.vertical-tabs a:focus',
        'border' => '.vertical-tabs,.vertical-tabs__menu,.vertical-tabs__menu-item',
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

      $form['colors'] = [
        '#type' => 'vertical_tabs',
        '#default_tab' => 'edit-page',
      ];
      $form['page_colors'] = [
        '#type' => 'details',
        '#title' => t('Page'),
        '#group' => 'colors',
      ];
      $form['box_colors'] = [
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
      $form['page_colors']['page_background'] = [
        '#type' => 'color',
        '#title' => t('Background Color'),
        '#required' => TRUE,
        '#default_value' => $pageColors['background']['color'],
      ];
      $form['page_colors']['page_text'] = [
        '#type' => 'color',
        '#title' => t('Text Color'),
        '#required' => TRUE,
        '#default_value' => $pageColors['text']['color'],
      ];
      $form['box_colors']['box_background'] = [
        '#type' => 'color',
        '#title' => t('Background Color'),
        '#required' => TRUE,
        '#default_value' => $boxColors['background']['color'],
      ];
      $form['box_colors']['box_text'] = [
        '#type' => 'color',
        '#title' => t('Text Color'),
        '#required' => TRUE,
        '#default_value' => $boxColors['text']['color'],
      ];
      $form['box_colors']['box_link'] = [
        '#type' => 'color',
        '#title' => t('Link Color'),
        '#required' => TRUE,
        '#default_value' => $boxColors['link']['color'],
      ];
      $form['box_colors']['box_link_hover'] = [
        '#type' => 'color',
        '#title' => t('Link Hover Color'),
        '#required' => TRUE,
        '#default_value' => $boxColors['link_hover']['color'],
      ];
      $form['box_colors']['box_border'] = [
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
        '#title' => 'Background Color',
        '#required' => TRUE,
        '#default_value' => $leftMenuDropdown['background']['color'],
      ];
      $form['left_menu_dropdown']['left_menu_dropdown_text'] = [
        '#type' => 'color',
        '#title' => 'Text Color',
        '#required' => TRUE,
        '#default_value' => $leftMenuDropdown['text']['color'],
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
      $pageColors = $configFactory->get('page_colors');
      $boxColors = $configFactory->get('box_colors');
      $verticalTab = $configFactory->get('vertical_tabs');
      $breadcrumb = $configFactory->get('breadcrumb');
      $topMenu = $configFactory->get('top_menu');
      $leftMenu = $configFactory->get('left_menu');
      $leftMenuDropdown = $configFactory->get('left_menu_dropdown');
      $pageColors['background']['color'] = $form_state->getValue('page_background');
      $pageColors['text']['color'] = $form_state->getValue('page_text');
      $boxColors['background']['color'] = $form_state->getValue('box_background');
      $boxColors['text']['color'] = $form_state->getValue('box_text');
      $boxColors['link']['color'] = $form_state->getValue('box_link');
      $boxColors['link_hover']['color'] = $form_state->getValue('box_link_hover');
      $boxColors['border']['color'] = $form_state->getValue('box_border');
      $verticalTab['background']['color'] = $form_state->getValue('vertical_tabs_background');
      $verticalTab['text']['color'] = $form_state->getValue('vertical_tabs_text');
      $verticalTab['link']['color'] = $form_state->getValue('vertical_tabs_link');
      $verticalTab['link_hover']['color'] = $form_state->getValue('vertical_tabs_link_hover');
      $verticalTab['border']['color'] = $form_state->getValue('vertical_tabs_border');
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
      $configFactory->set('page_colors', $pageColors);
      $configFactory->set('box_colors', $boxColors);
      $configFactory->set('vertical_tabs', $verticalTab);
      $configFactory->set('breadcrumb', $breadcrumb);
      $configFactory->set('top_menu', $topMenu);
      $configFactory->set('left_menu', $leftMenu);
      $configFactory->set('left_menu_dropdown', $leftMenuDropdown);
      $configFactory->save();

      $this->createCSSFile();
      parent::submitForm($form, $form_state);
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {

    }

    public function createCSSFile() {
      $filepath = drupal_get_path('module', 'westminster_colorful');
      $configFactory = $this->configFactory->getEditable('westminster_colorful.configuration');
      $pageColors = $configFactory->get('page_colors');
      $boxColors = $configFactory->get('box_colors');
      $verticalTab = $configFactory->get('vertical_tabs');
      $breadcrumb = $configFactory->get('breadcrumb');
      $topMenu = $configFactory->get('top_menu');
      $leftMenu = $configFactory->get('left_menu');
      $leftMenuDropdown = $configFactory->get('left_menu_dropdown');
      $css = self::CSS_SELECTORS['page']['background'].'{background-color:'.$pageColors['background']['color'].' !important;}'
              .self::CSS_SELECTORS['page']['text'].'{color:'.$pageColors['text']['color'].' !important;}'
              .self::CSS_SELECTORS['box']['background'].'{background-color:'.$boxColors['background']['color'].' !important;}'
              .self::CSS_SELECTORS['box']['text'].'{color:'.$boxColors['text']['color'].' !important;}'
              .self::CSS_SELECTORS['box']['link'].'{color:'.$boxColors['link']['color'].' !important;}'
              .self::CSS_SELECTORS['box']['link_hover'].'{color:'.$boxColors['link_hover']['color'].' !important;}'
              .self::CSS_SELECTORS['box']['border'].'{border-color:'.$boxColors['border']['color'].' !important;}'
              .self::CSS_SELECTORS['vertical_tabs']['background'].'{background-color:'.$verticalTab['background']['color'].' !important;}'
              .self::CSS_SELECTORS['vertical_tabs']['text'].'{color:'.$verticalTab['text']['color'].' !important;}'
              .self::CSS_SELECTORS['vertical_tabs']['link'].'{color:'.$verticalTab['link']['color'].' !important;}'
              .self::CSS_SELECTORS['vertical_tabs']['link_hover'].'{color:'.$verticalTab['link_hover']['color'].' !important;}'
              .self::CSS_SELECTORS['vertical_tabs']['border'].'{border-color:'.$verticalTab['border']['color'].' !important;}'
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
              .self::CSS_SELECTORS['left_menu_dropdown']['text'].'{color:'.$leftMenuDropdown['text']['color'].' !important;}';
      file_put_contents($filepath.'/css/westminster-colorful.css', $css);
      drupal_flush_all_caches();
    }

  }
