<?php

/***
 * Plugin Name: Comment Count Admin (by URL)
 * Plugin URI:  http://wordpress.org/plugins/comment-count-admin/
 * Text Domain: comment-count-admin
 * Domain Path: /languages
 * Description: Displays a count of each comment authors total number of comments next to their name on the admin pages.
 * Version:     1.5
 * License:     GPLv3
 * Author:      Jan Teriete
 * Author URI:  http://cms.interiete.net/
 * Last Change: 07/18/2014
 *
 * ------------------------------------------------------------------------------------
 * ACKNOWLEDGEMENT
 * ------------------------------------------------------------------------------------
 * This plugin was originally developed by Tanja Preuße
 * http://www.officetrend.de/
 * She thanks Olli for his ideas and active support
 * http://blog.splash.de/
 * ------------------------------------------------------------------------------------
 */

function comment_counter_admin() {
	global $wpdb, $comment;

	$comment_author = $comment->comment_author;
	if ( '' == $comment->comment_type ) {
		$count = __( 'N/A', 'comment-count-admin' );
		if ( ! empty( $comment->user_id ) ) {
			$query = $wpdb->prepare(
				"SELECT COUNT(*) as comments FROM $wpdb->comments WHERE user_id=%d",
				$comment->user_id
			);
			$count = $wpdb->get_var( $query );
		} elseif ( ! empty( $comment->comment_author_url ) ) {
			$query = $wpdb->prepare(
				"SELECT COUNT(*) as comments FROM $wpdb->comments WHERE comment_author_url=%s",
				$comment->comment_author_url
			);
			$count = $wpdb->get_var( $query );
		}
		$comment_author .= '&nbsp;(' . $count . ')';
	}

	return $comment_author;
}

// Only hook for backend
if ( is_admin() ) {
	load_plugin_textdomain(
		'comment-count-admin',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages/'
	);

	add_filter( 'get_comment_author', 'comment_counter_admin' );
}