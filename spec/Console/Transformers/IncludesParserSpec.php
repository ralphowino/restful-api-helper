<?php

namespace spec\Ralphowino\ApiStarter\Console\Transformers;

use PhpSpec\ObjectBehavior;

class IncludesParserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Ralphowino\ApiStarter\Console\Transformers\IncludesParser');
    }

    function it_parses_includes_to_an_array_of_the_names_and_types_of_includes()
    {
        $includes = "user:item, interviews:collection, comments, admin";

        $this->parse($includes)->shouldBe(array(
            array('name' => 'user', 'type' => 'item'),
            array('name' => 'interviews', 'type' => 'collection'),
            array('name' => 'comments', 'type' => 'collection'),
            array('name' => 'admin', 'type' => 'item'),
        ));
    }

}