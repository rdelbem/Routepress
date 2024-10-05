<?php

namespace Tests\EndToEnd;

use Tests\Support\EndToEndTester;

class ActivationCest
{
    public function test_homepage_works(EndToEndTester $I): void
    {
        $I->amOnPage('/');
        $I->seeElement('body');
    }
    
    public function test_can_login_as_admin(EndToEndTester $I): void
    {
        $I->loginAsAdmin();
        $I->amOnAdminPage('/');
        $I->seeElement('body.wp-admin');
    }
}
