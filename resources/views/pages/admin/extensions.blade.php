@include('partials.admin.header')       
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-lg-12">
            
                    
            <div class="table-responsive">
                <table class="table table-hover verticle-middle zero-configuration extensions-table" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Phone Number</th>
                            <th scope="col">Extension</th>
                            <th scope="col">Status</th>
                            <th scope="col">Expiration</th>
                            <th scope="col">Notes</th>
                            <th scope="col">Date Created</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

@include('components.extension-modal')
@include('components.extension-modal-edit')
@include('partials.admin.footer')

<script src="{{ asset('assets/axocall/js/modules/extensions.js') }}"></script>
<script src="{{ asset('assets/axocall/js/widgets-init.js') }}" defer></script>


