<div class="nk-sidebar">           
    <div class="nk-nav-scroll">
        <ul class="metismenu" id="menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" aria-expanded="false">
                    <i class="fa-solid fa-gauge menu-icon"></i><span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.contacts') }}" aria-expanded="false">
                    <i class="fa-solid fa-users menu-icon"></i><span class="nav-text">Contacts</span>
                </a>            
            </li>
            <li>
                <a href="{{ route('admin.extensions') }}" aria-expanded="false">
                    <i class="fa-regular fa-address-book menu-icon"></i><span class="nav-text">Extensions</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.communications') }}" aria-expanded="false">
                    <i class="fa-solid fa-phone menu-icon"></i><span class="nav-text">Communications</span>
                </a>
            </li>
            
            
            <li>
                <a href="{{ route('admin.follow_ups') }}" aria-expanded="false">
                    <i class="far fa-flag menu-icon"></i><span class="nav-text">Follow Up</span>
                </a>
            </li>
            {{-- <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="icon-speedometer menu-icon"></i><span class="nav-text">Dashboard</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('admin.dashboard') }}">Home 1</a></li>
                    <!-- <li><a href="./index-2.html">Home 2</a></li> -->
                </ul>
            </li> --}}
            
        </ul>
    </div>
</div>