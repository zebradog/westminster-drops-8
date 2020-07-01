<?php

namespace Drupal\flysystem_s3;

use Drupal\Core\Session\AccountInterface;

/**
 * Helper for altering and processing a managed_file element for CORS upload.
 */
class S3CorsManagedFileHelper {

  /**
   * Function alterInfo called by hook_element_info_alter().
   */
  public static function alterInfo(array &$types) {
    array_unshift($types['managed_file']['#process'], [get_called_class(), 'preProcessCors']);
    $types['managed_file']['#process'][] = [get_called_class(), 'postProcessCors'];
  }

  /**
   * Function preProcessCors prepare the field to use CORS upload.
   */
  public static function preProcessCors(array &$element) {
    if (isset($element['#s3_cors']) && !$element['#s3_cors']) {
      // S3 CORS support has been specifically disabled for this element.
      return $element;
    }

    // Default to off until the upload destination is confirmed to be an S3
    // scheme, CORS support is enabled in the flysystem config, and the user
    // has permission to upload files using CORS.
    $element['#s3_cors'] = FALSE;

    if (!empty($element['#upload_location']) && $scheme = \Drupal::service('file_system')->uriScheme($element['#upload_location'])) {
      if (static::isCorsAvailable($scheme)) {
        // @todo Verify account permission/role respected with cache tags.
        // Disable the default progress indicator.
        $element['#progress_indicator'] = 'none';

        // Add a flag to the element to indicate that this is a CORS upload.
        $element['#s3_cors'] = TRUE;

        // Attach the JS library to the element conditionally.
        $element['#attached']['library'][] = 'flysystem_s3/drupal.s3_cors_upload';

        // Set the default S3 ACL if it is not already set.
        if (!isset($element['#s3_acl'])) {
          $element['#s3_acl'] = static::getAcl($scheme);
        }
      }
    }

    return $element;
  }

  /**
   * Function postProcessCors add data attributes that are used flysystem_s3.js.
   */
  public static function postProcessCors(array &$element) {
    if (!empty($element['#s3_cors'])) {

      // Add data attributes that are used by flysystem_s3.js to submit the
      // AJAX request to sign the upload.
      $element['upload']['#attributes']['data-s3-acl'] = $element['#s3_acl'];
      $element['upload']['#attributes']['data-s3-destination'] = $element['#upload_location'];
      $element['upload']['#attributes']['data-flysystem-s3-cors'] = TRUE;

      // Add the valid extensions as data attributes.
      if (!empty($element['#upload_validators']['file_validate_extensions'][0])) {
        $element['upload']['#attributes']['data-valid-extensions'] = $element['#upload_validators']['file_validate_extensions'][0];
      }
    }

    return $element;
  }

  /**
   * Returns the settings for a Flysystem file scheme.
   *
   * @param string $scheme
   *   A file scheme.
   *
   * @return array
   *   The Flysystem file scheme's settings.
   */
  public static function getSchemeSettings($scheme) {
    return \Drupal::service('flysystem_factory')->getSettings($scheme);
  }

  /**
   * Determines if CORS upload is available.
   *
   * @param string $scheme
   *   A file scheme.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   A user account object.
   *
   * @return bool
   *   TRUE if CORS upload support is available, or FALSE otherwise.
   */
  public static function isCorsAvailable($scheme, AccountInterface $account = NULL) {
    if (!isset($account)) {
      $account = \Drupal::currentUser()->getAccount();
    }
    $settings = static::getSchemeSettings($scheme);

    return !empty($settings['driver']) && $settings['driver'] === 's3' && !empty($settings['config']['cors']) && $account->hasPermission('use S3 CORS upload');
  }

  /**
   * Get the default S3 ACL setting for a file scheme.
   *
   * @param string $scheme
   *   A file scheme.
   *
   * @return string
   *   The S3 ACL upload setting. If not set in the scheme settings, it will
   *   default to 'private'.
   */
  public static function getAcl($scheme) {
    $settings = static::getSchemeSettings($scheme);

    // Config options is not required and ACL can be set to NULL to ignore
    // x-amz-acl header.
    if (!empty($settings['config']['options']) && array_key_exists('ACL', $settings['config']['options'])) {
      return $settings['config']['options']['ACL'];
    }

    return 'private';
  }

}
