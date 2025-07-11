
<div class="card">
    <div class="card-body pb-0 d-flex justify-content-between">
        <div>
            <h4 class="mb-1">Keyword Performance</h4>
            <p>Tracing keyword usage in call transcripts.</p>
        </div>
        <div class="card-tools">
            <i 
                class="fas fa-eye text-primary cursor-pointer" 
                title="View Details" 
                data-trigger="keywords-details-modal"
                data-keyword-hits="{{ json_encode($dashboard_data['keywords_hits']) }}"
                data-total-communications="{{ $dashboard_data['total_communications'] }}"
            ></i>
        </div>
    </div>
    <div class="card-body py-0">
        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title">Overall Keywords Hit Rate</h5>
                        <div class="card-tools">
                            <i class="fas fa-bullseye text-danger"></i>
                        </div>
                    </div>
                    <div class="card-body py-0">
                        <h1 class="text-danger total-keywords-hit-rate">{{ $dashboard_data['overall_keywords_hit_rate'] }}</h1>
                        <p class="text-muted">Accross all calls in period</p>
                    </div>
                </div>    
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title">Calls w/ Missed Keywords</h5>
                        <div class="card-tools">
                            <i class="far fa-times-circle text-danger"></i>
                        </div>
                    </div>
                    <div class="card-body py-0">
                        <h1 class="text-danger total-missed-keywords">{{ $dashboard_data['keywords_missed'] }}</h1>
                        <p class="text-muted">Calls missing some keywords</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="keywords-details-modal" tabindex="-1" aria-labelledby="keywords-details-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="keywords-details-modal-label">Detailed Keyword Hit Rates</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-lg-12" id="keywords-details-modal-body"></div>
                </div>
            </div>
        </div>
    </div>
</div>  