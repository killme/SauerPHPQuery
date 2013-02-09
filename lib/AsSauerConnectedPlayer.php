<?php

/**
 * Represents a player that is connected to a server
 */
class AsSauerConnectedPlayer
{
	public $cn;
	public $ping;
	public $name;
	public $team;
	public $frags;
	public $flags;
	public $deaths;
	public $teamkills;
	public $acc;
	public $health;
	public $amour;
	public $gun;
	public $priv;
	public $state;
	public $ip;
	
	public function __construct($info)
	{
		//lazy us
		foreach($info as $k => $v)
			$this->$k = $v;			
	}
}
