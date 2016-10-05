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
        $post = Post::create(['title' => 'Post Test', 'content' => 'Content Test']);

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

    public function test_can_force_delete_all_from_relationship()
    {
        $post = Post::create(['title' => 'Post Test', 'content' => 'Content Test']);
        Comment::create(['content' => 'Content Test1','post_id' => $post->id]);
        Comment::create(['content' => 'Content Test2','post_id' => $post->id]);

        $post->comments()->forceDelete();
        $this->assertCount(0, $post->comments()->get());

    }

    public function test_can_restore_deleted_all_from_relationship()
    {
        $post = Post::create(['title' => 'Post Test', 'content' => 'Content Test']);
        $comment1 = Comment::create(['content' => 'Content Test1','post_id' => $post->id]);
        $comment2 = Comment::create(['content' => 'Content Test2','post_id' => $post->id]);

        $comment1->delete();
        $comment2->delete();

        $this->assertCount(0, $post->comments()->get());

        $post->comments()->restore();

        $this->assertCount(2, $post->comments()->get());

    }

    public function test_can_soft_delete()
    {
        $comment = $this->createComment();
        $comment->delete();

        $this->assertTrue($comment->trashed());
        $this->assertCount(0, Comment::all());
    }

    public function test_can_get_rows_deleted()
    {
        $comment = $this->createComment();
        $comment->delete();

        $this->createComment('Test 2');

        $comments = Comment::onlyTrashed()->get();
        $this->assertEquals(1, $comments[0]->id);
        $this->assertEquals('Content Test', $comments[0]->content);
    }

    public function test_can_get_rows_deleted_and_activated()
    {
        $comment = $this->createComment();
        $comment->delete();

        $this->createComment('Test 2');

        $comments = Comment::withTrashed()->get();
        $this->assertEquals(1, $comments[0]->id);
        $this->assertEquals('Content Test', $comments[0]->content);
        $this->assertCount(2, $comments);
    }


    public function test_can_force_delete()
    {
        $comment = $this->createComment();
        $comment->forceDelete();

        $this->assertCount(0, Comment::all());
    }

    public function test_can_restore_rows_from_deleted()
    {
        $comment = $this->createComment();
        $comment->delete();
        $comment->restore();

        $comments = Comment::all();
        $this->assertEquals(1, $comments[0]->id);
        $this->assertEquals('Content Test', $comments[0]->content);
    }

    public function createComment($value = null)
    {
        if(!$value) $value = "Test";

        $post = Post::create(['title' => "Post $value", 'content' => "Content $value"]);

        $comment = Comment::create(['content' => "Content $value",'post_id' => $post->id]);

        return $comment;
    }
}