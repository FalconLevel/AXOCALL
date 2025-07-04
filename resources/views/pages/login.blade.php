 @include('partials.unauth.header')

 <div class="login-form-bg h-100">
    <div class="container h-100">
    
        <div class="row justify-content-center h-100">
            <div class="col-xl-6">
                <div class="form-input-content">
                    <div class="card login-form mb-0">
                        <div class="card-body pt-5">
                            <div class="col-lg-12">
                                <div class="brand-title text-center">
                                    <h1>AXOCALL</h1>
                                </div>
                                <p class="text-center text-muted">Sign up to your account</p>
                            </div>
                            <form class="mt-5 mb-5 login-input">
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Email" data="req" data-key="Email">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" placeholder="Password" data="req" data-key="Password">
                                </div>
                                <button class="btn btn-outline-primary btn-block" data-trigger="login-submit">Sign In</button>
                            </form>
                            <p class="mt-5 login-form__footer">Dont have account? <a href="/sign-up" class="text-primary">Sign Up</a> now</p>
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
<script src="{{ asset('assets/axocall/js/modules/login.js') }}"></script>