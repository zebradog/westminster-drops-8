<?php

namespace Drupal\flysystem_s3\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines a form that configures devel settings.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'flysystem_s3_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'flysystem_s3.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {
    $config = $this->config('flysystem_s3.settings');
    // I'd like to be able to pull this information directly from the SDK, but
    // I couldn't find a good way to get the human-readable region names.
    $region_map = [
      '' => 'Default',
      'us-east-1' => 'US Standard (us-east-1)',
      'us-west-1' => 'US West - Northern California  (us-west-1)',
      'us-west-2' => 'US West - Oregon (us-west-2)',
      'us-gov-west-1' => 'USA GovCloud Standard (us-gov-west-1)',
      'eu-west-1' => 'EU - Ireland  (eu-west-1)',
      'eu-central-1' => 'EU - Frankfurt (eu-central-1)',
      'ap-south-1' => 'Asia Pacific - Mumbai',
      'ap-southeast-1' => 'Asia Pacific - Singapore (ap-southeast-1)',
      'ap-southeast-2' => 'Asia Pacific - Sydney (ap-southeast-2)',
      'ap-northeast-1' => 'Asia Pacific - Tokyo (ap-northeast-1)',
      'sa-east-1' => 'South America - Sao Paulo (sa-east-1)',
      'cn-north-1' => 'China - Beijing (cn-north-1)',
    ];
    $form['credentials'] = [
      '#type' => 'fieldset',
      '#title' => t('Amazon Web Services Credentials'),
      '#description' => t(
        "To configure your Amazon Web Services credentials, enter the values in the appropriate fields below.
        You may instead set \$conf['awssdk2_access_key'] and \$conf['awssdk2_secret_key'] in your site's settings.php   file.
        Values set in settings.php will override the values in these fields."
      ),
      '#collapsible' => TRUE,
      '#collapsed' => $config->get('use_instance_profile'),
    ];

    $form['credentials']['access_key'] = [
      '#type' => 'textfield',
      '#title' => t('Amazon Web Services Access Key'),
      '#default_value' => $config->get('access_key'),
    ];

    $form['credentials']['secret_key'] = [
      '#type' => 'textfield',
      '#title' => t('Amazon Web Services Secret Key'),
      '#default_value' => $config->get('secret_key'),
    ];
    $form['bucket'] = [
      '#type' => 'textfield',
      '#title' => t('S3 Bucket Name'),
      '#default_value' => $config->get('bucket'),
      '#required' => TRUE,
    ];
    $form['region'] = [
      '#type' => 'select',
      '#options' => $region_map,
      '#title' => t('S3 Region'),
      '#default_value' => $config->get('region'),
      '#description' => t(
        'The region in which your bucket resides. Be careful to specify this accurately,
      as you are likely to see strange or broken behavior if the region is set wrong.<br>
      Use of the USA GovCloud region requires @SPECIAL_PERMISSION.<br>
      Use of the China - Beijing region requires a @CHINESE_AWS_ACCT.',
        [
          '@CHINESE_AWS_ACCT' => Link::fromTextAndUrl($this->t('亚马逊 AWS account'), Url::fromUri('http://www.amazonaws.cn'))
            ->toString(),
          '@SPECIAL_PERMISSION' => Link::fromTextAndUrl($this->t('special permission'), Url::fromUri('http://aws.amazon.com/govcloud-us/'))
            ->toString(),
        ]
      ),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('s3fs.settings')
      ->set('access_key', $values['access_key'])
      ->set('secret_key', $values['secret_key'])
      ->set('bucket', $values['bucket'])
      ->set('region', $values['region'])
      ->save();

    parent::submitForm($form, $form_state);
  }


}
