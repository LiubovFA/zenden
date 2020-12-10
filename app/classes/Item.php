<?php

namespace zenden\app\classes;

class Item
{
    private $name;
    private $img;
    private $ref;
    private $current_price;
    private $old_price;
    private $discount;
    private $sizes;

    public function __construct($_name = '', $_img = '', $_ref = '', $c_price = '', $o_price = '', $_discount = '', $_sizes = '')
    {
        $this->name = $_name;
        $this->img = $_img;
        $this->ref = "zenden.ru".$_ref;
        $this->current_price = $c_price;
        $this->old_price = $o_price;
        $this->discount = $_discount;
        $this->sizes = $_sizes;
    }

    public function getImg()
    {
        return $this->img;
    }

    public function setImg($i)
    {
        $this->img = $i;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($n)
    {
        $this->name = $n;
    }

    public function getRef()
    {
        return $this->ref;
    }

    public function setRef($r)
    {
        $this->ref = "zenden.ru".$r;
    }

    public function getCurrentPrice()
    {
        return $this->current_price;
    }

    public function setCurrentPrice($p)
    {
        $this->current_price = $p;
    }

    public function getOldPrice()
    {
        return $this->old_price;
    }

    public function setOldPrice($p)
    {
        $this->old_price = $p;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setDiscount($d)
    {
        $this->discount = $d;
    }

    public function getSizes()
    {
        return $this->sizes;
    }

    public function setSizes($s)
    {
        $this->sizes = $s;
    }
}