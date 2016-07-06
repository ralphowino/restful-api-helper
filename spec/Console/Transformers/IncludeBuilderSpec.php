<?php

namespace spec\Ralphowino\ApiStarter\Console\Transformers;

use PhpSpec\ObjectBehavior;

class IncludeBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Ralphowino\ApiStarter\Console\Transformers\IncludeBuilder');
    }

    function it_builds_the_array_of_includes_into_php_code()
    {
        $includes = array(
            array('name' => 'user', 'type' => 'item'),
            array('name' => 'interviews', 'type' => 'collection'),
            array('name' => 'comments', 'type' => 'collection'),
            array('name' => 'admin', 'type' => 'item'),
        );

        $this->create($includes, 'task')['DummyTransformerIncludes']->shouldBe("'user','interviews','comments','admin'");
        $this->create($includes, 'task')['DummyTransformerIncludesMethods']->shouldBe(getIncludeMethods());
    }
}

function getIncludeMethods()
{
    return <<<EOT

    /**
     * Include User
     *
     * @param Task \$task
     * @return \League\Fractal\Resource\item
     */
    public function includeUser(Task \$task)
    {
        \$user = \$task->user;
        return \$this->item(\$user, new UserTransformer());
    }

    /**
     * Include Interviews
     *
     * @param Task \$task
     * @return \League\Fractal\Resource\collection
     */
    public function includeInterviews(Task \$task)
    {
        \$interviews = \$task->interviews;
        return \$this->collection(\$interviews, new InterviewTransformer());
    }

    /**
     * Include Comments
     *
     * @param Task \$task
     * @return \League\Fractal\Resource\collection
     */
    public function includeComments(Task \$task)
    {
        \$comments = \$task->comments;
        return \$this->collection(\$comments, new CommentTransformer());
    }

    /**
     * Include Admin
     *
     * @param Task \$task
     * @return \League\Fractal\Resource\item
     */
    public function includeAdmin(Task \$task)
    {
        \$admin = \$task->admin;
        return \$this->item(\$admin, new AdminTransformer());
    }
EOT;
}