<?php

namespace Jayen\NumberConverter;

class NumberConverter
{
    public function wordsToNumber(string $words): int
    {
        $words = strtolower(trim($words));

        $units = [
            'zero'=>0,'one'=>1,'two'=>2,'three'=>3,'four'=>4,'five'=>5,
            'six'=>6,'seven'=>7,'eight'=>8,'nine'=>9,'ten'=>10,
            'eleven'=>11,'twelve'=>12,'thirteen'=>13,'fourteen'=>14,
            'fifteen'=>15,'sixteen'=>16,'seventeen'=>17,'eighteen'=>18,'nineteen'=>19
        ];

        $tens = [
            'twenty'=>20,'thirty'=>30,'forty'=>40,'fifty'=>50,
            'sixty'=>60,'seventy'=>70,'eighty'=>80,'ninety'=>90
        ];

        $scales = [
            'hundred'=>100,
            'thousand'=>1000,
            'million'=>1000000,
            'billion'=>1000000000,
        ];

        $current = 0;
        $result = 0;

        foreach (preg_split('/[\s-]+/', $words) as $word) {
            if (isset($units[$word])) {
                $current += $units[$word];
            } elseif (isset($tens[$word])) {
                $current += $tens[$word];
            } elseif ($word === "hundred") {
                $current *= 100;
            } elseif (isset($scales[$word])) {
                $current *= $scales[$word];
                $result += $current;
                $current = 0;
            }
        }

        return $result + $current;
    }

    public static function numberToWords($number)
    {
        if (!is_numeric($number)) {
            throw new \InvalidArgumentException("Input must be a number");
        }

        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = [
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'forty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
        ];

        if ($number < 0) {
            return $negative . self::numberToWords(abs($number));
        }

        if ($number < 21) {
            return $dictionary[$number];
        } elseif ($number < 100) {
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            return $dictionary[$tens] . (($units) ? $hyphen . $dictionary[$units] : '');
        } elseif ($number < 1000) {
            $hundreds  = (int) ($number / 100);
            $remainder = $number % 100;
            return $dictionary[$hundreds] . ' ' . $dictionary[100] . ($remainder ? $conjunction . self::numberToWords($remainder) : '');
        } else {
            $baseUnit   = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder  = $number % $baseUnit;

            return self::numberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit] . ($remainder ? $separator . self::numberToWords($remainder) : '');
        }
    }
}
