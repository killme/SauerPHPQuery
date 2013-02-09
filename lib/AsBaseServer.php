<?php
/**
 * Base Server class for external servers.
 */
abstract class AsBaseServer
{
	public $ip;
	public $port;
	
	public $name;
	
	protected $players;
	
	public abstract function __construct($ip, $port);
	public abstract function getPlayerList();
	public abstract function setPlayerList($players);
}