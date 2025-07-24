<div class="modal fade" id="role-modal-add" tabindex="-1" role="dialog" aria-labelledby="roleAddModalLabel" aria-hidden="true">
    <form id="role-add-form">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <div>
                        <h5 class="modal-title">Add New Role</h5>
                        <p class="modal-description">Enter the role information to create a new role.</p>
                    </div>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="basic-form">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label class="text-label">Role Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-xs" id="add_role_name" placeholder="Enter role name" data-key="Role" data="req">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label class="text-label">Description</label>
                                <textarea 
                                    class="form-control form-control-xs" 
                                    id="add_role_description"
                                    rows="3" 
                                    placeholder="Enter role description (optional)"
                                    data-key="RoleDescription"
                                ></textarea>
                            </div>
                        </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label class="text-label">Permissions</label>
                            <table class="table table-bordered table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Menu</th>
                                        <th class="text-center">
                                            Read<br>
                                            <input type="checkbox" id="check-all-read" onclick="toggleAllPermission('read', this)">
                                        </th>
                                        <th class="text-center">
                                            Edit<br>
                                            <input type="checkbox" id="check-all-edit" onclick="toggleAllPermission('edit', this)">
                                        </th>
                                        <th class="text-center">
                                            Delete<br>
                                            <input type="checkbox" id="check-all-delete" onclick="toggleAllPermission('delete', this)">
                                        </th>
                                    </tr>
                                </thead>


                                <tbody>
                                    <tr>
                                        <td>Dashboard</td>
                                        <td class="text-center">
                                            <input type="checkbox" name="permissions[dashboard][read]" value="1">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="permissions[dashboard][edit]" value="1">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="permissions[dashboard][delete]" value="1">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Contacts</td>
                                        <td class="text-center">
                                            <input type="checkbox" name="permissions[contacts][read]" value="1">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="permissions[contacts][edit]" value="1">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="permissions[contacts][delete]" value="1">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Extensions</td>
                                        <td class="text-center">
                                            <input type="checkbox" name="permissions[extensions][read]" value="1">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="permissions[extensions][edit]" value="1">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="permissions[extensions][delete]" value="1">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Communications</td>
                                        <td class="text-center">
                                            <input type="checkbox" name="permissions[communications][read]" value="1">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="permissions[communications][edit]" value="1">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="permissions[communications][delete]" value="1">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Follw up</td>
                                        <td class="text-center">
                                            <input type="checkbox" name="permissions[follow_up][read]" value="1">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="permissions[follow_up][edit]" value="1">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="permissions[follow_up][delete]" value="1">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Settings</td>
                                        <td class="text-center">
                                            <input type="checkbox" name="permissions[settings][read]" value="1">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="permissions[settings][edit]" value="1">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="permissions[settings][delete]" value="1">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>User Management</td>
                                        <td class="text-center">
                                            <input type="checkbox" name="permissions[user_management][read]" value="1">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="permissions[user_management][edit]" value="1">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="permissions[user_management][delete]" value="1">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">
                        <i class="fa fa-times"></i>
                        Cancel
                    </button>
                    <button type="button" class="btn btn-outline-primary" data-trigger="save-role" id="save-role-btn">
                        <i class="fa fa-save"></i>
                        Save Role
                    </button>
                    <button type="button" class="btn btn-outline-primary" data-trigger="update-role" id="update-role-btn">
                        <i class="fa fa-save"></i>
                        Update Role
                    </button>
                </div>
            </div>
        </div>
    </form>
</div> 