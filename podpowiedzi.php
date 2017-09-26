<?php
$podpowiedz =
    [
        0 => 'Ustawienia stopki możesz zmienić w ustawieniach w zakładce "Wygląd".',
        1 => 'Codziennie w nocy otrzymujesz 2 losy na loterię.',
        2 => 'Kolor paneli można zmienić w ustawieniach w zakładce "Wygląd".',
        3 => 'Repeatballe pozwalają na ponowne rzucenie pokeballa przy nieudanej próbie złapania Pokemona.',
        4 => 'Latarka nie będzie działać bez baterii.',
        5 => 'Kolor tła można zmienić w ustawieniach w zakładce "Wygląd".',
        6 => 'Podczas polowania wciśnij "e", aby uleczyć swoją drużynę.',
        7 => 'Podczas polowania wciśnij "r", aby kontynuować wyprawę.',
        8 => 'Nie możesz kopać na Safari bez łopaty',
        9 => 'Aby zobaczyć jakość Pokemona przed walką, musisz wyposażyć się w pokedex 3. poziomu',
        10 => 'Aby zobaczyć poziom Pokemona przed walką, musisz wyposażyć się w pokedex 1. poziomu',
        11 => 'Aby zobaczyć statystyki Pokemona przed walką, musisz wyposażyć się w pokedex 2. poziomu',
        12 => 'Do czasu osiągnięcia 16 poziomu trenera leczenie jest darmowe. Od 16 do 25 poziomu leczenie kosztuje połowę normalnej ceny.',
        13 => 'W walce z Pokemonem pod ochroną otrzymujesz podwójne doświadczenie.',
        14 => 'Nestball działa najlepiej na Pokemony do 15 poziomu włącznie.',
    ];
$i = mt_rand(0, (count($podpowiedz)-1));
echo '<div class="alert alert-info"><span>'.$podpowiedz[$i].'</span></div>';
?>