<div class="row">
    <div class="col-lg-12">
        <div class="card card-border-radius-0">
            <div class="card-body">
                
                <h4 class="card-title card-header-title">
                    <i class="fa fa-volume-up"></i>
                    Audio & Messages Prompts
                </h4>
                <p>Manage audio files for system prompts and related text messages. Upload .mp3 or .wav files. Default audio files are placeholders; upload your own for full functionality.</p>
                <hr />
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-border-radius-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fa fa-cloud-upload"></i>
                                    Access Number Greeeting Audio
                                </h5>
                                <p class="text-muted">
                                    Plays when user calls the access number (e.g., "Please enter extension...")
                                </p>
                                <div class="form-group">
                                    <input type="file" class="form-control form-control-xs" data-key="AccessNumberGreetingAudio">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-border-radius-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fa fa-cloud-upload"></i>
                                    Connecting Message Audio
                                </h5>
                                <p class="text-muted">
                                    Plays after user inputs a valid extension.
                                </p>
                                <div class="form-group">
                                    <input type="file" class="form-control form-control-xs" data-key="ConnectingMessageAudio">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-border-radius-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fa fa-cloud-upload"></i>
                                    Extension Error Audio
                                </h5>
                                <p class="text-muted">
                                    Plays if no input is detected after greeting.
                                </p>
                                <div class="form-group">
                                    <input type="file" class="form-control form-control-xs" data-key="AccessNumberGreetingAudio">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-border-radius-0 shadow-sm" >
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    Extension Error Text Message
                                </h5>
                                <p class="text-muted">
                                    This SMS is sent if a caller texts an invalid extension to the access number.
                                </p>
                                <div class="form-group">
                                    <textarea name="" id="" rows="3" class="form-control form-control-xs" data-key="ExtensionErrorMessage"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>