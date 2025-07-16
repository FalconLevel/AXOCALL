@include('partials.unauth.header')
<div class="login-form-bg h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100">
            <div class="col-md-9">
                <div class="form-input-content">
                    <div class="card login-form mb-0">
                        <div class="card-body pt-5">
                            <input type="hidden" id="token" value="{{ $token }}">
                            <div class="row sign-up-form">
                                <div class="col-lg-12">
                                    <div class="col-lg-12">
                                        <div class="brand-title text-center d-flex align-items-center justify-content-center">
                                            <h1>AXOCALL</h1>
                                        </div>
                                        <p class="text-center text-muted">Verify your account</p>
                                    </div>
                                    <form class="mt-5 mb-5 login-input">
                                        <div class="row">
                                            <div class="col-lg-12">

                                                <div class="form-group">                                                    
                                                    <input 
                                                        type="text" 
                                                        class="form-control form-control-md" 
                                                        placeholder="Please enter the OTP sent to your email" 
                                                        data="req" 
                                                        data-key="Otp" 
                                                        >
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-outline-primary btn-block" data-trigger="verify-otp-submit">
                                            Verify
                                        </button>
                                        <p class="text-center text-muted mt-2">
                                            Resend OTP in <span id="expiry-countdown" class="text-muted">05:00</span>
                                        </p>
                                    </form>
                                    
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
<script src="{{ asset('assets/axocall/js/modules/verify-otp.js') }}"></script>