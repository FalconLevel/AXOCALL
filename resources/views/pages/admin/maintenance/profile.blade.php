@include('partials.admin.header')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-6">
            <form>
            <div class="card">
                <div class="card-body">
                    <div class="row profile-form">
                        <div class="col-lg-12">
                            <div class="brand-title d-flex justify-content-between align-items-center">
                                <h1>Profile Information</h1> <button class="btn btn-outline-primary btn-flat" data-trigger="edit-profile">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                    Edit Profile
                                </button>
                            </div>
                            <p class="text-muted">Manage your personal and company details</p>                                
                                <div class="row mt-4">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="FirstName">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-md form-control-static"  placeholder="John" data="req" data-key="FirstName" value="{{ $profile['first_name'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="LastName">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-md form-control-static"  placeholder="Doe" data="req" data-key="LastName" value="{{ $profile['last_name'] }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="FirstName">Company <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-md form-control-static"  placeholder="Company Name" data="req" data-key="Company" value="{{ $profile['profile'] ? $profile['profile']['company'] : '' }}">
                                        </div>
                                    </div>
                                </div>
                                
                                
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="Email">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control form-control-md form-control-static"  placeholder="you@example.com" data="req" data-key="Email" value="{{ $profile['email'] }}">    
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="PhoneNumber">Phone Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-md form-control-static phone-number"  placeholder="1234567890" maxlength="10" data-mask="9999999999" data="req" data-key="PhoneNumber" value="{{ $profile['phone_number'] }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="FirstName">Street Address <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-md form-control-static"  placeholder="123 Main St" data="req" data-key="StreetAddress" value="{{ $profile['profile'] ? $profile['profile']['street_address'] : '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="FirstName">Apartment/Suite (Optional)</label>
                                    <input type="text" class="form-control form-control-md form-control-static"  placeholder="123 Main St" data-key="Apartment" value="{{ $profile['profile'] ? $profile['profile']['street_address'] : '' }}">
                                </div>


                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="FirstName">City <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-md form-control-static"  placeholder="New York" data="req" data-key="City" value="{{ $profile['profile'] ? $profile['profile']['city'] : '' }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="FirstName">State <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-md form-control-static"  placeholder="New York" data="req" data-key="State" value="{{ $profile['profile'] ? $profile['profile']['state'] : '' }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="FirstName">Zip Code <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-md form-control-static"  placeholder="10001" data="req" data-key="ZipCode" value="{{ $profile['profile'] ? $profile['profile']['zip_code'] : '' }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="FirstName">Country <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-md form-control-static"  placeholder="United States" data="req" data-key="Country" value="{{ $profile['profile'] ? $profile['profile']['country'] : '' }}">
                                </div>
                                

                            
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-outline-primary btn-block btn-flat" data-trigger="save-profile">Save Changes</button>
                </div>
            </form>
            </div>
        </div>
        <div class="col-xl-6">
            
            <div class="card">
                <div class="card-body">
                    <div class="row profile-form">
                        <div class="col-lg-12">
                            <div class="brand-title">
                                <h1>Subscription and Billing</h1>
                            </div>
                            <p class="text-muted">View your current plan and manage billing details.</p>
                            
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@include('partials.admin.footer')

<script src="{{ asset('assets/axocall/js/modules/profile.js') }}"></script>