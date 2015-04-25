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

        if($buffer->getInteger() != -10)
        {
            throw new \InvalidArgumentException("Failed to parse protocol.");
        }

        if($buffer->isEmpty()) {
            return array();
        }

        $buff2 = new Buffer($buffer->stack);

        while(!$buff2->isEmpty())
        {
            print("- " . $buff2->getInteger() . "\n");
        }

        $players = array();

        if($buffer->isEmpty()) {
            throw new \InvalidArgumentException("Permature packet end.");
        }

        while(!$buffer->isEmpty())
        {
            $p = $this->parsePlayerPacket($buffer);
            print_r($p);
            $players[$p['cn']] = $p;
        }

        if(!$buffer->isEmpty())
        {
            do
            {
                print($buffer->getInteger() . "\n");
            }
            while(!$buffer->isEmpty());

            throw new \InvalidArgumentException("Garbage data in parse.");
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

        if($buffer->getInteger() != 1)
        {
            throw new \InvalidArgumentException("Failed to parse server info.");
        }

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

    protected function parsePlayerPacket(Buffer $buf)
    {
        while(!$buf->isEmpty())
        {
            if( $buf->getInteger() == 0 && $buf->getInteger() == 1 &&
                $buf->getInteger() == -1 && $buf->getInteger() == -1 &&
                $buf->getInteger() == 105 && $buf->getInteger() == 0  &&
                $buf->getInteger() == -11)
            {
                break;
            }
        }

        if($buf->isEmpty())
        {
            return;
        }

        return array(
            'cn' => $buf->getInteger(),
            'ping' => $buf->getInteger(),
            'name' => $buf->getString(),
            'team' => $buf->getString(),
            'frags' => $buf->getInteger(),
            'flags' => $buf->getInteger(),
            'deaths' => $buf->getInteger(),
            'teamkills' => $buf->getInteger(),
            'accuracy' => $buf->getInteger(),
            'health' => $buf->getInteger(),
            'armour' => $buf->getInteger(),
            'gunselect' => $buf->getInteger(),
            'privilege' => $buf->getInteger(), // CHECK YOUR PRIVILEGE !!!
            'state' => $buf->getInteger(),
            'ip' => array(
                $buf->getByte(),
                $buf->getByte(),
                $buf->getByte(),
            ),
        );
    }

    public function parseQuery(Server $server)
    {

    }
}
