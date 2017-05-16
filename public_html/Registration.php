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
require_once 'FormDefinition.php';
require_once 'EventPDO.php';

class Registration {

	private $registrationForm;

	public function __construct() {
		$this->registrationForm = FormDefinition::get();
	}

	public function dispatchRequest() {
		try {
			$pdo = new EventPDO();
		} catch ( PDOException $e ) {
			echo 'Bummer! Our database is currently unavailable. Please try again later.';
			return;
		}
		if ( isset( $_POST['firstname'] ) && isset( $_POST['dataprotection'] ) ) {
			$this->processData( $pdo );
			return;
		}
		ob_end_flush();
		$this->displayForm( $pdo );
	}

	private function displayForm( $pdo ) {
		$ec = EventConfig::getInstance();
		$totalPlaces = intval( $ec->getMaxPlaces() );
		$form = '<form method="POST"><p>';

		$sth = $pdo->prepare( 'SELECT count(*) AS taken FROM registrations;' );
		$sth->execute();

		$result = $sth->fetchAll();
		$placesTaken = $result[0]['taken'];
		$placesLeft = $totalPlaces - $placesTaken;
		if ( $placesLeft > 1 ) {
			$form .= "There are $placesLeft&nbsp;places left.";
		} else if ( $placesLeft > 0 ) {
			$form .= "There is only one free place left. Hurry!";
		} else {
			$form .= "It looks like all free places were already taken. But we haven't reviewed all registrations yet, so your registration will be queued and after we've completed review, we'll let you know in case you can participate.";
		}
		$form .= '</p>';

		foreach ( $this->registrationForm as $key => $question ) {
			$id = 'ctrl_' . $key;
			$placeholder = isset( $question['placeholder'] ) ? $question['placeholder'] : $question['label'];
			$type = ( isset( $question['type'] ) ? $question['type'] : 'text' );
			$form .= '<p>';
			$required = ( isset( $question['required'] ) &&
				 $question['required'] === false ) ? '' : ' required';
			$name = ' name="' . $key . '"';

			if ( isset( $question['radio'] ) ) {
				$form .= $this->getLabel( $id, $question );
				$form .= '<br />';
				$idx = 0;
				foreach ( $question['radio'] as $value => $radio ) {
					$idx ++;
					$disabled = isset( $radio['disabled'] ) ? ' disabled="disabled"' : '';
					$form .= '<span style="display: inline-block;"><input type="radio" id="' .
						 $idx . $id . '" name="' . $key . '" value="' . $value .
						 '" ' . $required . $disabled . ' />';
					$form .= '<label for="' . $idx . $id . '">' . $radio['label'] .
						 '</label></span> ';
				}
				$form .= '<br />';
			} elseif ( $type === 'textarea' ) {
				$form .= $this->getLabel( $id, $question );
				$form .= '<textarea id="' . $id . '" placeholder="' .
					 $placeholder . '"' . $name . '></textarea>';
			} elseif ( $type === 'checkbox' ) {
				$form .= '<input type="' . $type . '" id="' . $id .
					 '" placeholder="' . $placeholder . '" ' . $name . $required .
					 ' />';
				$form .= $this->getLabel( $id, $question );
			} else {
				$form .= $this->getLabel( $id, $question );
				$tags = isset( $question['tags'] ) ? ' class="tagsinput" data-tags="' .
					 implode( ',', $question['tags'] ) . '"' : '';
				$list = '';
				if ( isset( $question['autosuggest'] ) ) {
					$list = 'datalist_' . $id;
					$form .= '<datalist id="' . $list . '">';
					foreach ( $question['autosuggest'] as $value ) {
						$form .= '<option value="' . $value . '">';
					}
					$form .= '</datalist>';
					$list = ' list="' . $list . '"';
				}
				$form .= '<input type="' . $type . '" id="' . $id .
					 '" placeholder="' . $placeholder . '"' . $name . $tags .
					 $list . $required . ' />';
			}
			$form .= '</p>';
		}
		$form .= '<button class="button special fit">register now with costs</button>';
		$form .= 'We will send e-Mail to you as soon as we processed and accepted your registration.';
		$form .= '</form>';
		echo $form;
	}

	private function getLabel( $id, $question ) {
		return '<label for="' . $id . '">' . $question['label'] . '</label>';
	}

	private function processData( $dbo ) {
		// In theory we would validate data but since we are keeping them private
		// and we have plenty of resources, I don't feel this is needed.
		$cols = 'firstname,lastname,gender,lc,homecountry,email,mobile,food,payment,comment,dataprotection';

		$_POST['dataprotection'] = isset($_POST['dataprotection']) ? 1 : 0;

		$r = $dbo->query('SET NAMES utf8');
		if($r)
			$r->closeCursor();
		
		$sql = 'INSERT INTO registrations (reg_time,' . $cols .
			 ') VALUES (:reg_time,:' . implode( ',:', explode( ',', $cols ) ) . ')';
		$q = $dbo->prepare( $sql );
		$params = array();
		$subject = 'Registration for HalLeiLujah';
		$body = '';
		$params[':reg_time'] = date('Y-m-d H:i:s');
		foreach ( explode( ',', $cols ) as $col ) {
			$params[':' . $col] = $_POST[$col];
			$body .= $col . ': ' . $_POST[$col] . "\n";
		}

		$dboResult = $q->execute($params);
		
		try {
			$emailResult = ! ! $this->sendEMail( $subject, $body );
		} catch ( Exception $ex ) {
			$emailResult = false;
		}

		$destination = explode( '/', $_SERVER['REQUEST_URI'] );
		array_pop( $destination );
		$destination = implode( '/', $destination );
		if ( ! $destination ) {
			$destination = '/';
		}

		$ob = ob_get_clean();
		setcookie(
			'halleilujah-registration-success',
			"d/$dboResult/e/$emailResult",
			time() + 5,
			'/' );
		header( 'Location: ' . $destination, true, 303 );
		echo $ob;
		$message = '';
		$message .= $dboResult ? 'Your registation has been recorded in our database.' : 'There was an error storing your registration in our database. Please try again later.';
		$message .= $emailResult ? ' We were notified through e-Mail about your registration.' : ' But Bummer! There was an error sending your registation through e-Mail. Maybe try again?';
		echo $message;
	}

	private function sendEMail( $subject, $body ) {
		$ec = EventConfig::getInstance();

		$to = filter_var($ec->getSMTPRecipient(), FILTER_VALIDATE_EMAIL);
		$from = filter_var($ec->getSMTPSender(), FILTER_VALIDATE_EMAIL);

		if($to === false || $from === false)
			return false;
		else
			return mail($to, $subject, $body, "From: $from\r\nContent-type: text/plain; charset=UTF-8");
	}

	private function OLD_sendEMail( $subject, $body ) {
		$ec = EventConfig::getInstance();
		$ec->augmentIncludePath();
		include_once 'Mail.php';

		$recipients = $ec->getSMTPRecipient();

		$headers['From'] = $ec->getSMTPSender();
		$headers['To'] = $ec->getSMTPRecipient();
		$headers['Subject'] = $subject;
		$headers['Content-Type'] = 'text/plain; charset=UTF-8';

		$smtpinfo = $ec->getSMTPInfo();

		// Create the mail object using the Mail::factory method
		if ( class_exists( 'Mail' ) ) {
			$m = new Mail();
			$mail_object = & $m->factory( 'smtp', $smtpinfo );

			return ( true === $mail_object->send( $recipients, $headers, $body ) );
		} else {
			return false;
		}
	}
}
