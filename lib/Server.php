<?php

namespace Killme\SauerPHPQuery;

/**
 * Base Server class for external servers.
 */
class Server
{
    private $ip;
    private $port;

    private $queryData;

    public function __construct($ip, $port)
    {
        $this->ip = $ip;
        $this->port = $port;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getQueryData()
    {
        return $this->queryData;
    }

    public function setQueryData($data)
    {
        $this->queryData = $data;
    }
}
