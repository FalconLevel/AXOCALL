<div class="row">
    <div class="col-lg-12">
        <form>
            <div class="card card-border-radius-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title card-header-title">
                            <i class="fa fa-phone"></i>
                            Smart Callback Settings
                        </h4>
                        <label class="toggle-switch">
                            <input type="checkbox" id="smart-callback-toggle" data-key="SmartCallbackIsActive">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <p>Configure intelligent routing for return calls/text based on recent interaction times.</p>
                    <hr />
                    <div class="form-group mb-0">
                        <div class="toggle-switch-container">
                            <span class="toggle-label">
                                <h5 class="box-title">
                                    Extension Number Generation
                                </h5>
                                <p class="text-muted">
                                    When enabled, return calls or texts from a customer will be routed back to the last agent they interacted 
                                    with if the contact occurs within the specified duration after their last interaction and the agent's extension is active.
                                    The caller ID presented to the agent will be the access number. Otherwise, communications route to the main company number.
                                </p>
                            </span>
                            
                        </div>
                    </div>
                        

                    <form>
                        <div class="row mt-3">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">Hours (0-23)</label>
                                    <input type="number" class="form-control form-control-xs" placeholder="0" min="0" max="23" data-key="SmartCallbackHours" data="req">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">Minutes (0-59)</label>
                                    <input type="number" class="form-control form-control-xs" placeholder="0" min="0" max="59" data-key="SmartCallbackMinutes">
                                </div>
                            </div>
                            
                        </div>
                    </form>                
                </div>

                <div class="card-footer text-right">
                    <button class="btn btn-flat btn-outline-info" data-trigger="save-smart-callback">
                        <i class="fa fa-save"></i> Save Smart Callback
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>