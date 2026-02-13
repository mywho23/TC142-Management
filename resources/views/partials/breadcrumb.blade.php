@php
    use Illuminate\Support\Facades\Route;

    $route = Route::currentRouteName();

    // ambil device aktif (AMAN)
    $deviceName = null;

    if (isset($selectedDevice)) {
        $deviceName = $selectedDevice->device_name;
    } elseif (isset($device) && !($device instanceof \Illuminate\Support\Collection)) {
        $deviceName = $device->device_name ?? ($device->name ?? null);
    }

    $deviceName =
        $deviceName ?? ($device_name ?? (request()->route('device') ?? (request()->route('device_id') ?? null)));

    // mapping breadcrumb berdasarkan route prefix
    $breadcrumbMap = [
        'dashboard' => ['Dashboard'],

        // USERS
        'users.' => ['Users'],

        // JADWAL
        'jadwal.index' => ['Jadwal', $deviceName],
        'jadwal.create' => ['Create', $deviceName],
        'jadwal.rekap' => ['Rekap Jam'],

        // MMI
        'mmi.' => ['MMI', $deviceName],

        // RECORD / MAINTENANCE
        'record.' => ['Maintenance Record', $deviceName],

        // CEKLIS
        'ceklis.' => ['Checklist'],

        // QTG
        'qtg.' => ['QTG', $deviceName],

        // LOGBOOK
        'logbook.' => ['Logbook', $deviceName],

        // INVENTORY
        'inventory.' => [Route::is('inventory.create') ? 'Create' : 'Index'],

        // TEKNISI PANEL
        'teknisi.diskrepansi.' => ['Diskrepansi', $deviceName],
        'teknisi.panel' => ['Panel Teknisi'],
        'teknisi.device' => ['Panel Teknisi', $deviceName],
        'teknisi.' => ['Teknisi'],
    ];

    $crumbs = [];

    foreach ($breadcrumbMap as $key => $labels) {
        if (str_starts_with($route, $key)) {
            $crumbs = array_filter($labels);
            break;
        }
    }
@endphp

@if (count($crumbs))
    <div class="d-flex justify-content-end mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-body py-2 px-3">
                <small class="text-muted fw-semibold">
                    {{ implode(' / ', $crumbs) }}
                </small>
            </div>
        </div>
    </div>
@endif
