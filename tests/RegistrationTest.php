<?php

use Illuminate\Foundation\AliasLoader;
use TestsFixtures\Providers\ADevProvider;
use PercyMamedy\LaravelDevBooter\ServiceProvider as DevBooterProvider;

class RegistrationTest extends AbstractTestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    public function getPackageProviders($app)
    {
        config(['app.dev_providers' => [ADevProvider::class]]);
        config(['app.dev_aliases' => ['Bar' => \TestsFixtures\Facades\ADevFacade::class]]);

        return [
            DevBooterProvider::class,
        ];
    }

    /**
     * Test that dev providers are registered when on dev env.
     *
     * @return void
     */
    public function testThatDevProvidersAreRegisteredCorrectly()
    {
        $app = $this->createApplication('dev');

        // Package is registered.
        $this->assertTrue(array_key_exists('TestsFixtures\Providers\ADevProvider', $app->getLoadedProviders()));
        $this->assertEquals('dummy.value', $app->make('dummy.key'));
        $this->assertInstanceOf(\TestsFixtures\Foo\Bar::class, $app->make('bar'));
    }

    /**
     * Test that dev class aliases are properly booter.
     *
     * @return void
     */
    public function testThatClassAliasesAreBootedCorrectly()
    {
        $app = $this->createApplication('dev');

        $this->assertTrue(array_key_exists('Bar', AliasLoader::getInstance()->getAliases()));
    }

    /**
     * Test that when we are on production dev providers are
     * not registered.
     *
     * @return void
     */
    public function testThatDevProvidersAreNotRegisteredOnProd()
    {
        $app = $this->createApplication('production');

        $this->assertTrue(! array_key_exists('TestsFixtures\Providers\ADevProvider', $app->getLoadedProviders()));
    }
}
