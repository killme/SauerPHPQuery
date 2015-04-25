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
        if($this->isEmpty())
        {
            throw new \Exception("Buffer is empty.");
        }

        return array_shift($this->stack);
    }

    public function getInteger()
    {
        $c = $this->getByte();

        if($c === NULL)
            throw new \UnderflowException('buffer is empty.');

        if ($c == (-128 & 0xFF))
        {
            $n = $this->getByte();
            $n |= $this->getByte() << 8;

            if(($n & 0x8000) == 0x8000)
            {
                $n |= (~0 & ~0xFFFF);
            }
            return $n;
        }
        elseif ($c == (-127 & 0xFF))
        {
            $n = $this->getByte();
            $n |= $this->getByte() << 8;
            $n |= $this->getByte() << 16;
            $n |= $this->getByte() << 24;
            return $n;
        }
        elseif(($c & 0x80) == 0x80)
        {
            $c |= (~0 & ~0xFF);
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

    public function putByte($value)
    {
        $this->stack[] = $value & 255;
    }

    public function putInt($value)
    {
        if($value < 128 && $value > -127)
        {
            $this->putByte($value);
        }
        else if($value < 0x8000 && $value >= -0x8000)
        {
            $this->putByte(0x80);
            $this->putByte($value);
            $this->putByte($value >> 8);
        }
        else
        {
            $this->putByte(0x81);
            $this->putByte($value);
            $this->putByte($value >> 8);
            $this->putByte($value >> 16);
            $this->putByte($value >> 24);
        }
    }

    public function pack($format)
    {
        $list = $this->stack;
        array_unshift($list, $format);
        return call_user_func_array('pack', $list);
    }
}
