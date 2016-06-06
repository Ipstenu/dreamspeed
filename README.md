DreamSpeed CDN
==========

This is the development repository for the DreamSpeed CDN plugin.

* [WP Readme](readme.txt)
* [Changelog](changelog.txt)
* [WordPress Repoitory](https://wordpress.org/plugins/dreamspeed-cdn/)

## Build Procedure

1. Create new branch
2. Update composer: `composer update`
3. Update readme.txt
4. Update code
5. Test all the things
6. Review screenshots
7. Merge branch into master
8. Push Master
9. Copy Master to SVN/trunk



## Update

* Copy the code from ~/Development/wordpress/plugins-git/dreamspeed to ~/Development/wordpress/plugins-svn/trunk/dreamspeed-cdn

```
rsync -va --delete --exclude debug.txt --exclude vendor/ --exclude .git/ --exclude .DS_Store --exclude composer.* --exclude README.md ~/Development/wordpress/plugins-git/dreamspeed/ .
```

* Run `svn status` to see what’s missing or needs removing
* Run svn commit: `svn ci -m "Version X"`
* Run SVN CP

```
svn cp https://plugins.svn.wordpress.org/dreamspeed-cdn/trunk https://plugins.svn.wordpress.org/dreamspeed-cdn/tags/TAG/
```