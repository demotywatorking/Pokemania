<?php

namespace src\controllers;

use src\libs\Controller;

class Stopka extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function wylecz()
    {
        header('Location: ' . URL . 'lecznica/wylecz/wszystkie/?ajax');
        exit;
    }

    public function soda()
    {
        header('Location: ' . URL . 'plecak/rodzaj/soda/1/?ajax&komunikat');
        exit;
    }

    public function woda()
    {
        header('Location: ' . URL . 'plecak/rodzaj/woda/1/?ajax&komunikat');
        exit;
    }

    public function lemoniada()
    {
        header('Location: ' . URL . 'plecak/rodzaj/lemoniada/1/?ajax&komunikat');
        exit;
    }

    public function cheri()
    {
        header('Location: ' . URL . 'plecak/jagoda/Cheri_Berry/all/?ajax&komunikat');
        exit;
    }

    public function wiki()
    {
        header('Location: ' . URL . 'plecak/jagoda/Wiki_Berry/all/?ajax&komunikat');
        exit;
    }

    public function nakarm()
    {
        header('Location: ' . URL . 'plecak/rodzaj/karma/max/?ajax&komunikat');
        exit;
    }

}
