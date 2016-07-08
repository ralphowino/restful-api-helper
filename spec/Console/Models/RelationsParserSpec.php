<?php

namespace spec\Ralphowino\ApiStarter\Console\Models;

use PhpSpec\ObjectBehavior;

class RelationsParserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Ralphowino\ApiStarter\Console\Models\RelationsParser');
    }

    function it_parses_the_relations_into_an_array()
    {
        $relations = "user:belongsTo, interviews:hasMany";

        $this->parse($relations)->shouldBe(array(
            array('name' => 'user', 'relation' => 'belongsTo'),
            array('name' => 'interviews', 'relation' => 'hasMany')
        ));
    }
}