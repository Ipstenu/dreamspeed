<?php
/*
    This file is part of DreamSpeed CDN, a plugin for WordPress.

    DreamSpeed CDN is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    DreamSpeed CDN is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with WordPress.  If not, see <http://www.gnu.org/licenses/>.

*/

if (!defined('ABSPATH')) {
    die();
}

$screen = get_current_screen();

// For the DreamSpeed Page
if ($screen->id == 'media_page_dreamspeed-menu') {

    // Introduction
    $screen->add_help_tab( array(
		'id'      => 'dreamspeed-menu-base',
		'title'   => __('Overview', 'dreamspeed'),
		'content' => 
		'<h3>' . __('Welcome to DreamSpeed', 'dreamspeed') .'</h3>' .
		'<p>' . __( 'DreamSpeed&#153; NEEDS A COOL INTRO.', 'dreamspeed' ) . '</p>' .
		'<p>' . __( 'This plugin was built on and for DreamHost Servers. While it may work on other webhosts, provided of course you have DreamSpeed, it does so at your own risk.', 'dreamspeed' ) . '</p>' .
		'<p>' . __( 'If you haven\'t already signed up for DreamSpeed, you won\'t find this plugin of any use at all.', 'dreamspeed' ) . '</p>'
		));
    $screen->set_help_sidebar(
        '<h4>' . __('For more information:', 'dreamspeed') .'</h4>' .
        
        '<p><a href="http://dreamhost.com/cloud/dreamspeed/">' . __('DreamSpeed Homepage', 'dreamspeed' ) . '</a></p>' .
        '<p><a href="http://wordpress.org/support/plugin/dreamspeed">' . __('Get Help', 'dreamspeed' ) . '</a></p>'
        );

    // Setup
    $screen->add_help_tab( array(
		'id'      => 'dreamspeed-menu-signup',
		'title'   => __('Setup', 'dreamspeed'),
		'content' =>
		'<h3>' . __('Setup', 'dreamspeed') .'</h3>' .
		'<ol>' .
		  '<li>' . __( 'Sign up for <a href="http://dreamhost.com/cloud/dreamspeed/">DreamSpeed</a> and <a href="http://wiki.dreamhost.com/DreamSpeed_CDN_Overview">configure it</a>', dreamspeed ) . '</li>' .
		  '<li>' . __( 'Install and Activate the plugin', dreamspeed ) . '</li>' .
		  '<li>' . __( 'Fill in your Key and Secret Key', dreamspeed ) . '</li>' .
		  '<li>' . __( 'Enter your desired domain name', dreamspeed ) . '</li>' .
        '</ol>'
	  ));
    
    // Terminology
    $screen->add_help_tab( array(
		'id'      => 'dreamspeed-menu-terms',
		'title'   => __('Terminology', 'dreamspeed'),
		'content' =>
		'<h3>' . __('Terminology', 'dreamspeed') .'</h3>' .
		'<p><strong>' . __( 'Object: ', 'dreamspeed') .'</strong>' . __( 'Files uploaded to DreamSpeed.', 'dreamspeed') . '</p>' .
		'<p><strong>' . __( 'Bucket: ', 'dreamspeed') .'</strong>' . __( 'A mechanism for grouping objects, similar to a folder. One key distinction is that bucket names must be unique, like a domain name, since they are used to create public URLs to stored objects.', 'dreamspeed') . '</p>' .
		'<p><strong>' . __( 'Access Key: ', 'dreamspeed') .'</strong>' . __( 'A similar concept to a username for DreamSpeed users. One or more can be created for each user if desired. Each access key will allow access to all of the buckets and their contents for a user. You will need this key to connect.', 'dreamspeed') . '</p>' .
		'<p><strong>' . __( 'Secret Key: ', 'dreamspeed') .'</strong>' . __( 'A similar concept to a password for DreamSpeed users. A secret key is automatically generated for each access key and cannot be changed. Never give anyone your secret key.', 'dreamspeed') . '</p>' .
		'<p><strong>' . __( 'Key Pair: ', 'dreamspeed') .'</strong>' . __( 'A singular term used to describe both an access key and its secret key.', 'dreamspeed') . '</p>'
	  ));
	   }
else
    return;
