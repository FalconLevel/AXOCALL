@include('partials.admin.header')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-hover verticle-middle roles-table" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="">Role</th>
                            <th width="">Description</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>       
    </div>
</div>
@include('components.role-modal-add')


@include('partials.admin.footer')

<script src="{{ asset('assets/axocall/js/modules/roles.js') }}"></script>