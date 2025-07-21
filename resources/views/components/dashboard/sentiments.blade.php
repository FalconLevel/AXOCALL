
<div class="card card-border-radius-0">
    <div class="card-body pb-0 d-flex justify-content-between">
        <div>
            <h4 class="mb-1">Call Sentiments</h4>
            <p>Distribution of call sentiments</p>
        </div>
    </div>
    <div class="card-body py-0">
        <div class="row">
            <div class="col-lg-4">
                <div class="card shadow-sm card-border-radius-0">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title">Positive</h5>
                        <div class="card-tools">
                            <i class="fa-solid {{ config('twilio.sentiment.positive') }}"></i>
                        </div>
                    </div>
                    <div class="card-body py-0">
                        <h1 class="text-success total-positive-calls"></h1>
                        <p class="text-muted total-positive-calls-percentage"></p>
                    </div>
                </div>    
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm card-border-radius-0">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title">Neutral</h5>
                        <div class="card-tools">
                            <i class="fa-solid {{ config('twilio.sentiment.neutral') }}"></i>
                        </div>
                    </div>
                    <div class="card-body py-0">
                        <h1 class="text-warning total-neutral-calls"></h1>
                        <p class="text-muted total-neutral-calls-percentage"></p>
                    </div>
                </div>    
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm card-border-radius-0">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title">Negative</h5>
                        <div class="card-tools">
                            <i class="fa-solid {{ config('twilio.sentiment.negative') }}"></i>
                        </div>
                    </div>
                    <div class="card-body py-0">
                        <h1 class="text-danger total-negative-calls"></h1>
                        <p class="text-muted total-negative-calls-percentage"></p>
                    </div>
                </div>    
            </div>
            
        </div>
    </div>
</div>
