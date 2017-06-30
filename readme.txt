=== DreamSpeed CDN ===
Contributors: Ipstenu
Tags: cloud, dreamhost, dreamspeed, cdn
Requires at least: 4.0
Tested up to: 4.8
Stable tag: 0.7.4
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Connect your WordPress install to your DreamHost DreamSpeed CDN for supercharged media deployment.

== Description ==

<em>Note: If you have issues with the plugin, please post in the support forums here. Don't open a support ticket at DreamHost or use LiveChat unless it's for setting up DreamSpeed in your Panel.</em>

DreamHost has its own Cloud - <a href="https://dreamhost.com/cloud/dreamobjects/">DreamObjects&#153;</a> - an inexpensive, scalable object storage service that was developed from the ground up to provide a reliable, flexible cloud storage solution for entrepreneurs and developers. It provides a perfect, scalable storage solution for your WordPress media.

This plugin will automatically copy images, videos, documents, and any other media added through WordPress' media uploader to DreamSpeed. It then automatically replaces the URL to each media file with their respective DreamObjects URL or, if you have configured a CDN Alias, the respective custom URL. Image thumbnails are also copied to DreamSpeed and delivered similarly.

Uploading files directly to DreamSpeed is not supported by this plugin. They are uploaded to your server first, via the WordPress media uploader, then copied to DreamSpeed.

Development happens on <a href="https://github.com/Ipstenu/dreamspeed/">Github</a>. Issues and Pull Requests welcome.

= Known Conflicts =

* Broken Link Checker - This plugin will cause the "Migrate Existing Files" to fail.

= Credits =
<em>This plugin is a spork (fork and combination) of <a href="https://wordpress.org/plugins/amazon-s3-and-cloudfront/">Amazon S3 and Cloudfront</a> and <a href="https://github.com/deliciousbrains/wp-amazon-web-services">Amazon Web Services</a>, both by the awesome Brad Touesnard.</em>

== Installation ==

1. Sign up for <a href="http://dreamhost.com/cloud/dreamspeed/">DreamSpeed</a>
1. Install and Activate the plugin
1. Fill in your Key and Secret Key
1. Configure your settings
1. Watch your site load faster

== Frequently asked questions ==

<strong>What's this 'September 5th' warning I keep seeing?</strong>

As part of ongoing service improvements, DreamHost made a subtle, but critical, change to how we access DreamObjects. Specifically, they changed the DreamObjects hostname to prepare for upcoming enhancements.

Old hostname: `objects.dreamhost.com`
New hostname: `objects-us-west-1.dream.io`

You should have received an email about this, but just in case you didn't, or if you we're sure what this meant to you, let me explain. If you were ever using the URL `objects.dreamhost.com` in your site, you need to change it. This plugin will do it's utmost best to fix any URLs on your site, however it will *only* fix the ones for images it can detect are on DreamSpeed. I am aware that some people with a phenomenal number of images manually copied your uploads folder to DreamObjects and ran a command like this:

`wp search-replace 'example.com/wp-content/uploads' 'objects.dreamhost.com/bucketname/wp-content/uploads'`

Or perhaps you used the InterconnectDB script, or maybe even the Velvet Blues URL replacement plugin.

You have do do it again. And you have to do it before September 5th, 2016 or your images will break.

If you're on DreamHost, you can run the following command:

`wp search-replace objects.dreamhost.com objects-us-west-1.dream.io`

That will upgrade everything for you. If you're not on DreamHost, you'll need to use the search/replace tool of your choice.

I'm sorry about the change, but on the good side, we shouldn't ever need to do this again.

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

<strong>There are a lot of options, which ones do I want?</strong>

I would personally suggest checking the following:

* Copy files to DreamSpeed as they are uploaded to the Media Library
* Point file URLs to DreamSpeed/DNS Alias for files that have been copied to S3
* Serve files from dream.io

This will be the fastest.

<strong>Do I have to manually push images?</strong>

Nope! New image uploads are copied first to your normal location, then sync'd up.

<strong>Can I force older images to be pushed?</strong>

Yes. Go to the CDN page and at the bottom is a section "Migrate Existing Files" - If there's a checkbox and a button, you have files to upload, so check the box and press the button.

The uploader runs in chunks per hour, since it has to upload <em>all</em> image sizes, as well as edit your posts. If you have over 20k images, it may NOT work right, however.

<strong>How long will it take to upload everything?</strong>

That depends on how many files you have. It averages uploading 20 images per-hour, though that can change based on how large the images are and how many resizes you use.

<strong>How can I check if an image is uploaded?</strong>

Go to the Media Library and change to the list view. There's a column called 'CDN' and that will have a checkmark if it's uploaded.

<strong>Do I have to edit my posts to use the new URLs?</strong>

Generally no. The Migrate Existing Files features should edit the posts for you.

<strong>It <em>edits</em> my posts? Why not use a filter?</strong?>

It does edit. It saves as a post revision, so you can roll back. But there's no filter because you may not have all your images uploaded to the cloud yet.

= Advanced Stuff =

<strong>Can I use SSL?</strong>

Yes. If you set your WordPress home/site URLs to https, then the plugin will auto-detect that you're on https and attempt to serve up the files securely. If for any reason that doesn't work, there's an option to activate force SSL on the settings page.

Keep in mind, you cannot use a custom CDN (like cdn.yourdomain.com) with HTTPs at this time, due to issues with certificates outside the control of this plugin. You _can_ use the accelerated version of DreamSpeed (with `objects.cdn.dream.io` as the domain) though.

<strong>If I wanted to push my existing images up manually, how do I do that?</strong>

First copy it all up via a desktop tool like Cyberduck or Transmit. Once all the images are in the right place, do a search/replace on your content:

# Find `example.com/wp-content/uploads/`
# Replace with `NEWPATH/wp-content/uploads/`

The actual path for NEWPATH should be one of the following:

* `bucketname.objects-us-west-1.dream.io`
* `objects-us-west-1.dream.io/bucketname`
* `yourCDN.yourdomain.com`

<strong>I'm tired of this plugin. How do I go back?</strong>

I'm sad to see you go, but I understand. Right now you'll need to do the following:

1. Deactivate the plugin
2. search/replace your content for your DreamSpeed URLs and change it back to local

For example, if your DreamSpeed URL was `example.objects.cdn.dream.io` and your site is `example.com` then you could use WP-CLI to do this:

`wp search-replace example.objects.cdn.dream.io example.com --dry-run`

If that looked 'about right' then run it again without the dry-run flag. I recommend making a DB backup first (`wp db export	`). Keep in mind, if you changed up where it saves images and it wasn't in `wp-content`, you may need to be more precise with your changes.

= Errors, Bugs, and Weird Stuff =

<strong>Why, when my URLs changed to the CDN, are they all broken images?</strong>

Make sure your CDN URL is working. If you have Cloudflare or something proxy-ish in front of your domain, you may need to edit DNS directly and point your CDN alias to DreamSpeed.

<strong>I have a post and the links still are local, even though the images are on CDN. What gives?</strong>

This is a very rare case, and should no longer happen except in wild conditions where for some reason your OLD image URL doesn't match what WP thought it was. Sadly, you'll have to search/replace them after the fact for post content. If this happens because of how a plugin is changing the visible domain URL (like WordPress MU Domain Mapping), please let me know which plugin and I'll put in a check to accommodate it as best I can.

Of note: Currently the official WordPress importers aren't standardized, so there's not 100% safe way to check.

<strong>Why aren't my images found?</strong>

Check if they're failing on the CDN alias, but they do work at the objects-us-west-1.dream.io URL. If so, you somehow goofed your permissions. You have to go into the DreamObjects editor and set permissions from PRIVATE to PUBLIC. This happens usually because the bucket was private when you made it.

<strong>I viewed source and the srcset images are all local. Why is this happening?</strong>

There are weird issues with `srcset`. Basically it's impossible to be 100% sure that all the media is uploaded to the CDN, so auto-generating the `srcset` URLs is very tricky. This is being tracked in [Github Issue #20](https://github.com/Ipstenu/dreamspeed/issues/20) but I don't have an ETA yet. All current attempts have resulted in large gallery pages slowing to the point of un-usability.

== Screenshots ==

1. DreamObjects Keys on DreamPress Panel
2. DreamObjects Buckets on DreamPress Panel
3. DreamObjects Bucket Settings on DreamPress Panel
4. DreamSpeed CDN Key Settings
5. DreamSpeed CDN Configuration Settings
6. Media Library with CDN checkmarks
7. Migrate Existing Files section (on DreamSpeed CDN Configuration page)

== Changelog ==

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
Version 0.7.0 and 0.7.1 upgrades your existing posts to reflect the new URL for hostnames and upcoming regions. If you ever manually edited your image locations to objects.dreamhost.com you MUST change that to `objects-us-west-1.dream.io` before September 5th, 2016, or your images will break.