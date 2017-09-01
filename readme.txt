=== DreamSpeed CDN ===
Contributors: Ipstenu
Tags: cloud, dreamhost, dreamspeed, cdn
Requires at least: 4.0
Tested up to: 4.8
Stable tag: 1.0.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

The DreamSpeed CDN plugin has been retired.

== Description ==

<strong>As of December 2018 this plugin will be retired and no longer supported or maintained.</strong>

At that time, the DreamSpeed CDN service will itself be sunset, making this plugin no longer a viable service.

When uninstalled, this plugin will undo it's changes and revert your content back to localhosted URLs. However there is a possibility that it will be unable to correctly update all your content (if images aren't attached to posts, they may not be fixed). Should that happen, you will need to manually change your content. 

If you use WP-CLI, you can run the following command:

`wp search-replace 'objects-us-west-1.dream.io/bucketname/wp-content/uploads' 'example.com/wp-content/uploads' `

You may need to change `objects-us-west-1.dream.io/bucketname` to `custom-cdn.dream.io` or `bucketname.objects.cdn.dream.io` depending on what your URL is set to.

You can also do this via the [InterconnectDB script](https://interconnectit.com/products/search-and-replace-for-wordpress-databases/), or maybe even the [Velvet Blues URL replacement plugin](https://wordpress.org/plugins/velvet-blues-update-urls/).

= Credits =
<em>This plugin is a spork (fork and combination) of <a href="https://wordpress.org/plugins/amazon-s3-and-cloudfront/">Amazon S3 and Cloudfront</a> and <a href="https://github.com/deliciousbrains/wp-amazon-web-services">Amazon Web Services</a>, both by the awesome Brad Touesnard.</em>

== Installation ==

1. Please uninstall this plugin

== Frequently asked questions ==

<strong>Why is this plugin being retired?</strong>

Because the CDN service it relies on is being retired.

<strong>Couldn't this be changed to just use the cloud instead?</strong>

Yes, but it wouldn't be significantly faster than self-hosting. In fact, in many cases it would be slower.

<strong>Can you recommend a replacement?</strong>

Not at this time, no. If you just need to offload media, I recommend Photon by Jetpack. If you need to to host large files like podcasts or videos, there really isn't a great solution, which speaks to why this plugin had a serious failure to launch.

<strong>Is this plugin a failure?</strong>

Oh yeah, I totally call it a failure. It was never great and was barely passable at best.

== Changelog ==

= 1.0.0 =
* September 2018 by Ipstenu
* Retirement

= 0.7.4 =
* February 2017 by Ipstenu
* Fix: Display image on media library if using a custom CDN

= 0.7.3 = 
* January 2017 by Ipstenu
* Fix: If bucket has a period in the name, use domain http(s)://objects(*).dream.io/BUCKET
* Fix: Cloudfront filters
* Run: Upgrade feature to Fullspeed is working (part of #22)

= 0.7.2 =
* October 2016 by Ipstenu
* Fix: Trust fullspeed, even with SSL (resolves #22)
* (NB: This was not released as it ended up finding a couple major bugs)

= 0.7.1 =
* August 2016 by Ipstenu
* Fix: Serve files from dream.io points to the right location
* Run: Upgrade feature to ensure the above
* Fix: If SSL, don't use CDN Alias as that won't work
* Fix: If SSL, auto-check 'force ssl' because you did already
* Fix: If using dream.io, don't allow CDN Aliases (you can't have both)
* Fix: Improved messaging

= 0.7.0 =
* May 2016 by Ipstenu
* Change hostname to objects-us-west-1.dream.io
* Create upgrade feature to force all URLs to be updated
* Added explanatory descriptions to settings page
* Hide certain settings if requirements are not met (you can't upload existing media if you don't pick a bucket, for example)
* Build out framework for future regions

= 0.6.0 =
* March 2016 by Ipstenu
* Updating SDK
* Changing dev environment to use Composer
* Fixed SSL to work with Dream.io URLs
* Improved sanitization and escaping.

== Upgrade Notice ==
As of version 1.0.0 this plugin is no longer supported or maintained. Once you've upgraded, please uninstall.