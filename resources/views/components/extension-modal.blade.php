<div class="modal fade" id="extension-modal" tabindex="-1" role="dialog" aria-labelledby="extensionsModalLabel" aria-hidden="true">    
    <form id="extension-form">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Extension</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        
                        <label for="contact_id">Contact</label>
                        <select class="form-control select2" id="contact_id" name="contact_id" required data-key="contact_id">
                            <option value="">Select Contact</option>
                            
                            @foreach($contacts as $contact)
                                <option value="{{ $contact->id }}">{{ $contact->first_name }} {{ $contact->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <select class="form-control" id="phone_number" name="phone_number" required data-key="phone_number" data-key-id="phone_id">
                            <option value="">Select Phone Number</option>
                        </select>
                    </div>

                    <div class="form-group" id="selected-contact-info" style="display:none;">
                        <label>Selected Contact Info</label>
                        <div class="alert alert-info mb-0" id="contact-info-display" style="padding: 6px 12px;">
                            <!-- Contact name and phone will be shown here -->
                        </div>
                    </div>

                    
                    <div class="form-group">
                        <label for="extension_number">Extension Number</label>
                        <input type="text" class="form-control" id="extension_number" name="extension_number" placeholder="Enter extension number" required data-key="extension_number">
                    </div>
                    <div class="form-group">
                        <label for="expiration">Expiration Date</label>
                        <input type="date" class="form-control" id="expiration" name="expiration" data-key="expiration">
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Optional: Add any relevant notes" data-key="notes" ></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">
                        <i class="fa fa-times"></i>
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-outline-primary" data-trigger="save-extension">
                        <i class="fa fa-save"></i>
                        Save Extension
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
