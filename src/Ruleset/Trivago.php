<?php

namespace Allocine\Twigcs\Ruleset;

use Allocine\Twigcs\Rule;
use Allocine\Twigcs\Validator\Violation;
use Allocine\Twigcs\Whitelist\TokenWhitelist;

/**
 * The trivago twigcs ruleset
 *
 * @author Frank van Gemeren <frank.vangemeren@trivago.com>
 */
class Trivago implements RulesetInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRules()
    {
        return [
            new Rule\DelimiterSpacing(Violation::SEVERITY_ERROR, 1),
            new Rule\ParenthesisSpacing(Violation::SEVERITY_ERROR, 0, 1),
            new Rule\ArraySeparatorSpacing(Violation::SEVERITY_ERROR, 0, 1),
            new Rule\HashSeparatorSpacing(Violation::SEVERITY_ERROR, 0, 1),
            new Rule\OperatorSpacing(Violation::SEVERITY_ERROR, [
                '==', '!=', '<', '>', '>=', '<=',
                '+', '-', '/', '*', '%', '//', '**',
                'not', 'and', 'or',
                '~',
                'is', 'in'
            ], 1),
            new Rule\PunctuationSpacing(
                Violation::SEVERITY_ERROR,
                //['|', '.', '..', '[', ']'], // This has been changed to remove the |
                ['.', '..', '[', ']'],
                0,
                new TokenWhitelist([
                    ')',
                    \Twig_Token::NAME_TYPE,
                    \Twig_Token::NUMBER_TYPE,
                    \Twig_Token::STRING_TYPE
                ], [2])
            ),
            new Rule\TernarySpacing(Violation::SEVERITY_ERROR, 1),
            //new Rule\LowerCaseVariable(Violation::SEVERITY_ERROR),
            new Rule\UnusedVariable(Violation::SEVERITY_WARNING),
            new Rule\UnusedMacro(Violation::SEVERITY_WARNING),
            new Rule\SliceShorthandSpacing(Violation::SEVERITY_ERROR),
            new Rule\TrailingSpace(Violation::SEVERITY_ERROR),


            new Rule\CamelCaseVariable(Violation::SEVERITY_ERROR),

            // added rule to force 1 space before and after a |
            new Rule\PunctuationSpacing(
                Violation::SEVERITY_ERROR,
                ['|'],
                1
            ),
        ];
    }
}
