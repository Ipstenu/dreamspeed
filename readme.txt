=== DreamSpeed CDN ===
Contributors: Ipstenu
Tags: cloud, dreamhost, dreamspeed, backup
Requires at least: 3.8
Tested up to: 3.9
Stable tag: 0.1
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Connect your WordPress install to your DreamHost DreamSpeed CDN for supercharged media deployment.

== Description ==

This plugin automatically copies images, videos, documents, and any other media added through WordPress' media uploader to DreamSpeed. It then automatically replaces the URL to each media file with their respective DreamObjects URL or, if you have configured a CDN Alias, the respective custom URL. Image thumbnails are also copied to DreamSpeed and delivered similarly.

Uploading files directly to DreamSpeed is not currently supported by this plugin. They are uploaded to your server first, then copied to DreamSpeed. There is an option to automatically remove the files from your server once they are copied to DreamSpeed however.

If you're adding this plugin to a site that's been around for a while, your existing media files will <em>not</em> be copied or served from DreamSpeed. Only newly uploaded files will be copied and served from DreamSpeed.

Development happens on <a href="https://github.com/Ipstenu/dreamspeed/">Github</a>.

<em>This plugin is a fork and combination of <a href="https://wordpress.org/plugins/amazon-s3-and-cloudfront/">Amazon S3 and Cloudfront</a> and <a href="https://github.com/deliciousbrains/wp-amazon-web-services">Amazon Web Services</a>, both by the awesome Brad Touesnard.</em>

== Installation ==

1. Sign up for <a href="http://dreamhost.com/cloud/dreamspeed/">DreamSpeed</a>
1. Install and Activate the plugin
1. Fill in your Key and Secret Key

== Frequently asked questions ==

= General Questions =

<strong>What does it do?</strong>

DreamSpeed CDN connects your WordPress site to the DreamSpeed CDN, automatically pushing your media up to the CDN making it faster for your visitors.

<strong>Do you work for DreamHost?</strong>

Yes, but this isn't an official DreamHost plugin at this time. It just works.

<strong>Do I have to host my website on DreamHost?</strong>

No, but using it anywhere else is unsupported. You have to use Dream<em>Speed</em>, which belongs to Dream<em>Host</em>. This plugin was built on and specifically for DreamHost servers, so I can give you no assurance it'll work on other hosts

<strong>Can I use this on a Windows Server?</strong>

This is unsupported. You can try, and let me know how it goes. I built this for DreamHost, so it has only been tested on Linux boxes.

<strong>Can I use this on Multisite?</strong>

Not at this time.

= Using the Plugin =

<strong>Do I have to manually push images?</strong>

Nope! New image uploads are copied first to your normal location, then sync'd up.

<strong>Can I force older images to be pushed?</strong>

Not yet.

= Errors =



== Screenshots ==
1. DreamObjects Private Key
1. Your DreamObjects Public Key
1. The Settings Page

== Changelog ==

= 0.1 = 
* DATE by Ipstenu
* First fork