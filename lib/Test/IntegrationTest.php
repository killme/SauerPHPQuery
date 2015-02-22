<?php

namespace Killme\SauerPHPQuery\Test;

use Killme\SauerPHPQuery\SauerbratenQueryManager;
use Killme\SauerPHPQuery\Server;

class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * TODO: properly unit test
     */
    public function testTheThings()
    {
        $class = new \Killme\SauerPHPQuery\SauerbratenQueryManager;

        $pslServer = new Server('dksc.tk', 101);

        $start = microtime(true);
        $server2 = $class->query($pslServer);
        $total = microtime(true) - $start;
        echo 'took ',($total).PHP_EOL;

        print_r($server2);
    }
}
