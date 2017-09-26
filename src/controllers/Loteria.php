<?php

namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;

class Loteria extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('Loteria - ' . NAME, 1);
        }
    }

    public function index()
    {
        $rezultat = $this->model->losy();
        $rezultat = $rezultat[0];
        $this->view->losy = $rezultat['loteria'];
        $this->view->render('loteria/index');
        if (!isset($_GET['ajax'])) {
            $this->loadTemplate('', 2);
        }
    }

    public function losuj()
    {
        if (!isset($_GET['ajax'])) {
            unset($_GET['ajax']);
            $this->index();
            exit();
        }
        $show = '';
        $rezultat = $this->model->losy();
        $w = $rezultat[0];
        if ($w['loteria'] > 0) {
            $this->model->zabierzLos();
            $rand = rand(0, 1000);
            $show .= '<div class="alert alert-success text-medium text-center margin-top"><span>';
            if ($rand <= 1) {
                $show .= 'Gratulacje, wygrywasz Dratini! Pokemona znajdziesz w swojej rezerwie.';
                $this->model->dodajDratini();
                Session::_set('poki_magazyn', (Session::_get('poki_magazyn') + 1));
            } elseif ($rand <= 4) {
                $show .= 'Gratulacje, wygrywasz 2 miliony &yen;.';
                Session::_set('kasa', (Session::_get('kasa') + 2000000));
                $this->model->wygranaPieniadze(2000000);
            } elseif ($rand <= 75) {
                $show .= 'Wygrywasz 30 Cheri Berry.';
                $this->model->wygranaJagody('Cheri_Berry', 30);
            } elseif ($rand <= 90) {
                $show .= 'Wygrywasz 20 Chesto Berry.';
                $this->model->wygranaJagody('Chesto_Berry', 20);
            } elseif ($rand <= 120) {
                $show .= 'Wygrywasz 20 Pecha Berry!';
                $this->model->wygranaJagody('Pecha_Berry', 20);
            } elseif ($rand <= 150) {
                $show .= 'Wygrywasz 20 Rawst Berry.';
                $this->model->wygranaJagody('Rawst_Berry', 20);
            } elseif ($rand <= 170) {
                $show .= 'Wygrywasz 15 Wiki Berry.';
                $this->model->wygranaJagody('Wiki_Berry', 15);
            } elseif ($rand <= 190) {
                $show .= 'Wygrywasz 15 Mago Berry.';
                $this->model->wygranaJagody('Mago_Berry', 15);
            } elseif ($rand <= 210) {
                $show .= 'Wygrywasz 15 Lapapa Berry.';
                $this->model->wygranaJagody('Lapapa_Berry', 15);
            } elseif ($rand <= 230) {
                $show .= 'Wygrywasz 15 Aguav Berry.';
                $this->model->wygranaJagody('Squav_Berry', 15);
            } elseif ($rand <= 290) {
                $show .= 'Wygrywasz 30 Pokeballi.';
                $this->model->wygranaPokeball('pokeballe', 30);
            } elseif ($rand <= 320) {
                $show .= 'Wygrywasz 20 Nestballi.';
                $this->model->wygranaPokeball('nestballe', 20);
            } elseif ($rand <= 350) {
                $show .= 'Wygrywasz 20 Greatballi.';
                $this->model->wygranaPokeball('greatballe', 20);
            } elseif ($rand <= 360) {
                $show .= 'Wygrywasz 5 Ultraballi.';
                $this->model->wygranaPokeball('ultraballe', 5);
            } elseif ($rand <= 370) {
                $show .= 'Wygrywasz 3 Cherishballe.';
                $this->model->wygranaPokeball('cherishballe', 3);
            } elseif ($rand <= 371) {
                $show .= 'Gratulacje, wygrywasz Masterballa.';
                $this->model->wygranaPokeball('masterballe', 1);
            } elseif ($rand <= 375) {
                $show .= 'Wygrywasz 10 losów do loterii';
                $this->model->wygranaLosy();
            } elseif ($rand <= 377) {
                $show .= 'Wygrywasz kamień roślinny.';
                $this->model->wygranaKamienie('roslinne');
            } elseif ($rand <= 379) {
                $show .= 'Wygrywasz kamień ognisty.';
                $this->model->wygranaKamienie('ogniste');
            } elseif ($rand <= 381) {
                $show .= 'Wygrywasz kamień gromu.';
                $this->model->wygranaKamienie('gromu');
            } elseif ($rand <= 383) {
                $show .= 'Wygrywasz kamień księżycowy.';
                $this->model->wygranaKamienie('ksiezycowe');
            } elseif ($rand <= 385) {
                $show .= 'Wygrywasz kamień wodny.';
                $this->model->wygranaKamienie('wodne');
            } elseif ($rand <= 387) {
                $show .= 'Wygrywasz kamień słoneczny.';
                $this->model->wygranaKamienie('sloneczne');
            } else {
                $rand = rand(9000, 11000) / 10000;
                $kasa = floor(Session::_get('poziom') * $rand * 1000);
                Session::_set('kasa', (Session::_get('kasa') + $kasa));
                $show .= 'Wygrywasz ' . $kasa . ' &yen;.';
                $this->model->wygranaPieniadze($kasa);
            }
            $show .= '</span></div>';
        } else {
            $show .= '<div class="alert alert-danger text-medium text-center margin-top"><span>Przykro mi, ale nie masz już losów na loterię.</span></div>';
        }
        $this->view->wynik = $show;
        $this->view->render('loteria/wynik');
    }

}