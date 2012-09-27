<?php

class AsSauerConnection
{
	private $_connection;
	
	public $ip;
	public $port;

	public function __construct(AsSauerServer &$server)
	{
		$this->ip = $server->ip;
		$this->port = $server->port;
	}
	
	public function open()
	{
		$this->_connection = stream_socket_client('udp://'.$this->ip.':'.$this->port, $errno, $errstr, 0);
		
		if(!$this->_connection)
			throw new AsIOException('Could not connect: '.$errstr.' ('.$errno.')');
	}
	
	public function getPlayerInfo()
	{
		$buff = new AsSauerBuffer;
		$buff->putInt(0);
		$buff->putInt(1);
		$buff->putInt(-1);
		
		$buff = $this->query('ccc', $buff);
		
		for ($i = 0; $i <= 7; $i++)
			if ($buff->getInt() === 0)
			{
				for ($i=1;$i<=5;$i++)
					$buff->getInt();
				break;
			}

		$players = array();
		
		for ($i = 7; $i < 100; $i++)
		{
			try
			{
				$players[$i-7] = array('tmp' => $buff->getInt());
			}
			catch(AsIOException $e)
			{
			
			}
		}
		
		foreach($players as $k => &$player)
		{
			try
			{
				for ($i = 1; $i <= 7; $i++)
					$buff->getInt();

				$player["cn"] = $buff->getInt();

				$player["ping"] = $buff->getInt();

				$player["name"] = $buff->getString();

				$player["team"] = $buff->getString();

				$frags = $buff->getInt();

				$player["frags"] = $frags > 150 ? $frags - 256 : $frags;

				$player["flags"] = $buff->getInt();

				$player["deaths"] = $buff->getInt();

				$player["teamkills"] = $buff->getInt();

				$player["acc"] = $buff->getInt();

				$player["health"] = $buff->getInt();

				$player["armour"] = $buff->getInt();

				$player["gun"] = $buff->getInt();

				$player["priv"] = $buff->getInt();

				$player["state"] = $buff->getInt();

				$player["ip"] = $buff->getInt().
					'.'.$buff->getint().
					'.'.$buff->getint().'.255';	
			}
			catch(AsIOException $e)
			{
				unset ($players[$k]);
			}

		}

		return $players;
	}
	
	public function query($format, AsSauerBuffer $buffer)
	{
		stream_set_timeout($this->_connection, 0, 100000);
		stream_get_contents($this->_connection); //clear
		fwrite($this->_connection, $buffer->toBinary($format));
		
		return new AsSauerBuffer(stream_get_contents($this->_connection));
	}
	
	public function getServerInfo()
	{
		
		$buff = new AsSauerBuffer;
		$buff->putInt(1);
		
		$buff = $this->query('c', $buff);
		
		assert($buff->getInt() == 1);

		$info['playerCount'] = $buff->getInt();
		$info['attrCount'] = $buff->getInt();
		$info['protocol'] = $buff->getInt();		
		
		if($info['attrCount'] !== 5 || $info['protocol'] !== 258)
		{
			//Incompatible server
			$info['gamemode'] = 'ictf';
			$info['timeleft'] = 0;
			$info['maxPlayers'] = 0;
			$info['mastermode'] = 'private';

			$info['mapName'] = 'incompatible server!';
			$info['serverName'] = 'incompatible server!';
		}
		else
		{
			$info['gamemode'] = AsSauerServer::$GAMEMODES[$buff->getInt()];
			$info['timeleft'] = $buff->getInt();
			$info['maxPlayers'] = $buff->getInt();
			$info['mastermode'] = AsSauerServer::$MASTERMODES[$buff->getInt()];

			$info['mapName'] = $buff->getString();
			$info['serverName'] = $buff->getString();
		}
		
		return $info;
	}
}
