# Number Converter for Laravel

A Laravel package to convert **numbers written in words** into **numeric values**.

For example:

- `"two hundred million"` → `200000000`
- `"Three Hundred Thousand"` → `300000`

---

## 📦 Installation

This package is not published on Packagist yet.  
You can install it directly from **GitHub**.

### 1. Add Repository in `composer.json`

Open your Laravel project’s `composer.json` and add the following to the `repositories` array:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/JayenKB/number-converter.git"
    }
]
```

### 2. Require the package

Run:

```bash
composer require jayen/number-converter:^1.0
```

### 3. Configuration

Laravel supports auto-discovery.  
If it does not work, register the service provider and alias manually in `config/app.php`:

```php
'providers' => [
    Jayen\NumberConverter\NumberConverterServiceProvider::class,
],

'aliases' => [
    'NumberConverter' => Jayen\NumberConverter\Facades\NumberConverter::class,
],
```

### 4. Usage

**Using the Facade:**

```php
use NumberConverter;

$number = NumberConverter::toNumber("One Thousand Two Hundred Thirty Four");
// Output: 1234
```

**Using dependency injection:**

```php
use Jayen\NumberConverter\NumberConverter;

public function convert(NumberConverter $converter)
{
    return $converter->toNumber("Five Million Six Hundred");
    // Output: 5600000
}
```

---

## 👨‍💻 Author

**Jayen Patel**  
📧 jayenbambharoliya@gmail.com

## 📜 License

This package is open-sourced software licensed under the [MIT license](LICENSE).
