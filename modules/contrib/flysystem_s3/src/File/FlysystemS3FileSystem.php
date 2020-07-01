<?php

namespace Drupal\flysystem_s3\File;

use Drupal\Core\File\FileSystem;
use Drupal\Core\Site\Settings;
use Drupal\Core\StreamWrapper\StreamWrapperManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Decorates the Drupal FileSystem service to handle chmod() for S3.
 */
class FlysystemS3FileSystem extends FileSystem {

  /**
   * FlysystemS3FileSystem constructor.
   *
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The file system being decorated.
   * @param \Drupal\Core\StreamWrapper\StreamWrapperManagerInterface $stream_wrapper_manager
   *   The stream wrapper manager.
   * @param \Drupal\Core\Site\Settings $settings
   *   The site settings.
   * @param \Psr\Log\LoggerInterface $logger
   *   The file logger channel.
   */
  public function __construct(StreamWrapperManagerInterface $stream_wrapper_manager, Settings $settings, LoggerInterface $logger) {
    parent::__construct($stream_wrapper_manager, $settings, $logger);
  }

  /**
   * {@inheritdoc}
   *
   * Extend chmod(), respecting S3's ACL setting.
   *
   * With Drupal's private files, chmod() is called by file_save_upload() to
   * ensure the new file is readable by the file server, using the same file
   * system permissions as the public file system. However, since private files
   * are stored outside of the docroot, they are forced to go be accessed
   * through Drupal's file permissions handling.
   *
   * With S3, \Twistor\FlysystemStreamWrapper::stream_metadata() automatically
   * maps chmod() calls to basic S3 ACLs, which means that while a file can be
   * initially uploaded as 'private', Drupal will immediately chmod it to
   * public using the default file mask in settings.php.
   *
   * This method checks to see if we are using a private S3 scheme, and if so,
   * ensures that group / other permissions are always unset, ensuring the
   * stream wrapper preserves private permissions.
   *
   * @param string $uri
   *   A string containing a URI file, or directory path.
   * @param int $mode
   *   Integer value for the permissions. Consult PHP chmod() documentation for
   *   more information.
   *
   * @return bool
   *   TRUE for success, FALSE in the event of an error.
   *
   * @see \Twistor\FlysystemStreamWrapper::stream_metadata
   */
  public function chmod($uri, $mode = NULL) {
    $scheme = parent::uriScheme($uri);

    if ($this->isPrivateS3Scheme($scheme)) {
      is_dir($uri) ? $mode = 0700 : $mode = 0600;
    }

    return parent::chmod($uri, $mode);
  }

  /**
   * Return if a scheme is a private S3 scheme.
   *
   * @param string $scheme
   *   The scheme to check.
   *
   * @return bool
   *   TRUE if the scheme's S3 acl is set to 'private'.
   */
  protected function isPrivateS3Scheme($scheme) {
    $settings = $this->settings->get('flysystem', []);
    return isset($settings[$scheme])
      && $settings[$scheme]['driver'] == 's3'
      && isset($settings[$scheme]['config']['options']['ACL'])
      && $settings[$scheme]['config']['options']['ACL'] == 'private';
  }

}
