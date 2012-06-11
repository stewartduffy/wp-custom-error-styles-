<?php
/*
Plugin Name: Custom Error Styles
Description: Lets the theme developer customize the CSS for permission & comment error pages.
Version: 0.1
License: GPL
Plugin URI: URI here
Author: Stewart Duffy,
Credits: http://www.velvetblues.com/web-development-blog/custom-wordpress-comment-error-page/
Author URI: http://stewartduffy.com/

==========================================================================

Copyright 2012-2012  Stewart Duffy

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

add_filter('wp_die_handler', 'get_my_custom_die_handler');

function get_my_custom_die_handler() {
    return 'my_custom_die_handler';
}

function my_custom_die_handler( $message, $title = '', $args = array() ) {
	$defaults = array( 'response' => 500 );
	$r = wp_parse_args($args, $defaults);

	$have_gettext = function_exists('__');

	if ( function_exists( 'is_wp_error' ) && is_wp_error( $message ) ) {
		if ( empty( $title ) ) {
			$error_data = $message->get_error_data();
			if ( is_array( $error_data ) && isset( $error_data['title'] ) )
				$title = $error_data['title'];
		}
		$errors = $message->get_error_messages();
		switch ( count( $errors ) ) :
		case 0 :
			$message = '';
			break;
		case 1 :
			$message = "<p>{$errors[0]}</p>";
			break;
		default :
			$message = "<ul>\n\t\t<li>" . join( "</li>\n\t\t<li>", $errors ) . "</li>\n\t</ul>";
			break;
		endswitch;
	} elseif ( is_string( $message ) ) {
		$message = "<p>$message</p>";
	}

	if ( isset( $r['back_link'] ) && $r['back_link'] ) {
		$back_text = $have_gettext? __('&laquo; Back') : '&laquo; Back';
		$message .= "\n<p><a href='javascript:history.back()'>$back_text</a></p>";
	}

	if ( !function_exists( 'did_action' ) || !did_action( 'admin_head' ) ) :
		if ( !headers_sent() ) {
			status_header( $r['response'] );
			nocache_headers();
			header( 'Content-Type: text/html; charset=utf-8' );
		}

		if ( empty($title) )
			$title = $have_gettext ? __('WordPress &rsaquo; Error') : 'WordPress &rsaquo; Error';

		$text_direction = 'ltr';
		if ( isset($r['text_direction']) && 'rtl' == $r['text_direction'] )
			$text_direction = 'rtl';
		elseif ( function_exists( 'is_rtl' ) && is_rtl() )
			$text_direction = 'rtl';
?>
<!DOCTYPE html>
<!-- Ticket #11289, IE bug fix: always pad the error page with enough characters such that it is greater than 512 bytes, even after gzip compression abcdefghijklmnopqrstuvwxyz1234567890aabbccddeeffgghhiijjkkllmmnnooppqqrrssttuuvvwwxxyyzz11223344556677889900abacbcbdcdcededfefegfgfhghgihihjijikjkjlklkmlmlnmnmononpopoqpqprqrqsrsrtstsubcbcdcdedefefgfabcadefbghicjkldmnoepqrfstugvwxhyz1i234j567k890laabmbccnddeoeffpgghqhiirjjksklltmmnunoovppqwqrrxsstytuuzvvw0wxx1yyz2z113223434455666777889890091abc2def3ghi4jkl5mno6pqr7stu8vwx9yz11aab2bcc3dd4ee5ff6gg7hh8ii9j0jk1kl2lmm3nnoo4p5pq6qrr7ss8tt9uuvv0wwx1x2yyzz13aba4cbcb5dcdc6dedfef8egf9gfh0ghg1ihi2hji3jik4jkj5lkl6kml7mln8mnm9ono
-->
<html xmlns="http://www.w3.org/1999/xhtml" <?php if ( function_exists( 'language_attributes' ) && function_exists( 'is_rtl' ) ) language_attributes(); else echo "dir='$text_direction'"; ?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $title ?></title>
	<style type="text/css">
		html {
			background: #f9f9f9;
		}
		body {
			background: #fff;
			color: #333;
			font-family: sans-serif;
			margin: 2em auto;
			padding: 1em 2em;
			-webkit-border-radius: 3px;
			border-radius: 3px;
			border: 1px solid #dfdfdf;
			max-width: 700px;
		}
		#error-page {
			margin-top: 50px;
		}
		#error-page p {
                        text-align: center;
			font-size: 14px;
			line-height: 1.5;
			margin: 25px 0 20px;
		}
		#error-page code {
			font-family: Consolas, Monaco, monospace;
		}
		ul li {
			margin-bottom: 10px;
			font-size: 14px ;
		}
		a {
			color: #21759B;
			text-decoration: none;
		}
		a:hover {
			color: #D54E21;
		}

		.button {
			font-family: sans-serif;
			text-decoration: none;
			font-size: 14px !important;
			line-height: 16px;
			padding: 6px 12px;
			cursor: pointer;
			border: 1px solid #bbb;
			color: #464646;
			-webkit-border-radius: 15px;
			border-radius: 15px;
			-moz-box-sizing: content-box;
			-webkit-box-sizing: content-box;
			box-sizing: content-box;
			background-color: #f5f5f5;
			background-image: -ms-linear-gradient(top, #ffffff, #f2f2f2);
			background-image: -moz-linear-gradient(top, #ffffff, #f2f2f2);
			background-image: -o-linear-gradient(top, #ffffff, #f2f2f2);
			background-image: -webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#f2f2f2));
			background-image: -webkit-linear-gradient(top, #ffffff, #f2f2f2);
			background-image: linear-gradient(top, #ffffff, #f2f2f2);
		}

		.button:hover {
			color: #000;
			border-color: #666;
		}

		.button:active {
			background-image: -ms-linear-gradient(top, #f2f2f2, #ffffff);
			background-image: -moz-linear-gradient(top, #f2f2f2, #ffffff);
			background-image: -o-linear-gradient(top, #f2f2f2, #ffffff);
			background-image: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#ffffff));
			background-image: -webkit-linear-gradient(top, #f2f2f2, #ffffff);
			background-image: linear-gradient(top, #f2f2f2, #ffffff);
		}

		<?php if ( 'rtl' == $text_direction ) : ?>
		body { font-family: Tahoma, Arial; }
		<?php endif; ?>
	</style>
</head>
<body id="error-page">
<?php endif; // !function_exists( 'did_action' ) || !did_action( 'admin_head' ) ?>
	<?php echo $message; ?>
</body>
</html>
<?php
	die();
}