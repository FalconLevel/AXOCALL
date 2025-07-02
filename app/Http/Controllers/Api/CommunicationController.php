<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Communication;
use App\Models\Message;
use App\Services\SentimentAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CommunicationController extends Controller {
    
    private $sentimentService;
    
    public function __construct(SentimentAnalysisService $sentimentService)
    {
        $this->sentimentService = $sentimentService;
    }
    
    public function all() {
        return Communication::orderBy('date_time', 'desc')->paginate(10);
    }
    
    /**
     * Get communications with sentiment analysis
     */
    public function withSentiment(Request $request): JsonResponse
    {
        $query = Communication::query();
        
        // Filter by sentiment
        if ($request->has('sentiment')) {
            $query->where('sentiment', $request->sentiment);
        }
        
        // Filter by call type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        // Filter by booking status
        if ($request->has('is_booked')) {
            $query->where('is_booked', $request->is_booked);
        }
        
        // Filter by date range
        if ($request->has('date_from')) {
            $query->where('date_time', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->where('date_time', '<=', $request->date_to);
        }
        
        $communications = $query->orderBy('date_time', 'desc')->paginate(10);
        
        return response()->json([
            'success' => true,
            'data' => $communications,
            'filters' => $request->only(['sentiment', 'type', 'is_booked', 'date_from', 'date_to'])
        ]);
    }
    
    /**
     * Analyze specific communication
     */
    public function analyze(string $id): JsonResponse
    {
        $communication = Communication::findOrFail($id);
        
        if (empty($communication->transcriptions)) {
            return response()->json([
                'success' => false,
                'message' => 'No transcription available for analysis'
            ], 400);
        }
        
        $analysis = $this->sentimentService->analyzeCallRecording(
            $communication->transcriptions,
            $communication->type
        );
        
        // Update the communication record
        $communication->update([
            'sentiment' => $analysis['sentiment'],
            'keywords' => $analysis['keywords'],
            'summary' => $analysis['summary'],
            'is_booked' => $analysis['is_booked'],
        ]);
        
        return response()->json([
            'success' => true,
            'data' => [
                'communication' => $communication,
                'analysis' => $analysis
            ]
        ]);
    }
    
    /**
     * Get sentiment statistics
     */
    public function sentimentStats(): JsonResponse
    {
        $stats = [
            'total_calls' => Communication::count(),
            'positive_calls' => Communication::where('sentiment', 'positive')->count(),
            'negative_calls' => Communication::where('sentiment', 'negative')->count(),
            'neutral_calls' => Communication::where('sentiment', 'neutral')->count(),
            'booked_calls' => Communication::where('is_booked', 'yes')->count(),
        ];
        
        if ($stats['total_calls'] > 0) {
            $stats['positive_percentage'] = round(($stats['positive_calls'] / $stats['total_calls']) * 100, 2);
            $stats['negative_percentage'] = round(($stats['negative_calls'] / $stats['total_calls']) * 100, 2);
            $stats['booking_rate'] = round(($stats['booked_calls'] / $stats['total_calls']) * 100, 2);
        }
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
    
    /**
     * Get top keywords
     */
    public function topKeywords(): JsonResponse
    {
        $communications = Communication::whereNotNull('keywords')
            ->where('keywords', '!=', '')
            ->get();
        
        $keywordCounts = [];
        
        foreach ($communications as $communication) {
            $keywords = explode(', ', $communication->keywords);
            foreach ($keywords as $keyword) {
                $keyword = trim($keyword);
                if (!empty($keyword)) {
                    $keywordCounts[$keyword] = ($keywordCounts[$keyword] ?? 0) + 1;
                }
            }
        }
        
        arsort($keywordCounts);
        $topKeywords = array_slice($keywordCounts, 0, 20, true);
        
        return response()->json([
            'success' => true,
            'data' => $topKeywords
        ]);
    }
    
    /**
     * Get communication details
     */
    public function show(string $id): JsonResponse
    {
        $communication = Communication::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $communication
        ]);
    }
    
    /**
     * Update communication notes
     */
    public function updateNotes(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'notes' => 'required|string|max:1000'
        ]);
        
        $communication = Communication::findOrFail($id);
        $communication->update(['notes' => $request->notes]);
        
        return response()->json([
            'success' => true,
            'message' => 'Notes updated successfully',
            'data' => $communication
        ]);
    }

    /**
     * Transcribe a specific communication
     */
    public function transcribe(string $id): JsonResponse
    {
        $communication = Communication::findOrFail($id);
        
        if (empty($communication->recording_filename)) {
            return response()->json([
                'success' => false,
                'message' => 'No recording file available for transcription'
            ], 400);
        }

        try {
            $transcriptionService = app(\App\Services\TranscriptionService::class);
            
            $transcription = $transcriptionService->transcribeRecording(
                $communication->recording_filename,
                $communication->recording_sid
            );

            // Update the communication record
            $communication->update([
                'transcription_sid' => $transcription['transcription_sid'],
                'transcriptions' => $transcription['transcription_text'],
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'communication' => $communication,
                    'transcription' => $transcription
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Transcription failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transcription status for communications
     */
    public function transcriptionStats(): JsonResponse
    {
        $stats = [
            'total_recordings' => Communication::whereNotNull('recording_filename')->count(),
            'transcribed_recordings' => Communication::whereNotNull('transcriptions')->count(),
            'pending_transcriptions' => Communication::whereNotNull('recording_filename')
                ->whereNull('transcriptions')->count(),
        ];
        
        if ($stats['total_recordings'] > 0) {
            $stats['transcription_rate'] = round(($stats['transcribed_recordings'] / $stats['total_recordings']) * 100, 2);
        } else {
            $stats['transcription_rate'] = 0;
        }
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function archive(string $id, string $type): JsonResponse
    {
        try {
            if ($type == "communication") {
                $communication = Communication::find($id);
                $communication->update([
                    'is_archived' => 'yes'
                ]);
            } elseif ($type == "message") {
                $message = Message::find($id);
                $message->update([
                    'is_archived' => 'yes'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => ucfirst($type) . ' archived successfully',
                'data' => $communication ?? $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to archive ' . ucfirst($type) . ' ' . $id,
                'error' => $e->getMessage()
            ], 500);
        } 
    }

    public function unArchive(string $id, string $type): JsonResponse
    {
        try {
            if ($type == "communication") {
                $communication = Communication::find($id);
                $communication->update([
                    'is_archived' => 'no'
                ]);
            } elseif ($type == "message") { 
                $message = Message::find($id);
                $message->update([
                    'is_archived' => 'no'
                ]);
            }
            return response()->json([
                'success' => true,
                'message' => ucfirst($type) . ' unarchived successfully',
                'data' => $communication ?? $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to unarchive ' . ucfirst($type) . ' ' . $id,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function transcribeRecording(Request $request)
    {
        return $transcription = recordingHelper()->transcribeRecording('','');
        return response()->json([
            'success' => true,
            'data' => $transcription
        ]);
    }
}