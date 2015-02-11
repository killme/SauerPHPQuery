<?php
/**
 * A sauerbraten server.
 *
 * @author Fohlen
 */
 
class AsSauerServer extends AsBaseServer
{
	// See https://github.com/inexor-game/sauerbraten/blob/master/src/fpsgame/game.h for specifications

	const defaultPort = 28786;
	public static $GAMEMODES = array(
		// -1, -2 and -3 are singleplayer modes
		0 => 'ffa',
		1 => 'coop edit',
		2 => 'teamplay',
		3 => 'instagib',
		4 => 'insta team',
		5 => 'efficiency',
		6 => 'effic team',
		7 => 'tactics',
		8 => 'tac team',
		9 => 'capture',
		10 => 'regen capture',
		11 => 'ctf',
		12 => 'insta ctf',
		13 => 'protect',
		14 => 'insta protect',
		15 => 'hold',
		16 => 'insta hold',
		17 => 'effic ctf',
		18 => 'effic protect',
		19 => 'effic hold',
		20 => 'collect',
		21 => 'insta collect',
		22 => 'effic collect'
	);
	
	public static $MASTERMODES = array(
		// MM_START is not implemented because it is ALWAYS set to MM_AUTH 
		-1 => 'auth',
		0 => 'open',
		1 => 'veto',
		2 => 'locked',
		3 => 'private',
		4 => 'password'
	);
	

	public function getPlayerList()
	{
		return $this->players;
	}
	
	public function setPlayerList($players)
	{
		foreach($players as $player)
			$this->players[$player['cn']] = new AsSauerConnectedPlayer($player);
	}
	
	public function __construct($ip, $port)
	{
		$this->ip = $ip;
		$this->port = $port;
	}
	
	public function setServerInfo($info)
	{
		foreach($info as $k => $v)
		{
			$this->$k = $v;
		}
	}
}
