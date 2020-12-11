<?php

namespace zenden\app\classes;

use zenden\app\classes\Item;

class Parsing
{
    private string $source;

    public function __construct()
    {
    }

    public function start($html)
    {
        $this->source = $html;

        //div с которого начинаются все позиции
        $items_div_class = "<div class=\"products-list__item product-card js-reveal";

        //отрезаем лишнюю часть, которая находится до указанного div
        $items_part = strstr($this->source, $items_div_class);

        //определяем позицию блока, который идет ниже всех позиций
        $end_index = strpos($items_part, "<div class=\"products-list__pager");

        //получаем кусок html-кода, который содержит только все позиции
        $all_items_str = substr($items_part, 0, $end_index);

        //удаляем все закомментированные разделы
        $all_items_str = str_replace(array ('<!--', '-->'), "", $all_items_str);

        //div class - для каждой позиции
        $item_div = "<div class=\"products-list__item product-card js-reveal\">";

        //получаем массив, в ячейках которого содержатся позиции обуви по отдельности
        $items_full_info = explode($item_div, $all_items_str);

        //удаляем первый пустой элемент
        unset($items_full_info[0]);

        //переиндексация массива
        $items_full_info = array_values($items_full_info);

        $items_array = array();

        for ($i=0; $i<24;$i++)
        {
            $item = new Item();

            //echo "i = $i <br>";

            $main_parts = explode("<div class=\"product-card__sizes\">", $items_full_info[$i]);

            $parts = explode("<div ", $main_parts[0]);

            //убираем закомменченный ранее раздел
            unset($parts[1]);

            //переиндексация
            $parts = array_values($parts);

            //получаем ссылку, изображение из parts[0]
            $ref = $this->getRef($parts[0]);
            $img = $this->getImg($parts[0]);

            //получаем название позиции из parts[1]
            $name = $this->getName($parts[1]);

            $price_parts = explode('<span', $parts[2]);

            $current_price = $this->getCurrentPrice($price_parts[1]);
            $old_price = $this->getOldPrice($price_parts[0]);

            $discount = $this->getDiscount($parts[3]);
            $sizes = $this->getSizes($main_parts[1]);

            //присваиваем
            $item->setRef($ref);
            $item->setImg($img);
            $item->setName($name);
            $item->setCurrentPrice($current_price);
            $item->setOldPrice($old_price);
            $item->setDiscount($discount);
            $item->setSizes($sizes);

            $items_array[] = $item;
/*
            echo "ПОЗИЦИЯ $i <br>";
            echo "ref = ".$item->getRef()."<br>";
            echo "img = ".$item->getImg()."<br>";
            echo "cur price = ".$item->getCurrentPrice().'<br>';
            echo "old price = ".$item->getOldPrice().'<br>';
            echo "Discount = ".$item->getDiscount().'<br>';
            echo "sizes = ".$item->getSizes().'<br>';*/
        }

        //echo "length of array items = ". count($items_array)."<br>";


        /*$item1 = new Item('n1', 'img1', 'ref1', 'p1');
        $item2 = new Item('n2', 'img2', 'ref2', 'p2');
        $item3 = new Item('n3', 'img3', 'ref3', 'p3');

        $arr = array ($item1, $item2, $item3);

        $item4 = new Item('n4', 'img4', 'ref4', 'p4');

        $arr[] = $item4;

        for ($i=0; $i<count($arr); $i++)
        {
            echo $arr[$i]->getName()." ".$arr[$i]->getImg()." ".$arr[$i]->getRef()." ".$arr[$i]->getPrice()."<br>";
        }*/
        return $items_array;
    }

    private function getRef($src_str)
    {
        //вычисляем начало и конец ссылки и ее длину
        $start = strpos($src_str, "\"")+1;
        $end = strpos($src_str, "\"", $start);
        $length = $end-$start;

        //получаем ссылку
        $ref = substr($src_str, $start, $length);

        return $ref;
    }

    private function getImg($src_str)
    {
        $start = strpos($src_str, "src=\"")+5;

        $end = strpos($src_str, "\"", $start);

        $length = $end-$start;

        $img = substr($src_str, $start, $length);

        return $img;
    }

    private function getName($src_str)
    {
        $end = strpos($src_str, "</a>");

        $name = substr($src_str, 0 , $end);

        $start = strrpos($name, ">")+1;

        $name = substr($name, $start, $end-$start);

        return $name;
    }

    private function getCurrentPrice($src_str)
    {
        $end = strpos($src_str, "</span>")-4;

        $current = substr($src_str, 0 , $end);

        $start = strrpos($current, ">")+1;

        $current = substr($current, $start, $end-$start);

        return $current;
    }

    private function getOldPrice($src_str)
    {
        $end = strpos($src_str, "</del>")-4;

        $old = substr($src_str, 0 , $end);

        $start = strrpos($old, ">")+1;

        $old = substr($old, $start, $end-$start);

        //echo "old price = $old <br>";

        return $old;
    }

    private function getDiscount($src_str)
    {
        $parts = explode('span', $src_str);

        $end = strlen($parts[1]) - 2;

        $start = strpos($parts[1], '>')+1;

        $length = $end - $start;

        $discount = substr($parts[1], $start, $length);

        return $discount;
    }

    private function getSizes($src_str)
    {
        $parts = explode("<div class=\"product-card__sizes-item", $src_str);

        $parts_length = count($parts);

       // echo "$parts_length <br>";
        $sizes = "";

        for ($i=0; $i<$parts_length; $i++)
        {
            //echo "size part = $parts[$i] <br>";

            $pos = strpos($parts[$i], "out-of-stock");

            if ( $pos === false)
            {
                $part = str_replace(array ('</div>', '>', '"'), '', $parts[$i]);
                $part = trim($part);

                if ($part != "" || $part != " ")
                    $sizes .= "$part ";
            }
        }

        return trim($sizes);
    }
}