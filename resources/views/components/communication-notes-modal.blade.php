<div class="modal fade" id="editNotesModal" tabindex="-1" role="dialog" aria-labelledby="editNotesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editNotesModalLabel">Edit Notes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editNotesForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="communication_id" id="editNotesCommunicationId" value="">
                    <div class="form-group">
                        <label for="editNotesTextarea">Notes</label>
                        <textarea class="form-control" id="editNotesTextarea" name="notes" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>