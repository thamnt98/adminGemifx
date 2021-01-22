<div class="c-sidebar-brand">
    <a href="https://accounts.gemifx.com">
        <img class="c-sidebar-brand-full" src="{{ url('images/logo.png') }}" width="118" height="46" alt="CoreUI Logo">
    </a>
</div>
<ul class="c-sidebar-nav ps">
    <li class="c-sidebar-nav-item">
        <a class="c-sidebar-nav-link" href="">
            <i class="fa fa-dashboard"></i>
            Dashboard
        </a>
    </li>
    <li class="c-sidebar-nav-item">
        <a class="c-sidebar-nav-link" href="{{ route('user.list') }}">
            <i class="fa fa-user"></i>
            Khách hàng
        </a>
    </li>
    <li class="c-sidebar-nav-item">
        <a class="c-sidebar-nav-link" href="{{ route('account.live') }}">
            <i class="fa fa-home"></i>
            Tài khoản
        </a>
    </li>
    <li class="c-sidebar-nav-item">
        <a class="c-sidebar-nav-link" href="{{ route('deposit.list') }}">
            <i class="fa fa-credit-card"></i>
            Deposit
        </a>
    </li>
    <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
        <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
    </div>
    <div class="ps__rail-y" style="top: 0px; right: 0px;">
        <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
    </div>
</ul>
</div>
