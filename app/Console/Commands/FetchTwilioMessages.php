<?php

namespace App\Console\Commands;

use App\Models\Message;
use Illuminate\Console\Command;
use Twilio\Rest\Client;

class FetchTwilioMessages extends Command
{
    const OUTBOUND_MESSAGE = 'outbound';
    const INBOUND_MESSAGE = 'inbound';

    protected $signature = 'app:fetch-twilio-messages';

    protected $description = 'Fetch Twilio messages';

    public function handle()
    {
        $twilio_client = new Client(config('twilio.twilio.sid'), config('twilio.twilio.token'));
        $messages = $twilio_client->messages->read([
            "dateSentAfter" => new \DateTime("2025-06-01T00:00:00Z"),
            "dateSentBefore" => new \DateTime("2025-06-22T23:59:59Z"),
        ]);
        $filtered_messages = [];
        foreach ($messages as $message) {
            $filtered_messages[] = [
                'date_sent' => $message->dateSent,
                'from_number' => $message->from,
                'to_number' => $message->to,
                'message_body' => $message->body,
                'type' => $this->getMessageType($message->from),
                'status' => $message->status,
                'error_message' => $message->errorMessage,
                'error_code' => $message->errorCode,
            ];
        }

        if ($filtered_messages) {
            Message::upsert($filtered_messages, ['from_number', 'to_number', 'date_sent']);
            echo "Messages fetched and inserted successfully\n";
        } else {
            echo "No messages found\n";
        }
    }

    private function getMessageType(string $from): string {
        return in_array($from, $this->getAccessNumbers()) ? self::OUTBOUND_MESSAGE : self::INBOUND_MESSAGE;
    }

    private function getAccessNumbers(): array {
        return array_map(function($number) {
            return formatHelper()->formatPhoneNumber($number);
        }, explode('|', config('twilio.twilio.number')));
    }
}