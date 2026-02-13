<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile border-bottom">
            <a href="#" class="nav-link flex-column">
                @php
                    $users = Auth::user();
                @endphp
                <div class="nav-profile-image">
                    <img style="border-radius: 50%; box-shadow: 5px 5px 10px rgba(0,0,0,0.5);"
                        src="{{ $users->img
                            ? asset('template/assets/images/user/' . $users->img)
                            : asset('template/assets/images/faces/face1.jpg') }}"
                        alt="profile">
                    <!--change to offline or busy as needed-->
                </div>
                <div class="nav-profile-text d-flex ms-0 mb-3 flex-column">
                    <span class="fw-semibold mb-1 mt-2 text-center">
                        {{ $users->full_name ?? $users->username }}
                    </span>
                    <span class="fw-semibold mb-1 mt-2 text-center">
                        {{ $users->role->nama }}
                    </span>
                </div>
            </a>
        </li>

        <li class="nav-item pt-3">
            <a class="nav-link d-block" href="index.html">
                <img class="sidebar-brand-logo" src="{{ asset('template/assets/images/tc.png') }}"
                    style="width: 150px; height: auto;" alt="">
                <img class="sidebar-brand-logomini" src="{{ asset('template/assets/images/logo.png') }}"
                    style="width: 30px; height: auto;" alt="">
                <div class="small fw-light pt-1">Sunrose Project</div>
            </a>
            <form class="d-flex align-items-center" action="#">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <i class="input-group-text border-0 mdi mdi-magnify"></i>
                    </div>
                    <input type="text" class="form-control border-0" placeholder="Search">
                </div>
            </form>
        </li>
        <li class="pt-2 pb-1">
            <span class="nav-item-head">Pages</span>
        </li>
        <li class="nav-item">
            @if (hasRole(['Administrator']))
                <a class="nav-link" href="/">
                    <i class="mdi mdi-compass-outline menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            @endif
            @if (hasRole(['Operation']))
                <a class="nav-link" href="/">
                    <i class="mdi mdi-compass-outline menu-icon"></i>
                    <span class="menu-title">Dashboard Operation</span>
                </a>
            @endif
            @if (hasRole(['Engineer']))
                <a class="nav-link" href="{{ route('teknisi.panel') }}">
                    <i class="mdi mdi-compass-outline menu-icon"></i>
                    <span class="menu-title">Dashboard Teknisi</span>
                </a>
                <a class="nav-link" href="{{ route('inventory.index') }}">
                    <i class="fa fa-exchange menu-icon"></i>
                    <span class="menu-title">Inventory Sparepart</span>
                </a>
            @endif
        </li>
        @if (hasRole(['Administrator']))
            <li class="pt-2 pb-1">
                <span class="nav-item-head">DATA USER</span>
            </li>
            <li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#data" aria-expanded="false" aria-controls="data">
                    <i class="fa fa-address-book menu-icon"></i>
                    <span class="menu-title">Index</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse {{ request()->routeIs('users.*') ? 'show' : '' }}" id="data">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="{{ route('users.index') }}">User</a></li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route('users.create') }}">Tambah</a></li>
                    </ul>
                </div>
            </li>
        @endif
        @php
            use App\Models\Device;
            $devices = Device::orderBy('device_name')->get();
        @endphp
        @if (hasRole(['Operation', 'SQM', 'Administrator']))
            <li class="pt-2 pb-1">
                <span class="nav-item-head">SIMULATOR OPERATION</span>
            </li>
            <li class="nav-item {{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#jadwal" aria-expanded="false"
                    aria-controls="jadwal">
                    <i class="fa fa-calendar menu-icon"></i>
                    <span class="menu-title">Schedule</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse {{ request()->routeIs('jadwal.*') ? 'show' : '' }}" id="jadwal">
                    <ul class="nav flex-column sub-menu">
                        @foreach ($devices as $device)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('jadwal.index', $device->id) }}">
                                    {{ $device->device_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#mmi" aria-expanded="false" aria-controls="mmi">
                    <i class="fa fa-list-alt menu-icon"></i>
                    <span class="menu-title">MMI List</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="mmi">
                    <ul class="nav flex-column sub-menu">
                        @foreach ($devices as $d)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('mmi.index', $d->id) }}">
                                    {{ $d->device_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>
        @endif
        @if (hasRole(['Engineer', 'Administrator']))
            <li class="pt-2 pb-1">
                <span class="nav-item-head">Simulator Maintenance</span>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#record" aria-expanded="false"
                    aria-controls="record">
                    <i class="fa fa-gears menu-icon"></i>
                    <span class="menu-title">Maintenance Record</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="record">
                    <ul class="nav flex-column sub-menu">
                        @foreach ($devices as $d)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('record.menu', $d->id) }}">
                                    {{ $d->device_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#checklist" aria-expanded="false"
                    aria-controls="checklist">
                    <i class="fa fa-check-square-o menu-icon"></i>
                    <span class="menu-title">Maintenance Checklist</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="checklist">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('ceklis.index') }}">Checklist List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('ceklis.create') }}">New Checklist</a>
                        </li>
                    </ul>
                </div>
            </li>


            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#logbook" aria-expanded="false"
                    aria-controls="logbook">
                    <i class="fa fa-book menu-icon"></i>
                    <span class="menu-title">Logbook Release</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="logbook">
                    <ul class="nav flex-column sub-menu">
                        @foreach ($devices as $d)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('logbook.indexCustomer', $d->id) }}">
                                    {{ $d->device_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#diskrepansi" aria-expanded="false"
                    aria-controls="diskrepansi">
                    <i class="fa fa-tasks menu-icon"></i>
                    <span class="menu-title">Diskrepansi</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="diskrepansi">
                    <ul class="nav flex-column sub-menu">
                        @foreach ($devices as $d)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('teknisi.diskrepansi.pending', $d->id) }}">
                                    {{ $d->device_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#qtg" aria-expanded="false"
                    aria-controls="qtg">
                    <i class="fa fa-upload menu-icon"></i>
                    <span class="menu-title">Upload QTG</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="qtg">
                    <ul class="nav flex-column sub-menu">
                        @foreach ($devices as $d)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('qtg.chapter.index', $d->id) }}">
                                    {{ $d->device_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>
        @endif
    </ul>
</nav>
