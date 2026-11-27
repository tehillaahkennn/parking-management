
<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical"
      class="light" data-header-styles="light" data-menu-styles="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register Vehicle | Parking Management System</title>

    <!-- Original Valex CSS -->
<link rel="stylesheet" href="{{ asset('backend/assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/libs/simplebar/simplebar.min.css') }}">
    <style>
        body {
            background: #f0f1f7;
        }

        .parking-topbar {
            height: 75px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
            border-bottom: 1px solid #e5e7eb;
        }

        .parking-user {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .parking-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #e8efff;
            color: #0866d9;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
        }

        .parking-form-card {
            background: white;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 3px 12px rgba(0,0,0,.04);
        }

        .parking-form-card input,
        .parking-form-card select {
            width: 100%;
            min-height: 42px;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 10px 12px;
            background: white;
            outline: none;
        }

        .parking-form-card input:focus,
        .parking-form-card select:focus {
            border-color: #0866d9;
            box-shadow: 0 0 0 2px rgba(8,102,217,.10);
        }

        .parking-label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #344054;
        }

        .parking-field {
            margin-bottom: 22px;
        }

        .parking-submit {
            background: #0866d9;
            color: white;
            padding: 11px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
        }

        .parking-submit:hover {
            background: #0756bd;
        }

        .parking-cancel {
            background: #eef1f6;
            color: #52617c;
            padding: 11px 20px;
            border-radius: 5px;
            font-size: 13px;
            font-weight: 600;
        }

        .parking-cancel:hover {
            background: #e1e6ef;
        }

        .parking-menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 22px;
            color: #53627d;
            font-size: 13px;
            border-left: 3px solid transparent;
        }

        .parking-menu-link:hover {
            color: #0866d9;
            background: #f4f7ff;
        }

        .parking-menu-link.active {
            color: #0866d9;
            background: #f2f6ff;
            border-left-color: #0866d9;
        }

        .parking-menu-heading {
            padding: 24px 25px 10px;
            color: #263858;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .parking-sidebar-logo {
            height: 75px;
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            color: #0866d9;
            font-size: 17px;
            font-weight: bold;
        }

        @media(max-width: 767px) {
            .parking-topbar {
                padding: 0 12px;
            }

            .parking-user-details {
                display: none;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <!-- SIDEBAR -->
    <aside class="app-sidebar" id="sidebar">

        <div class="parking-sidebar-logo">
            🅿 PARKING SYSTEM
        </div>

        <div class="main-sidebar" id="sidebar-scroll">

            <nav class="main-menu-container">

                <ul class="main-menu">

                    <li class="slide__category">
                        <span class="category-name">Main</span>
                    </li>

                    <li class="slide">
                        <a href="{{ route('parking.index') }}"
                           class="parking-menu-link">
                            <span>▦</span>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="slide__category">
                        <span class="category-name">Parking Management</span>
                    </li>

                    <li class="slide">
                        <a href="{{ route('parking.create') }}"
                           class="parking-menu-link active">
                            <span>🚘</span>
                            <span>Register Vehicle</span>
                        </a>
                    </li>

                    <li class="slide">
                        <a href="{{ route('parking-slots.index') }}"
                           class="parking-menu-link">
                            <span>▤</span>
                            <span>Parking Slots</span>
                        </a>
                    </li>

                    <li class="slide">
                        <a href="{{ route('vehicles.index') }}"
                           class="parking-menu-link">
                            <span>🚗</span>
                            <span>Vehicles</span>
                        </a>
                    </li>

                    <li class="slide">
                        <a href="{{ route('payments.index') }}"
                           class="parking-menu-link">
                            <span>₱</span>
                            <span>Payments</span>
                        </a>
                    </li>

                    @if (auth()->user()->role === 'Admin')

                        <li class="slide__category">
                            <span class="category-name">Administration</span>
                        </li>

                        <li class="slide">
                            <a href="{{ route('staff.index') }}"
                               class="parking-menu-link">
                                <span>♙</span>
                                <span>Staff Management</span>
                            </a>
                        </li>

                        <li class="slide">
                            <a href="{{ route('reports.index') }}"
                               class="parking-menu-link">
                                <span>▥</span>
                                <span>Reports</span>
                            </a>
                        </li>

                        <li class="slide">
                            <a href="{{ route('audit-logs.index') }}"
                               class="parking-menu-link">
                                <span>▣</span>
                                <span>Audit Logs</span>
                            </a>
                        </li>

                    @endif

                </ul>

            </nav>

        </div>
    </aside>


    <!-- MAIN CONTENT -->
    <div class="main-content app-content">

        <!-- TOP NAVIGATION -->
        <header class="parking-topbar">

            <div>
                <h5 class="font-semibold text-[15px]">
                    Parking Management System
                </h5>
                <p class="text-[12px] text-[#8c9097]">
                    Vehicle Registration
                </p>
            </div>

            <div class="parking-user">

                <div class="parking-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div class="parking-user-details text-right">
                    <p class="text-[13px] font-semibold">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-[11px] text-[#8c9097]">
                        {{ auth()->user()->role }}
                    </p>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button type="submit"
                            class="ti-btn ti-btn-danger !py-2 !px-3 !text-[12px]">
                        Logout
                    </button>
                </form>

            </div>

        </header>


        <!-- PAGE CONTENT -->
        <div class="content">

            <div class="main-content">

                <!-- PAGE TITLE -->
                <div class="md:flex block items-center justify-between mb-6 mt-6">

                    <div>
                        <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-2">
                            Register Vehicle
                        </h5>

                        <nav>
                            <ol class="flex items-center text-[12px] text-[#8c9097]">
                                <li>
                                    <a href="{{ route('parking.index') }}"
                                       class="text-primary">
                                        Dashboard
                                    </a>
                                </li>

                                <li class="mx-3">›</li>

                                <li>Register Vehicle</li>
                            </ol>
                        </nav>
                    </div>

                    <a href="{{ route('parking.index') }}"
                       class="ti-btn ti-btn-light !py-2 !px-4 !text-[12px] mt-3 md:mt-0">
                        ← Back to Parking Records
                    </a>

                </div>


                <!-- VALIDATION ERRORS -->
                @if ($errors->any())

                    <div class="alert alert-danger mb-5" role="alert">
                        <strong>Please correct the following errors:</strong>

                        <ul class="list-disc pl-5 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>

                @endif


                <!-- REGISTRATION FORM CARD -->
                <div class="parking-form-card">

                    <div class="p-5 border-b border-gray-200">

                        <h5 class="font-semibold text-[16px]">
                            Vehicle Information
                        </h5>

                        <p class="text-[12px] text-[#8c9097] mt-1">
                            Enter the vehicle details and select an available parking slot.
                        </p>

                    </div>


                    <form action="{{ route('parking.store') }}" method="POST">

                        @csrf

                        <div class="p-5">

                            <div class="grid grid-cols-12 gap-x-6">

                                <!-- PLATE NUMBER -->
                                <div class="col-span-12 md:col-span-6">

                                    <div class="parking-field">

                                        <label for="plate_number"
                                               class="parking-label">
                                            Plate Number
                                            <span class="text-danger">*</span>
                                        </label>

                                        <input
                                            type="text"
                                            id="plate_number"
                                            name="plate_number"
                                            value="{{ old('plate_number') }}"
                                            placeholder="Enter vehicle plate number"
                                            required
                                        >

                                        @error('plate_number')
                                            <p class="text-danger text-[12px] mt-1">
                                                {{ $message }}
                                            </p>
                                        @enderror

                                    </div>

                                </div>


                                <!-- OWNER NAME -->
                                <div class="col-span-12 md:col-span-6">

                                    <div class="parking-field">

                                        <label for="owner_name"
                                               class="parking-label">
                                            Owner Name
                                            <span class="text-danger">*</span>
                                        </label>

                                        <input
                                            type="text"
                                            id="owner_name"
                                            name="owner_name"
                                            value="{{ old('owner_name') }}"
                                            placeholder="Enter vehicle owner's name"
                                            required
                                        >

                                        @error('owner_name')
                                            <p class="text-danger text-[12px] mt-1">
                                                {{ $message }}
                                            </p>
                                        @enderror

                                    </div>

                                </div>


                                <!-- VEHICLE TYPE -->
                                <div class="col-span-12 md:col-span-6">

                                    <div class="parking-field">

                                        <label for="vehicle_type"
                                               class="parking-label">
                                            Vehicle Type
                                            <span class="text-danger">*</span>
                                        </label>

                                        <select
                                            id="vehicle_type"
                                            name="vehicle_type"
                                            required
                                        >
                                            <option value="">
                                                -- Select Vehicle Type --
                                            </option>

                                            <option value="Car"
                                                {{ old('vehicle_type') == 'Car' ? 'selected' : '' }}>
                                                Car
                                            </option>

                                            <option value="Motorcycle"
                                                {{ old('vehicle_type') == 'Motorcycle' ? 'selected' : '' }}>
                                                Motorcycle
                                            </option>

                                            <option value="Van"
                                                {{ old('vehicle_type') == 'Van' ? 'selected' : '' }}>
                                                Van
                                            </option>

                                        </select>

                                        @error('vehicle_type')
                                            <p class="text-danger text-[12px] mt-1">
                                                {{ $message }}
                                            </p>
                                        @enderror

                                    </div>

                                </div>


                                <!-- PARKING SLOT -->
                                <div class="col-span-12 md:col-span-6">

                                    <div class="parking-field">

                                        <label for="parking_slot_id"
                                               class="parking-label">
                                            Parking Slot
                                            <span class="text-danger">*</span>
                                        </label>

                                        <select
                                            id="parking_slot_id"
                                            name="parking_slot_id"
                                            required
                                        >

                                            <option value="">
                                                -- Select Available Slot --
                                            </option>

                                            @forelse ($parkingSlots as $slot)

                                                <option
                                                    value="{{ $slot->id }}"
                                                    {{ old('parking_slot_id') == $slot->id ? 'selected' : '' }}
                                                >
                                                    {{ $slot->slot_number }}
                                                    - {{ $slot->slot_type }}
                                                </option>

                                            @empty

                                            @endforelse

                                        </select>

                                        @error('parking_slot_id')
                                            <p class="text-danger text-[12px] mt-1">
                                                {{ $message }}
                                            </p>
                                        @enderror

                                        @if ($parkingSlots->isEmpty())
                                            <p class="text-danger text-[12px] mt-2">
                                                No available parking slots found.
                                            </p>
                                        @endif

                                    </div>

                                </div>

                            </div>


                            <!-- FORM BUTTONS -->
                            <div class="flex flex-wrap justify-end gap-3 pt-5 border-t border-gray-200">

                                <a href="{{ route('parking.index') }}"
                                   class="parking-cancel">
                                    Cancel
                                </a>

                                <button
                                    type="submit"
                                    class="parking-submit"
                                    @disabled($parkingSlots->isEmpty())
                                >
                                    Register Vehicle
                                </button>

                            </div>

                        </div>

                    </form>

                </div>


                <!-- FOOTER -->
                <footer class="text-center text-[12px] text-[#8c9097] py-6">
                    Parking Management System © {{ now()->year }}
                </footer>

            </div>

        </div>

    </div>

</div>


<!-- VALEx JAVASCRIPT -->
<script src="{{ asset('backend/assets/libs/@popperjs/core/umd/popper.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/preline/preline.js') }}"></script>
<script src="{{ asset('backend/assets/js/defaultmenu.js') }}"></script>
<script src="{{ asset('backend/assets/js/custom.js') }}"></script>

</body>
</html>
