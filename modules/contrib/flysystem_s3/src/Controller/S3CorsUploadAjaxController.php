<?php

namespace Drupal\flysystem_s3\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\file\Entity\File;
use Drupal\flysystem\FlysystemFactory;
use Drupal\Core\File\FileSystemInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Aws\S3\PostObjectV4;
use Drupal\Core\StreamWrapper\StreamWrapperManager;

/**
 * Defines a controller to respond to S3 CORS upload AJAX requests.
 */
class S3CorsUploadAjaxController extends ControllerBase {

  /**
   * The form builder.
   *
   * @var \Drupal\flysystem\FlysystemFactory
   */
  protected $flysystemFactory;

  /**
   * The file system.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('flysystem_factory'),
      $container->get('file_system')
    );
  }

  /**
   * Constructs an S3CorsUploadAjaxController object.
   *
   * @param \Drupal\flysystem\FlysystemFactory $flysystem_factory
   *   The Flysystem factory.
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The file system.
   */
  public function __construct(FlysystemFactory $flysystem_factory, FileSystemInterface $file_system) {
    $this->flysystemFactory = $flysystem_factory;
    $this->fileSystem = $file_system;
  }

  /**
   * Returns the signed request.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The current request.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   A JsonResponse object.
   */
  public function signRequest(Request $request) {
    $post = $request->request->all();

    /** @var \Drupal\flysystem_s3\Flysystem\Adapter\S3Adapter $adapter */
    $scheme = \Drupal::service('file_system')->uriScheme($post['destination']);
    $adapter = $this->flysystemFactory->getPlugin($scheme)->getAdapter();

    $client = $adapter->getClient();
    $bucket = $adapter->getBucket();
    $destination = $adapter->applyPathPrefix(StreamWrapperManager::getTarget($post['destination']));

    $options = [
      ['acl' => $post['acl']],
      ['bucket' => $bucket],
      ['starts-with', '$key', $destination . '/'],
    ];

    // Retrieve the file name and build the URI.
    // Destination does not contain a prefix as it is applied by the fly system.
    $uri = \Drupal::service('file_system')->createFilename($post['filename'], $post['destination']);
    // Apply the prefix to the URI and use it as a key in the POST request.
    $post['key'] = $adapter->applyPathPrefix(StreamWrapperManager::getTarget($uri));

    // Create a temporary file to return with a file ID in the response.
    $file = File::create([
      'uri' => $post['key'],
      'filesize' => $post['filesize'],
      'filename' => $post['filename'],
      'filemime' => $post['filemime'],
      'uid' => \Drupal::currentUser()->getAccount()->id(),
    ]);
    $file->save();

    // Remove values not necessary for the request to Amazon.
    unset($post['destination']);
    unset($post['filename']);
    unset($post['filemime']);
    unset($post['filesize']);

    // @todo Make this interval configurable.
    $expiration = '+5 hours';
    $postObject = new PostObjectV4($client, $bucket, $post, $options, $expiration);

    $data = [];
    $data['attributes'] = $postObject->getFormAttributes();
    $data['inputs'] = $postObject->getFormInputs();
    $data['options'] = $options;
    $data['fid'] = $file->id();

    return new JsonResponse($data);
  }

}
