<?php
declare(strict_types = 1);

namespace Gettext\Scanner;

use Peast\Peast;
use Peast\Traverser;

class JsFunctionsScanner implements FunctionsScannerInterface
{
    protected $validFunctions;
    protected $parser = ['latest', []];

    public function __construct(array $validFunctions = null)
    {
        $this->validFunctions = $validFunctions;
    }

    public function parser(string $version, array $options): self
    {
        $this->parser = [$version, $options];

        return $this;
    }

    public function scan(string $code, string $filename = null): array
    {
        list($version, $options) = $this->parser;

        $ast = Peast::$version($code, $options)->parse();

        $traverser = new Traverser();
        $visitor = new JsNodeVisitor($filename, $this->validFunctions);
        $traverser->addFunction($visitor);
        $traverser->traverse($ast);

        return $visitor->getFunctions();
    }
}
