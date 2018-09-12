<?php

  namespace Drupal\westminster_s3\Plugin\media\Source;

  use Drupal\Core\Site\Settings;
  use Drupal\file\FileInterface;
  use Drupal\media\MediaTypeInterface;
  use Drupal\media\Plugin\media\Source\File;

  /**
   * Flysystem S3 media source.
   *
   * TODO: Look into automatically enabling the queue_thumbnail_downloads setting for created media types that utilize this source.
   *
   * @MediaSource(
   *   id = "s3file",
   *   label = @Translation("S3File"),
   *   description = @Translation("Use flysystem_s3 for reusable media."),
   *   allowed_field_types = {"s3file"},
   *   default_thumbnail_filename = "generic.png"
   * )
   */
  Class S3File extends File {

    /**
     * Fallback value if no scheme can be inferred.
     * @see _inferUriScheme()
     */
    const DEFAULT_URI_SCHEME = 'public';

    /**
     * The maximum file size for thumbnail generation.
     * On Pantheon we can expect performance degradation after 50 MB.
     */
    const THUMBNAIL_MAX_FILESIZE = 50000000;

    /**
     * {@inheritdoc}
     */
    public function createSourceField(MediaTypeInterface $type) {
      return parent::createSourceField($type)->set('settings', [
        'file_extensions' => 'gif jpeg jpg mp4 ogv png webm',
      ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function createSourceFieldStorage() {
      $storage = parent::createSourceFieldStorage();

      $settings = $storage->get('settings');
      $settings['uri_scheme'] = $this->_inferUriScheme();
      $storage->set('settings', $settings);

      return $storage;
    }

    /**
     * {@inheritdoc}
     */
    protected function getThumbnail(FileInterface $file) {
      $isImage = strpos($file->getMimeType(), 'image/') === 0;
      $isValidSize = $file->getSize() < static::THUMBNAIL_MAX_FILESIZE;

      if ($isImage && $isValidSize) {
        return $file->getFileUri();
      }

      return parent::getThumbnail($file);
    }

    /**
     * Returns the first Flysystem scheme that uses the S3 driver.
     * Typically there is only one, but the user can configure the field if a different scheme is required.
     */
    protected function _inferUriScheme() {
      if ($flysystemFactory = \Drupal::service('flysystem_factory')) {
        $schemes = $flysystemFactory->getSchemes();

        foreach ($schemes as $scheme) {
          $settings = $flysystemFactory->getSettings($scheme);

          if ($settings['driver'] == 's3') {
            return $scheme;
          }
        }
      }

      return static::DEFAULT_URI_SCHEME;
    }

  }
