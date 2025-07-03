@include('partials.admin.header')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        <i class="fa fa-tags"></i>
                        Tags Management
                    </h4>
                    <p>Manage available tags for contacts with custom colors. These tags can be selected when creating or editing contacts.</p>
                    <h5 class="box-title m-t-30">
                        Add New Tag
                    </h5>

                    <form>
                        <div class="row">
                            <div class="col-lg-9">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-xs" id="tag-name" placeholder="Enter tag name" data-key="TagName" data="req">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <input type="text" class="colorpicker form-control form-control-xs" value="#7ab2fa" data-key="TagColor" data="req">
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <button class="btn btn-info btn-lg" data-trigger="add-tag">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <h4 class="box-title m-t-30">
                        Existing Tags (<span class="tag-count">0</span>)
                    </h4>
                    <div class="bootstrap-label existing-tags"></div>
                </div>
                
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <form>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <i class="fa fa-users"></i>
                            Default Extension Settings
                        </h4>
                        <p>Set default expiration times and extension number generation preferences for new customer extensions.</p>
                        <h5 class="box-title m-t-30">
                            Default Extension Expiration
                        </h5>
                        <form>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="number" class="form-control form-control-xs" placeholder="No of days" min="1" max="365" data-key="ExtensionExpirationDays" data="req">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="number" class="form-control form-control-xs" placeholder="No of hrs" min="0" max="24" data-key="ExtensionExpirationHrs">
                                    </div>
                                </div>
                            </div>
                        </form>

                    
                        <div class="form-group mb-0">
                            <div class="toggle-switch-container">
                                <span class="toggle-label">
                                    <h5 class="box-title m-t-30">
                                        Extension Number Generation
                                    </h5>
                                    <p class="text-muted">
                                        When enabled, new extenstions will use random 4 digit numbers instead of sequential ones.
                                        This prevents others from guessing extension numbers.
                                    </p>
                                </span>
                                <label class="toggle-switch">
                                    <input type="checkbox" id="random-extension-toggle" data-key="RandomExtensionGeneration">
                                    <span class="slider round"></span>
                                </label>
                                
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <button class="btn btn-flat btn-outline-info" data-trigger="save-extension-settings">
                            <i class="fa fa-save"></i> Save Extension Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('partials.admin.footer')

<script src="{{ asset('assets/axocall/js/modules/settings.js') }}"></script>