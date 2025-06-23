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
                                    <input type="text" class="form-control" id="tag-name" placeholder="Enter tag name" data-key="TagName" data="req">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <input type="text" class="colorpicker form-control " value="#7ab2fa" data-key="TagColor" data="req">
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
                    <div class="bootstrap-label existing-tags">
                        <span class="label label-pill label-primary">
                            Primary
                            <a href="javascript:void(0)" class="text-white"><i class="fa fa-times"></i></a>
                        </span> 
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
@include('partials.admin.footer')

<script src="{{ asset('assets/axocall/js/modules/settings.js') }}"></script>