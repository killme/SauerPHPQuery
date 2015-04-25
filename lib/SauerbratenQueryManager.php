<?php

namespace Killme\SauerPHPQuery;

class SauerbratenQueryManager extends BaseQueryManager
{
    public static $GAME_MODES = array(
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

    public static $SERVER_MODES = array(
        -1 => 'auth',
        0 => 'open',
        1 => 'veto',
        2 => 'locked',
        3 => 'private',
        4 => 'password'
    );

    public function parseQuery(Server $server)
    {
        $queryData = $server->getQueryData();

        if(isset($queryData['attr']))
        {
            foreach($queryData['attr'] as $k => $v)
            {
                switch($k)
                {
                    case 0: $queryData['protocolVersion'] = $v; break;
                    case 1: $queryData['gameMode'] = $this->parseGamemode($v); break;
                    case 2: $queryData['timeLeft'] = $v; break;
                    case 3: $queryData['maxClients'] = $v; break;
                    case 4: $queryData['serverMode'] = $this->parseServerMode($v); break;
                    case 5: $queryData['gamePaused'] = $v != 0; break;
                    case 6: $queryData['gameSpeed'] = $v; break;
                    default:
                        throw new \RuntimeException("Invalid extinfo attribute: ".$k);
                        break;
                }
            }
        }

        $server->setQueryData($queryData);
    }

    protected function parseGamemode($mode)
    {
        return array(
            'mode' => $mode,
            'name' => self::$GAME_MODES[$mode]
        );
    }

    protected function parseServerMode($mode)
    {
        return array(
            'mode' => $mode,
            'name' => self::$SERVER_MODES[$mode]
        );
    }
}
