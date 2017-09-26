<?php

namespace src\libs;

use src\controllers\Index;
use src\controllers\Problem;

class Router
{
    private $url = null;
    private $controller = null;

    private $controllerPath = 'src/controllers/';
    private $defaultFile = 'Index.php';
    private $modelPath = 'src/models/';
    private $errorFile = 'Problem.php';

    public function __construct()
    {
        $this->init();
        $this->save();
    }

    private function init()
    {
        Session::getInstance();
        User::getInstance();
        // Handle language on page
        $this->getUrl();
        //$this->setLang();
        //tutorial and other thing to keep session with user in good condition
        if (!$this->start()) {
            return false;
        }
        if (empty($this->url[0])) {
            $this->loadDefaultController();
            return false;
        }
        $this->loadCustomController();
        $this->callControllerMethod();
    }

    private function getUrl()
    {
        foreach ($_GET as $key => $value) {
            Debug::addGet($key, $value);
        }
        foreach ($_POST as $key => $value) {
            Debug::addPost($key, $value);
        }
        if (isset($_GET['url'])) {
            $this->url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }

    private function loadDefaultController()
    {
        //require $this->controllerPath . $this->defaultFile;
        define('MODE', 'INDEX');
        Debug::$mode = MODE;
        $this->controller = new Index();
        $this->controller->loadModel('index', $this->modelPath);
        $this->controller->index();
    }

    private function loadCustomController()
    {
        $file = $this->controllerPath . ucfirst($this->url[0]) . '.php';
        if (file_exists($file)) {
            define('MODE', $this->url[0]);
            Debug::$mode = MODE;
            $className = "src\\controllers\\" . ucfirst($this->url[0]);
            $this->controller = new $className();
            $this->controller->loadModel($this->url[0], $this->modelPath);
        } else {
            $this->error();
            return false;
        }
    }

    private function callControllerMethod()
    {
        $length = count($this->url);
        try {
            //Determine what to load
            if ($length > 1) {
                if (!method_exists($this->controller, $this->url[1])) {
                    Debug::addInfo('Method', 'Index with argument instead of method');
                    Debug::addInfo('Argument', $this->url[1]);
                    $this->controller->index($this->url[1]);
                    return;
                }
                Debug::addInfo('Method', $this->url[1]);
                $this->params = [];
                for ($i = 2; $i < $length; $i++) {
                    array_push($this->params, $this->url[$i]);
                    Debug::addInfo('Argument ' . ($i - 2), $this->url[$i]);
                }
                $reflection = new \ReflectionMethod($this->controller, $this->url[1]);
                if ($reflection->isPrivate() || $reflection->isProtected()) {
                    throw new \Exception();
                }
                call_user_func_array([$this->controller, $this->url[1]], $this->params);
            } else {
                Debug::addInfo('Method', 'Index without arguments');
                $this->controller->index();
            }
        } catch (\TypeError $e) {
            if (Session::_get('admin')) {
                $this->getError($e);
            }
            echo '<div class="col-xs-12 col-sm-8 col-md-9" id="prawo"><div class="panel panel-success jeden_ttlo">
                  <div class="panel-heading"><span>BŁĄD</span></div><div class="panel-body">Podajesz błędne dane.</div></div></div>';
            $this->save();
            exit;
        } catch (\Throwable $e) {
            if (Session::_get('admin')) {
                $this->getError($e);
            }
            echo '<div class="col-xs-12 col-sm-8 col-md-9" id="prawo"><div class="panel panel-success jeden_ttlo">
                <div class="panel-heading"><span>BŁĄD</span></div><div class="panel-body">Próbujesz dostać się do zakazanej części krainy. Zaniechaj dalszych prób.</div></div></div>';
            $this->save();
            exit;
        }
    }

    private function getError($e)
    {
        Debug::addInfo('GetMessage', $e->getMessage());
        Debug::addInfo('getCode', $e->getCode());
        Debug::addInfo('getFile', $e->getFile());
        Debug::addInfo('getLine', $e->getLine());
        Debug::addInfo('getTraceAsString', $e->getTraceAsString());
        Debug::addInfo('getPrevious', $e->getPrevious());
        Debug::showInfo();
    }

    private function error()
    {
        define('MODE', 'Problem');
        $this->controller = new Problem();
        $this->controller->index();
        exit;
    }

    private function setLang()
    {
        if (isset($_GET['lang']))
            $lang = $_GET['lang'];
        else if (isset($_COOKIE['lang']))
            $lang = $_COOKIE['lang'];
        else if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
            $lang = array_keys(Lang::detectLang($_SERVER['HTTP_ACCEPT_LANGUAGE']))[0];
        else
            $lang = 'pl';
        if (!empty($this->url[0])) {
            Lang::$path = __LANG__ . $this->url[0];
        } else {
            Lang::$path = __LANG__ . 'index';
        }
        Lang::setLang($lang);
        setcookie('lang', $lang, time() + 3600 * 24 * 365);
    }

    private function start()
    {
        if (Session::_get('logged')) {
            if (Session::_get('samouczek') <= ILOSC_SAMOUCZEK && $this->url[0] != 'samouczek' && $this->url[0] != 'wyloguj') {
                header('Location: ' . URL . 'samouczek');
                return false;
            }
        }
        return true;
    }

    public static function save()
    {
        if (Session::_isset('logged') && MODE != 'zaloguj') {
            for ($i = 1; $i < 7; $i++) {
                if (User::_isset('pok', $i) && User::_get('pok', $i)->get('id') > 0) {
                    Session::_set('pok' . $i, User::_get('pok', $i)->get_all());
                }
            }
            $odznaka = '';
            for ($i = 1; $i < 9; $i++) {
                $odznaka .= User::$odznaki->kanto[$i];
                if ($i < 8) $odznaka .= '|';
            }
            $odznaka .= '|' . User::$odznaki->zlapanych;
            Session::_set('odznaki', $odznaka);
            Session::_set('ustawienia', User::$ustawienia->get_all());
            Session::_set('przedmioty', User::$przedmioty->get_all());
            Session::_set('umiejetnosci', User::$umiejetnosci->get_all());
        }
    }
}
