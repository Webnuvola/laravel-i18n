<?php

namespace Webnuvola\Laravel\I18n\Test;

class I18nRedirectorTest extends TestCase
{
    /** @var \Webnuvola\Laravel\I18n\I18n */
    protected $i18n;

    /** @var \Webnuvola\Laravel\I18n\I18nRedirector */
    protected $i18nRedirector;

    /** @var \Illuminate\Routing\Router */
    protected $router;

    public function setUp(): void
    {
        parent::setUp();

        $this->i18n = app('i18n');
        $this->i18nRedirector = app('i18n.redirect');
        $this->router = app('router');

        app('i18n.routes')->group(function () {
            $this->router->get('/')->name('home');
            $this->router->get('product/{id}')->name('product');
            $this->router->get('signed')->name('signed');
        });

        $this->router->getRoutes()->refreshNameLookups();
    }

    public function testHome()
    {
        $this->i18n->setRegion('it-it');
        $this->assertEquals('http://localhost/it-it', $this->i18nRedirector->home()->getTargetUrl());

        $this->i18n->setRegion('en-us');
        $this->assertEquals('http://localhost/en-us', $this->i18nRedirector->home()->getTargetUrl());

        $this->i18n->setRegion('es-us');
        $this->assertEquals('http://localhost/es-us', $this->i18nRedirector->home()->getTargetUrl());

        $this->i18n->setRegion('en-gb');
        $this->assertEquals('http://localhost/en-gb', $this->i18nRedirector->home()->getTargetUrl());
    }

    public function testTo()
    {
        $this->i18n->setRegion('it-it');
        $this->assertEquals('http://localhost/it-it/page', $this->i18nRedirector->to('page')->getTargetUrl());

        $this->i18n->setRegion('en-us');
        $this->assertEquals('http://localhost/en-us/page', $this->i18nRedirector->to('page')->getTargetUrl());

        $this->i18n->setRegion('es-us');
        $this->assertEquals('http://localhost/es-us/page', $this->i18nRedirector->to('page')->getTargetUrl());

        $this->i18n->setRegion('en-gb');
        $this->assertEquals('http://localhost/en-gb/page', $this->i18nRedirector->to('page')->getTargetUrl());
    }

    public function testRoute()
    {
        $this->i18n->setRegion('it-it');
        $this->assertEquals('http://localhost/it-it/product/5', $this->i18nRedirector->route('product', 5)->getTargetUrl());

        $this->i18n->setRegion('en-us');
        $this->assertEquals('http://localhost/en-us/product/5', $this->i18nRedirector->route('product', 5)->getTargetUrl());

        $this->i18n->setRegion('es-us');
        $this->assertEquals('http://localhost/es-us/product/5', $this->i18nRedirector->route('product', 5)->getTargetUrl());

        $this->i18n->setRegion('en-gb');
        $this->assertEquals('http://localhost/en-gb/product/5', $this->i18nRedirector->route('product', 5)->getTargetUrl());
    }

    public function testSignedRoute()
    {
        $this->i18n->setRegion('it-it');
        $this->assertStringStartsWith('http://localhost/it-it/signed?signature=', $this->i18nRedirector->signedRoute('signed')->getTargetUrl());

        $this->i18n->setRegion('en-us');
        $this->assertStringStartsWith('http://localhost/en-us/signed?signature=', $this->i18nRedirector->signedRoute('signed')->getTargetUrl());

        $this->i18n->setRegion('es-us');
        $this->assertStringStartsWith('http://localhost/es-us/signed?signature=', $this->i18nRedirector->signedRoute('signed')->getTargetUrl());

        $this->i18n->setRegion('en-gb');
        $this->assertStringStartsWith('http://localhost/en-gb/signed?signature=', $this->i18nRedirector->signedRoute('signed')->getTargetUrl());
    }

    public function testHelpers()
    {
        $this->i18n->setRegion('it-it');
        $this->assertEquals('http://localhost/it-it', i18n_redirect()->home()->getTargetUrl());
        $this->assertEquals('http://localhost/it-it/page', i18n_redirect()->to('page')->getTargetUrl());
        $this->assertEquals('http://localhost/it-it/page/subpage', i18n_redirect('page/subpage')->getTargetUrl());
        $this->assertEquals('http://localhost/it-it/product/5', i18n_redirect()->route('product', 5)->getTargetUrl());
        $this->assertStringStartsWith('http://localhost/it-it/signed?signature=', i18n_redirect()->signedRoute('signed')->getTargetUrl());
    }
}
