# JS Scanner

Created by Oscar Otero <http://oscarotero.com> <oom@oscarotero.com> (MIT License)

Javascript code scanner to use with [gettext/gettext](https://github.com/php-gettext/Gettext)

## Installation

```
composer require gettext/js-scanner
```

## Usage example

```php
use Gettext\Scanner\JsScanner;
use Gettext\Generator\PoGenerator;
use Gettext\Translations;

//Create a new scanner, adding a translation for each domain we want to get:
$jsScanner = new JsScanner(
    Translations::create('domain1'),
    Translations::create('domain2'),
    Translations::create('domain3')
);

//Scan files
foreach (glob('*.js') as $file) {
    $jsScanner->scanFile($file);
}

//Save the translations in .po files
$generator = new PoGenerator();

foreach ($jsScanner->getTranslations() as $translations) {
    $domain = $translations->getDomain();
    $generator->generateFile($translations, "locales/{$domain}.po");
}
```
