<div class="row">
    <div class="col-lg-12">
        <form>
            <div class="card ">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">
                            <i class="fa fa-envelope"></i>
                            Email Summaries
                        </h4>
                        <label class="toggle-switch">
                            <input type="checkbox" id="email-summaries-toggle" data-key="IsEmailSummaries">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <p>Configure and toggle daily or weekly email summaries of dashboard activity.</p>
                    
                    <form>
                        <div class="row">
                            
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <h5 class="box-title mt-3">
                                        Daily Email Summary
                                    </h5>
                                    <input type="text" class="form-control form-control-xs" placeholder="Email Addresses" data-key="EmailAddresses" data="req">
                                    <p class="text-muted mt-1">Enter email addresses separated by commas. Example: "john@example.com, jane@example.com"</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                
                                <div class="form-group">
                                    <h5 class="box-title mt-3">
                                        Frequency
                                    </h5>
                                    <select class="form-control form-control-xs" data-key="Frequency" data="req">
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 d-none" id="day-of-week-container">
                                <div class="form-group">
                                    <h5 class="box-title mt-3">
                                        Day of the Week
                                    </h5>
                                    <select class="form-control form-control-xs" data-key="DayOfWeek" data="req">
                                        <option value="monday">Monday</option>
                                        <option value="tuesday">Tuesday</option>
                                        <option value="wednesday">Wednesday</option>
                                        <option value="thursday">Thursday</option>
                                        <option value="friday">Friday</option>
                                        <option value="saturday">Saturday</option>
                                        <option value="sunday">Sunday</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

                <div class="card-footer text-right">
                    <button class="btn btn-flat btn-outline-info" data-trigger="save-email-settings">
                        <i class="fa fa-save"></i> Save Email Settings
                    </button>
                </div>  
            </div>
        </form>
    </div>
</div>