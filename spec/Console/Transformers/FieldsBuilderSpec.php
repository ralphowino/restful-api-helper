<?php

namespace spec\Ralphowino\ApiStarter\Console\Transformers;


use PhpSpec\ObjectBehavior;

class FieldsBuilderSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Ralphowino\ApiStarter\Console\Transformers\FieldsBuilder');
    }

    function it_should_generate_php_code_for_transformer_fields()
    {
        $parsedFields = array('name', 'priority', 'user');

        $this->create($parsedFields, 'task')->shouldBe(getFieldsStub());
    }
}

function getFieldsStub() {
    return <<<EOT
'name' => \$task->name,
            'priority' => \$task->priority,
            'user' => \$task->user,
EOT;
}