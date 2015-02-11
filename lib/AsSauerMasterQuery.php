<?php
/**
 * Implement masterserver logic
 * A base implementation by Thomas Poechtraeger (from 2008 respectivly) can be found here:
 * http://sourceforge.net/p/cubelister/webcsl/ci/webcsl/tree/php/func/functions.php
 * 
 * @author fohlen
 */

class AsSauerMasterQuery {
	public $servers = array();
	private $ip;
	private $port;
	
	public function __construct($host = AsSauerMaster::defaultHost, $port = AsSauerMaster::defaultPort)
	{
		$this->ip = gethostbyname($host);
		$this->port = $port;
	}
	
	public function update()
	{
		try {
			$temporaryServers = "";
			
			$sock = stream_socket_client("tcp://$this->ip:$this->port");
			fwrite($sock, "list\n");
			while($str = fread($sock, 4096)) {
				$temporaryServers . $str; 
			}

			$temporaryServers = explode("\n", $temporaryServers);
			$temporaryServer = new AsSauerQuery();
			
			foreach ($temporaryServers as $Server)
			{
				$Server = explode(":", $Server);
				array_push($this->servers, $temporaryServer->query($Server[0], $Server[1]));
			}
			
		} catch (AsIOException $e) {
			
		}
	}
}