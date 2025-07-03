<div class="modal fade" id="contact-modal-edit" tabindex="-1" role="dialog" aria-labelledby="contactEditModalLabel" aria-hidden="true">
    <form id="contact-edit-form">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <div>
                        <h5 class="modal-title">Edit Contact</h5>
                        <p class="modal-description">Update the contact information and modify tags as needed.</p>
                    </div>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="contact_id" name="contact_id" data-key="contact_id">
                    <div class="basic-form">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="text-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-xs" id="edit_first_name" placeholder="John" data-key="FirstName" data="req">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="text-label">Last Name </label>
                                <input type="text" class="form-control form-control-xs" id="edit_last_name" placeholder="Doe" data-key="LastName">
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-md-6 ">
                                    <label class="text-label">Phone Numbers</label>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="button" class="btn btn-outline-secondary btn-md" data-trigger="add-phone-edit">
                                        <i class="fa fa-plus"></i>
                                        Add Phone
                                    </button>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12 phone-card-container-edit">
                                    <!-- Phone cards will be dynamically populated here -->
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-row mt-2">
                            <div class="form-group col-md-12">
                                <label class="text-label">Selected Tags</label>
                                <div class="bootstrap-label selected-tags-edit"></div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Available Tags</label>
                                <div class="bootstrap-label existing-tags-edit">
                                    @foreach($tags as $tag)
                                        <span 
                                            class="mr-1 p-2 text-white label label-pill tag-labels cursor-pointer" 
                                            style="background-color: {{$tag['tag_color']}} !important;"
                                            data-trigger="select-tag-edit"
                                            data-id="{{ $tag['id'] }}"
                                        >
                                            <i class="fa fa-tag"></i>
                                            {{ ucfirst(strtolower($tag['tag_name'])) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label class="text-label">Notes</label>
                                <textarea 
                                    class="form-control form-control-xs" 
                                    id="edit_notes"
                                    rows="3" 
                                    placeholder="Optional: Add any relevant notes for this contact"
                                    data-key="Notes"
                                ></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">
                        <i class="fa fa-times"></i>
                        Cancel
                    </button>
                    <button type="button" class="btn btn-outline-primary" data-trigger="update-contact">
                        <i class="fa fa-save"></i>
                        Update Contact
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

