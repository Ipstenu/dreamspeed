=== DreamSpeed CDN ===
Contributors: Ipstenu
Tags: cloud, dreamhost, dreamspeed, backup
Requires at least: 3.8
Tested up to: 3.9
Stable tag: 0.1-beta
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Connect your WordPress install to your DreamHost DreamSpeed CDN for supercharged media deployment.

== Description ==

<em>Note: If you have issues with the plugin, please post in the support forums here. Don't open a support ticket unless it's for setting up DreamSpeed in your Panel.</em>

This plugin automatically copies images, videos, documents, and any other media added through WordPress' media uploader to DreamSpeed. It then automatically replaces the URL to each media file with their respective DreamObjects URL or, if you have configured a CDN Alias, the respective custom URL. Image thumbnails are also copied to DreamSpeed and delivered similarly.

Uploading files directly to DreamSpeed is not currently supported by this plugin. They are uploaded to your server first, via the WordPress media uploader, then copied to DreamSpeed. This allows you to roll back if needed!

Development happens on <a href="https://github.com/Ipstenu/dreamspeed/">Github</a>. Issues and Pull Requests welcome.

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

Yes, but this isn't an official DreamHost plugin at this time. I know that's a weird concept, but right now since I'm the only one writing and supporting it, it's not officially supported via DreamHost support. If you have problems, please post in the forums.

<strong>Do I have to host my website on DreamHost?</strong>

You have to use Dream<em>Speed</em>, which belongs to Dream<em>Host</em>. It should work on any modern host with PHP 5.3.3+ and cURL 7.16.2+ (with zLib and OpenSSL) and was tested on a few different Linux hosts, with both Apache and nginx. I have not tested on Windows Servers.

<strong>Can I use this on Multisite?</strong>

Yes, but it has to be configured per site.

= Using the Plugin =

<strong>Do I have to manually push images?</strong>

Nope! New image uploads are copied first to your normal location, then sync'd up.

<strong>Can I force older images to be pushed?</strong>

Yes. Go to the CDN page and at the bottom is a section "Migrate Exisiting Files" - If there's a checkbox and a button, you have files to upload, so check the box and press the button.

The uploader runs in chunks per hour, since it has to upload <em>all</em> image sizes, as well as edit your posts.

<strong>How can I check if an image is uploaded?</strong>

Go to the Media Library. There's a column called 'CDN' and that will have a checkmark if it's uploaded.

<strong>Do I have to edit my posts to use the new URLs?</strong>

Generally no. The Migrate Existing Files features will edit the posts for you.

<strong>It <em>edits</em> my posts? Why not use a filter?</strong?>

It does edit. It saves as a post revision, so you can roll back. But there's no filter because you may not have all your images uploaded to the cloud yet.

= Errors, Bugs, and Weird Stuff =

<strong>Why, when my URLs changed to the CDN, are they all broken images?</strong>

Make sure your CDN URL is working. If you have Cloudflare or something proxy-ish in front of your domain, you may need to edit DNS directly and point your CDN alias to DreamSpeed.

<strong>I have a post and the links still are local, even though the images are on CDN. What gives?</strong>

One of two possibilities:

1) The media wasn't <em>attached</em> to the post.
2) You uploaded the media at a time different than the post.

Let me explain that second one, it's weird, and yes, it's a bug. So if you upload media and write your post and post it on the same day, no problem. But if you upload media in January and then decide to attach it to a post in June (or even schedule your post for June), there's a small disconnect in how WP thinks the attachment should be uploaded. Basically it wants to 'fix' the attachment and put it in the folder for June, when the post was published.

Again. Yes, its a bug. The fallback right now is that it simply won't edit your existing posts that don't match URLs 100%.

<strong>Why aren't my images found?</strong>

Check if they're failing on the CDN alias, but they do work at the objects.dreamhost.com URL. If so, you somehow goofed your permissions. You have to go into the DreamObjects editor andf set permissions from PRIVATE to PUBLIC. This happens usually because the bucket was private when you made it.

== Screenshots ==

1. DreamObjects Keys
1. DreamObjects Buckets
2. DreamObjects Bucket Settings


== Changelog ==

= 0.1 = 
* DATE by Ipstenu
* First fork
