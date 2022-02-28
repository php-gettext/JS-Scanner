<?php
declare(strict_types = 1);

namespace Gettext\Scanner;

use Gettext\Translations;

/**
 * Class to scan PHP files and get gettext translations
 */
class JsScanner extends CodeScanner
{
    use FunctionsHandlersTrait;

    protected $functions = [
        'gettext' => 'gettext',
        '__' => 'gettext',
        '_' => 'gettext',
        'ngettext' => 'ngettext',
        'n__' => 'ngettext',
        'pgettext' => 'pgettext',
        'p__' => 'pgettext',
        'dgettext' => 'dgettext',
        'd__' => 'dgettext',
        'dngettext' => 'dngettext',
        'dn__' => 'dngettext',
        'dpgettext' => 'dpgettext',
        'dp__' => 'dpgettext',
        'npgettext' => 'npgettext',
        'np__' => 'npgettext',
        'dnpgettext' => 'dnpgettext',
        'dnp__' => 'dnpgettext',
        'noop' => 'gettext',
        'noop__' => 'gettext',
    ];

    public function getFunctionsScanner(): FunctionsScannerInterface
    {
        return new JsFunctionsScanner(array_keys($this->functions));
    }
}
