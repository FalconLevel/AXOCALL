@include('partials.admin.header')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-hover verticle-middle users-table" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="">Name</th>
                            <th width="">Email (Username)</th>
                            <th width="">Role</th>
                            <th width="">Status</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>       
    </div>
</div>
@include('components.user-role-modal')
@include('partials.admin.footer')
<script src="{{ asset('assets/axocall/js/modules/users.js') }}"></script>