<?php

namespace Killme\SauerPHPQuery;

use Killme\SauerPHPQuery\Protocol\Buffer;
use Killme\SauerPHPQuery\Protocol\Connection;

class BaseQueryManager
{
    protected $connections = array();

    public function getConnection(Server $server)
    {
        $hash = spl_object_hash ($server);
        return $this->connections[$hash] = isset($this->connections[$hash]) ? $this->connections[$hash] : new Connection($server);
    }

    public function query(Server $server, QueryScope $scope = null)
    {
        $info = $this->getServerInfo($server);
        $info['players'] = $this->getPlayerInfo($server);

        $server->setQueryData($info);

        $this->parseQuery($server);

        return $server;
    }

    public function getPlayerInfo(Server $server, $cn = -1)
    {
        $connection = $this->getConnection($server);

        $buffer = new Buffer;
        $buffer->putInt(0);
        $buffer->putInt(1);
        $buffer->putInt($cn);

        $buffer = $connection->query(
            $buffer->pack('ccc'));

        if($buffer->isEmpty()) {
            return array();
        }

        assert($buffer->getInteger() == 0);
        assert($buffer->getInteger() == 1);
        assert($buffer->getInteger() == $cn);
        assert($buffer->getInteger() == -1);
        assert($buffer->getInteger() == 105);
        if($buffer->getInteger() != 0)
        {
            throw new \InvalidArgumentException("Invalid client number.");
        }

        assert($buffer->getInteger() == -10);

        if($buffer->isEmpty()) {
            return array();
        }

        $players = array();

        while(($cn = $buffer->getInteger()) != -11)
        {
            $players[$cn] = array();
            print_r($cn, $players);
        }

        return $players;
    }



    public function getServerInfo(Server $server)
    {
        $connection = $this->getConnection($server);

        $buffer = new Buffer;
        $buffer->putInt(1);

        $buffer = $connection->query(
            $buffer->pack('c'));

        assert($buffer->getInteger() == 1);

        $info['playerCount'] = $buffer->getInteger();
        $attrCount = $buffer->getInteger();
        $info['attr'] = array();

        for($i = 0; $i < $attrCount; $i++)
        {
            $info['attr'][] = $buffer->getInteger();
        }

        $info['mapName'] = $buffer->getString();
        $info['serverDescription'] = $buffer->getString();

        return $info;
    }

    public function parseQuery(Server $server)
    {

    }
}
