<div class="modal fade" id="extension-modal-edit" tabindex="-1" role="dialog" aria-labelledby="extensionEditModalLabel" aria-hidden="true">    
    <form id="extension-edit-form">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Extension</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group" id="edit-selected-contact-info" style="display:none;">
                        <label>Selected Contact Info</label>
                        <div class="alert alert-info mb-0" id="edit-contact-info-display" style="padding: 6px 12px;">
                            <!-- Contact name and phone will be shown here -->
                        </div>
                    </div>

                    
                    <div class="form-group">
                        <label for="edit_extension_number">Extension Number</label>
                        <input type="text" class="form-control" id="edit_extension_number" name="extension_number" placeholder="Enter extension number" required data-key="extension_number">
                    </div>
                    <div class="form-group">
                        <label for="edit_expiration">Expiration Date</label>
                        <input type="text" class="form-control datetimepicker" id="expiration" name="expiration" data-key="expiration">
                    </div>
                    <div class="form-group">
                        <label for="edit_notes">Notes</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3" placeholder="Optional: Add any relevant notes" data-key="notes" ></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">
                        <i class="fa fa-times"></i>
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-outline-primary" data-trigger="update-extension">
                        <i class="fa fa-save"></i>
                        Update Extension
                    </button>
                </div>
            </div>
        </form>
    </div>
</div> 