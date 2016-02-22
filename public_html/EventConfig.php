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
class EventConfig {

	private static $instance;

	private $config;

	public function __construct() {
		$this->config = parse_ini_file( '.event.cfg.ini', true );
		
		// server config may override ini file config
		// set via setenv in apache config file
		// (SMTP account data is not used, since php mail() is used)
		
		$config_entries = array(
			'CFG_PHP_INCLUDES'		=> array('php', 'includes'),
			'CFG_DB_CONNECTION'		=> array('db', 'connection'),
			'CFG_DB_USER'			=> array('db', 'user'),
			'CFG_DB_PASS'			=> array('db', 'pass'),
			'CFG_SMTP_SENDER'		=> array('smtp', 'sender'),
			'CFG_SMTP_RECIPIENT'	=> array('smtp', 'recipient'),
			'CFG_ACCESS_USER'		=> array('access', 'user'),
			'CFG_ACCESS_PASS'		=> array('access', 'pass'),
			'CFG_ACCESS_SALT'		=> array('access', 'salt'),
			'CFG_EVENT_MAXPLACES'	=> array('event', 'maxplaces'),
			);
			
		foreach($config_entries as $cfg_name => $cfg_config)
		{
			if(isset($_SERVER[$cfg_name]))
				$this->config[$cfg_config[0]][$cfg_config[1]] = $_SERVER[$cfg_name];
		}
	}

	public function getDBConnectionParams() {
		return $this->config['db']['connection'];
	}

	public function getDBUser() {
		return $this->config['db']['user'];
	}

	public function getDBPass() {
		return $this->config['db']['pass'];
	}

	public function getSMTPHost() {
		return $this->config['smtp']['host'];
	}

	public function getSMTPPort() {
		return $this->config['smtp']['port'];
	}

	public function getSMTPUser() {
		return $this->config['smtp']['user'];
	}

	public function getSMTPPass() {
		return $this->config['smtp']['pass'];
	}

	public function getSMTPInfo() {
		$smtpinfo = array();
		$smtpinfo['host'] = $this->getSMTPHost();
		$smtpinfo['port'] = $this->getSMTPPort();
		$smtpinfo['auth'] = true;
		$smtpinfo['username'] = $this->getSMTPUser();
		$smtpinfo['password'] = $this->getSMTPPass();
		return $smtpinfo;
	}

	public function getSMTPSender() {
		return $this->config['smtp']['sender'];
	}

	public function getSMTPRecipient() {
		return $this->config['smtp']['recipient'];
	}

	public function augmentIncludePath() {
		ini_set(
			'include_path',
			$this->config['php']['includes'] . ':' . ini_get( 'include_path' ) );
	}

	public function getAccessUser() {
		return $this->config['access']['user'];
	}

	public function getAccessPass() {
		return $this->config['access']['pass'];
	}

	public function getAccessSalt() {
		return $this->config['access']['salt'];
	}

	public function getMaxPlaces() {
		return $this->config['event']['maxplaces'];
	}

	/**
	 * @return EventConfig
	 */
	public static function getInstance() {
		if ( ! static::$instance ) {
			static::$instance = new EventConfig();
		}
		return static::$instance;
	}
}
