<?php namespace Tests\Repositories;

use App\Models\BotProperty;
use App\Models\Section;
use App\Models\User;
use App\Repositories\BotPropertyRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class BotPropertyRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var BotPropertyRepository
     */
    protected $botPropertyRepo;
    protected $adminUser;

    public function setUp() : void
    {
        parent::setUp();
        //create an admin user.....
        $adminUser = factory(User::class, 1)->create();
        $this->adminUser = $adminUser[0];
        $this->adminUser->assignRole('admin');
        $this->botPropertyRepo = \App::make(BotPropertyRepository::class);
        //create a section
        $this->be($this->adminUser);
        factory(Section::class, 1)->create(['type'=>'BOT_PROPERTY']);

    }

    /**
     * @test create
     */
    public function testCreateBotProperty()
    {
        $this->be($this->adminUser);
        $botProperty = factory(BotProperty::class)->make(['user_id'=>$this->adminUser->id])->toArray();

        $createdBotProperty = $this->botPropertyRepo->create($botProperty);

        $createdBotProperty = $createdBotProperty->toArray();
        $this->assertArrayHasKey('id', $createdBotProperty);
        $this->assertNotNull($createdBotProperty['id'], 'Created BotProperty must have id specified');
        $this->assertNotNull(BotProperty::find($createdBotProperty['id']), 'BotProperty with given id must be in DB');
        $this->assertModelData($botProperty, $createdBotProperty);
    }

    /**
     * @test read
     */
    public function testReadBotProperty()
    {
        $this->be($this->adminUser);
        $botProperty = factory(BotProperty::class)->create();

        $dbBotProperty = $this->botPropertyRepo->find($botProperty->id);

        $dbBotProperty = $dbBotProperty->toArray();
        $this->assertModelData($botProperty->toArray(), $dbBotProperty);
    }

    /**
     * @test update
     */
    public function testUpdateBotProperty()
    {
        $this->be($this->adminUser);

        $botProperty = factory(BotProperty::class)->create();
        //create a completely new bot property with a new value
        $fakeBotProperty = factory(BotProperty::class)->make(['user_id'=>$this->adminUser->id,'value'=>'test'])->toArray();
        //the expected changes should only be to the value .. nothing else should change
        $expectedProperty = factory(BotProperty::class)->make(
            [
                'bot_id'=>$botProperty->bot_id,
                'user_id'=>$botProperty->user_id,
                'section_id'=>$botProperty->section_id,
                'slug'=>$botProperty->slug,
                'name'=>$botProperty->name,
                'value'=>'test'
            ])->toArray();

        $updatedBotProperty = $this->botPropertyRepo->update($fakeBotProperty, $botProperty->id);
        $this->assertModelData($expectedProperty, $updatedBotProperty->toArray());
        $dbBotProperty = $this->botPropertyRepo->find($botProperty->id);
        $this->assertModelData($expectedProperty, $dbBotProperty->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteBotProperty()
    {
        $this->be($this->adminUser);
        $botProperty = factory(BotProperty::class)->create();

        $resp = $this->botPropertyRepo->delete($botProperty->id);

        $this->assertTrue($resp);
        $this->assertNull(BotProperty::find($botProperty->id), 'BotProperty should not exist in DB');
    }

    /**
     *
     */
    public function tearDown() :void
    {

        $config = app('config');
        $this->artisan('config:clear');
        $this->artisan('cache:clear');
        $this->artisan('route:clear');
        parent::tearDown();
        app()->instance('config', $config);
    }
}
