<?php
namespace src\includes\functions;

trait FunctionsDate
{
    private function pokazDate($when, $poch = 0, $roz = 0)
    {
        $data = \DateTime::createFromFormat('Y-m-d H:i:s', $when);
        $data = $data->getTimestamp();
        $show = date("d", $data) . ' ';
        if($roz) {
            switch (date("m", $data)) {
                case 1:
                    $show .= 'Stycznia';
                    break;
                case 2:
                    $show .= 'Lutego';
                    break;
                case 3:
                    $show .= 'Marca';
                    break;
                case 4:
                    $show .= 'Kwietnia';
                    break;
                case 5:
                    $show .= 'Maja';
                    break;
                case 6:
                    $show .= 'Czerwca';
                    break;
                case 7:
                    $show .= 'Lipca';
                    break;
                case 8:
                    $show .= 'Sierpnia';
                    break;
                case 9:
                    $show .= 'Września';
                    break;
                case 10:
                    $show .= 'Października';
                    break;
                case 11:
                    $show .= 'Listopada';
                    break;
                case 12:
                    $show .= 'Grudnia';
                    break;
            }
        } else {
            switch (date("m", $data)) {
                case 1:
                    $show .= 'Sty';
                    break;
                case 2:
                    $show .= 'Lut';
                    break;
                case 3:
                    $show .= 'Mar';
                    break;
                case 4:
                    $show .= 'Kwi';
                    break;
                case 5:
                    $show .= 'Maj';
                    break;
                case 6:
                    $show .= 'Cze';
                    break;
                case 7:
                    $show .= 'Lip';
                    break;
                case 8:
                    $show .= 'Sier';
                    break;
                case 9:
                    $show .= 'Wrze';
                    break;
                case 10:
                    $show .= 'Paź';
                    break;
                case 11:
                    $show .= 'Lis';
                    break;
                case 12:
                    $show .= 'Gru';
                    break;
            }
        }
        $show.= ' '. date("Y", $data) . "\t";
        if($poch) $show .= '<span class="pochylenie">' . date("H:i", $data).'</span>';
        else $show .= date("H:i", $data);
        return $show;
    }
}