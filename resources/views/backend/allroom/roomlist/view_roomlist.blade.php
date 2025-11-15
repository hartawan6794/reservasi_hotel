@extends('admin.admin_dashboard')
@section('admin') 

<div class="page-content"> 
	<!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
         
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">All Room List </li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('add.room.list') }}" class="btn btn-primary px-5">Add Booking </a>
                
            </div>
        </div>
    </div>
    <!--end breadcrumb-->


    
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Room Type</th>
                            <th>Room Number</th>
                            <th>B Status </th>
                            <th>In/Out Date</th>
                            <th>Booking No</th>
                            <th>Customer</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                       @foreach ($room_number_list as $key=> $item ) 
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $item->room_type->name ?? 'N/A' }}</td>
                            <td>{{ $item->room_no }}</td>
                            <td>
                                @if ($item->current_booking)
                                    @if ($item->current_booking->booking_status == 1)
                                        <span class="badge bg-danger">Booked</span>
                                    @else   
                                        <span class="badge bg-warning">Pending</span>
                                    @endif 
                                @else
                                    <span class="badge bg-success">Available</span>
                                @endif    
                            </td>

                            <td>
                                @if ($item->current_booking)
                                    <span class="badge rounded-pill bg-secondary">
                                        {{ date('d-m-Y', strtotime($item->current_booking->check_in)) }}
                                    </span>
                                    to 
                                    <span class="badge rounded-pill bg-info text-dark">
                                        {{ date('d-m-Y', strtotime($item->current_booking->check_out)) }}
                                    </span>
                                    @if ($item->upcoming_bookings->count() > 0)
                                        <br><small class="text-muted">+ {{ $item->upcoming_bookings->count() }} upcoming</small>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif   
                            </td>

                            <td>
                                @if ($item->current_booking)
                                    {{ $item->current_booking->booking_no }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif 
                            </td>

                            <td>
                                @if ($item->current_booking)
                                    {{ $item->current_booking->customer_name }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif 
                            </td>

                            <td>
                                @if ($item->status == 'Active')
                                    <span class="badge bg-success">Published</span>
                                @else
                                    <span class="badge bg-danger">InActive</span>
                                @endif
                            </td> 

                        </tr>
                        @endforeach 
                      
                    </tbody>
                 
                </table>
            </div>
        </div>
    </div>
     
    <hr/>
     
</div>




@endsection