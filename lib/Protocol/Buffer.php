<?php

namespace Killme\SauerPHPQuery\Protocol;

/**
 * Class for the Sauerbraten protocol data.
 */
class Buffer
{
    public $stack = array();

    public function __construct($stack = array())
    {
        if(is_string($stack))
        {
            $this->stack = unpack('C*', $stack);
        }
        elseif(is_array($stack))
        {
            $this->stack = $stack;
        }
        else
        {
            throw new \InvalidArgumentException('$stack is neither an array nor a string.');
        }
    }

    public function isEmpty()
    {
        return count($this->stack) === 0;
    }

    public function getByte()
    {
        return array_shift($this->stack);
    }

    public function getInteger()
    {
        $c = $this->getByte();

        if($c === NULL)
            throw new \UnderflowException('buffer is empty.');

        if ($c == 0x80)
        {
            $n = $this->getByte();
            $n |= $this->getByte() << 8;
            return $n;
        }
        else if ($c == 0x81)
        {
            $n = $this->getByte();
            $n |= $this->getByte() << 8;
            $n |= $this->getByte() << 16;
            $n |= $this->getByte() << 24;
            return $n;
        }
        else if ($c & 128)
        {
            $c = -(((~$c) & 255) +1);
        }

        return $c;
    }

    public function getString()
    {
        $r = ""; $i = 0;
        while (true)
        {
            $c = $this->getInteger();
            if ($c == 0) return $r;
            $r .= chr($c);
        }
    }

    public function putInt($value)
    {
        $this->stack[] = $value;
    }

    public function pack($format)
    {
        $list = $this->stack;
        array_unshift($list, $format);
        return call_user_func_array('pack', $list);
    }
}
