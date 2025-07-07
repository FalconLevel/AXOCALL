@if($xtype == 'dashboard')
    <ul class="nav nav-pills mb-3 justify-content-end">
        <li class="nav-item">
            <a href="#navpills-1" class="nav-link active" data-trigger="dashboard-today" data-toggle="tab" aria-expanded="false">
                Today
            </a>
        </li>
        <li class="nav-item">
            <a href="#navpills-2" class="nav-link" data-trigger="dashboard-week" data-toggle="tab" aria-expanded="false">
                Week
            </a>
        </li>
        <li class="nav-item">
            <a href="#navpills-3" class="nav-link" data-trigger="dashboard-all-time" data-toggle="tab" aria-expanded="false">
                All Time
            </a>
        </li>
        <li class="nav-item">
            <input class="form-control input-daterange-datepicker" type="hidden" name="daterange" value="01/01/2015 - 01/31/2015">
            <a href="#navpills-3" class="nav-link" data-trigger="dashboard-custom" data-toggle="tab" aria-expanded="false">

                Custom
            </a>
        </li>

        <li class="nav-item">
            {{-- <a href="#navpills-3" class="nav-link" data-toggle="tab" aria-expanded="true"> --}}
                <button type="button" class="btn ml-1 mb-1  btn-outline-danger">Export Report</button>
                
            {{-- </a> --}}
        </li>
    </ul>
@elseif($xtype == 'contacts')
    <div class="d-flex justify-content-end align-middle">
        {{-- <input type="text" class="form-control form-control-xs w-50" placeholder="Search Contact"> --}}
        <ul class="nav nav-pills mb-3 justify-content-end align-middle">
            <li class="nav-item">
                <button type="button" class="btn ml-1  btn-outline-danger" data-trigger="export-contacts">
                    <i class="fa fa-download"></i>
                    Export
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="btn ml-1  btn-outline-primary" data-trigger="modal"  data-modal="{{$xtype}}">
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
                <button type="button" class="btn ml-1  btn-outline-danger" data-trigger="export-extensions">
                    <i class="fa fa-download"></i>
                    Export
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="btn ml-1  btn-outline-primary" data-trigger="add-extension" data-modal="extension-modal">
                    <i class="fa fa-user-plus"></i>
                    Add Extension
                </button>
            </li>
        </ul>
    </div>
@elseif($xtype == 'communications')
    <div class="d-flex justify-content-end align-middle border-bottom" data-trigger="export-communications">
        <ul class="nav nav-pills mb-3 justify-content-end align-middle">
            <li class="nav-item">
                <button type="button" class="btn ml-1  btn-outline-danger">
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
    
@endif
