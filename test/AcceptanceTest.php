<?php

use Domain\Email;
use Infrastructure\FileSystemEmployeeRepository;
use PHPUnit\Framework\TestCase;

class AcceptanceTest extends TestCase
{
    /** @var Email[] */
    private ?array $messagesSent = [];

    private ?BirthdayService $service;

    public function setUp(): void
    {
        $messageHandler = function (Email $msg) {
            $this->messagesSent[] = $msg;
        };

        $this->service = new TestableBirthdayService(new FileSystemEmployeeRepository());
        $this->service->setMessageHandler($messageHandler->bindTo($this));
    }

    public function tearDown(): void
    {
        $this->service = $this->messagesSent = null;
    }

    /** @test */
    public function willSendGreetings_whenItsSomebodysBirthday()
    {
        $this->service->sendGreetings(new XDate('2008/10/08'));

        $this->assertCount(1, $this->messagesSent, 'message not sent?');
        $message = $this->messagesSent[0];
        $this->assertEquals('Happy Birthday, dear John!', $message->body);
        $this->assertEquals('Happy Birthday!', $message->subject);
        $this->assertEquals('john.doe@foobar.com', $message->recipient);
    }

    /** @test */
    public function willNotSendEmailsWhenNobodysBirthday()
    {
        $this->service->sendGreetings(new XDate('2008/01/01'));

        $this->assertCount(0, $this->messagesSent, 'what? messages?');
    }
}

class TestableBirthdayService extends BirthdayService
{
    private Closure $callback;

    public function setMessageHandler(Closure $callback): static
    {
        $this->callback = $callback;

        return $this;
    }

    protected function doSendMessage(Email $msg): void
    {
        $callable = $this->callback;
        $callable($msg);
    }
}
