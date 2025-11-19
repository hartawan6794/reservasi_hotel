@extends('admin.admin_dashboard')
@section('admin') 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<style>
    .large-checkbox{
        transform: scale(1.5);
    }
    .rating-stars {
        color: #ffc107;
    }
    .rating-stars i {
        font-size: 16px;
    }
</style>

<div class="page-content"> 
	<!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
         
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">All Room Reviews</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group"> 
                
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
                            <th>Room</th>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
         @foreach ($reviews as $key=> $item ) 
    <tr>
        <td>{{ $key+1 }}</td> 
        <td>{{ $item->room->type->name ?? 'N/A' }}</td>
        <td>{{ $item->name }}</td>
        <td>{{ $item->email }}</td>
        <td>
            <div class="rating-stars">
                @for($i = 1; $i <= 5; $i++)
                    <i class='bx {{ $i <= $item->rating ? 'bxs-star' : 'bx-star' }}'></i>
                @endfor
                <span class="ms-2">({{ $item->rating }}/5)</span>
            </div>
        </td>
        <td>{{ Str::limit($item->comment, 50) }}</td>
        <td>
            @if($item->status == 1)
                <span class="badge bg-success">Approved</span>
            @else
                <span class="badge bg-warning">Pending</span>
            @endif
        </td>
        <td>{{ $item->created_at->format('M d, Y') }}</td>
        <td>
            <div class="d-flex gap-2">
                <div class="form-check-danger form-check form-switch">
                    <input class="form-check-input status-toggle large-checkbox" type="checkbox" 
                           data-review-id="{{ $item->id }}" 
                           {{ $item->status ? 'checked' : '' }}>
                    <label class="form-check-label"></label>
                </div>
                <a href="{{ route('delete.review', $item->id) }}" class="btn btn-danger btn-sm" id="delete">
                    <i class='bx bx-trash'></i>
                </a>
            </div>
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

<script>
    $(document).ready(function(){
        $('.status-toggle').on('change', function(){
            var reviewId = $(this).data('review-id');
            var isChecked = $(this).is(':checked');
            var status = isChecked ? 1 : 0;

            // Send an ajax request to update status 
            $.ajax({
                url: "{{ route('update.review.status') }}",
                method: "POST",
                data: {
                    review_id: reviewId,
                    status: status,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response){
                    toastr.success(response.message);
                    // Reload page after 1 second to update status badge
                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                },
                error: function(xhr){
                    toastr.error('Error updating review status');
                    console.error(xhr);
                }
            }); 

        });
    });
</script>

@endsection

