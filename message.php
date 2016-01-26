<?php

/*!
 * Copyright © 2016 Rainer Rillke <rillke@wikipedia.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 */
if ( isset( $_POST['email'] ) && strlen( $_POST['email'] ) < 150 ) {
	require_once 'EventConfig.php';
	require_once 'Mail.php';
	$ec = EventConfig::getInstance();
	$ec->augmentIncludePath();

	$recipients = $ec->getSMTPRecipient();

	$headers['From'] = $ec->getSMTPSender();
	$headers['To'] = $ec->getSMTPRecipient();
	$headers['Subject'] = $_POST['subject'];

	$body = 'supposed sender: ' . $_POST['name'] . ' <' . $_POST['email'] . ">\n" .
		 $_POST['message'];

	$ec = EventConfig::getInstance();
	$smtpinfo = $ec->getSMTPInfo();

	// Create the mail object using the Mail::factory method
	$mail_object = & Mail::factory( 'smtp', $smtpinfo );

	$mail_object->send( $recipients, $headers, $body );
	$destination = explode( '/', $_SERVER['REQUEST_URI'] );
	array_pop( $destination );
	$destination = implode( '/', $destination );
	if ( ! $destination ) {
		$destination = '/';
	}

	setcookie( 'halleilujah-mail-success', '1', time() + 5, '/' );
	header( 'Location: ' . $destination, true, 303 );
	echo 'Thanks for your message.';
} else {
	setcookie( 'halleilujah-mail-success', '0', time() + 5, '/' );
	header( 'Location: ' . $destination, true, 303 );
	echo 'Bummer! Something went wrong sending the message. Please try e-Mailing us directly.';
}
