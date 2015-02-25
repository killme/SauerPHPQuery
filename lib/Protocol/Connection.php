<?php

namespace Killme\SauerPHPQuery\Protocol;

use Killme\SauerPHPQuery\Server;

class Connection
{
    private $_connection;

    public function __construct(Server $server)
    {
        $this->_connection = stream_socket_client('udp://'.$server->getIp().':'.$server->getPort(), $errno, $errstr, 0);

        if(!$this->_connection)
            throw new Exception('Could not connect: '.$errstr.' ('.$errno.')');
    }

    public function query($buffer)
    {
        stream_set_timeout($this->_connection, 1);
        stream_get_contents($this->_connection); //clear
        fwrite($this->_connection, $buffer);

        return new Buffer(stream_get_contents($this->_connection));
    }

    public function close()
    {
        fclose($this->_connection);
    }
}
