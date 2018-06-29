<?php

namespace Allocine\Twigcs\Rule;

use Allocine\Twigcs\Lexer;
use Allocine\Twigcs\Token;
use Allocine\Twigcs\Validator\Violation;

class CamelCaseVariable extends AbstractRule implements RuleInterface
{
    /**
     * {@inheritdoc}
     */
    public function check(\Twig_TokenStream $tokens)
    {
        $this->reset();

        while (!$tokens->isEOF()) {
            $token = $tokens->getCurrent();

            if ($token->getType() === \Twig_Token::NAME_TYPE && preg_match('/_/', $token->getValue())) {
                if ($tokens->look(Lexer::PREVIOUS_TOKEN)->getType() === Token::WHITESPACE_TYPE && $tokens->look(-2)->getValue() === 'set') {
                    $this->addViolation($tokens->getSourceContext()->getPath(), $token->getLine(), $token->columnno, sprintf('The "%s" variable should be in camelCase.', $token->getValue()));
                }
            }

            $tokens->next();
        }

        return $this->violations;
    }
}
