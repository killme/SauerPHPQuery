<?php
/**
 * A sauerbraten server.
 *
 * @author killme
 */
 
class AsSauerServer extends AsBaseServer
{
	const defaultPort = 10001;
	public static $GAMEMODES = array(
		0 => '',
		12 => 'ictf',
	);
	
	public static $MASTERMODES = array(
		255 => 'auth',
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