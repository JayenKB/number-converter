<?php

namespace Jayen\NumberConverter;

class NumberConverter
{
    private const CURRENCIES = [
        'INR' => ['symbol' => '₹', 'decimals' => 2, 'format' => 'indian', 'major' => ['rupee', 'rupees'], 'minor' => ['paise', 'paise']],
        'USD' => ['symbol' => '$', 'decimals' => 2, 'format' => 'international', 'major' => ['dollar', 'dollars'], 'minor' => ['cent', 'cents']],
        'EUR' => ['symbol' => '€', 'decimals' => 2, 'format' => 'international', 'major' => ['euro', 'euros'], 'minor' => ['cent', 'cents']],
        'GBP' => ['symbol' => '£', 'decimals' => 2, 'format' => 'international', 'major' => ['pound', 'pounds'], 'minor' => ['penny', 'pence']],
        'JPY' => ['symbol' => '¥', 'decimals' => 0, 'format' => 'international', 'major' => ['yen', 'yen'], 'minor' => ['', '']],
        'AUD' => ['symbol' => 'A$', 'decimals' => 2, 'format' => 'international', 'major' => ['dollar', 'dollars'], 'minor' => ['cent', 'cents']],
        'CAD' => ['symbol' => 'C$', 'decimals' => 2, 'format' => 'international', 'major' => ['dollar', 'dollars'], 'minor' => ['cent', 'cents']],
        'CNY' => ['symbol' => '¥', 'decimals' => 2, 'format' => 'international', 'major' => ['yuan', 'yuan'], 'minor' => ['fen', 'fen']],
        'AED' => ['symbol' => 'د.إ ', 'decimals' => 2, 'format' => 'international', 'major' => ['dirham', 'dirhams'], 'minor' => ['fil', 'fils']],
    ];

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
            'lakh'=>100000,
            'lakhs'=>100000,
            'crore'=>10000000,
            'crores'=>10000000,
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

    public static function formatCurrency($number, string $currencyCode = 'INR', bool $withSymbol = true, ?int $decimals = null): string
    {
        if (!is_numeric($number)) {
            throw new \InvalidArgumentException("Input must be a number");
        }

        $currencyCode = strtoupper(trim($currencyCode));

        if (!isset(self::CURRENCIES[$currencyCode])) {
            throw new \InvalidArgumentException("Unsupported currency code: {$currencyCode}");
        }

        $currency = self::CURRENCIES[$currencyCode];
        $decimals ??= $currency['decimals'];

        if ($decimals < 0) {
            throw new \InvalidArgumentException("Decimals must be zero or greater");
        }

        $negative = $number < 0 ? '-' : '';
        $amount = number_format(abs((float) $number), $decimals, '.', '');
        [$integer, $fraction] = array_pad(explode('.', $amount, 2), 2, '');
        $formatted = $currency['format'] === 'indian'
            ? self::formatIndianNumber($integer)
            : number_format((int) $integer, 0, '.', ',');

        if ($decimals > 0) {
            $formatted .= '.' . $fraction;
        }

        return $negative . ($withSymbol ? $currency['symbol'] : $currencyCode . ' ') . $formatted;
    }

    public static function numberToCurrencyWords($number, string $currencyCode = 'INR', bool $includeMinorUnit = true): string
    {
        if (!is_numeric($number)) {
            throw new \InvalidArgumentException("Input must be a number");
        }

        $currencyCode = strtoupper(trim($currencyCode));

        if (!isset(self::CURRENCIES[$currencyCode])) {
            throw new \InvalidArgumentException("Unsupported currency code: {$currencyCode}");
        }

        $currency = self::CURRENCIES[$currencyCode];
        $amount = number_format(abs((float) $number), $currency['decimals'], '.', '');
        [$majorAmount, $minorAmount] = array_pad(explode('.', $amount, 2), 2, '0');

        $majorAmount = (int) $majorAmount;
        $minorAmount = (int) $minorAmount;
        $words = self::numberWordsByFormat($majorAmount, $currency['format']) . ' ' . self::currencyUnit($majorAmount, $currency['major']);

        if ($includeMinorUnit && $currency['decimals'] > 0 && $minorAmount > 0) {
            $words .= ' and ' . self::numberToWords($minorAmount) . ' ' . self::currencyUnit($minorAmount, $currency['minor']);
        }

        return $number < 0 ? 'negative ' . $words : $words;
    }

    private static function currencyUnit(int $amount, array $units): string
    {
        return $amount === 1 ? $units[0] : $units[1];
    }

    private static function numberWordsByFormat(int $number, string $format): string
    {
        return $format === 'indian'
            ? self::numberToIndianWords($number)
            : self::numberToWords($number);
    }

    private static function formatIndianNumber(string $integer): string
    {
        $lastThree = substr($integer, -3);
        $leading = substr($integer, 0, -3);

        if ($leading === '') {
            return $lastThree;
        }

        return preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $leading) . ',' . $lastThree;
    }

    private static function numberToIndianWords($number): string
    {
        if (!is_numeric($number)) {
            throw new \InvalidArgumentException("Input must be a number");
        }

        $number = (int) $number;

        if ($number < 0) {
            return 'negative ' . self::numberToIndianWords(abs($number));
        }

        if ($number < 100000) {
            return self::numberToWords($number);
        }

        $scales = [
            10000000 => 'crore',
            100000 => 'lakh',
            1000 => 'thousand',
        ];

        foreach ($scales as $scale => $scaleName) {
            if ($number >= $scale) {
                $baseUnits = intdiv($number, $scale);
                $remainder = $number % $scale;
                $words = self::numberToIndianWords($baseUnits) . ' ' . $scaleName;

                if ($remainder === 0) {
                    return $words;
                }

                return $words . ', ' . self::numberToIndianWords($remainder);
            }
        }

        return self::numberToWords($number);
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
