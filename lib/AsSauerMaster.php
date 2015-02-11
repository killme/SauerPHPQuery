<?php
// Set default host and port of sauerbraten
class AsSauerMaster extends AsBaseMaster
{
	const defaultHost = "sauerbraten.org";
	const defaultPort = 28787;
	
	public function __construct($host, $ip)
	{
		$this->ip = $host;
		$this->port = $port;
	}
}
