@if($xtype == 'dashboard')
    <ul class="nav nav-pills mb-3 justify-content-end">
        <li class="nav-item">
            <input class="form-control form-control-xs input-daterange-datepicker" type="text" name="daterange" value="">
        </li>

        <li class="nav-item">
            <button type="button" class="btn ml-1 mb-1 btn-flat btn-outline-danger" data-trigger="export-dashboard">Export Report</button>
        </li>
    </ul>
@elseif($xtype == 'contacts')
    <div class="d-flex justify-content-end align-middle">
        <ul class="nav nav-pills mb-3 justify-content-end align-middle">
            <li class="nav-item">
                <button type="button" class="btn ml-1 btn-flat btn-outline-danger" data-trigger="export-contacts">
                    <i class="fa fa-download"></i>
                    Export
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="btn ml-1 btn-flat  btn-outline-primary" data-trigger="modal"  data-modal="{{$xtype}}">
                    <i class="fa fa-user-plus"></i>
                    Add Contact
                </button>
            </li>
        </ul>
    </div>
@elseif($xtype == 'extensions')
    <div class="d-flex justify-content-end align-middle border-bottom">
        <ul class="nav nav-pills mb-3 justify-content-end align-middle">
            <li class="nav-item">
                <button type="button" class="btn ml-1 btn-flat btn-outline-danger" data-trigger="export-extensions">
                    <i class="fa fa-download"></i>
                    Export
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="btn ml-1 btn-flat btn-outline-primary" data-trigger="add-extension" data-modal="extension-modal">
                    <i class="fa fa-user-plus"></i>
                    Add Extension
                </button>
            </li>
        </ul>
    </div>
@elseif($xtype == 'communications')
    <div class="d-flex justify-content-end align-middle border-bottom">
        <ul class="nav nav-pills mb-3 justify-content-end align-middle">
            <li class="nav-item">
                <input class="form-control form-control-xs input-daterange-datepicker" type="text" name="daterange" value="01/01/2015 - 01/31/2015">
            </li>
            <li class="nav-item">
                <button type="button" class="btn ml-1 btn-flat btn-outline-danger" data-trigger="export-communications">
                    <i class="fa fa-download"></i>
                    Export
                </button>
            </li>
        </ul>
    </div>
@elseif($xtype == 'follow_ups')
    
    <ul class="nav nav-pills mb-3 justify-content-end align-middle">
        <li class="nav-item">
            <a href="#active" class="nav-link active text-center" data-toggle="tab" aria-expanded="false">
                <i class="fa fa-clock"></i>
                Active
            </a>   
        </li>
        <li class="nav-item">
            <a href="#archived" class="nav-link text-center" data-toggle="tab" aria-expanded="false">
                <i class="fa fa-archive"></i>
                Archived
            </a>
        </li>
    </ul>
    
@elseif($xtype == 'users')
    <div class="d-flex justify-content-end align-middle border-bottom">
        <ul class="nav nav-pills mb-3 justify-content-end align-middle">
            <li class="nav-item">
                <button type="button" class="btn ml-1  btn-flat btn-outline-primary" data-trigger="add-role" data-url="/maintenance/users/roles">
                    <i class="fa fa-user-cog"></i>
                    Roles
                </button>
            </li>
        </ul>
    </div>
@elseif($xtype == 'roles')
<div class="d-flex justify-content-end align-middle border-bottom">
    <ul class="nav nav-pills mb-3 justify-content-end align-middle">
        <li class="nav-item">
            <a href="/maintenance/users" class="btn ml-1 mr-1 btn-flat btn-outline-info">
                <i class="fa fa-arrow-left"></i>
                Users
            </a>
        </li>
        <li class="nav-item">
            <button type="button" class="btn ml-1  btn-flat btn-outline-primary" data-trigger="add-role">
                <i class="fa fa-plus"></i>
                Add Role
            </button>
        </li>
    </ul>
</div>
@endif
