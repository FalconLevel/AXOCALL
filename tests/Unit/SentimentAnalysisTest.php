<?php

namespace Tests\Unit;

use App\Services\SentimentAnalysisService;
use Tests\TestCase;

class SentimentAnalysisTest extends TestCase
{
    private $sentimentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sentimentService = new SentimentAnalysisService();
    }

    public function test_positive_sentiment_analysis()
    {
        $transcription = "I'm very interested in booking an appointment. This service sounds great and I'm happy to proceed.";
        
        $analysis = $this->sentimentService->analyzeCallRecording($transcription, 'inbound');
        
        $this->assertEquals('positive', $analysis['sentiment']);
        $this->assertEquals('yes', $analysis['is_booked']);
        $this->assertStringContainsString('interested', $analysis['keywords']);
        $this->assertStringContainsString('book', $analysis['keywords']);
    }

    public function test_negative_sentiment_analysis()
    {
        $transcription = "I'm very disappointed with the service. The price is too expensive and I'm frustrated with the quality.";
        
        $analysis = $this->sentimentService->analyzeCallRecording($transcription, 'inbound');
        
        $this->assertEquals('negative', $analysis['sentiment']);
        $this->assertEquals('no', $analysis['is_booked']);
        $this->assertStringContainsString('disappointed', $analysis['keywords']);
        $this->assertStringContainsString('expensive', $analysis['keywords']);
    }

    public function test_neutral_sentiment_analysis()
    {
        $transcription = "I just wanted to ask a question about your business hours.";
        
        $analysis = $this->sentimentService->analyzeCallRecording($transcription, 'inbound');
        
        $this->assertEquals('neutral', $analysis['sentiment']);
        $this->assertEquals('no', $analysis['is_booked']);
    }

    public function test_empty_transcription()
    {
        $analysis = $this->sentimentService->analyzeCallRecording('', 'inbound');
        
        $this->assertEquals('neutral', $analysis['sentiment']);
        $this->assertEquals('no', $analysis['is_booked']);
        $this->assertEquals('No transcription available', $analysis['summary']);
    }

    public function test_booking_detection()
    {
        $transcription = "Yes, I would like to confirm my appointment for next Tuesday at 2 PM.";
        
        $analysis = $this->sentimentService->analyzeCallRecording($transcription, 'inbound');
        
        $this->assertEquals('yes', $analysis['is_booked']);
        $this->assertStringContainsString('confirm', $analysis['keywords']);
        $this->assertStringContainsString('appointment', $analysis['keywords']);
    }

    public function test_call_intent_detection()
    {
        $inboundTranscription = "I need to book an appointment for a consultation.";
        $outboundTranscription = "This is a follow-up call regarding your recent appointment.";
        
        $inboundAnalysis = $this->sentimentService->analyzeCallRecording($inboundTranscription, 'inbound');
        $outboundAnalysis = $this->sentimentService->analyzeCallRecording($outboundTranscription, 'outbound');
        
        $this->assertEquals('Appointment booking request', $inboundAnalysis['call_intent']);
        $this->assertEquals('Outbound call', $outboundAnalysis['call_intent']);
    }
} 