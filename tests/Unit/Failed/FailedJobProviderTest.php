<?php

use Google\Cloud\PubSub\PubSubClient;
use Google\Cloud\PubSub\Topic;
use SysMatter\GooglePubSub\Exceptions\PubSubException;
use SysMatter\GooglePubSub\Failed\PubSubFailedJobProvider;

beforeEach(function () {
    $this->config = [
        'project_id' => 'test-project',
        'auth_method' => 'application_default',
        'monitoring' => [
            'log_failed_messages' => true,
        ],
    ];

    $this->provider = new PubSubFailedJobProvider($this->config);
});

describe('PubSubFailedJobProvider', function () {
    it('logs failed job to pubsub topic', function () {
        $mockClient = Mockery::mock(PubSubClient::class);
        $mockTopic = Mockery::mock(Topic::class);

        // Mock the topic creation/retrieval
        $mockClient->shouldReceive('topic')
            ->with('laravel-failed-jobs')
            ->andReturn($mockTopic);

        $mockTopic->shouldReceive('exists')
            ->andReturn(false);

        $mockTopic->shouldReceive('create')
            ->once();

        $mockTopic->shouldReceive('publish')
            ->withArgs(function ($message) {
                $decoded = json_decode($message['data'], true);
                return $decoded['connection'] === 'pubsub'
                    && $decoded['queue'] === 'default'
                    && isset($decoded['payload'])
                    && isset($decoded['exception'])
                    && isset($decoded['failed_at'])
                    && $message['attributes']['connection'] === 'pubsub'
                    && $message['attributes']['queue'] === 'default';
            })
            ->andReturn(['messageIds' => ['msg-failed-123']])
            ->once();

        // Use reflection to inject the mock client
        $reflection = new ReflectionClass($this->provider);
        $property = $reflection->getProperty('pubsub');
        $property->setAccessible(true);
        $property->setValue($this->provider, $mockClient);

        $exception = new Exception('Job processing failed');
        $result = $this->provider->log('pubsub', 'default', '{"job":"TestJob"}', $exception);

        expect($result)->toBe('msg-failed-123');
    });

    it('creates failed jobs topic if it does not exist', function () {
        $mockClient = Mockery::mock(PubSubClient::class);
        $mockTopic = Mockery::mock(Topic::class);

        $mockClient->shouldReceive('topic')
            ->with('laravel-failed-jobs')
            ->andReturn($mockTopic);

        $mockTopic->shouldReceive('exists')
            ->andReturn(false);

        $mockTopic->shouldReceive('create')
            ->once();

        $mockTopic->shouldReceive('publish')
            ->andReturn(['messageIds' => ['msg-123']]);

        $reflection = new ReflectionClass($this->provider);
        $property = $reflection->getProperty('pubsub');
        $property->setAccessible(true);
        $property->setValue($this->provider, $mockClient);

        $this->provider->log('pubsub', 'default', 'payload', new Exception('error'));

        // If we got here without exception, topic was created
        expect(true)->toBeTrue();
    });

    it('uses existing topic if it exists', function () {
        $mockClient = Mockery::mock(PubSubClient::class);
        $mockTopic = Mockery::mock(Topic::class);

        $mockClient->shouldReceive('topic')
            ->with('laravel-failed-jobs')
            ->andReturn($mockTopic);

        $mockTopic->shouldReceive('exists')
            ->andReturn(true);

        $mockTopic->shouldNotReceive('create');

        $mockTopic->shouldReceive('publish')
            ->andReturn(['messageIds' => ['msg-456']]);

        $reflection = new ReflectionClass($this->provider);
        $property = $reflection->getProperty('pubsub');
        $property->setAccessible(true);
        $property->setValue($this->provider, $mockClient);

        $this->provider->log('pubsub', 'default', 'payload', new Exception('error'));

        expect(true)->toBeTrue();
    });

    it('throws exception when logging fails', function () {
        $mockClient = Mockery::mock(PubSubClient::class);
        $mockTopic = Mockery::mock(Topic::class);

        $mockClient->shouldReceive('topic')
            ->andReturn($mockTopic);

        $mockTopic->shouldReceive('exists')
            ->andReturn(true);

        $mockTopic->shouldReceive('publish')
            ->andThrow(new Exception('Network error'));

        $reflection = new ReflectionClass($this->provider);
        $property = $reflection->getProperty('pubsub');
        $property->setAccessible(true);
        $property->setValue($this->provider, $mockClient);

        expect(fn () => $this->provider->log('pubsub', 'default', 'payload', new Exception('error')))
            ->toThrow(PubSubException::class, 'Failed to log failed job');
    });

    it('includes exception class in attributes', function () {
        $mockClient = Mockery::mock(PubSubClient::class);
        $mockTopic = Mockery::mock(Topic::class);

        $mockClient->shouldReceive('topic')
            ->andReturn($mockTopic);

        $mockTopic->shouldReceive('exists')
            ->andReturn(true);

        $mockTopic->shouldReceive('publish')
            ->withArgs(function ($message) {
                return $message['attributes']['exception_class'] === 'RuntimeException';
            })
            ->andReturn(['messageIds' => ['msg-789']]);

        $reflection = new ReflectionClass($this->provider);
        $property = $reflection->getProperty('pubsub');
        $property->setAccessible(true);
        $property->setValue($this->provider, $mockClient);

        $exception = new RuntimeException('Runtime error');
        $this->provider->log('pubsub', 'default', 'payload', $exception);

        expect(true)->toBeTrue();
    });

    it('returns empty array for all() method', function () {
        $result = $this->provider->all();

        expect($result)->toBeArray()->toBeEmpty();
    });

    it('returns null for find() method', function () {
        $result = $this->provider->find('some-id');

        expect($result)->toBeNull();
    });

    it('returns false for forget() method', function () {
        $result = $this->provider->forget('some-id');

        expect($result)->toBeFalse();
    });

    it('returns empty array for ids() method', function () {
        $result = $this->provider->ids();

        expect($result)->toBeArray()->toBeEmpty();
    });

    it('returns empty array for ids() with queue parameter', function () {
        $result = $this->provider->ids('default');

        expect($result)->toBeArray()->toBeEmpty();
    });

    it('flush() method does nothing', function () {
        // This should not throw
        $this->provider->flush();

        expect(true)->toBeTrue();
    });

    it('flush() with hours parameter does nothing', function () {
        // This should not throw
        $this->provider->flush(24);

        expect(true)->toBeTrue();
    });

    it('uses key file authentication when configured', function () {
        $tempKeyFile = tempnam(sys_get_temp_dir(), 'key');
        file_put_contents($tempKeyFile, '{"type":"service_account"}');

        $config = [
            'project_id' => 'test-project',
            'auth_method' => 'key_file',
            'key_file' => $tempKeyFile,
            'monitoring' => [],
        ];

        $provider = new PubSubFailedJobProvider($config);

        // Get the client to ensure it uses key file config
        $reflection = new ReflectionClass($provider);
        $method = $reflection->getMethod('getPubSubClient');
        $method->setAccessible(true);

        // This will fail without actual credentials but confirms the path is used
        expect(fn () => $method->invoke($provider))
            ->toThrow(Exception::class);

        unlink($tempKeyFile);
    });

    it('caches pubsub client instance', function () {
        $mockClient = Mockery::mock(PubSubClient::class);

        $reflection = new ReflectionClass($this->provider);
        $property = $reflection->getProperty('pubsub');
        $property->setAccessible(true);
        $property->setValue($this->provider, $mockClient);

        $method = $reflection->getMethod('getPubSubClient');
        $method->setAccessible(true);

        $client1 = $method->invoke($this->provider);
        $client2 = $method->invoke($this->provider);

        expect($client1)->toBe($client2);
    });
});
