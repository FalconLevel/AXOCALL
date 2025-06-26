

@if($panel_type == 'contacts')
{{-- Contacts --}}
    <div class="modal fade" id="{{$panel_type}}-modal" tabindex="-1" role="dialog" aria-labelledby="component-modal-label" aria-hidden="true">
        <form>
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0">
                        <div>
                            <h5 class="modal-title">Add New Contact</h5>
                            <p class="modal-description">Enter the contact information and select tags from your configured options.</p>
                        </div>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="basic-form">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="text-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-xs" placeholder="John" data-key="FirstName" data="req">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="text-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-xs" placeholder="Doe" data-key="LastName" data="req">
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <label class="text-label">Phone Numbers</label>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button type="button" class="btn btn-outline-secondary btn-md" data-trigger="add-phone">
                                            <i class="fa fa-plus"></i>
                                            Add Phone
                                        </button>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12 phone-card-container">
                                        <div class="card shadow-none p-1 phone-card">
                                            <div class="card-body p-0">
                                                <div class="d-flex justify-content-between">
                                                    <label class="text-label">
                                                        Phone <span class="phone-count">1</span>
                                                    </label>
                                                    <i class="fa fa-trash cursor-pointer text-danger d-none" data-trigger="remove-phone"></i>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control form-control-xs phone-number" placeholder="Phone Number">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control form-control-xs phone-ext" placeholder="1234#">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control form-control-xs phone-type" placeholder="Home">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-row mt-2">
                                <div class="form-group col-md-12">
                                    <label class="text-label">Tags</label>
                                    <div class="bootstrap-label selected-tags"></div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label >Available Tags</label>
                                    <div class="bootstrap-label existing-tags">
                                        @foreach($tags as $tag)
                                            <span 
                                                class="mr-1 p-2 text-white label label-pill tag-labels cursor-pointer" 
                                                style="background-color: {{$tag['tag_color']}} !important;"
                                                data-trigger="select-tag"
                                                data-id="{{ $tag['id'] }}"
                                            >
                                                <i class="fa fa-tag"></i>
                                                {{ ucfirst(strtolower($tag['tag_name'])) }} 
                                                {{-- <a href="javascript:void(0)" class="text-white" data-trigger="delete-tag" data-id="{{$tag['id']}}">
                                                    <i class="fa fa-trash"></i>
                                                </a> --}}
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
                        <button type="button" class="btn btn-outline-primary" data-trigger="save-contact">
                            <i class="fa fa-save"></i>
                            Save Contact
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
{{-- End of Contacts --}}
@endif