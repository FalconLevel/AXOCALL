<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Communication;
use App\Services\SentimentAnalysisService;
use Illuminate\Console\Command;
use Twilio\Rest\Client;

class AnalyzeCallRecordings extends Command
{
    protected $signature = 'app:analyze-call-recordings {--call-sid= : Analyze specific call by SID} {--all : Analyze all calls without sentiment}';
    protected $description = 'Analyze call recordings and add sentiment analysis';

    private $sentimentService;
    private $twilio_client;

    public function __construct(SentimentAnalysisService $sentimentService)
    {
        parent::__construct();
        $this->sentimentService = $sentimentService;
        $this->twilio_client = new Client(config('twilio.twilio.sid'), config('twilio.twilio.token'));
    }

    public function handle()
    {
        $callSid = $this->option('call-sid');
        $analyzeAll = $this->option('all');

        if ($callSid) {
            $this->analyzeSpecificCall($callSid);
        } else {
            $this->analyzeAllCalls($analyzeAll);
        }
    }

    private function analyzeSpecificCall(string $callSid)
    {
        $communication = Communication::where('call_sid', $callSid)->first();
        
        if (!$communication) {
            $this->error("Call with SID {$callSid} not found.");
            return;
        }

        $this->info("Analyzing call: {$callSid}");
        $this->analyzeCommunication($communication);
    }

    private function analyzeAllCalls(bool $analyzeAll)
    {
        $query = Communication::whereNull('sentiment');
        
        if (!$analyzeAll) {
            $query->whereNotNull('transcriptions');
        }

        $communications = $query->get();
        
        if ($communications->isEmpty()) {
            $this->info("No calls found for analysis.");
            return;
        }

        $this->info("Found {$communications->count()} calls to analyze.");
        
        $bar = $this->output->createProgressBar($communications->count());
        $bar->start();

        foreach ($communications as $communication) {
            $this->analyzeCommunication($communication);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Analysis completed successfully!");
    }

    private function analyzeCommunication(Communication $communication)
    {
        try {
            // Get transcription if not available
            if (empty($communication->transcriptions)) {
                $transcription = $this->getTranscription($communication->call_sid);
                if ($transcription) {
                    $communication->transcriptions = $transcription;
                }
            }

            // Perform sentiment analysis
            $analysis = $this->sentimentService->analyzeCallRecording(
                $communication->transcriptions ?? '',
                $communication->type
            );

            // Update communication record
            $communication->update([
                'sentiment' => $analysis['sentiment'],
                'keywords' => $analysis['keywords'],
                'summary' => $analysis['summary'],
                'is_booked' => $analysis['is_booked'],
                'notes' => $this->generateNotes($analysis)
            ]);

        } catch (\Exception $e) {
            $this->error("Error analyzing call {$communication->call_sid}: " . $e->getMessage());
        }
    }

    private function getTranscription(string $callSid): ?string
    {
        try {
            // For now, return null as transcriptions might not be available
            // This can be enhanced later with proper Twilio transcription API
            return null;
        } catch (\Exception $e) {
            $this->warn("Could not fetch transcription for call {$callSid}: " . $e->getMessage());
        }

        return null;
    }

    private function generateNotes(array $analysis): string
    {
        $notes = [];
        
        if ($analysis['urgency_level'] === 'high') {
            $notes[] = "High urgency call";
        }
        
        if ($analysis['confidence_score'] < 0.3) {
            $notes[] = "Low confidence analysis";
        }
        
        if ($analysis['customer_satisfaction'] === 'dissatisfied') {
            $notes[] = "Customer satisfaction concerns";
        }
        
        return implode('. ', $notes);
    }
} 