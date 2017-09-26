5 ostatnio złapanych Pokemonów
<?php foreach ($this->pok as $pok) : ?>
<div class="pokemon">
    <img src="<?=URL?>public/img/poki/<?=$pok['id_poka']?>.png" class="pokemon_img" />
    <?=$pok['nazwa']?>
</div>
<div class="clear"></div>
<?php endforeach;?>