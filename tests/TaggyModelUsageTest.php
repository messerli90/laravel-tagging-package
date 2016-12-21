<?php

class TaggyModelUsageTest extends TestCase
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
        $this->post->tag(\TagStub::where('slug', 'laravel')->first());

        $this->assertCount(1, $this->post->tags);
        $this->assertContains('Laravel', $this->post->tags->pluck('name'));
    }

    /** @test */
    public function can_tag_post_with_collection_of_tags ()
    {
        $tags = TagStub::whereIn('slug', ['laravel', 'php', 'redis'])->get();

        $this->post->tag($tags);

        $this->assertCount(3, $this->post->tags);

        foreach (['Laravel', 'PHP', 'Redis'] as $tag) {
            $this->assertContains($tag, $this->post->tags->pluck('name'));
        }
    }

    /** @test */
    public function can_untag_post_tags ()
    {
        $tags = TagStub::whereIn('slug', ['laravel', 'php', 'redis'])->get();

        $this->post->tag($tags);

        $this->post->untag($tags->first());

        $this->assertCount(2, $this->post->tags);

        foreach (['PHP', 'Redis'] as $tag) {
            $this->assertContains($tag, $this->post->tags->pluck('name'));
        }
    }

    /** @test */
    public function can_untag_all_post_tags ()
    {
        $tags = TagStub::whereIn('slug', ['laravel', 'php', 'redis'])->get();

        $this->post->tag($tags);

        $this->post->untag();

        $this->post->load('tags');

        $this->assertCount(0, $this->post->tags);
    }

    /** @test */
    public function can_retag_post_tags ()
    {
        $tags = TagStub::whereIn('slug', ['laravel', 'php', 'redis'])->get();
        $new_tags = TagStub::whereIn('slug', ['laravel', 'fun-stuff', 'postgres'])->get();

        $this->post->tag($tags);

        $this->post->retag($new_tags);

        $this->post->load('tags');

        $this->assertCount(3, $this->post->tags);

        foreach (['Laravel', 'Fun stuff', 'Postgres'] as $tag) {
            $this->assertContains($tag, $this->post->tags->pluck('name'));
        }
    }

    /** @test */
    public function non_models_are_filtered_when_using_collection ()
    {
        $tags = TagStub::whereIn('slug', ['laravel', 'php', 'redis'])->get();
        $tags->push('c++');

        $this->post->tag($tags);

        $this->assertCount(3, $this->post->tags);

        foreach (['Laravel', 'PHP', 'Redis'] as $tag) {
            $this->assertContains($tag, $this->post->tags->pluck('name'));
        }
    }

}