@if($xtype == 'dashboard')
    <ul class="nav nav-pills mb-3 justify-content-end">
        <li class="nav-item">
            <a href="#navpills-1" class="nav-link active" data-toggle="tab" aria-expanded="false">
                Today
            </a>
        </li>
        <li class="nav-item">
            <a href="#navpills-2" class="nav-link" data-toggle="tab" aria-expanded="false">
                Week
            </a>
        </li>
        <li class="nav-item">
            <a href="#navpills-3" class="nav-link" data-toggle="tab" aria-expanded="true">
                All Time
            </a>
        </li>
        <li class="nav-item">
            <a href="#navpills-3" class="nav-link" data-toggle="tab" aria-expanded="true">
                Custom
            </a>
        </li>

        <li class="nav-item">
            {{-- <a href="#navpills-3" class="nav-link" data-toggle="tab" aria-expanded="true"> --}}
                <button type="button" class="btn mb-1  btn-outline-danger">Export Report</button>
                
            {{-- </a> --}}
        </li>
    </ul>
@elseif($xtype == 'contacts')
    <div class="d-flex justify-content-end align-middle border-bottom">
        <input type="text" class="form-control form-control-xs w-50" placeholder="Search Contact">
        <ul class="nav nav-pills mb-3 justify-content-end align-middle">
            <li class="nav-item">
                <button type="button" class="btn ml-1  btn-outline-danger">
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
        <input type="text" class="form-control form-control-xs w-50" placeholder="Search Extensions">
        <ul class="nav nav-pills mb-3 justify-content-end align-middle">
            <li class="nav-item">
                <button type="button" class="btn ml-1  btn-outline-danger">
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
    <div class="d-flex justify-content-end align-middle border-bottom">
        <input type="text" class="form-control form-control-xs w-50" placeholder="Search Logs">
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
                <button type="button" class="btn ml-1  btn-outline-danger">
                    <i class="fa fa-download"></i>
                    Active
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="btn ml-1  btn-outline-primary">
                    <i class="fa fa-user-plus"></i>
                    Archived
                </button>
            </li>
        </ul>
    
@endif
