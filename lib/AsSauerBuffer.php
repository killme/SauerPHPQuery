<?php
/**
 * Class for the Sauerbraten protocol data.
 */
class AsSauerBuffer
{
	public $stack = array();
	
	public function __construct($stack = array())
	{
		if(is_string($stack))
			$this->stack = unpack('C*', $stack);
		elseif(is_array($stack))
			$this->stack = $stack;
		
		else
		{
			throw new InvalidArgumentException('$stack should be array or string on AsSauerBuffer.__construct($stack).');
		}
	}
	
	public function isEmpty()
	{
		return count($this->stack) === 0;
	}

	private function getC()
	{ 
		return array_shift($this->stack);
	}

	public function getInt()
	{  
		$c = $this->getc();
		
		if($c === NULL)
			throw new AsIOException('buffer is empty.');
	
		if ($c == 0x80)
		{
			$n = $this->getc(); 
			$n |= $this->getc() << 8; 
			return $n;
		}
		else if ($c == 0x81)
		{
			$n = $this->getc();
			$n |= $this->getc() << 8;
			$n |= $this->getc() << 16;
			$n |= $this->getc() << 24;
			return $n;
		}
		
		return $c;
	}

	public function getString($len=10000)
	{
		$r = ""; $i = 0; 
		while (true)
		{ 
			$c = $this->getint();
			if ($c == 0) return $r;
			$r .= chr($c);
		} 
	}
	
	public function putInt($value)
	{
		$this->stack[] = $value;
	}

	public function toBinary($format)
	{
		$list = $this->stack;
		array_unshift($list, $format);
		return call_user_func_array('pack', $list);
	}
}
