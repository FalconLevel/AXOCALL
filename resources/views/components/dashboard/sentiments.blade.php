<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body pb-0 d-flex justify-content-between">
                        <div>
                            <h4 class="mb-1">Call Sentiments</h4>
                            <p>Distribution of call sentiments</p>
                        </div>
                    </div>
                    <div class="card-body py-0">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card shadow-sm">
                                    <div class="card-header d-flex justify-content-between">
                                        <h5 class="card-title">Positive</h5>
                                        <div class="card-tools">
                                            <i class="fa-solid {{ config('twilio.sentiment.positive') }}"></i>
                                        </div>
                                    </div>
                                    <div class="card-body py-0">
                                        <h1 class="text-success">{{ $dashboard_data['total_positive_calls'] }}</h1>
                                        <p class="text-muted">
                                            {{ 
                                                $dashboard_data['total_calls_with_sentiment'] > 0 ?
                                                number_format($dashboard_data['total_positive_calls'] / $dashboard_data['total_calls_with_sentiment'] * 100, 2) : 0
                                                }}% 
                                                of 
                                            {{ $dashboard_data['total_calls_with_sentiment'] }} calls
                                        </p>
                                    </div>
                                </div>    
                            </div>

                            <div class="col-lg-4">
                                <div class="card shadow-sm">
                                    <div class="card-header d-flex justify-content-between">
                                        <h5 class="card-title">Neutral</h5>
                                        <div class="card-tools">
                                            <i class="fa-solid {{ config('twilio.sentiment.neutral') }}"></i>
                                        </div>
                                    </div>
                                    <div class="card-body py-0">
                                        <h1 class="text-warning">{{ $dashboard_data['total_neutral_calls'] }}</h1>
                                        <p class="text-muted">
                                            {{ 
                                                $dashboard_data['total_calls_with_sentiment'] > 0 ?
                                                number_format($dashboard_data['total_neutral_calls'] / $dashboard_data['total_calls_with_sentiment'] * 100, 2) : 0
                                                }}% 
                                                of 
                                            {{ $dashboard_data['total_calls_with_sentiment'] }} calls
                                        </p>
                                    </div>
                                </div>    
                            </div>

                            <div class="col-lg-4">
                                <div class="card shadow-sm">
                                    <div class="card-header d-flex justify-content-between">
                                        <h5 class="card-title">Negative</h5>
                                        <div class="card-tools">
                                            <i class="fa-solid {{ config('twilio.sentiment.negative') }}"></i>
                                        </div>
                                    </div>
                                    <div class="card-body py-0">
                                        <h1 class="text-danger">{{ $dashboard_data['total_negative_calls'] }}</h1>
                                        <p class="text-muted">
                                            {{ 
                                                $dashboard_data['total_calls_with_sentiment'] > 0 ?
                                                    number_format($dashboard_data['total_negative_calls'] / $dashboard_data['total_calls_with_sentiment'] * 100, 2) : 0
                                                }}% 
                                                of 
                                            {{ $dashboard_data['total_calls_with_sentiment'] }} calls
                                        </p>
                                    </div>
                                </div>    
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>