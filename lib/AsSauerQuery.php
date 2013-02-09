<?php

/**
 * Class for querying the gameservers that are provided.
 *
 * @author killme
 */
class AsSauerQuery
{
	private static $_cache;
	
	public function query($ip, $port = AsSauerServer::defaultPort)
	{
		if(($server = $this->findCache($ip, $port)) !== false)
			return $server;
		else
		{
			try
			{
				$server = new AsSauerServer($ip, $port);
				$con = $this->openConnection($server);
				$server->setServerInfo($con->getServerInfo());
				$server->setPlayerList($con->getPlayerInfo());
				
				$this->setCache($ip, $port, $server);
			}
			catch(AsIOException $e)
			{
				//ignore timeouts
			}			
			
			return $server;
		}
	}
	
	protected function findCache($ip, $port)
	{
		return isset(self::$_cache[$ip], self::$_cache[$ip][$port])
				? self::$_cache[$ip][$port]
				: false;
	}
	
	protected function setCache($ip, $port, &$server)
	{
		self::$_cache[$ip][$port] = $server;
	}
	
	protected function openConnection(AsSauerServer &$server)
	{
		$c = new AsSauerConnection($server);
		$c->open();
		return $c;
	}
}