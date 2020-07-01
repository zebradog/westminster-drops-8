Flysystem S3
============

For setup instructions see the Flysystem README.md.

## CONFIGURATION ##

The region needs to be set to the region id, not the region name. Here is a list
of the region names and their corresponding ids:

|Region name               |Region id      |
|:-------------------------|:--------------|
|US East (N. Virginia)     |us-east-1      |
|US West (N. California)   |us-west-1      |
|US West (Oregon)          |us-west-2      |
|EU (Ireland)              |eu-west-1      |
|EU (Frankfurt)            |eu-central-1   |
|Asia Pacific (Tokyo)      |ap-northeast-1 |
|Asia Pacific (Seoul)      |ap-northeast-2 |
|Asia Pacific (Singapore)  |ap-southeast-1 |
|Asia Pacific (Sydney)     |ap-southeast-2 |
|South America (Sao Paulo) |sa-east-1      |

The `s3-cors-example.json` file has a template you can use to configure CORS
using the REST API or the `aws s3api put-bucket-cors` command.

Example configuration:

```php
$schemes = [
  's3' => [
    'driver' => 's3',
    'config' => [
      'key'    => '[your key]',      // 'key' and 'secret' do not need to be
      'secret' => '[your secret]',   // provided if using IAM roles.
      'region' => '[aws-region-id]',
      'bucket' => '[bucket-name]',

      // Optional configuration settings.

      // 'options' => [
      //   'ACL' => 'public-read',
      //   'StorageClass' => 'REDUCED_REDUNDANCY',
      // ],

      // 'protocol' => 'https',                   // Autodetected based on the
                                                  // current request if not
                                                  // provided.

      // 'prefix' => 'an/optional/prefix',        // Directory prefix for all
                                                  // uploaded/viewed files.

      // 'cname' => 'static.example.com',         // A CNAME that resolves to
                                                  // your bucket. Used for URL
                                                  // generation.

      // 'cname_is_bucket' => TRUE,               // Set to FALSE if the CNAME
                                                  // does not resolve to a
                                                  // bucket and the bucuket
                                                  // should be included in the
                                                  // path.

      // 'endpoint' => 'https://api.example.com', // An alternative API endpoint
                                                  // for 3rd party S3 providers.

      // 'public' => TRUE,                        // Set to TRUE to link to files
                                                  // using direct links.

      // 'cors' => TRUE,                          // Set to TRUE if CORS upload
                                                  // support is enabled for the
                                                  // bucket.
    ],

    'cache' => TRUE, // Creates a metadata cache to speed up lookups.
  ],
];

$settings['flysystem'] = $schemes;
```
