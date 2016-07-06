<?php

namespace spec\Ralphowino\ApiStarter\Console\Transformers;

use PhpSpec\ObjectBehavior;

class FieldsParserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Ralphowino\ApiStarter\Console\Transformers\FieldsParser');
    }

    function it_parses_the_fields_to_an_array_of_fields()
    {
        $fields = "name, user, comments";

        $this->parse($fields)->shouldBe(array('name', 'user', 'comments'));
    }
}