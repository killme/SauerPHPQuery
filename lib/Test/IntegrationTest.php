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

    public function testBuffer()
    {
        $buf = new \Killme\SauerPHPQuery\Protocol\Buffer;

        $this->assertTrue($buf->isEmpty());

        for($i = -255; $i <= 427819; $i++)
        {
            $buf->putByte($i);
            $this->assertEquals($i & 255, $buf->getByte());
            $this->assertTrue($buf->isEmpty());

            $buf->putInt($i);
            $this->assertTrue(!$buf->isEmpty());
            $this->assertEquals($i, $buf->getInteger($i));
            $this->assertTrue($buf->isEmpty());
        }
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

        $class = new SauerbratenQueryManager;


        $pslServer = new Server('crowd.gg', 28786);

        $server2 = $class->query($pslServer);
        print_r($server2);

    }
}
