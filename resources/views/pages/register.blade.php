@include('partials.unauth.header')
<div class="login-form-bg h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100">
            <div class="col-xl-6">
                <div class="form-input-content">
                    <div class="card login-form mb-0">
                        <div class="card-body pt-5">

                            <div class="row sign-up-category">
                                <div class="col-lg-12">
                                    <div class="brand-title text-center">
                                        <h1>AXOCALL</h1>
                                    </div>
                                    <p class="text-center text-muted">Sign up to your account</p>
                                </div>

                                <div class="col-lg-12">
                                    <button class="btn btn-outline-light btn-block" data-trigger="sign-up-via-google">
                                        <img src="{{ asset('assets/axocall/icons/google.svg') }}" alt="Google" class="img-fluid" style="width: 20px; height: 20px;">
                                        Sign in with Google
                                    </button>
                                    <button class="btn btn-outline-light btn-block" data-trigger="sign-up-via-email">
                                        <i class="fa-solid fa-envelope"></i>
                                        Sign in with Email
                                    </button>
                                </div>
                            </div>

                            <div class="row d-none sign-up-form">
                                <div class="col-lg-12">
                                    <div class="col-lg-12">
                                        <div class="brand-title text-center d-flex align-items-center justify-content-center">
                                            <a href="#" data-trigger="sign-up-category">
                                                <i class="fa-solid fa-chevron-left"></i>
                                            </a>&nbsp;&nbsp;
                                            <h1>AXOCALL</h1>
                                        </div>
                                        <p class="text-center text-muted">Sign up to your account</p>
                                    </div>
                                    <form class="mt-5 mb-5 login-input">
                                        <div class="form-group">
                                            <label for="FirstName">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-md"  placeholder="John" data="req" data-key="FirstName">
                                        </div>
                                        <div class="form-group">
                                            <label for="LastName">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-md"  placeholder="Doe" data="req" data-key="LastName">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="Email">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control form-control-md"  placeholder="you@example.com" data="req" data-key="Email">
                                        </div>

                                        <div class="form-group">
                                            <label for="PhoneNumber">Phone Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-md phone-number"  placeholder="1234567890" maxlength="10" data-mask="9999999999" data="req" data-key="PhoneNumber">
                                        </div>
                                        <div class="form-group">
                                            <label for="Password">Password <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control form-control-md" placeholder="******" data="req" data-key="Password">
                                        </div>
                                        <div class="form-group">
                                            <label for="ConfirmPassword">Confirm Password <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control form-control-md" placeholder="******" data="req" data-key="ConfirmPassword">
                                        </div>
                                        <button class="btn btn-outline-primary btn-block" data-trigger="sign-up-submit">Sign Up</button>
                                    </form>
                                    <p class="mt-5 login-form__footer">Have account <a href="/" class="text-primary">Log In </a> now</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/system/plugins/common/common.min.js') }}"></script>
<script src="{{ asset('assets/system/plugins/toastr/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/system/plugins/toastr/js/toastr.init.js') }}"></script>
<script src="{{ asset('assets/axocall/js/scripts.js') }}"></script>
<script src="{{ asset('assets/axocall/js/widgets-init.js') }}"></script>
<script src="{{ asset('assets/axocall/js/modules/register.js') }}"></script>