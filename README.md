# Number Converter for Laravel

A Laravel package to convert **numbers written in words** into **numeric values**.

For example:

- `"two hundred million"` → `200000000`
- `"Three Hundred Thousand"` → `300000`

---

## 📦 Installation

This package is published on Packagist. You can install it using Composer.

### 1. Require the package

Run:

```bash
composer require jayen/number-converter
```

### 2. Usage

**Using the Facade:**

```php
use Jayen\NumberConverter\Facades\NumberConverter;

$number = NumberConverter::wordsToNumber("One Thousand Two Hundred Thirty Four");
// Output: 1234
```

**Using dependency injection:**

```php
use Jayen\NumberConverter\NumberConverter;

public function convert(NumberConverter $converter)
{
    return $converter->wordsToNumber("Five Million Six Hundred");
    // Output: 5600000
}
```

---

## 👨‍💻 Author

**Jayen Bambhroliya**  
📧 jayenbambharoliya@gmail.com

## 📜 License

This package is open-sourced software licensed under the [MIT license](LICENSE).
