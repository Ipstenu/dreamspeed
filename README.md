DreamSpeed CDN
==========

* License: GPLv3
* License URI: https://www.gnu.org/licenses/gpl-3.0.html

Connect your WordPress install to your DreamHost DreamSpeed CDN for supercharged media deployment.

## Description

<em>Note: If you have issues with the plugin, please post in the WordPress support forums. Don't open a support ticket unless it's for setting up DreamSpeed in your Panel.</em>

This plugin automatically copies images, videos, documents, and any other media added through WordPress' media uploader to DreamSpeed. It then automatically replaces the URL to each media file with their respective DreamObjects URL or, if you have configured a CDN Alias, the respective custom URL. Image thumbnails are also copied to DreamSpeed and delivered similarly.

Uploading files directly to DreamSpeed is not currently supported by this plugin. They are uploaded to your server first, then copied to DreamSpeed.

If you're adding this plugin to a site that's been around for a while, your existing media files will <em>not</em> be copied or served from DreamSpeed. Only newly uploaded files will be copied and served from DreamSpeed at this time.

Development happens on <a href="https://github.com/Ipstenu/dreamspeed/">Github</a>. Issues and Pull Requests welcome.

<em>This plugin is a fork and combination of <a href="https://wordpress.org/plugins/amazon-s3-and-cloudfront/">Amazon S3 and Cloudfront</a> and <a href="https://github.com/deliciousbrains/wp-amazon-web-services">Amazon Web Services</a>, both by the awesome Brad Touesnard.</em>

### To Do

* Figure out why sometimes the existing media URLs are wrong and fix that
* Prettify and clean language
* Drink moar
* Install a unicorn