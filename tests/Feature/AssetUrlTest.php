<?php

namespace Tests\Feature;

use App\Providers\AppServiceProvider;
use Illuminate\Http\Request;
use Tests\TestCase;

class AssetUrlTest extends TestCase
{
    public function test_asset_urls_include_the_current_project_base_path(): void
    {
        $request = Request::create('http://localhost/tours/home');
        $request->server->set('SCRIPT_NAME', '/tours/index.php');
        $request->server->set('SCRIPT_FILENAME', '/xampp/htdocs/tours/public/index.php');

        $this->app->instance('request', $request);
        $this->app['url']->setRequest($request);

        $provider = new AppServiceProvider($this->app);
        $provider->boot();

        $this->assertSame(
            'http://localhost/tours/public/images/kranbstour-logo.svg',
            asset('/images/kranbstour-logo.svg')
        );
    }
}
