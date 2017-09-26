<?php

namespace src\libs;

class View
{
    
    public function render($name)
    {
        require './src/views/' . $name . '.php';
    }

}