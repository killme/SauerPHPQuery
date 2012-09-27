<?php

class AsSauerQueryTest extends CTestCase
{

	public function testQuery()
	{
		echo PHP_EOL;
		Yii::import('application.components.AsQuery.*');
		$class = new AsSauerQuery;
		
		$start = microtime(true);
		$server = $class->query('nooblounge.net', 10031);
		$total = microtime(true) - $start;
		echo 'took ',($total).PHP_EOL;
		
		$this->assertTrue($server instanceof AsSauerServer);
		$this->assertTrue(count($server->getPlayerList()) === 0); //nooblounge blocks extinfo
		

		$start = microtime(true);
		$server2 = $class->query('psl.sauerleague.org', 10001);
		$total = microtime(true) - $start;
		echo 'took ',($total).PHP_EOL;
	}
}
