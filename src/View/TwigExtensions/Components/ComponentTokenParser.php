<?php

namespace Sitefrog\View\TwigExtensions\Components;

use Sitefrog\Facades\ComponentManager;
use Twig\Node\Expression\AbstractExpression;
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Expression\NameExpression;
use Twig\Node\Node;
use Twig\Node\TextNode;
use Twig\Token;
use Twig\TokenParser\IncludeTokenParser;

class ComponentTokenParser extends IncludeTokenParser
{
    public function parse(Token $token): Node
    {
        $stream = $this->parser->getStream();
        $parent = $this->parser->getExpressionParser()->parseExpression();
        [$variables, $only] = $this->parseArguments();
        if (null === $variables) {
            $variables = new ArrayExpression([], $parent->getTemplateLine());
        }

        $componentName = $this->getComponentName($parent);
        try {
            $component = ComponentManager::get($componentName);
        } catch (\Exception $e) {
            $this->parser->parse($stream, [$this, 'decideBlockEnd'], true);
            $stream->expect(Token::BLOCK_END_TYPE);
            return new TextNode('ERROR: '.$e->getMessage(), $token->getLine());
        }

        $parentToken = new Token(Token::STRING_TYPE, $component::getTemplate(), $token->getLine());
        $fakeParentToken = new Token(Token::STRING_TYPE, '__parent__', $token->getLine());

        // inject a fake parent to make the parent() function work
        $stream->injectTokens([
            new Token(Token::BLOCK_START_TYPE, '', $token->getLine()),
            new Token(Token::NAME_TYPE, 'extends', $token->getLine()),
            $parentToken,
            new Token(Token::BLOCK_END_TYPE, '', $token->getLine()),
        ]);

        $module = $this->parser->parse($stream, [$this, 'decideBlockEnd'], true);
        if ($fakeParentToken === $parentToken) {
            $module->setNode('parent', $parent);
        }

        $this->parser->embedTemplate($module);
        $stream->expect(Token::BLOCK_END_TYPE);

        return new ComponentNode($componentName, $module->getTemplateName(), $module->getAttribute('index'), $variables, $only, $token->getLine(), $this->getTag());
    }

    public function decideBlockEnd(Token $token): bool
    {
        return $token->test('endcomponent');
    }

    public function getTag(): string
    {
        return 'component';
    }


    private function getComponentName(AbstractExpression $expression): string
    {
        if ($expression instanceof ConstantExpression) {
            return $expression->getAttribute('value');
        }

        if ($expression instanceof NameExpression) {
            return $expression->getAttribute('name');
        }

        throw new \InvalidArgumentException('todo');
    }
}
