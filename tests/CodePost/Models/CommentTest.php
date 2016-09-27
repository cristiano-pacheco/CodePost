<?php

namespace CodePress\CodeComment\Tests\Models;

use CodePress\CodePost\Models\Post;
use CodePress\CodePost\Models\Comment;
use CodePress\CodePost\Tests\AbstractTestCase;
use Illuminate\Validation\Validator;
use Mockery as m;

class CommentTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->migrate();
    }

    public function test_inject_validator_in_comment_model()
    {
        $comment = new Comment();

        $validator = m::mock(Validator::class);

        $comment->setValidator($validator);

        $this->assertEquals($comment->getValidator(), $validator);
    }

    public function test_should_check_if_it_is_valid_when_it_is()
    {
        $comment = new Comment();
        $comment->content = 'Content Test';

        $validator = m::mock(Validator::class);

        $validator->shouldReceive('setRules')->with([
            'content' => 'required'
        ]);

        $validator->shouldReceive('setData')->with([
            'content' => 'Content Test',
        ]);

        $validator->shouldReceive('fails')->andReturn(false);

        $comment->setValidator($validator);

        $this->assertTrue($comment->isValid());
    }

    public function test_should_check_if_it_is_invalid_when_it_is()
    {
        $comment = new Comment();
        $comment->content = 'Content Test';

        $validator = m::mock(Validator::class);
        $messageBag = m::mock('Illuminate\Support\MessageBag');

        $validator->shouldReceive('setRules')->with([
            'content' => 'required'
        ]);

        $validator->shouldReceive('setData')->with([
            'content' => 'Content Test',
        ]);

        $validator->shouldReceive('fails')->andReturn(true);

        $validator->shouldReceive('errors')->andReturn($messageBag);

        $comment->setValidator($validator);

        $this->assertFalse($comment->isValid());
        $this->assertEquals($messageBag, $comment->errors);
    }

    public function test_check_if_a_comment_can_be_persisted()
    {
        $post = Post::create(['title' => 'Comment Test', 'content' => 'Content Test']);

        $comment = Comment::create(['content' => 'Content Test','post_id' => $post->id]);
        $this->assertEquals('Content Test', $comment->content);

        $comment = Comment::all()->first();
        $this->assertEquals('Content Test', $comment->content);
    }


    public function test_can_validate_post()
    {
        $comment = new Comment();
        $comment->content = 'Content Test';

        $factory = $this->app->make('Illuminate\Validation\Factory');
        $validator = $factory->make([],[]);

        $comment->setValidator($validator);

        $this->assertTrue($comment->isValid());

        $comment->content = null;

        $this->assertFalse($comment->isValid());

    }
}