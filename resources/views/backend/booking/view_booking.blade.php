@extends('admin.admin_dashboard')
@section('admin') 

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('booking.list') }}">All Booking</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View Booking</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('booking.list') }}" class="btn btn-primary px-5">Back to List</a>
                <a href="{{ route('download.invoice',$editData->id) }}" class="btn btn-warning px-5"><i class="lni lni-download"></i> Download Invoice</a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-5">
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Booking No:</p>
                            <h6 class="my-1 text-info">{{ $editData->code }}</h6>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class='bx bxs-cart'></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Booking Date:</p>
                            <h6 class="my-1 text-danger">{{ \Carbon\Carbon::parse($editData->created_at)->format('d/m/Y') }}</h6>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto"><i class='bx bxs-wallet'></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Payment Method</p>
                            <h6 class="my-1 text-success">{{ $editData->payment_method }}</h6>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='bx bxs-bar-chart-alt-2'></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Payment Status</p>
                            <h6 class="my-1 text-warning">
                                @if ($editData->payment_status == '1')
                                    <span class="text-success">Complete</span>
                                @else
                                    <span class="text-danger">Pending</span>
                                @endif
                            </h6>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i class='bx bxs-group'></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Booking Status</p>
                            <h6 class="my-1 text-warning">
                                @if ($editData->status == '1')
                                    <span class="text-success">Active</span>
                                @else
                                    <span class="text-danger">Pending</span>
                                @endif
                            </h6>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i class='bx bxs-group'></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->

    <div class="row mt-4">
        <div class="col-12 col-lg-8 d-flex">
            <div class="card radius-10 w-100">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Booking Details</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Room Type</th>
                                    <th>Total Room</th>
                                    <th>Price</th>
                                    <th>Check In / Out Date</th>
                                    <th>Total Days</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $editData->room->type->name }}</td>
                                    <td>{{ $editData->number_of_rooms }}</td>
                                    <td>{{ rupiah($editData->actual_price) }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $editData->check_in }}</span> /<br>
                                        <span class="badge bg-warning text-dark">{{ $editData->check_out }}</span>
                                    </td>
                                    <td>{{ $editData->total_night }}</td>
                                    <td>{{ rupiah($editData->actual_price * $editData->number_of_rooms) }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="col-md-6" style="float: right; margin-top: 20px;">
                            <style>
                                .test_table td{text-align: right;}
                            </style>
                            <table class="table test_table" style="float: right" border="none">
                                <tr>
                                    <td>Subtotal</td>
                                    <td>{{ rupiah($editData->subtotal) }}</td>
                                </tr>
                                <tr>
                                    <td>Discount</td>
                                    <td>{{ rupiah($editData->discount) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Grand Total</strong></td>
                                    <td><strong>{{ rupiah($editData->total_price) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                        <div style="clear: both"></div>

                        @php
                            $assign_rooms = App\Models\BookingRoomList::with('room_number')->where('booking_id',$editData->id)->get();
                        @endphp

                        @if (count($assign_rooms) > 0)
                        <div style="margin-top: 40px;">
                            <h6 class="mb-3">Assigned Rooms</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Room Number</th>
                                </tr>
                                @foreach ($assign_rooms as $assign_room)
                                <tr>
                                    <td>{{ $assign_room->room_number->room_no }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                        @else
                        <div class="alert alert-info text-center mt-4">
                            No rooms assigned yet
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card radius-10 w-100">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Customer Information</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center border-top">
                            Name <span class="badge bg-success rounded-pill">{{ $editData['user']['name'] ?? $editData->name }}</span>
                        </li>
                        <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">
                            Email <span class="badge bg-danger rounded-pill">{{ $editData['user']['email'] ?? $editData->email }}</span>
                        </li>
                        <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">
                            Phone <span class="badge bg-primary rounded-pill">{{ $editData['user']['phone'] ?? $editData->phone ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center border-top">
                            Country <span class="badge bg-warning text-dark rounded-pill">{{ $editData->country ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">
                            State <span class="badge bg-success rounded-pill">{{ $editData->state ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">
                            Zip Code <span class="badge bg-danger rounded-pill">{{ $editData->zip_code ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">
                            Address <span class="badge bg-danger rounded-pill">{{ $editData->address ?? 'N/A' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card radius-10 w-100 mt-3">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Actions</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('download.invoice',$editData->id) }}" class="btn btn-warning">
                            <i class="lni lni-download"></i> Download Invoice
                        </a>
                        @if ($editData->payment_status != '1')
                            <a href="{{ route('edit_booking',$editData->id) }}" class="btn btn-primary">Edit Booking</a>
                        @else
                            <button class="btn btn-secondary" disabled>Edit (Locked)</button>
                        @endif
                        <a href="{{ route('booking.list') }}" class="btn btn-info">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

