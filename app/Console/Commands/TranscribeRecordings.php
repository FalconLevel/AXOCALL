<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Communication;
use App\Services\TranscriptionService;
use Illuminate\Console\Command;

class TranscribeRecordings extends Command
{
    protected $signature = 'app:transcribe-recordings 
                            {--recording-sid= : Transcribe specific recording by SID}
                            {--call-sid= : Transcribe all recordings for a specific call}
                            {--all : Transcribe all recordings without transcriptions}
                            {--service= : Specify transcription service (openai, google, twilio)}';
    
    protected $description = 'Transcribe call recordings using various services';

    private $transcriptionService;

    public function __construct(TranscriptionService $transcriptionService)
    {
        parent::__construct();
        $this->transcriptionService = $transcriptionService;
    }

    public function handle()
    {
        $recordingSid = $this->option('recording-sid');
        $callSid = $this->option('call-sid');
        $transcribeAll = $this->option('all');
        $service = $this->option('service');

        if ($recordingSid) {
            $this->transcribeSpecificRecording($recordingSid, $service);
        } elseif ($callSid) {
            $this->transcribeCallRecordings($callSid, $service);
        } else {
            $this->transcribeAllRecordings($transcribeAll, $service);
        }
    }

    private function transcribeSpecificRecording(string $recordingSid, ?string $service)
    {
        $communication = Communication::where('recording_sid', $recordingSid)->first();
        
        if (!$communication) {
            $this->error("Recording with SID {$recordingSid} not found.");
            return;
        }

        $this->info("Transcribing recording: {$recordingSid}");
        $this->transcribeCommunication($communication, $service);
    }

    private function transcribeCallRecordings(string $callSid, ?string $service)
    {
        $communications = Communication::where('call_sid', $callSid)->get();
        
        if ($communications->isEmpty()) {
            $this->error("No recordings found for call SID {$callSid}.");
            return;
        }

        $this->info("Found {$communications->count()} recordings for call {$callSid}");
        
        $bar = $this->output->createProgressBar($communications->count());
        $bar->start();

        foreach ($communications as $communication) {
            $this->transcribeCommunication($communication, $service);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Transcription completed for call {$callSid}!");
    }

    private function transcribeAllRecordings(bool $transcribeAll, ?string $service)
    {
        $query = Communication::whereNotNull('recording_filename')
            ->where('recording_filename', '!=', '');
        
        if (!$transcribeAll) {
            $query->whereNull('transcriptions');
        }

        $communications = $query->get();
        
        if ($communications->isEmpty()) {
            $this->info("No recordings found for transcription.");
            return;
        }

        $this->info("Found {$communications->count()} recordings to transcribe.");
        
        $bar = $this->output->createProgressBar($communications->count());
        $bar->start();

        foreach ($communications as $communication) {
            $this->transcribeCommunication($communication, $service);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Transcription completed successfully!");
    }

    private function transcribeCommunication(Communication $communication, ?string $service)
    {
        try {
            if (empty($communication->recording_filename)) {
                $this->warn("No recording file for communication {$communication->id}");
                return;
            }

            // Perform transcription
            $transcription = $this->transcriptionService->transcribeRecording(
                $communication->recording_filename,
                $communication->recording_sid
            );

            // Update communication record
            $communication->update([
                'transcription_sid' => $transcription['transcription_sid'],
                'transcriptions' => $transcription['transcription_text'],
            ]);

            if ($transcription['transcription_text']) {
                $this->info("✓ Transcribed: {$communication->recording_sid} (Source: {$transcription['source']})");
            } else {
                $this->warn("✗ No transcription available: {$communication->recording_sid}");
            }

        } catch (\Exception $e) {
            $this->error("Error transcribing communication {$communication->id}: " . $e->getMessage());
        }
    }
} 