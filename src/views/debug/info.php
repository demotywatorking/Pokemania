<?php
if (!src\libs\Session::_get('admin')) {
    exit;
} else {
    echo '<div class="clearfix"></div></div><div class="panel panel-success jeden_ttlo"><div class="panel-heading"><span>DEBUG INFO</span></div><div class="panel-body">';

    echo '<div class="panel panel-success jeden_ttlo"><div class="panel-heading"><span>GET</span></div><div class="panel-body">';
    foreach (src\libs\Debug::$get as $key => $value) {
        echo $key . '<br />';
        echo '<pre>' . $value . '</pre>';
    }
    echo '</div></div>';

    echo '<div class="panel panel-success jeden_ttlo"><div class="panel-heading"><span>POST</span></div><div class="panel-body">';
    foreach (src\libs\Debug::$post as $key => $value) {
        echo $key . '<br />';
        echo '<pre>' . $value . '</pre>';
    }
    echo '</div></div>';

    echo '<div class="panel panel-success jeden_ttlo"><div class="panel-heading"><span>INFO</span></div><div class="panel-body">';
    foreach (src\libs\Debug::$info as $key => $value) {
        echo $key . '<br />';
        echo '<pre>' . $value . '</pre>';
    }
    echo 'MODE : <pre>'.src\libs\Debug::$mode.'</pre><br />';
    echo '</div></div>';

    echo '</div></div>';
}
?>