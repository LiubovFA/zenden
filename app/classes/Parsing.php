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

            if (count($main_parts) > 1)
                $sizes = $this->getSizes($main_parts[1]);
            else
                $sizes = "";

            //присваиваем
            $item->setRef($ref);
            $item->setImg($img);
            $item->setName($name);
            $item->setCurrentPrice($current_price);
            $item->setOldPrice($old_price);
            $item->setDiscount($discount);
            $item->setSizes($sizes);

            $items_array[] = $item;
        }

        return $items_array;
    }

    private function getRef($src_str)
    {
        $ref = $this->getImgOrRef($src_str, "\"", "\"", 1);

        return $ref;
    }

    private function getImg($src_str)
    {
        $img = $this->getImgOrRef($src_str, "src=\"", "\"", 5);

        return $img;
    }

    private function getName($src_str)
    {
        $name = $this->getNameOrPrice($src_str, "</a>");

        return $name;
    }

    private function getCurrentPrice($src_str)
    {
        $current = $this->getNameOrPrice($src_str, "</span>");

        return $current;
    }

    private function getOldPrice($src_str)
    {
        $old = $this->getNameOrPrice($src_str, "</del>");

        return $old;
    }

    private function getDiscount($src_str)
    {
        $parts = explode('span', $src_str);

        if (count($parts) > 1) {

            $end = strlen($parts[1]) - 2;

            $start = strpos($parts[1], '>') + 1;

            $length = $end - $start;

            $discount = substr($parts[1], $start, $length);

            return $discount;
        }

        return "";
    }

    private function getNameOrPrice($source, $needle)
    {
        $end = strpos($source, $needle);

        $part = substr($source, 0 , $end);

        $start = strrpos($part, ">")+1;

        $part = substr($part, $start, $end-$start);

        return $part;
    }

    private function getImgOrRef($source, $needleStart, $needleEnd, $num)
    {
        $start = strpos($source, $needleStart)+$num;

        $end = strpos($source, $needleEnd, $start);

        $phrase = substr($source, $start, $end-$start);

        return $phrase;
    }

    private function getSizes($src_str)
    {
        $parts = explode("<div class=\"product-card__sizes-item", $src_str);

        $parts_length = count($parts);

        $sizes = "";

        for ($i=0; $i<$parts_length; $i++)
        {
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