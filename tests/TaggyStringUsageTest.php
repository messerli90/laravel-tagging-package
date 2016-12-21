<?php

class TaggyStringUsageTest extends TestCase
{
    protected $post;

    public function setUp()
    {
        parent::setUp();

        foreach (['PHP', 'Laravel', 'Testing', 'Redis', 'Postgres', 'Fun stuff'] as $tag) {
            TagStub::create([
                'name' => $tag,
                'slug' => str_slug($tag)
            ]);
        }

        $this->post = PostStub::create([
            'title' => 'A Post Title'
        ]);
    }

    /** @test */
    public function can_tag_a_post ()
    {
        $this->post->tag(['Laravel', 'Fun stuff']);

        $this->assertCount(2, $this->post->tags);

        foreach (['Laravel', 'Fun stuff'] as $tag) {
            $this->assertContains($tag, $this->post->tags->pluck('name'));
        }
    }

    /** @test */
    public function can_untag_a_post ()
    {
        $this->post->tag(['Laravel', 'Fun stuff', 'Testing']);
        $this->post->untag(['Laravel']);

        $this->assertCount(2, $this->post->tags);

        foreach (['Fun stuff', 'Testing'] as $tag) {
            $this->assertContains($tag, $this->post->tags->pluck('name'));
        }
    }

    /** @test */
    public function can_untag_all_post_tags ()
    {
        $this->post->tag(['Laravel', 'Fun stuff', 'Testing']);
        $this->post->untag();

        $this->post->load('tags');

        $this->assertCount(0, $this->post->tags);
        $this->assertEquals(0, $this->post->tags()->count());
    }

    /** @test */
    public function can_retag_post_tags ()
    {
        $this->post->tag(['Laravel', 'Fun stuff', 'Testing']);
        $this->post->retag(['Postgres', 'PHP', 'Testing']);

        $this->post->load('tags');

        $this->assertCount(3, $this->post->tags);

        foreach (['Postgres', 'PHP', 'Testing'] as $tag) {
            $this->assertContains($tag, $this->post->tags->pluck('name'));
        }
    }

    /** @test */
    public function non_existing_tags_are_ignored_on_tagging ()
    {
        $this->post->tag(['Laravel', 'Fun stuff', 'c++']);

        $this->assertCount(2, $this->post->tags);

        foreach (['Laravel', 'Fun stuff'] as $tag) {
            $this->assertContains($tag, $this->post->tags->pluck('name'));
        }
    }

    /** @test */
    public function inconsistent_tag_names_are_normalized ()
    {
        $this->post->tag(['LaraveL', 'FUN stuff', 'TeSTing']);

        $this->assertCount(3, $this->post->tags);

        foreach (['Laravel', 'Fun stuff', 'Testing'] as $tag) {
            $this->assertContains($tag, $this->post->tags->pluck('name'));
        }

        foreach (['laravel', 'fun-stuff', 'testing'] as $tag) {
            $this->assertContains($tag, $this->post->tags->pluck('slug'));
        }
    }

}