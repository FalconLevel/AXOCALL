<?php

declare(strict_types=1);

namespace App\Services;

use Phpml\Classification\SVC;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\Tokenization\WordTokenizer;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SentimentAnalysisService
{
    private $classifier;
    private $vectorizer;
    private $transformer;
    
    // Keywords for different business contexts
    private $positiveKeywords = [
        'interested', 'yes', 'sure', 'great', 'good', 'excellent', 'perfect', 'love', 'like',
        'happy', 'satisfied', 'pleased', 'wonderful', 'amazing', 'fantastic', 'awesome',
        'book', 'appointment', 'schedule', 'available', 'confirm', 'definitely', 'absolutely',
        'thank you', 'thanks', 'appreciate', 'helpful', 'useful', 'beneficial'
    ];
    
    private $negativeKeywords = [
        'no', 'not', 'never', 'bad', 'terrible', 'awful', 'horrible', 'disappointed',
        'unhappy', 'angry', 'frustrated', 'annoyed', 'upset', 'worried', 'concerned',
        'expensive', 'costly', 'pricey', 'unaffordable', 'cancel', 'refund', 'complaint',
        'problem', 'issue', 'difficult', 'complicated', 'confusing', 'waste', 'useless'
    ];
    
    private $bookingKeywords = [
        'book', 'appointment', 'schedule', 'reservation', 'booking', 'available',
        'confirm', 'definitely', 'yes', 'sure', 'okay', 'alright', 'when', 'time',
        'date', 'calendar', 'reserve', 'hold', 'secure', 'set up'
    ];
    
    private $urgencyKeywords = [
        'urgent', 'emergency', 'asap', 'immediately', 'right away', 'soon', 'quick',
        'fast', 'hurry', 'rush', 'critical', 'important', 'priority', 'needed now'
    ];

    // Neutral keywords
    private $neutralKeywords = [
        'okay', 'fine', 'normal', 'average', 'alright', 'standard', 'regular', 'typical', 'ordinary', 'routine'
    ];

    public function __construct()
    {
        $this->initializeClassifier();
    }

    private function initializeClassifier()
    {
        // Initialize the SVM classifier for sentiment analysis
        $this->classifier = new SVC(Kernel::RBF, $cost = 1000);
        $this->vectorizer = new TokenCountVectorizer(new WordTokenizer());
        $this->transformer = new TfIdfTransformer();
    }

    /**
     * Analyze call recording and return comprehensive analysis
     */
    public function analyzeCallRecording(string $transcription, string $callType = 'inbound'): array
    {
        if (empty($transcription)) {
            $default = $this->getDefaultAnalysis();
            $default['sentiment_keyword_hits'] = [
                'positive' => 0,
                'negative' => 0,
                'neutral' => 0
            ];
            return $default;
        }

        $transcription = strtolower(trim($transcription));
        $sentimentKeywordHits = $this->countSentimentKeywordHits($transcription);
        return [
            'sentiment' => $this->analyzeSentiment($transcription),
            'keywords' => $this->extractKeywords($transcription),
            'summary' => $this->generateSummary($transcription, $callType),
            'is_booked' => $this->detectBooking($transcription),
            'urgency_level' => $this->detectUrgency($transcription),
            'confidence_score' => $this->calculateConfidence($transcription),
            'call_intent' => $this->detectCallIntent($transcription, $callType),
            'customer_satisfaction' => $this->assessCustomerSatisfaction($transcription),
            'sentiment_keyword_hits' => $sentimentKeywordHits
        ];
    }

    /**
     * Analyze sentiment using keyword-based approach
     */
    private function analyzeSentiment(string $text): string
    {
        $positiveCount = 0;
        $negativeCount = 0;
        
        // Count positive keywords
        foreach ($this->positiveKeywords as $keyword) {
            $count = substr_count($text, $keyword);
            $positiveCount += $count;
        }
        
        // Count negative keywords
        foreach ($this->negativeKeywords as $keyword) {
            $count = substr_count($text, $keyword);
            $negativeCount += $count;
        }
        
        // Calculate sentiment score
        $total = $positiveCount + $negativeCount;
        if ($total === 0) {
            return 'neutral';
        }
        
        $sentimentScore = ($positiveCount - $negativeCount) / $total;
        
        if ($sentimentScore > 0.1) {
            return 'positive';
        } elseif ($sentimentScore < -0.1) {
            return 'negative';
        } else {
            return 'neutral';
        }
    }

    /**
     * Extract relevant keywords from the transcription
     */
    private function extractKeywords(string $text): string
    {
        $keywords = [];
        $allKeywords = array_merge($this->positiveKeywords, $this->negativeKeywords, $this->bookingKeywords, $this->urgencyKeywords);
        
        foreach ($allKeywords as $keyword) {
            if (strpos($text, $keyword) !== false) {
                $keywords[] = $keyword;
            }
        }
        
        // Add business-specific keywords
        $businessKeywords = $this->extractBusinessKeywords($text);
        $keywords = array_merge($keywords, $businessKeywords);
        
        return implode(', ', array_unique($keywords));
    }

    /**
     * Generate a summary of the call
     */
    private function generateSummary(string $text, string $callType): string
    {
        $summary = [];
        
        // Detect call purpose
        if ($this->detectBooking($text)) {
            $summary[] = "Customer interested in booking";
        }
        
        if ($this->detectUrgency($text) === 'high') {
            $summary[] = "Urgent request";
        }
        
        // Detect sentiment
        $sentiment = $this->analyzeSentiment($text);
        if ($sentiment === 'positive') {
            $summary[] = "Positive customer interaction";
        } elseif ($sentiment === 'negative') {
            $summary[] = "Customer expressed concerns";
        }
        
        // Detect call intent
        $intent = $this->detectCallIntent($text, $callType);
        if ($intent) {
            $summary[] = $intent;
        }
        
        if (empty($summary)) {
            return "General inquiry call";
        }
        
        return implode('. ', $summary) . '.';
    }

    /**
     * Detect if the call resulted in a booking
     */
    private function detectBooking(string $text): string
    {
        $bookingCount = 0;
        
        foreach ($this->bookingKeywords as $keyword) {
            $count = substr_count($text, $keyword);
            $bookingCount += $count;
        }
        
        // Additional booking indicators
        if (strpos($text, 'confirm') !== false && strpos($text, 'appointment') !== false) {
            $bookingCount += 2;
        }
        
        if (strpos($text, 'yes') !== false && (strpos($text, 'book') !== false || strpos($text, 'schedule') !== false)) {
            $bookingCount += 3;
        }
        
        return $bookingCount >= 2 ? 'yes' : 'no';
    }

    /**
     * Detect urgency level
     */
    private function detectUrgency(string $text): string
    {
        $urgencyCount = 0;
        
        foreach ($this->urgencyKeywords as $keyword) {
            $count = substr_count($text, $keyword);
            $urgencyCount += $count;
        }
        
        if ($urgencyCount >= 3) {
            return 'high';
        } elseif ($urgencyCount >= 1) {
            return 'medium';
        }
        
        return 'low';
    }

    /**
     * Calculate confidence score for the analysis
     */
    private function calculateConfidence(string $text): float
    {
        $wordCount = str_word_count($text);
        $keywordCount = 0;
        
        $allKeywords = array_merge($this->positiveKeywords, $this->negativeKeywords, $this->bookingKeywords, $this->urgencyKeywords);
        
        foreach ($allKeywords as $keyword) {
            if (strpos($text, $keyword) !== false) {
                $keywordCount++;
            }
        }
        
        if ($wordCount === 0) {
            return 0.0;
        }
        
        $confidence = min(1.0, ($keywordCount / $wordCount) * 10);
        return round($confidence, 2);
    }

    /**
     * Detect the primary intent of the call
     */
    private function detectCallIntent(string $text, string $callType): string
    {
        if ($callType === 'inbound') {
            if (strpos($text, 'appointment') !== false || strpos($text, 'book') !== false) {
                return 'Appointment booking request';
            }
            if (strpos($text, 'question') !== false || strpos($text, 'ask') !== false) {
                return 'Information request';
            }
            if (strpos($text, 'complaint') !== false || strpos($text, 'problem') !== false) {
                return 'Customer service issue';
            }
            return 'General inquiry';
        } else {
            if (strpos($text, 'follow up') !== false) {
                return 'Follow-up call';
            }
            if (strpos($text, 'reminder') !== false) {
                return 'Appointment reminder';
            }
            return 'Outbound call';
        }
    }

    /**
     * Assess customer satisfaction level
     */
    private function assessCustomerSatisfaction(string $text): string
    {
        $positiveWords = ['satisfied', 'happy', 'pleased', 'great', 'excellent', 'love', 'like'];
        $negativeWords = ['dissatisfied', 'unhappy', 'disappointed', 'angry', 'frustrated', 'hate', 'dislike'];
        
        $positiveCount = 0;
        $negativeCount = 0;
        
        foreach ($positiveWords as $word) {
            $positiveCount += substr_count($text, $word);
        }
        
        foreach ($negativeWords as $word) {
            $negativeCount += substr_count($text, $word);
        }
        
        if ($positiveCount > $negativeCount) {
            return 'satisfied';
        } elseif ($negativeCount > $positiveCount) {
            return 'dissatisfied';
        }
        
        return 'neutral';
    }

    /**
     * Extract business-specific keywords
     */
    private function extractBusinessKeywords(string $text): array
    {
        $businessKeywords = [];
        
        // Common business terms
        $businessTerms = [
            'service', 'product', 'price', 'cost', 'payment', 'invoice', 'quote',
            'consultation', 'meeting', 'session', 'treatment', 'therapy', 'care',
            'health', 'medical', 'dental', 'legal', 'financial', 'insurance'
        ];
        
        foreach ($businessTerms as $term) {
            if (strpos($text, $term) !== false) {
                $businessKeywords[] = $term;
            }
        }
        
        return $businessKeywords;
    }

    /**
     * Get default analysis for empty transcriptions
     */
    private function getDefaultAnalysis(): array
    {
        return [
            'sentiment' => 'neutral',
            'keywords' => '',
            'summary' => 'No transcription available',
            'is_booked' => 'no',
            'urgency_level' => 'low',
            'confidence_score' => 0.0,
            'call_intent' => 'Unknown',
            'customer_satisfaction' => 'neutral'
        ];
    }

    /**
     * Use external AI service for more advanced analysis (optional)
     */
    public function analyzeWithExternalService(string $transcription): array
    {
        try {
            // This is a placeholder for integration with external AI services
            // like OpenAI GPT, Google Cloud Natural Language, or Azure Cognitive Services
            
            $response = Http::timeout(30)->post(config('services.ai.endpoint'), [
                'text' => $transcription,
                'api_key' => config('services.ai.key')
            ]);
            
            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('External AI service error: ' . $e->getMessage());
        }
        
        // Fallback to local analysis
        return $this->analyzeCallRecording($transcription);
    }

    /**
     * Count keyword hits for positive, negative, and neutral sentiment
     */
    private function countSentimentKeywordHits(string $text): array
    {
        $positiveCount = 0;
        $negativeCount = 0;
        $neutralCount = 0;

        foreach ($this->positiveKeywords as $keyword) {
            $positiveCount += substr_count($text, $keyword);
        }
        foreach ($this->negativeKeywords as $keyword) {
            $negativeCount += substr_count($text, $keyword);
        }
        foreach ($this->neutralKeywords as $keyword) {
            $neutralCount += substr_count($text, $keyword);
        }

        return [
            'positive' => $positiveCount .'/'.count($this->positiveKeywords),
            'negative' => $negativeCount .'/'.count($this->negativeKeywords),
            'neutral' => $neutralCount .'/'.count($this->neutralKeywords)
        ];
    }
} 