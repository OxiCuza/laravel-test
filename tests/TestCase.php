<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function getOwner(): User
    {
        return User::where('email', 'owner@localhost.com')->first();
    }

    public function getRegular(): User
    {
        return User::where('email', 'regular@localhost.com')->first();
    }
}
