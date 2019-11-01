<?php
declare(strict_types = 1);

namespace Gettext\Scanner;

use Peast\Syntax\Node\CallExpression;
use Peast\Syntax\Node\Node;

class JsNodeVisitor
{
    protected $validFunctions;
    protected $filename;
    protected $functions = [];

    public function __construct(string $filename = null, array $validFunctions = null)
    {
        $this->filename = $filename;
        $this->validFunctions = $validFunctions;
    }

    public function __invoke(Node $node)
    {
        if ($node->getType() === 'CallExpression') {
            $function = $this->createFunction($node);

            if ($function) {
                $this->functions[] = $function;
            }
        }
    }

    public function getFunctions(): array
    {
        return $this->functions;
    }

    protected function createFunction(CallExpression $node): ?ParsedFunction
    {
        $name = static::getFunctionName($node);

        if (empty($name) || ($this->validFunctions !== null && !in_array($name, $this->validFunctions))) {
            return null;
        }

        $position = $node->getLocation();

        $function = new ParsedFunction(
            $name,
            $this->filename,
            $position->getStart()->getLine(),
            $position->getEnd()->getLine()
        );

        foreach ($node->getArguments() as $argument) {
            switch ($argument->getType()) {
                case 'Literal':
                    $function->addArgument($argument->getValue());
                    break;
                default:
                    $function->addArgument();
            }
        }

        foreach ($node->getLeadingComments() as $comment) {
            $function->addComment($comment->getText());
        }

        return $function;
    }

    protected static function getFunctionName(CallExpression $node): ?string
    {
        $callee = $node->getCallee();

        switch ($callee->getType()) {
            case 'Identifier':
                return $callee->getName();
            case 'MemberExpression':
                return $callee->getProperty()->getName();
            default:
                return null;
        }
    }
}
