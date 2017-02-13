<?php

namespace Drupal\flysystem_s3\Flysystem;

use Aws\AwsClientInterface;
use Aws\Credentials\Credentials;
use Aws\S3\S3Client;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Logger\RfcLogLevel;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\flysystem\Plugin\FlysystemPluginInterface;
use Drupal\flysystem\Plugin\FlysystemUrlTrait;
use Drupal\flysystem\Plugin\ImageStyleGenerationTrait;
use Drupal\flysystem_s3\AwsCacheAdapter;
use Drupal\flysystem_s3\Flysystem\Adapter\S3Adapter;
use League\Flysystem\Config;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Drupal plugin for the "S3" Flysystem adapter.
 *
 * @Adapter(id = "s3")
 */
class S3 implements FlysystemPluginInterface, ContainerFactoryPluginInterface {

  use ImageStyleGenerationTrait;
  use FlysystemUrlTrait { getExternalUrl as getDownloadlUrl; }

  /**
   * The S3 bucket.
   *
   * @var string
   */
  protected $bucket;

  /**
   * The S3 client.
   *
   * @var \Aws\AwsClientInterface
   */
  protected $client;

  /**
   * Options to pass into \League\Flysystem\AwsS3v3\AwsS3Adapter.
   *
   * @var array
   */
  protected $options;

  /**
   * The path prefix inside the bucket.
   *
   * @var string
   */
  protected $prefix;

  /**
   * The URL prefix.
   *
   * @var string
   */
  protected $urlPrefix;

  /**
   * Constructs an S3 object.
   *
   * @param \Aws\AwsClientInterface $client
   *   The AWS client.
   * @param \League\Flysystem\Config $config
   *   The configuration.
   */
  public function __construct(AwsClientInterface $client, Config $config) {
    $this->client = $client;
    $this->bucket = $config->get('bucket', '');
    $this->prefix = $config->get('prefix', '');
    $this->options = $config->get('options', []);

    $this->urlPrefix = $this->calculateUrlPrefix($config);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $configuration = static::mergeConfiguration($container, $configuration);
    $client_config = static::mergeClientConfiguration($container, $configuration);

    $client = S3Client::factory($client_config);

    unset($configuration['key'], $configuration['secret']);

    return new static($client, new Config($configuration));
  }

  /**
   * Returns an S3 client configuration based on a Flysystem configuration.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container to pull out services used in the plugin.
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   *
   * @return array
   *   The client configuration.
   */
  public static function mergeClientConfiguration(ContainerInterface $container, array $configuration) {
    $client_config = [
      'version' => 'latest',
      'region' => $configuration['region'],
      'endpoint' => $configuration['endpoint'],
    ];

    // Allow authentication with standard secret/key or IAM roles.
    if (isset($configuration['key']) && isset($configuration['secret'])) {
      $client_config['credentials'] = new Credentials($configuration['key'], $configuration['secret']);

      return $client_config;
    }

    $client_config['credentials.cache'] = new AwsCacheAdapter(
      $container->get('cache.default'),
      'flysystem_s3:'
    );

    return $client_config;
  }

  /**
   * Merges default Flysystem configuration.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container to pull out services used in the plugin.
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   *
   * @return array
   *   The Flysystem configuration.
   */
  public static function mergeConfiguration(ContainerInterface $container, array $configuration) {
    $protocol = $container->get('request_stack')
      ->getCurrentRequest()
      ->getScheme();

    return $configuration += [
      'protocol' => $protocol,
      'region' => 'us-east-1',
      'endpoint' => NULL,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getAdapter() {
    return new S3Adapter($this->client, $this->bucket, $this->prefix, $this->options);
  }

  /**
   * {@inheritdoc}
   */
  public function getExternalUrl($uri) {
    $target = $this->getTarget($uri);

    if (strpos($target, 'styles/') === 0 && !file_exists($uri)) {
      $this->generateImageStyle($target);
    }

    return $this->urlPrefix . '/' . UrlHelper::encodePath($target);
  }

  /**
   * {@inheritdoc}
   */
  public function ensure($force = FALSE) {
    // @TODO: If the bucket exists, can we write to it? Find a way to test that.
    if (!$this->client->doesBucketExist($this->bucket)) {
      return [[
        'severity' => RfcLogLevel::ERROR,
        'message' => 'Bucket %bucket does not exist.',
        'context' => [
          '%bucket' => $this->bucket,
        ],
      ]];
    }

    return [];
  }

  /**
   * Calculates the URL prefix.
   *
   * @param \League\Flysystem\Config $config
   *   The configuration.
   *
   * @return string
   *   The URL prefix in the form protocol://cname[/bucket][/prefix].
   */
  private function calculateUrlPrefix(Config $config) {
    $protocol = $config->get('protocol', 'http');

    $cname = (string) $config->get('cname');

    $prefix = (string) $config->get('prefix', '');
    $prefix = $prefix === '' ? '' : '/' . UrlHelper::encodePath($prefix);

    if ($cname !== '' && $config->get('cname_is_bucket', TRUE)) {
       return $protocol . '://' . $cname . $prefix;
     }

    $bucket = (string) $config->get('bucket', '');
    $bucket = $bucket === '' ? '' : '/' . UrlHelper::encodePath($bucket);

    // No custom CNAME was provided. Generate the default S3 one.
    if ($cname === '') {
      $cname = 's3-' . $config->get('region', 'us-east-1') . '.amazonaws.com';
    }

    // us-east-1 doesn't follow the consistent mapping.
    if ($cname === 's3-us-east-1.amazonaws.com') {
      $cname = 's3.amazonaws.com';
    }

    return $protocol . '://' . $cname . $bucket . $prefix;
  }

}
