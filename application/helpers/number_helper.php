<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Number Helper
 */
class Number_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    /**
     * Return a Number's Text representation
     * @param  int $number         Number
     * @return string              Number's Text representation
     */
    public static function numberToText($number)
    {
        $ci =& get_instance();
        $ci->lang->load('number');

        //list($number, $dec) = explode(".", (float) $number);
        $dec = '';

        $output = "";

        if($number{0} == "-") {
            $output = $ci->lang->line("number_negative") . " ";
            $number = ltrim($number, "-");
        } elseif($number{0} == "+") {
            $output = $ci->lang->line("number_positive") . " ";
            $number = ltrim($number, "+");
        }

        if($number{0} == "0") {
            $output .= $ci->lang->line("number_zero");
        } else {
            $number = str_pad($number, 36, "0", STR_PAD_LEFT);
            $group = rtrim(chunk_split($number, 3, " "), " ");
            $groups = explode(" ", $group);

            $groups2 = array();
            foreach($groups as $g) {
                $groups2[] = self::convertThreeDigit($g{0}, $g{1}, $g{2});
            }

            for($z = 0; $z < count($groups2); $z++) {
                if($groups2[$z] != "") {
                    $output .= $groups2[$z] . self::convertGroup(11 - $z).($z < 11 && !array_search('', array_slice($groups2, $z + 1, -1)) && $groups2[11] != '' && $groups[11]{0} == '0' ? " " . $ci->lang->line("number_and") . " " : ", ");
                }
            }

            $output = rtrim($output, ", ");
        }

        if($dec > 0)
        {
        $output .= " " . $ci->lang->line("number_zero");
        for($i = 0; $i < strlen($dec); $i++) $output .= " ".self::convertDigit($dec{$i});
        }

        return $output;
    }

    /**
     * Convert a number group
     * @param  int $index    Value
     * @return string        Number Group
     */
    public static function convertGroup($index)
    {
        $ci =& get_instance();

        switch($index) {
            case 11:
                return " " . $ci->lang->line("number_decillion");
            case 10:
                return " " . $ci->lang->line("number_nonillion");
            case 9:
                return " " . $ci->lang->line("number_octillion");
            case 8:
                return " " . $ci->lang->line("number_septillion");
            case 7:
                return " " . $ci->lang->line("number_sextillion");
            case 6:
                return " " . $ci->lang->line("number_quintrillion");
            case 5:
                return " " . $ci->lang->line("number_quadrillion");
            case 4:
                return " " . $ci->lang->line("number_trillion");
            case 3:
                return " " . $ci->lang->line("number_billion");
            case 2:
                return " " . $ci->lang->line("number_million");
            case 1:
                return " " . $ci->lang->line("number_thousand");
        }

        return "";
    }

    /**
     * Convert a 3 digit number
     * @param  int $dig1    Digit 1
     * @param  int $dig2    Digit 2
     * @param  int $dig3    Digit 3
     * @return string       Output
     */
    public static function convertThreeDigit($dig1, $dig2, $dig3)
    {
        $ci =& get_instance();

        $output = "";

        if($dig1 == "0" && $dig2 == "0" && $dig3 == "0") {
            return "";
        }

        if($dig1 != "0") {
            $output .= self::convertDigit($dig1)." " . $ci->lang->line("number_hundred");
            if($dig2 != "0" || $dig3 != "0") {
                $output .= " " . $ci->lang->line("number_and") . " ";
            }
        }

        if($dig2 != "0") {
            $output .= self::convertTwoDigit($dig2, $dig3);
        } elseif($dig3 != "0") {
            $output .= self::convertDigit($dig3);
        }

        return $output;
    }

    /**
     * Convert a 2 digit number
     * @param  int $dig1    Digit 1
     * @param  int $dig2    Digit 2
     * @return string       Output
     */
    public static function convertTwoDigit($dig1, $dig2)
    {
        $ci =& get_instance();

        if($dig2 == "0") {
            switch($dig1) {
                case "1":
                    return $ci->lang->line("number_ten");
                case "2":
                    return $ci->lang->line("number_twenty");
                case "3":
                    return $ci->lang->line("number_thirty");
                case "4":
                    return $ci->lang->line("number_forty");
                case "5":
                    return $ci->lang->line("number_fifty");
                case "6":
                    return $ci->lang->line("number_sixty");
                case "7":
                    return $ci->lang->line("number_seventy");
                case "8":
                    return $ci->lang->line("number_eighty");
                case "9":
                    return $ci->lang->line("number_ninety");
            }
        } elseif($dig1 == "1") {
            switch($dig2) {
                case "1":
                    return $ci->lang->line("number_eleven");
                case "2":
                    return $ci->lang->line("number_twelve");
                case "3":
                    return $ci->lang->line("number_thirteen");
                case "4":
                    return $ci->lang->line("number_fourteen");
                case "5":
                    return $ci->lang->line("number_fifteen");
                case "6":
                    return $ci->lang->line("number_sixteen");
                case "7":
                    return $ci->lang->line("number_seventeen");
                case "8":
                    return $ci->lang->line("number_eighteen");
                case "9":
                    return $ci->lang->line("number_nineteen");
            }
        } else {
            $temp = self::convertDigit($dig2);
            switch($dig1) {
                case "2":
                    return $ci->lang->line("number_twenty") . "-$temp";
                case "3":
                    return $ci->lang->line("number_thirty") . "-$temp";
                case "4":
                    return $ci->lang->line("number_forty") . "-$temp";
                case "5":
                    return $ci->lang->line("number_fifty") . "-$temp";
                case "6":
                    return $ci->lang->line("number_sixty") . "-$temp";
                case "7":
                    return $ci->lang->line("number_seventy") . "-$temp";
                case "8":
                    return $ci->lang->line("number_eighty") . "-$temp";
                case "9":
                    return $ci->lang->line("number_ninety") . "-$temp";
            }
        }
    }

    /**
     * Convert a 1 digit number
     * @param  int $digit   Digit
     * @return string       Output
     */
    public static function convertDigit($digit)
    {
        switch($digit) {
            case "0":
                return "zero";
            case "1":
                return "one";
            case "2":
                return "two";
            case "3":
                return "three";
            case "4":
                return "four";
            case "5":
                return "five";
            case "6":
                return "six";
            case "7":
                return "seven";
            case "8":
                return "eight";
            case "9":
                return "nine";
        }
    }
}







?>