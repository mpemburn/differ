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
            $browser->visit('https://www.clarku.edu/all-campus-events')
                ->assertSee('Clark');
            $height = $browser->script([
                'let body = document.body;
                let html = document.documentElement;
                let totalHeight = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight );
                return {width: body.offsetWidth, height: totalHeight};'
            ]);
            var_dump(current($height)['height']);
        });
    }
}
