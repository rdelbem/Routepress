<?php

namespace Tests;

use lucatume\WPBrowser\TestCase\WPTestCase;

class SampleTest extends WPTestCase
{
    public function setUp(): void
    {
        // Before...
        parent::setUp();

        // Your set-up methods here.
    }

    public function tearDown(): void
    {
        // Your tear down methods here.

        // Then...
        parent::tearDown();
    }

    // Tests
    public function test_factory(): void
    {
        $post = static::factory()->post->create_and_get();

        $this->assertInstanceOf(\WP_Post::class, $post);
    }

    public function test_default_users(): void
    {
        $this->assertEquals(0, wp_get_current_user()->ID);
        
        wp_set_current_user(1);
        
        $this->assertEquals(1, wp_get_current_user()->ID);
        $this->assertEquals('admin', wp_get_current_user()->user_login);
    }
}