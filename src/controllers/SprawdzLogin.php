<?php

namespace src\controllers;

use src\libs\Controller;

class SprawdzLogin extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login(string $login)
    {
        $this->view->ilosc = $this->model->login($login);
        $this->view->render('sprawdzLogin/login');
    }

    public function email(string $email)
    {
        $sprawdz = '/^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9\-.]+\.[a-zA-Z]{2,4}$/';
        if (!preg_match($sprawdz, $email)) {
            $this->view->zlyFormat = 1;
        } else {
            $this->view->ilosc = $this->model->email($email);
        }
        $this->view->render('sprawdzLogin/mail');

    }
}