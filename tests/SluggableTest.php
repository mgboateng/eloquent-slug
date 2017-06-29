<?php 
namespace MGBoateng\EloquentSlugs\Test;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use MGBoateng\EloquentSlugs\Test\Post;

class SluggableTest extends TestCase 
{
    use DatabaseTransactions;

    public function setUp() 
    {
        parent::setUp();
        $this->seperator = '_';
    }

    /** @test */
    public function it_saves_a_slug_when_creating_a_model() 
    {
        $model = Post::create([
            'title' => 'Hello World',
            'body' => 'Here comes a great programmer'
        ]);
        $this->assertSame(str_slug('Hello World', $this->seperator), $model->fresh()->slug);
    }

    /** @test */
    public function it_generates_unique_slugs_when_a_model_is_created() 
    {
        factory(Post::class, 3)->create(['title' => 'Hello World', 'slug' => null]);
        $model = factory(Post::class)->create(['title' => 'Hello World', 'slug' => null]);
        $this->assertSame(str_slug('Hello World', $this->seperator). $this->seperator .'3', $model->fresh()->slug);
    }

    /** @test */
    public function it_will_generate_a_slug_from_inputs_if_slug_is_set_directly() 
    {
        $model = factory(Post::class)->create(['slug' => 'Hello World']);
        $this->assertSame(str_slug('Hello World', $this->seperator), $model->fresh()->slug);
    }

    /** @test */
    public function it_can_handle_multiple_slugs_from_inputs_if_slug_is_set_directly() 
    {
        factory(Post::class, 3)->create(['slug' => 'Hello World']);
        $model = factory(Post::class)->create(['slug' => 'Hello World']);
        $this->assertSame(str_slug('Hello World', $this->seperator) . $this->seperator . '3', $model->fresh()->slug);
    }

    /** @test */
    public function it_does_not_modify_slug_when_updating_the_model_if_the_source_attribute_is_unchanged() 
    {
        $model = factory(Post::class)->create(['title' => 'Hello World', 'slug' => null]);
        $model->body = "Here comes a greate programmer";
        $model->save();
        $this->assertEquals(str_slug('Hello World', $this->seperator), $model->fresh()->slug);
    }

    /** @test */
    public function it_modifies_the_existing_slug_with_a_new_one_when_the_source_input_is_different_from_the_existing() 
    {
        $model = factory(Post::class)->create();
        $model->title = "Hello World";
        $model->slug = null;
        $model->save();
        $this->assertEquals(str_slug('Hello World', $this->seperator), $model->fresh()->slug);
    }

    /** @test */
    public function it_can_handle_duplication_when_updating_if_the_slug_already_exist() 
    {
        $model = factory(Post::class, 2)->create(['title' => 'Hello World', 'slug' => null]);
        $model = factory(Post::class)->create();
        $model->title = "Hello World";
        $model->slug = null;
        $model->save();
        $this->assertEquals(str_slug('Hello World', $this->seperator) . $this->seperator . '2', $model->fresh()->slug);
    }

    /** @test */
    public function it_updates_the_slug_field_directly_should_the_field_be_set_during_update() 
    {
        $model = factory(Post::class)->create();
        $model->slug = "Hello World";
        $model->save();
        $this->assertEquals(str_slug("hello-world", $this->seperator), $model->fresh()->slug);
    }

    /** @test */
    public function it_can_handles_duplication_when_slug_field_is_directly_set() 
    {
        factory(Post::class)->create(['title' => 'Hello World', 'slug' => null]);
        factory(Post::class)->create(['slug' => 'Hello World']);
        $model = factory(Post::class)->create(['slug' => 'Hello World']);
        $this->assertEquals(str_slug("hello-world", $this->seperator) . $this->seperator . '2', $model->fresh()->slug);
    }
}