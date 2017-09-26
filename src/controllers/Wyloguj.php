<?php
namespace src\controllers;

use src\libs\Controller;
use src\libs\Session;

class Wyloguj extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }
    
    public function index($wyloguj = 0)
    {
        $komentarz = '';
        $last = '';
        if($wyloguj) {
            $get = $wyloguj;
            if (Session::_isset('lastpage')) {
                $last = Session::_get('lastpage');
                $last = substr($last, 1, strlen($last)-1);
            }
            if ($wyloguj == 1) {
                $komentarz = 'Normalne wylogowanie';
            } elseif ($wyloguj == 2) {
                $komentarz = 'Wygaśnięta sesja';
            } elseif ($wyloguj == 3) {
                $komentarz = 'Wygaśnięta sesja (baza)';
                $get = '2';
            } elseif ($wyloguj == 4) {
                $komentarz = 'Złe IP';
                $get = '2';
            }
        } else {
            $get = '1';
        }
        $komentarz .= ' last: '.$last;
        $godzina = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $this->model->log($godzina, $ip, $komentarz);
        if ($wyloguj != 4) {
            $this->model->wylogujUzytkownika();
        }
        header('Location: '.URL);
        Session::regenerate();
        if (isset($_COOKIE['al'])) {
            setcookie('al', '' ,time()-10000, '/', NULL, NULL, 1); // 28 dni
        }
        Session::_set('last', $last);
        Session::_set('get', $get);
        exit;
    }
}
