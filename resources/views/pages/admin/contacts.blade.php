@include('partials.admin.header')
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Contacts</h4>
                    <div class="table-responsive">
                        <table class="table table-hover verticle-middle zero-configuration" cellspacing="0">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Tags</th>
                                    <th scope="col">Notes</th>
                                    <th scope="col">Date Created</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('components.modals')
@include('components.contact-modal-edit')
@include('partials.admin.footer')

<script src="{{ asset('assets/axocall/js/modules/contacts.js') }}"></script>
<script src="{{ asset('assets/axocall/js/modules/contact-edit.js') }}"></script>