<?php

namespace Tests\Policies;

use App\Models\WordSpellingGroup;
use App\Models\User;
use App\Policies\WordSpellingGroupPolicy;
use Tests\TestCase;
use Tests\CreatesApplication;

class WordSpellingGroupPolicyAdminTest extends TestCase
{
    private $user;
    private $policy;
    private $wordSpellingGroup;

    public function setUp() :void
    {

        parent::setUp();

        //create an admin user.....
        $user = factory(User::class)->create();
        $this->user = $user;
        $this->user->assignRole('admin');
        $this->be($this->user);

        $this->policy = new WordSpellingGroupPolicy();
        $this->wordSpellingGroup = factory(WordSpellingGroup::class)->create(['language_id'=>1]);
    }

    public function testCanViewAnyAsAdmin()
    {
        $response = $this->policy->viewAny($this->user);
        $this->assertTrue($response->allowed());
    }

    public function testCanViewAsAdmin()
    {
        $response = $this->policy->view($this->user, $this->wordSpellingGroup);
        $this->assertTrue($response->allowed());
    }

    public function testCanCreateAsAdmin()
    {
        $response = $this->policy->create($this->user);
        $this->assertTrue($response->allowed());
    }

    public function testCanUpdateAsAdmin()
    {
        $response = $this->policy->update($this->user, $this->wordSpellingGroup);
        $this->assertTrue($response->allowed());
    }

    public function testCanDeleteAsAdmin()
    {
        $response = $this->policy->delete($this->user, $this->wordSpellingGroup);
        $this->assertTrue($response->allowed());
    }

    public function testCanRestoreAsAdmin()
    {
        $response = $this->policy->restore($this->user, $this->wordSpellingGroup);
        $this->assertTrue($response->allowed());
    }

    public function testCanForceDeleteAsAdmin()
    {
        $response = $this->policy->forceDelete($this->user, $this->wordSpellingGroup);
        $this->assertTrue($response->allowed());
    }

    /**
     *
     */
    public function tearDown() :void
    {


        $config = app('config');
        parent::tearDown();
        app()->instance('config', $config);
    }
}
