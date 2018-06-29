<?php

namespace Allocine\Twigcs\Test;

use Allocine\Twigcs\Lexer;
use Allocine\Twigcs\Ruleset\Trivago;
use Allocine\Twigcs\Validator\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Twigcs' main functional tests based on trivago twig styleguide
 *
 * @author Frank van Gemeren <frank.vangemeren@trivago.com>
 */
class TrivagoFunctionalTest extends TestCase
{
    /**
     * @dataProvider getData
     */
    public function testExpressions($expression, $expectedViolation)
    {
        $twig = new \Twig_Environment(new \Twig_Loader_Array());
        $twig->setLexer(new Lexer($twig));

        $validator = new Validator();

        $violations = $validator->validate(new Trivago(), $twig->tokenize(new \Twig_Source($expression, 'src', 'src.html.twig')));

        if ($expectedViolation) {
            $this->assertCount(1, $violations, sprintf("There should be exactly one violation in:\n %s", $expression));
            $this->assertSame($expectedViolation, $violations[0]->getReason());
        } else {
            $this->assertCount(0, $violations, sprintf("There should be no violations in:\n %s", $expression));
        }
    }

    public function getData()
    {
        return [
            // Do not put any spaces before and after the following operators: |, ., .., [].
            // Put exactly 1 space before and after |
            //['{{ foo|baz }}', 'There should be 1 space(s) after "|"'], // gets tested with 2 separate tests
            ['{{ foo |baz }}', 'There should be 1 space(s) after "|".'],
            ['{{ foo| baz }}', 'There should be 1 space(s) before "|".'],
            ['{{ foo  | baz }}', 'More than 1 space(s) found before "|".'],
            ['{{ foo |  baz }}', 'More than 1 space(s) found after "|".'],
            ['{{ foo | baz }}', null],
            ['{{ foo[0] }}', null],
            ['{{ foo[0] |bar }}', 'There should be 1 space(s) after "|".'],
            ['{{ foo[0]| bar }}', 'There should be 1 space(s) before "|".'],
            ['{{ foo() |baz }}', 'There should be 1 space(s) after "|".'],
            ['{{ foo() * 2 |baz }}', 'There should be 1 space(s) after "|".'],

            // Use camelCase-d variable names
            ['{% set foo = 1 %}{{ foo }}', null],
            ['{% set foo_bar = 1 %}{{ foo_bar }}', 'The "foo_bar" variable should be in camelCase.'],
            ['{% set fooBar = 1 %}{{ fooBar }}', null],

            // rest of the tests would be similar to the official tests, so not repeating them
        ];
    }
}
