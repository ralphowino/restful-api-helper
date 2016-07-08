<?php

namespace spec\Ralphowino\ApiStarter\Console\Models;

use PhpSpec\ObjectBehavior;

class RelationsBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Ralphowino\ApiStarter\Console\Models\RelationsBuilder');
    }

    function it_builds_the_array_of_includes_into_php_code()
    {
        $includes = array(
            array('name' => 'user', 'relation' => 'belongsTo'),
            array('name' => 'interviews', 'relation' => 'hasMany')
        );

        $this->create($includes)->shouldBe(getRelationsMethods());
    }
}

function getRelationsMethods() 
{
    return <<<EOT

    /**
     * Links to it's user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
     public function user()
     {
        return \$this->belongsTo(User::class);
     }

    /**
     * Links to it's interviews
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
     public function interviews()
     {
        return \$this->hasMany(Interview::class);
     }
EOT;
}