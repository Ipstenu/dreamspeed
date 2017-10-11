=== DreamSpeed CDN (RETIRED) ===
Contributors: Ipstenu
Tags: cloud, dreamhost, dreamspeed, cdn
Requires at least: 4.0
Tested up to: 4.9
Stable tag: 1.0.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

THE DREAMSPEED CDN PLUGIN HAS BEEN RETIRED.

== Description ==

<strong>As of November 2017 this plugin will be retired and no longer supported or maintained.</strong>

At that time, the DreamSpeed CDN service will itself be sunset, making this plugin no longer a viable service. You should have received an email with the following notice:

<blockquote>We will discontinue the DreamSpeed CDN add-on service on November 30, 2017 to make way for future enhancements to DreamObjects. DreamSpeed CDN didn’t quite live up to our expectations, and we’ve made the difficult decision to wind it down.
 
All data stored in your DreamObjects buckets will remain safe and unaffected once DreamSpeed CDN reaches the end of its natural life.
 
Existing CDN URLs will begin redirecting to the non-CDN URL once the service is discontinued, however we do still encourage you to update your links to point to the non-CDN URL.</blockquote>

When uninstalled, this plugin will undo it's changes and revert your content back to locally hosted URLs. However there is a possibility that it will be unable to correctly update all your content (if images aren't attached to posts, they may not be fixed). Should that happen, you will need to manually change your content.

If you use WP-CLI, you can run the following command:

`wp search-replace 'objects-us-west-1.dream.io/bucketname/wp-content/uploads' 'example.com/wp-content/uploads' `

You may need to change `objects-us-west-1.dream.io/bucketname` to `custom-cdn.dream.io` or `bucketname.objects.cdn.dream.io` depending on what your URL is set to.

You can also do this via the [InterconnectDB script](https://interconnectit.com/products/search-and-replace-for-wordpress-databases/), or the [Velvet Blues URL replacement plugin](https://wordpress.org/plugins/velvet-blues-update-urls/).

= Credits =
<em>This plugin was a spork (fork and combination) of <a href="https://wordpress.org/plugins/amazon-s3-and-cloudfront/">Amazon S3 and Cloudfront</a> and <a href="https://github.com/deliciousbrains/wp-amazon-web-services">Amazon Web Services</a>, both by the awesome Brad Touesnard.</em>

== Installation ==

1. Please uninstall this plugin

== Frequently asked questions ==

<strong>Why is this plugin being retired?</strong>

Because the CDN service it relies on is being retired.

<strong>Couldn't this be changed to just use the cloud instead?</strong>

Yes, but it wouldn't be significantly faster than self-hosting. In many cases it would be noticeably slower. Without actually being a CDN, you're just remote-hosting media and that isn't actually what most people want to do, it turns out.

<strong>But couldn't I just set up a CDN on my own?</strong>

You mean set up a new account with any “origin-pull compatible” CDN provider like Fastly, StackPath, or Cloudflare.

Yes. But the ability of this plugin to properly, dynamically handle your URLs is limited and in all tests was incredibly error prone. With that in mind, the call was made to pull the plug.

<strong>Can you recommend a replacement?</strong>

Not at this time, no. If you just need to offload media, I recommend Photon by Jetpack. If you need to to host large files like podcasts or videos, there really isn't a great solution, which speaks to why this plugin had a serious failure to launch.

<strong>Is this plugin a failure?</strong>

Oh yeah, I totally call it a failure. It was never great and was barely passable at best.

<strong>What if I need to host large files on the cloud?</strong>

I have some ideas but for now, manually upload them to your cloud locations and link directly. It's perfect for MP3s and movies.

== Changelog ==

= 1.0.0 =
* October 2017 by Ipstenu
* Retirement

== Upgrade Notice ==
As of version 1.0.0 this plugin is no longer supported or maintained. Once you've upgraded, please uninstall.