<?php

namespace App\Repositories\Functions;



class Strings
{
    static function plural($n, $form1, $form2, $form3)
    {
        $plural = ($n % 10 == 1 && $n % 100 != 11 ? 0 : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 or $n % 100 >= 20) ? 1 : 2));
        switch($plural) {
            case 0:
            default:
                return $form1;
            case 1:
                return $form2;
            case 2:
                return $form3;
        }
    }

    static function ru_date($format, $date = false)
    {
        setlocale(LC_ALL, 'ru_RU.cp1251');
        if ($date === false) {
            $date = time();
        }
        if ($format === '') {
            $format = '"%e" %bg %Y г.';
        }
        $months = explode("|", '|января|февраля|марта|апреля|мая|июня|июля|августа|сентября|октября|ноября|декабря');
        $format = preg_replace("~\%bg~", $months[date('n', $date)], $format);
        $res = strftime($format, $date);

        return $res;
    }

    static function prepare_company_name($name, $director)
    {
        if (strstr(mb_strtolower($name), 'ооо ')) {
            return 'Общество с ограниченной ответственностью ' . $director;
        } elseif (strstr(mb_strtolower($name), 'ип ')) {
            return 'Индивидуальный предприниматель ' . $director;
        } else {
            return $director;
        }
    }

    static function name_for_signature($name)
    {
        $name = explode(' ', $name);

        return $name[0] . ' ' . mb_substr($name[1], 0, 1) . '. ' . mb_substr($name[2], 0, 1) . '.';
    }

    static function number_to_str($value){
        if ((int)$value > 100) $value = (int)$value % 10;
        $array = [
            1=>'одного',
            2=>'двух',
            3=>'трёх',
            4=>'четырёх',
            5=>'пяти',
            6=>'шести',
            7=>'семи',
            8=>'восьми',
            9=>'девять',
            10=>'десяти',
            11=>'одиннадцати',
            12=>'двенадцати',
            13=>'тринадцати',
            14=>'четырнадцати',
            15=>'пятнадцати',
            16=>'шестнадцати',
            17=>'семнадцати',
            18=>'восемнадцати',
            19=>'девятнадцати',
            20=>'двадцати',
            30=>'тридцати',
            40=>'сорока',
            50=>'пятидесяти',
            60=>'шестидесяти',
            70=>'семидесяти',
            80=>'восьмидесяти',
            90=>'девяноста',
            100=>'ста',
        ];
        $value = (int) $value;

        if(isset($array[$value])) return $array[$value];

        $value2 = $value%100;


        if ($value >= $value2)
            $value2 = $value%10;

        return $array[$value-$value2].' '.$array[$value2];
    }
    static function str_price($value)
    {
        $number_price = $value;
        $value = explode('.', number_format($value, 2, '.', ''));

        $f = new \NumberFormatter('ru', \NumberFormatter::SPELLOUT);
        $str = $f->format($value[0]);

        // Первую букву в верхний регистр.
        $str = mb_strtoupper(mb_substr($str, 0, 1)) . mb_substr($str, 1, mb_strlen($str));

        // Склонение слова "рубль".
        $num = $value[0] % 100;
        if ($num > 19) {
            $num = $num % 10;
        }
        switch ($num) {
            case 1: $rub = 'рубль'; break;
            case 2:
            case 3:
            case 4: $rub = 'рубля'; break;
            default: $rub = 'рублей';
        }

        return $number_price.' ('.$str . ') ' . $rub . ' ' . $value[1] . ' копеек';
    }

    static function get_month_name(int $month)
    {
        $monthes = ['январе', 'феврале', "марте", "апреле", "мае", "июне","июле","августе","сентябре","октябре","ноябре","декабре"];
        return $monthes[$month-1];
    }
    static function translit($value)
    {
        $converter = [
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'e',
            'ж' => 'zh',
            'з' => 'z',
            'и' => 'i',
            'й' => 'y',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'c',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'sch',
            'ь' => '',
            'ы' => 'y',
            'ъ' => '',
            'э' => 'e',
            'ю' => 'yu',
            'я' => 'ya',

            'А' => 'A',
            'Б' => 'B',
            'В' => 'V',
            'Г' => 'G',
            'Д' => 'D',
            'Е' => 'E',
            'Ё' => 'E',
            'Ж' => 'Zh',
            'З' => 'Z',
            'И' => 'I',
            'Й' => 'Y',
            'К' => 'K',
            'Л' => 'L',
            'М' => 'M',
            'Н' => 'N',
            'О' => 'O',
            'П' => 'P',
            'Р' => 'R',
            'С' => 'S',
            'Т' => 'T',
            'У' => 'U',
            'Ф' => 'F',
            'Х' => 'H',
            'Ц' => 'C',
            'Ч' => 'Ch',
            'Ш' => 'Sh',
            'Щ' => 'Sch',
            'Ь' => '',
            'Ы' => 'Y',
            'Ъ' => '',
            'Э' => 'E',
            'Ю' => 'Yu',
            'Я' => 'Ya',
        ];
        $value = strtr($value, $converter);
        return $value;
    }
}
