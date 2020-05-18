<?php

namespace Sysel\Utils;
use Sysel\conf\Exceptions\Array_tool_exception;

class Array_tools {
    
    /*
     * prohledá 2d array a vrátí array, kde ve sloupcích $kde jsou hodoty $co;
     */
    public function hledej_ve_vicepoli(array $input, $co, string $kde) {
        $result = array();
        foreach ($input as $row) {
            if ($row[$kde] == $co) {
                $result[] = $row;
            }
        }
        return $result;
    }
    
    /*
     * převede vícepole na asoc. 1d pole, kde klíče jsou hodnoty sloupců $klic a hodnoty $hodnota
     */
    public function vicepole_na_value_asoc(array $input, string $klic, string $hodnota) {
        $result = array();
        foreach ($input as $row) {
            if (isset($row[$klic])) {
                $result[$row[$klic]] = $row[$hodnota];
            }
        }
        return $result;
    }
    
    /*
     * převede vícepole na asoc. 2d pole, kde klíče jsou hodnoty sloupců $klic
     */
    public function vicepole_na_2d_asoc(array $input, string $klic) {
        $result = array();
        foreach ($input as $row) {
            if (isset($row[$klic])) {
                $result[$row[$klic]] = $row;
            }
        }
        return $result;
    }
    
    /*
     * změní null hodnoty v poli na prázdné řetězce
     */
    public function null_na_empty_string(array $input) {
        foreach ($input as &$v) {
            $v = $v == null? '':$v;
        }
        return $input;
    }
    public function null_na_empty_string_vicepole(array $input) {
        foreach ($input as &$v) {
            $v = $this->null_na_empty_string($v);
        }
        return $input;
    }
    
    /*
     * imploduje prostý array do řetězce, tak aby jej šlo použit do IN() termu DB dotazu
     * @var quotes = zda má hodnoty ohraničit měkkýma úvozovkama
     * !!ACHTUNG!! Nepoužívat u hodnot náchylných k SQL injection (k tomu je Protected_in)
     */
    public function implode_pro_in(array $input, bool $quotes = false) {
        if (count($input) == 0) {
            throw new Array_tool_exception('Pole pro IN nemuze byt prazdne');
        }
        if (!$quotes) {
            return implode(',', $input);
        }
        else {
            return "'" . implode("','", $input) . "'";
        }        
    }
    
    public function asoc_na_uri(array $input) {
        $out = array();
        foreach ($input as $k=>$v) {
            $v = urlencode($v);
            $out[] = "{$k}={$v}";
        }
        return implode('&', $out);
    }
}
