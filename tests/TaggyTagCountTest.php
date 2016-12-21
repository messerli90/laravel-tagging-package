<?php

class TaggyTagCountTest extends TestCase
{
    protected $post;

    public function setUp()
    {
        parent::setUp();

        $this->post = PostStub::create([
            'title' => 'A Post Title'
        ]);
    }

    /** @test */
    public function tag_count_is_incremented_when_used ()
    {
        $tag = TagStub::create([
            'name' => 'Laravel',
            'slug' => str_slug('Laravel'),
            'count' => 0
        ]);

        $this->post->tag($tag);

        $tag = $tag->fresh();

        $this->assertEquals(1, $tag->count);
    }

    /** @test */
    public function tag_count_is_decremented_when_removed ()
    {
        $tag = TagStub::create([
            'name' => 'Laravel',
            'slug' => str_slug('Laravel'),
            'count' => 70
        ]);

        $this->post->tag($tag);
        $this->post->untag($tag);

        $tag = $tag->fresh();

        $this->assertEquals(70, $tag->count);
    }

    /** @test */
    public function tag_count_does_not_dip_below_zero ()
    {
        $tag = TagStub::create([
            'name' => 'Laravel',
            'slug' => str_slug('Laravel'),
            'count' => 0
        ]);

        $this->post->untag($tag);

        $tag = $tag->fresh();

        $this->assertEquals(0, $tag->count);
    }

    /** @test */
    public function tag_count_does_not_increment_if_already_exists ()
    {
        $tag = TagStub::create([
            'name' => 'Laravel',
            'slug' => str_slug('Laravel'),
            'count' => 0
        ]);

        $this->post->tag($tag);
        $this->post->tag($tag);
        $this->post->retag($tag);
        $this->post->tag($tag);

        $tag = $tag->fresh();

        $this->assertEquals(1, $tag->count);
    }

}