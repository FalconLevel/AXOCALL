<div class="row">
    <div class="col-lg-12">
        
            <div class="card card-border-radius-0">
                <div class="card-body">      
                    <h4 class="card-title card-header-title">
                        <i class="fa fa-phone"></i>
                        System Numbers
                    </h4>
                    <p>These are your system-assigned numbers and cannot be changed here.</p>
                    
                    <form>
                        <div class="row mt-4">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">Main Caller ID Number</label>
                                    <input 
                                        type="text" 
                                        class="form-control form-control-xs" 
                                        placeholder="+1 (555) 000-1111" 
                                        data-key="MainCallerIdNumber" 
                                        value="{{ isset($system_numbers[0]) ? $system_numbers[0] : '' }}"
                                        disabled
                                    >
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">SMS Sender ID</label>
                                    <input 
                                        type="text" 
                                        class="form-control form-control-xs" 
                                        placeholder="+1 (555) 000-2222" 
                                        data-key="SmsSenderId" 
                                        value="{{ isset($system_numbers[1]) ? $system_numbers[1] : '' }}"
                                        disabled>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">Access Number</label>
                                    <input 
                                        type="text" 
                                        class="form-control form-control-xs" 
                                        placeholder="+1 (555) 000-3333" 
                                        data-key="AccessNumber" 
                                        value="{{ isset($system_numbers[2]) ? $system_numbers[2] : '' }}"
                                        disabled>
                                </div>
                            </div>
                        </div>
                    </form>                
                </div>

            </div>
        
    </div>
</div>