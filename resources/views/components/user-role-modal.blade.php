<div class="modal fade" id="user-role-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editUserRoleForm">
                    <input type="hidden" id="edit_user_id" value="${userId}">
                    <div class="form-group">
                        <label for="edit_role_id">Select Role</label>
                        <select class="form-control" id="edit_role_id" required>
                            <option value="">Select a role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role['id'] }}">{{ $role['role'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="_updateUserRole()">Update Role</button>
            </div>
        </div>
    </div>
</div>