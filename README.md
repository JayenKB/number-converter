# Number Converter for Laravel

A Laravel package to convert **numbers written in words** into **numeric values**, and vice versa.

## ✨ Features

- Convert `"two hundred million"` → `200000000`
- Convert `"Three Hundred Thousand"` → `300000`
- Convert `200000000` → `"two hundred million"`
- Convert `300000` → `"Three Hundred Thousand"`

---

## 📦 Installation

Install via [Packagist](https://packagist.org/packages/jayen/number-converter) using Composer:

```bash
composer require jayen/number-converter
```

---

## 🚀 Usage

### Using the Facade

```php
use Jayen\NumberConverter\Facades\NumberConverter;

$number = NumberConverter::wordsToNumber("One Thousand Two Hundred Thirty Four");
// Output: 1234

$words = NumberConverter::numberToWords("1234");
// Output: one thousand, two hundred and thirty-four
```

### Using Dependency Injection

```php
use Jayen\NumberConverter\NumberConverter;

public function convert(NumberConverter $converter)
{
    $number = $converter->wordsToNumber("Five Million Six Hundred");
    // Output: 5600000

    $words = $converter->numberToWords("5600000");
    // Output: Five Million Six Hundred
}
```

---

## 📜 License

This package is open-sourced software licensed under the [MIT license](LICENSE).
