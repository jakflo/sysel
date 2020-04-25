<?php


namespace Sysel\Utils;


class Date_tools {
    public function en_date_na_cz(string $en_date, bool $without_year = false) {
        $date = strtotime($en_date);
        $format = $without_year? 'd.m.':'d.m.Y';
        return date($format, $date);
    }
}
