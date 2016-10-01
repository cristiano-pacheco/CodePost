<?php

namespace CodePress\CodePost\Tests\Models;

use CodePress\CodeCategory\Models\Category;
use CodePress\CodePost\Models\Post;
use CodePress\CodePost\Tests\AbstractTestCase;
use CodePress\CodeTag\Models\Tag;
use Illuminate\Validation\Validator;
use Mockery as m;

class PostTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->migrate();
    }

    public function test_inject_validator_in_post_model()
    {
        $post = new Post();

        $validator = m::mock(Validator::class);

        $post->setValidator($validator);

        $this->assertEquals($post->getValidator(), $validator);
    }

    public function test_should_check_if_it_is_valid_when_it_is()
    {
        $post = new Post();
        $post->title = 'Post Test';
        $post->content = 'Content Test';

        $validator = m::mock(Validator::class);

        $validator->shouldReceive('setRules')->with([
            'title' => 'required|max:255',
            'content' => 'required'
        ]);

        $validator->shouldReceive('setData')->with([
            'title' => 'Post Test',
            'content' => 'Content Test',
        ]);

        $validator->shouldReceive('fails')->andReturn(false);

        $post->setValidator($validator);

        $this->assertTrue($post->isValid());
    }

    public function test_should_check_if_it_is_invalid_when_it_is()
    {
        $post = new Post();
        $post->title = 'Post Test';
        $post->content = 'Content Test';

        $validator = m::mock(Validator::class);
        $messageBag = m::mock('Illuminate\Support\MessageBag');

        $validator->shouldReceive('setRules')->with([
            'title' => 'required|max:255',
            'content' => 'required'
        ]);

        $validator->shouldReceive('setData')->with([
            'title' => 'Post Test',
            'content' => 'Content Test',
        ]);

        $validator->shouldReceive('fails')->andReturn(true);

        $validator->shouldReceive('errors')->andReturn($messageBag);

        $post->setValidator($validator);

        $this->assertFalse($post->isValid());
        $this->assertEquals($messageBag, $post->errors);
    }

    public function test_check_if_a_post_can_be_persisted()
    {
        $post = $this->createPost();

        $this->assertEquals('Post Test', $post->title);
        $this->assertEquals('Content Test', $post->content);
    }


    public function test_can_validate_post()
    {
        $post = new Post();
        $post->title = 'Post Test';
        $post->content = 'Content Test';

        $factory = $this->app->make('Illuminate\Validation\Factory');
        $validator = $factory->make([],[]);

        $post->setValidator($validator);

        $this->assertTrue($post->isValid());

        $post->title = null;

        $this->assertFalse($post->isValid());

    }

    public function test_can_sluggable()
    {
        $post = $this->createPost();

        $this->assertEquals($post->slug, "post-test");

        $post = $this->createPost();

        $this->assertEquals($post->slug, "post-test-1");

        $post = Post::findBySlug('post-test-1');

        $this->assertInstanceOf(Post::class, $post);
    }

    public function createPost()
    {
        $post = Post::create([
            'title' => 'Post Test',
            'content' => 'Content Test'
        ]);

        return $post;
    }

    public function test_can_add_tags_to_posts()
    {
        $tag = Tag::create(['name' => 'Tag Test']);

        $post1 = Post::create(['title' => 'Post 1', 'content' => 'Content 1']);

        $post2 = Post::create(['title' => 'Post 2', 'content' => 'Content 2']);

        $post1->tags()->save($tag);
        $post2->tags()->save($tag);

        $this->assertCount(1,Tag::all());

        $this->assertEquals('Tag Test', $post1->tags->first()->name);
        $this->assertEquals('Tag Test', $post2->tags->first()->name);

        $posts = Tag::find(1)->posts;
        $this->assertCount(2, $posts);
        $this->assertEquals('Post 1', $posts[0]->title);
        $this->assertEquals('Post 2', $posts[1]->title);
    }

    public function test_can_add_posts_to_tags()
    {
        $post = Post::create(['title' => 'Post', 'content' => 'Content']);

        $tag1 = Tag::create(['name' => 'Tag 1']);

        $tag2 = Tag::create(['name' => 'Tag 2']);

        $tag1->posts()->save($post);
        $tag2->posts()->save($post);

        $this->assertCount(1, Post::all());

        $this->assertEquals('Post', $tag1->posts->first()->title);
        $this->assertEquals('Post', $tag2->posts->first()->title);

        $tags = Post::find(1)->tags;
        $this->assertCount(2, $tags);
        $this->assertEquals('Tag 1', $tags[0]->name);
        $this->assertEquals('Tag 2', $tags[1]->name);
    }

    public function test_can_add_categories_to_posts()
    {
        $post = Post::create(['title' => 'Post', 'content' => 'Content']);

        $category1 = Category::create(['name' => 'Category 1']);

        $category2 = Category::create(['name' => 'Category 2']);

        $category1->posts()->save($post);
        $category2->posts()->save($post);

        $this->assertCount(1, Post::all());

        $this->assertEquals('Post', $category1->posts->first()->title);
        $this->assertEquals('Post', $category2->posts->first()->title);

        $categories = Post::find(1)->categories;
        $this->assertCount(2, $categories);
        $this->assertEquals('Category 1', $categories[0]->name);
        $this->assertEquals('Category 2', $categories[1]->name);
    }

    public function test_can_add_comments()
    {
        $post = Post::create(['title' => 'Post', 'content' => 'Content']);

        $post->comments()->create(['content' => 'Comentario 1']);
        $post->comments()->create(['content' => 'Comentario 2']);

        $comments = Post::find(1)->comments;
        $this->assertCount(2, $comments);
        $this->assertEquals('Comentario 1',$comments[0]->content);
        $this->assertEquals('Comentario 2',$comments[1]->content);
    }


}