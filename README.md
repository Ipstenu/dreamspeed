dreamspeed
==========

DreamSpeed CDN

Requirements

* PHP 5.3 or higher
* A DreamHost DreamObjects account


DFAQ

* Why is this separate from DreamObjects Connection?

Because

== Notes

require 'aws-autoloader.php';

use Aws\Common\Aws;

// Use custom config file
$aws = Aws::factory('/home/user/dreamobjects_sdk_v2.php');

$dreamobjects = $aws->get('DreamObjects');

// List Buckets
$result = $dreamobjects->listBuckets();
foreach ($result['Buckets'] as $bucket) {
   echo "- {$bucket['Name']}\n";
}
