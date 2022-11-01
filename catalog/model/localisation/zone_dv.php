<?php
// *** Почта России - версия 3.1 ***
class ModelLocalisationZoneDv extends Model {
    public function getZone($zone_name) {
        foreach($zone_name as $key => $value){
            switch($value){
                case 'Москва': $send[$key] = '101000'; break;
                case 'Санкт-Петербург': $send[$key] = '190000'; break;
                case 'Алтайский край': $send[$key] = '656000'; break;
                case 'Амурская область': $send[$key] = '675000'; break;
                case 'Архангельская область': $send[$key] = '163000'; break;
                case 'Астраханская область': $send[$key] = '414000'; break;
                case 'Белгородская область': $send[$key] = '308000'; break;
                case 'Байконур': $send[$key] = '468320'; break;
                case 'Брянская область': $send[$key] = '241000'; break;
                case 'Владимирская область': $send[$key] = '600000'; break;
                case 'Волгоградская область': $send[$key] = '400000'; break;
                case 'Вологодская область': $send[$key] = '160000'; break;
                case 'Воронежская область': $send[$key] = '394000'; break;
                case 'Еврейская АО': $send[$key] = '679000'; break;
                case 'Забайкальский край': $send[$key] = '672000'; break;
                case 'Ивановская область': $send[$key] = '153000'; break;
                case 'Иркутская область': $send[$key] = '664000'; break;
                case 'Республика Кабардино-Балкария': $send[$key] = '360000'; break;
                case 'Калининградская область': $send[$key] = '236001'; break;
                case 'Калужская область': $send[$key] = '248000'; break;
                case 'Камчатская область': $send[$key] = '683000'; break;
                case 'Камчатский край': $send[$key] = '683000'; break;
                case 'Карачаево-Черкесская pecпублика': $send[$key] = '369000'; break;
                case 'Карачаево-Черкеcия': $send[$key] = '369000'; break;
                case 'Кемеровская область': $send[$key] = '650000'; break;
                case 'Кировская область': $send[$key] = '610000'; break;
                case 'Костромская область': $send[$key] = '156000'; break;
                case 'Краснодарский край': $send[$key] = '352000'; break;
                case 'Красноярский край': $send[$key] = '660000'; break;
                //case 'Крым': $send[$key] = /*'295000'*/'160000'; break;
                case 'Крым': $send[$key] = '295000'; break;
                case 'Курганская область': $send[$key] = '640000'; break;
                case 'Курская область': $send[$key] = '305000'; break;
                case 'Ленинградская область': $send[$key] = '187000'; break;
                case 'Липецкая область': $send[$key] = '398000'; break;
                case 'Магаданская область': $send[$key] = '685000'; break;
                case 'Московская область': $send[$key] = '140000'; break;
                case 'Мурманская область': $send[$key] = '183000'; break;
                case 'Ненецкий АО': $send[$key] = '166000'; break;
                case 'Нижегородская область': $send[$key] = '603000'; break;
                case 'Новгородская область': $send[$key] = '173000'; break;
                case 'Новосибирская область': $send[$key] = '630000'; break;
                case 'Омская область': $send[$key] = '644000'; break;
                case 'Оренбургская область': $send[$key] = '460000'; break;
                case 'Орловская область': $send[$key] = '302000'; break;
                case 'Пензенская область': $send[$key] = '440000'; break;
                case 'Пермский край': $send[$key] = '614000'; break;
                case 'Приморский край': $send[$key] = '690000'; break;
                case 'Псковская область': $send[$key] = '180000'; break;
                case 'Республика Адыгея': $send[$key] = '385000'; break;
                case 'Республика Алтай': $send[$key] = '649000'; break;
                case 'Республика Башкортостан': $send[$key] = '450000'; break;
                case 'Республика Бурятия': $send[$key] = '670000'; break;
                case 'Республика Дагестан': $send[$key] = '367000'; break;
                case 'Республика Ингушетия': $send[$key] = '386001'; break;
                case 'Республика Кабардино-Балкария': $send[$key] = '360000'; break;
                case 'Республика Калмыкия': $send[$key] = '358000'; break;
                case 'Республика Карелия': $send[$key] = '185000'; break;
                case 'Республика Крым': $send[$key] = '295000'; break;
                case 'Республика Коми': $send[$key] = '167000'; break;
                case 'Республика Марий Эл': $send[$key] = '424000'; break;
                case 'Республика Мордовия': $send[$key] = '430000'; break;
                case 'Республика Саха': $send[$key] = '677000'; break;
                case 'Республика Саха (Якутия)': $send[$key] = '677000'; break;
                case 'Республика Северная Осетия': $send[$key] = '362000'; break;
                case 'Республика Сев.Осетия-Алания': $send[$key] = '362000'; break;
                case 'Республика Татарстан': $send[$key] = '420000'; break;
                case 'Республика Тыва': $send[$key] = '667000'; break;
                case 'Республика Хакасия': $send[$key] = '655000'; break;
                case 'Ростовская область': $send[$key] = '344000'; break;
                case 'Рязанская область': $send[$key] = '390000'; break;
                case 'Самарская область': $send[$key] = '443000'; break;
                case 'Саратовская область': $send[$key] = '410000'; break;
                case 'Сахалинская область': $send[$key] = '693000'; break;
                case 'Свердловская область': $send[$key] = '620000'; break;
                case 'Севастополь': $send[$key] = '299001'; break;
                case 'Смоленская область': $send[$key] = '214000'; break;
                case 'Ставропольский край': $send[$key] = '355000'; break;
                case 'Таймырский АО': $send[$key] = '647000'; break;
                case 'Тамбовская область': $send[$key] = '392000'; break;
                case 'Тверская область': $send[$key] = '170000'; break;
                case 'Томская область': $send[$key] = '634000'; break;
                case 'Тульская область': $send[$key] = '300000'; break;
                case 'Тюменская область': $send[$key] = '625000'; break;
                case 'Удмуртская Республика': $send[$key] = '426000'; break;
                case 'Ульяновская область': $send[$key] = '432000'; break;
                case 'Хабаровский край': $send[$key] = '680000'; break;
                case 'Ханты-Мансийский АО': $send[$key] = '628000'; break;
                case 'Ханты-Мансийский АО - Югра': $send[$key] = '628000'; break;
                case 'Челябинская область': $send[$key] = '454000'; break;
                case 'Чеченская Республика': $send[$key] = '364000'; break;
                case 'Чеченская Республика (Грозный)': $send[$key] = '364000'; break;
                case 'Читинская область': $send[$key] = '672000'; break;
                case 'Чувашская Республика': $send[$key] = '428000'; break;
                case 'Чукотский АО': $send[$key] = '689000'; break;
                case 'Ямало-Ненецкий АО': $send[$key] = '629000'; break;
                case 'Ярославская область': $send[$key] = '150000'; break;
            }
        }

        if (isset($send) && count($send) > 0 ) {
            return $send;
        }
        else {
            return false;
        }
    }


    public function getRequest($data) {
        if (!isset($this->session->data['currency'])) {
            $this->session->data['currency'] = $this->config->get('config_currency');
        }


        $local_total = $data['local_total'];

        if ($this->session->data['currency'] != 'RUB') {
            // $local_total = $this->currency->convert($local_total, $this->session->data['currency'], 'RUB');
        }


        $url = 'http://api.postcalc.ru/';

        if ((isset($data['address']['iso_code_2']) and $data['address']['iso_code_2'] != 'RU') or (isset($data['address']['country_id']) and $data['address']['country_id'] != 176)) {
            if ($data['local_total'] > 50000) {
                $data['local_total'] = 50000;
            }
            $Request = $url . '?f=' . $data['from'] . '&t=' . $data['to'] . '&c=' . $data['address']['iso_code_2'] . '&w=' . $data['weight'] . '&v=' . $local_total . '&o=php&e=0&st=' . $data['server'] . '&ml=' . $this->config->get('config_email') . '&cs=utf-8&pn=' . $this->to_seo($this->config->get('config_owner')) . '&co=' . $data['co'] . '&p=' . $data['p'] . '&key=' . $this->config->get('config_postcalc_key');
        }
        else {
            if ($data['local_total'] > 100000) {
                $data['local_total'] = 100000;
            }
            $Request = $url . '?f=' . $data['from'] . '&t=' . $data['to'] . '&w=' . $data['weight'] . '&v=' . $local_total . '&o=php&e=0&st=' . $data['server'] . '&ml=' . $this->config->get('config_email') . '&cs=utf-8&pn=' . $this->to_seo($this->config->get('config_owner')) . '&co=' . $data['co'] . '&p=' . $data['p'] . '&key=' . $this->config->get('config_postcalc_key');
        }

        // echo "<-- Request=".$Request." -->";

        return $Request;
    }


    public function getResponse($Response) {
        //print_r($Response);
            if (substr($Response, 0, 3) == "\x1f\x8b\x08") {
                //$Response = gzinflate(substr($Response, 10, -8));
                $Response=gzinflate(substr($Response,10));
            }

            return unserialize($Response);
    }


    public function getPrice($arrResponse, $local) {
        $price = floor($arrResponse['Отправления'][$local['name']][$local['price']]);

        return $price;
    }


    public function getPriceNalozhka($arrResponse, $local) {
        $price = $arrResponse['Отправления'][$local['name']]['НаложенныйПлатеж'];

        return $price;
    }


    private function to_seo($s) {
        $s = $this->to_translit($s);
        $s = $this->to_lower($s);
        $s = str_replace("'", "", $s);
        $s = str_replace('"', "", $s);

        $s = trim($s);
        $s = str_replace("&quot;", "", $s);
        $s = str_replace("/", "_", $s);

        $s = preg_replace("/[-.,]/i", "_", $s);
        $s = preg_replace("/\s+/", "_", $s);
        $s = preg_replace("/_+/", "_", $s);
        $s = preg_replace('/([_!();:,.?])\\1+/', '$1', $s);

        $s = preg_replace("/[^a-z0-9_]/i","",$s);

        return $s;
    }


    private function to_lower($s) {
        $pattern = array(
            "'А'","'Б'","'В'","'Г'","'Д'","'Е'","'Ё'","'Ж'","'З'","'И'","'Й'","'К'","'Л'","'М'","'Н'","'О'","'П'","'Р'","'С'","'Т'","'У'","'Ф'","'Х'","'Ц'","'Ч'","'Ш'","'Щ'","'Ъ'","'Ы'","'Ь'","'Э'","'Ю'","'Я'",
            "'A'", "'B'", "'C'", "'D'", "'E'", "'F'", "'G'", "'H'", "'I'", "'J'", "'K'", "'L'", "'M'", "'N'", "'O'", "'P'", "'Q'", "'R'", "'S'", "'T'", "'U'", "'V'", "'W'", "'X'", "'Y'", "'Z'");

        $replacement = array(
            "а","б","в","г","д","е","ё","ж","з","и","й","к","л","м","н","о","п","р","с","т","у",
            "ф","х","ц","ч","ш","щ","ъ","ы","ь","э","ю","я",
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n",
            "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"
        );
        return preg_replace ($pattern, $replacement, $s);
    }


    private function to_translit($s) {
        $pattern = array(
            "'а'","'б'","'в'","'г'","'д'","'е'","'ё'","'ж'","'з'","'и'","'й'","'к'","'л'","'м'","'н'","'о'","'п'","'р'","'с'","'т'","'у'","'ф'","'х'","'ц'","'ч'","'ш'","'щ'","'ъ'","'ы'","'ь'","'э'","'ю'","'я'",
            "'А'","'Б'","'В'","'Г'","'Д'","'Е'","'Ё'","'Ж'","'З'","'И'","'Й'","'К'","'Л'","'М'","'Н'","'О'","'П'","'Р'","'С'","'Т'","'У'","'Ф'","'Х'","'Ц'","'Ч'","'Ш'","'Щ'","'Ъ'","'Ы'","'Ь'","'Э'","'Ю'","'Я'" );
        $replacement = array(
            "a", "b", "v", "g", "d", "e", "yo", "zh", "z", "i", "j", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "h", "c", "ch", "sh", "sch","", "y", "", "e", "yu", "ya",
            "A", "B", "V", 'G', "D", "E", "Yo", "Zh", "Z", "I", "J", "K", "L", "M", "N", "O", "P", "R", "S", "T", "U", "F", "H", "C", "Ch", "Sh", "Sch","", "Y", "", "E", "Ju", "Ya" );
        return preg_replace ($pattern, $replacement, $s);
    }
}
?>