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
                    <li class="breadcrumb-item active" aria-current="page">All Room</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('add.room') }}" class="btn btn-primary px-5">Add Room</a>
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
                            <th>Image</th>
                            <th>Room Type</th>
                            <th>Price</th>
                            <th>Size</th>
                            <th>Capacity</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allData as $key => $item)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>
                                <img src="{{ (!empty($item->image)) ? url('upload/roomimg/'.$item->image) : url('upload/no_image.jpg') }}" 
                                     alt="" style="width: 70px; height: 50px;">
                            </td>
                            <td>{{ $item->type->name ?? 'N/A' }}</td>
                            <td>{{ $item->price ?? 'N/A' }}</td>
                            <td>{{ $item->size ?? 'N/A' }}</td>
                            <td>{{ $item->room_capacity ?? 'N/A' }}</td>
                            <td>
                                @if($item->status == 1)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('edit.room', $item->id) }}" class="btn btn-warning px-3 radius-30">Edit</a>
                                <a href="{{ route('delete.room', $item->id) }}" class="btn btn-danger px-3 radius-30" id="delete">Delete</a>
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

