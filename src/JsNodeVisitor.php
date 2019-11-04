<?php
declare(strict_types = 1);

namespace Gettext\Scanner;

use Peast\Syntax\Node\CallExpression;
use Peast\Syntax\Node\Comment;
use Peast\Syntax\Node\Node;

class JsNodeVisitor
{
    protected $validFunctions;
    protected $filename;
    protected $functions = [];

    public function __construct(string $filename, array $validFunctions = null)
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

        static::addComments($function, $node->getCallee());

        foreach ($node->getArguments() as $argument) {
            switch ($argument->getType()) {
                case 'Literal':
                    $function->addArgument($argument->getValue());
                    static::addComments($function, $argument);
                    break;
                case 'TemplateLiteral':
                    if ($argument->getExpressions()) {
                        $function->addArgument();
                        break;
                    }

                    $quasis = $argument->getQuasis();
                    $quasis = array_shift($quasis);
                    $function->addArgument($quasis->getValue());
                    static::addComments($function, $argument);
                    break;
                default:
                    $function->addArgument();
            }
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

    protected static function addComments(ParsedFunction $function, Node $node): void
    {
        foreach ($node->getLeadingComments() as $comment) {
            $function->addComment(static::getComment($comment));
        }
    }

    protected static function getComment(Comment $comment): string
    {
        $text = $comment->getText();

        $lines = array_map(function ($line) {
            $line = ltrim($line, "#*/ \t");
            $line = rtrim($line, "#*/ \t");
            return trim($line);
        }, explode("\n", $text));

        return trim(implode("\n", $lines));
    }
}
