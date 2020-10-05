<?php

namespace Rocramer\MailcoachPostalFeedback\Tests;

use CreateMailCoachTables;
use CreateWebhookCallsTable;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Mailcoach\MailcoachServiceProvider;
use Rocramer\MailcoachPostalFeedback\MailcoachPostalFeedbackServiceProvider;
use Rocramer\MailcoachPostalFeedback\PostalWebhookConfig;
use Spatie\WebhookClient\Models\WebhookCall;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__ . '/../vendor/spatie/laravel-mailcoach/database/factories');

        Route::mailcoach('mailcoach');

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            MailcoachServiceProvider::class,
            MailcoachPostalFeedbackServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('mail.driver', 'log');
    }

    protected function setUpDatabase()
    {
        include_once __DIR__ . '/../vendor/spatie/laravel-mailcoach/database/migrations/create_webhook_calls_table.php.stub';
        (new CreateWebhookCallsTable())->up();

        include_once __DIR__ . '/../vendor/spatie/laravel-mailcoach/database/migrations/create_mailcoach_tables.php.stub';
        (new CreateMailCoachTables())->up();
    }

    public function getStub(string $name): array
    {
        $content = file_get_contents(__DIR__ . "/stubs/{$name}.json");

        return json_decode($content, true);
    }

    public function addValidSignature(array $payloadContent = []): array
    {
        return array_merge(
            $payloadContent,

        );
    }
}
