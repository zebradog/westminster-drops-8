<?php

  namespace Drupal\westminster_support\Form;

  use \Drupal\Core\Form\FormBase;
  use \Drupal\Core\Form\FormStateInterface;
  use \Drupal\user\Entity\User;

  /**
   * Contact form that appears on the main page.
   */
  Class ContactForm extends FormBase {

    /**
     * Name of the module configuration object.
     */
    const CONFIGURATION_NAME = 'westminster_support.configuration';

    /**
     * Instance of the module configuration object.
     * @see _getConfiguration()
     * @var Config
     */
    protected $_configuration;

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
      $configuration = $this->_getConfiguration();

      $form['subject'] = [
        '#required' => true,
        '#title' => $this->t('Subject'),
        '#type' => 'textfield',
      ];

      $form['body'] = [
        '#title' => $this->t('Description'),
        '#type' => 'textarea',
      ];

      $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Send'),
      ];

      return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
      return 'westminster_support_contact';
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
      $config = $this->_getConfiguration();
      $messenger = \Drupal::messenger();

      $values = [
        'body' => $form_state->getValue('body'),
        'subject' => $form_state->getValue('subject'),
      ];

      $result = false;

      if ($config->get('form.email.active')) {
        $result = $this->_sendEmail($values);
      }

      if ($result) {
        $messenger->addStatus(
          $this->t(
            $config->get('form.success')
          )
        );
      } else {
        $messenger->addError(
          $this->t(
            $config->get('form.error')
          )
        );
      }
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
      // TODO: Check required fields
    }

    /**
     * Returns the module configuration object.
     * @return Config
     */
    protected function _getConfiguration() {
      if (!$this->_configuration) {
        $this->_configuration = $this->config(static::CONFIGURATION_NAME);
      }

      return $this->_configuration;
    }

    /**
     * Sends an email with the provided values.
     * @param mixed[] $values
     * @return boolean - Result of MailManager::send()
     * @see https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Mail%21MailManager.php/function/MailManager%3A%3Amail
     */
    protected function _sendEmail(array $values) {
      $config = $this->_getConfiguration();
      $mailManager = \Drupal::service('plugin.manager.mail');
      $user = User::load(\Drupal::currentUser()->id());

      $from = $user ? $user->getEmail() : NULL;
      $langcode = $user ? $user->getPreferredLangcode() : 'en';
      $params = $values;
      $to = $config->get('form.email.recipient') ?: \Drupal::config('system.site')->get('mail');

      $subjectPrefix = $config->get('form.email.subjectPrefix');
      if ($subjectPrefix) {
        $params['subject'] = $subjectPrefix . ' ' . $params['subject'];
      }

      $result = $mailManager->mail('westminster_support', 'contact', $to, $langcode, $params, $from);
      return $result['result'] === true;
    }

  }
