<?php

namespace Tests\Browser;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverDimension;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    public function testBasicExample(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://news.test.clarku.edu/academic-affairs')
                ->assertSee('Sign in with your organizational account');

            $browser->type('UserName', 'mpemburn@clarku.edu')
                ->type('Password', 'wildPl@nner')
                ->press('#submitButton')
                ->pause(3000);
        });
    }
}
