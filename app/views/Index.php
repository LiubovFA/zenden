<?php

if (!empty($data))
{
    echo "<h3>Обувь</h3>";
    $length = count($data);

    $modulo = $length % 3;
    $rows = $length / 3;

    if ($modulo > 0)
        $rows += 1;

    //echo "rows $rows <br>";

    for ($r=0; $r<$rows; $r++)
    {
        echo "<div class='row'>";
        for ($col=1; $col<=3; $col++)
        {
            $pos = 2*$r+($r+$col);
            $index = $pos-1;

            if ($index < $length)
            {
                echo "<div class='column'>
                        <div class='index'><label>Позиция #".$pos."</label></div><hr>
                        <div class='img'><img src=".$data[$index]->getImg()."></div>
                        <div class='name'><a href=\"https://".$data[$index]->getRef()."\">".$data[$index]->getName() ."</a></div><hr>
                        <div class='price'>";
                if ($data[$index]->getOldPrice() != "")
                    echo "<del>".$data[$index]->getOldPrice()."</del><br>";

                echo "<span>".$data[$index]->getCurrentPrice()."</span></div>";

                if ($data[$index]->getDiscount() != "")
                        echo "<div class='discount'><label>".$data[$index]->getDiscount()."</label></div>";

                echo "<hr>";

                if ($data[$index]->getSizes() != "")
                        echo "<div class='sizes'><label>Доступные размеры:<br>".$data[$index]->getSizes()."</label></div>";
                echo "</div>";
            }
        }
        echo "</div><hr>";
    }
}