@extends('admin.admin_dashboard')
@section('admin') 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Add Room</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add Room</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    
    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="mb-4">Add New Room</h5>

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form class="row g-3" action="{{ route('store.room') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="col-md-4">
                                    <label for="roomtype_id" class="form-label">Room Type <span class="text-danger">*</span></label>
                                    <select name="roomtype_id" id="roomtype_id" class="form-select" required>
                                        <option value="">Select Room Type</option>
                                        @foreach ($roomtypes as $roomtype)
                                            <option value="{{ $roomtype->id }}" {{ old('roomtype_id') == $roomtype->id ? 'selected' : '' }}>
                                                {{ $roomtype->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('roomtype_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="total_adult" class="form-label">Total Adult</label>
                                    <input type="text" name="total_adult" class="form-control" id="total_adult" value="{{ old('total_adult') }}">
                                </div>

                                <div class="col-md-4">
                                    <label for="total_child" class="form-label">Total Child</label>
                                    <input type="text" name="total_child" class="form-control" id="total_child" value="{{ old('total_child') }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="image" class="form-label">Main Image <span class="text-danger">*</span></label>
                                    <input type="file" name="image" class="form-control" id="image" required>
                                    <img id="showImage" src="{{ url('upload/no_image.jpg') }}" alt="Room" class="bg-primary mt-2" width="100" height="80">
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="multiImg" class="form-label">Gallery Image</label>
                                    <input type="file" name="multi_img[]" class="form-control" multiple id="multiImg" accept="image/jpeg, image/jpg, image/gif, image/png">
                                    <div class="row mt-2" id="preview_img"></div>
                                </div>

                                <div class="col-md-3">
                                    <label for="price" class="form-label">Room Price</label>
                                    <input type="text" name="price" class="form-control" id="price" value="{{ old('price') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="size" class="form-label">Size</label>
                                    <input type="text" name="size" class="form-control" id="size" value="{{ old('size') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="discount" class="form-label">Discount (%)</label>
                                    <input type="number" name="discount" class="form-control" id="discount" value="{{ old('discount', 0) }}" min="0" max="100">
                                </div>

                                <div class="col-md-3">
                                    <label for="room_capacity" class="form-label">Room Capacity</label>
                                    <input type="text" name="room_capacity" class="form-control" id="room_capacity" value="{{ old('room_capacity') }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="view" class="form-label">Room View</label>
                                    <select name="view" id="view" class="form-select">
                                        <option value="">Choose...</option>
                                        <option value="Sea View" {{ old('view') == 'Sea View' ? 'selected' : '' }}>Sea View</option>
                                        <option value="Hill View" {{ old('view') == 'Hill View' ? 'selected' : '' }}>Hill View</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="bed_style" class="form-label">Bed Style</label>
                                    <select name="bed_style" id="bed_style" class="form-select">
                                        <option value="">Choose...</option>
                                        <option value="Queen Bed" {{ old('bed_style') == 'Queen Bed' ? 'selected' : '' }}>Queen Bed</option>
                                        <option value="Twin Bed" {{ old('bed_style') == 'Twin Bed' ? 'selected' : '' }}>Twin Bed</option>
                                        <option value="King Bed" {{ old('bed_style') == 'King Bed' ? 'selected' : '' }}>King Bed</option>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label for="short_desc" class="form-label">Short Description</label>
                                    <textarea name="short_desc" class="form-control" id="short_desc" rows="3">{{ old('short_desc') }}</textarea>
                                </div>

                                <div class="col-md-12">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" class="form-control" id="myeditorinstance">{{ old('description') }}</textarea>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-12 mb-3">
                                        <div class="basic_facility_section_remove" id="basic_facility_section_remove">
                                            <div class="row add_item">
                                                <div class="col-md-6">
                                                    <label for="basic_facility_name" class="form-label">Room Facilities</label>
                                                    <select name="facility_name[]" id="basic_facility_name" class="form-control">
                                                        <option value="">Select Facility</option>
                                                        <option value="Complimentary Breakfast">Complimentary Breakfast</option>
                                                        <option value="32/42 inch LED TV">32/42 inch LED TV</option>
                                                        <option value="Smoke alarms">Smoke alarms</option>
                                                        <option value="Minibar">Minibar</option>
                                                        <option value="Work Desk">Work Desk</option>
                                                        <option value="Free Wi-Fi">Free Wi-Fi</option>
                                                        <option value="Safety box">Safety box</option>
                                                        <option value="Rain Shower">Rain Shower</option>
                                                        <option value="Slippers">Slippers</option>
                                                        <option value="Hair dryer">Hair dryer</option>
                                                        <option value="Wake-up service">Wake-up service</option>
                                                        <option value="Laundry & Dry Cleaning">Laundry & Dry Cleaning</option>
                                                        <option value="Electronic door lock">Electronic door lock</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group" style="padding-top: 30px;">
                                                        <a class="btn btn-success addeventmore"><i class="lni lni-circle-plus"></i></a>
                                                        <span class="btn btn-danger removeeventmore"><i class="lni lni-circle-minus"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <button type="submit" class="btn btn-primary px-4">Save Room</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#image').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#showImage').attr('src',e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        });
    });
</script>

<!-- Show MultiImage -->
<script>
    $(document).ready(function(){
        var selectedFiles = []; // Array to store selected files
        var fileIdCounter = 0; // Counter for unique file IDs
        
        $('#multiImg').on('change', function(){
            if (window.File && window.FileReader && window.FileList && window.Blob) {
                var data = $(this)[0].files;
                
                // Add new files to selectedFiles array
                $.each(data, function(index, file){
                    if(/(\.|\/)(gif|jpe?g|png|webp)$/i.test(file.type)){
                        // Check if file already exists
                        var isDuplicate = selectedFiles.some(function(existingFile) {
                            return existingFile.name === file.name && existingFile.size === file.size;
                        });
                        
                        if (!isDuplicate) {
                            // Add unique ID to file object
                            file.uniqueId = 'file_' + fileIdCounter++;
                            selectedFiles.push(file);
                            displayPreview(file);
                        }
                    }
                });
                
                // Update file input
                updateFileInput();
            } else {
                alert("Your browser doesn't support File API!");
            }
        });
        
        function displayPreview(file) {
            var fRead = new FileReader();
            fRead.onload = function(e) {
                // Create preview container with image and delete button
                var previewContainer = $('<div/>')
                    .addClass('col-md-3 mb-3 position-relative')
                    .attr('data-file-id', file.uniqueId);
                
                // Create image element
                var img = $('<img/>')
                    .addClass('img-thumbnail')
                    .attr('src', e.target.result)
                    .css({
                        'width': '100%',
                        'height': '150px',
                        'object-fit': 'cover'
                    });
                
                // Create delete button (X)
                var deleteBtn = $('<button/>')
                    .attr('type', 'button')
                    .addClass('btn btn-danger btn-sm position-absolute')
                    .css({
                        'top': '5px',
                        'right': '5px',
                        'width': '30px',
                        'height': '30px',
                        'padding': '0',
                        'border-radius': '50%',
                        'font-size': '18px',
                        'line-height': '1',
                        'z-index': '10',
                        'cursor': 'pointer'
                    })
                    .html('&times;')
                    .on('click', function() {
                        removeImage(file.uniqueId);
                    });
                
                previewContainer.append(img).append(deleteBtn);
                $('#preview_img').append(previewContainer);
            };
            fRead.readAsDataURL(file);
        }
        
        function removeImage(fileId) {
            // Remove file from array
            selectedFiles = selectedFiles.filter(function(file) {
                return file.uniqueId !== fileId;
            });
            
            // Remove preview container
            $('[data-file-id="' + fileId + '"]').remove();
            
            // Update file input
            updateFileInput();
        }
        
        function updateFileInput() {
            // Create new DataTransfer object
            var dataTransfer = new DataTransfer();
            
            // Add all files from selectedFiles array
            selectedFiles.forEach(function(file) {
                dataTransfer.items.add(file);
            });
            
            // Update file input with new files
            $('#multiImg')[0].files = dataTransfer.files;
        }
    });
</script>

<!-- Add Basic Plan Facilities -->
<div style="visibility: hidden">
    <div class="whole_extra_item_add" id="whole_extra_item_add">
        <div class="basic_facility_section_remove" id="basic_facility_section_remove">
            <div class="container mt-2">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="basic_facility_name">Room Facilities</label>
                        <select name="facility_name[]" id="basic_facility_name" class="form-control">
                            <option value="">Select Facility</option>
                            <option value="Complimentary Breakfast">Complimentary Breakfast</option>
                            <option value="32/42 inch LED TV">32/42 inch LED TV</option>
                            <option value="Smoke alarms">Smoke alarms</option>
                            <option value="Minibar">Minibar</option>
                            <option value="Work Desk">Work Desk</option>
                            <option value="Free Wi-Fi">Free Wi-Fi</option>
                            <option value="Safety box">Safety box</option>
                            <option value="Rain Shower">Rain Shower</option>
                            <option value="Slippers">Slippers</option>
                            <option value="Hair dryer">Hair dryer</option>
                            <option value="Wake-up service">Wake-up service</option>
                            <option value="Laundry & Dry Cleaning">Laundry & Dry Cleaning</option>
                            <option value="Electronic door lock">Electronic door lock</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6" style="padding-top: 20px">
                        <span class="btn btn-success addeventmore"><i class="lni lni-circle-plus"></i></span>
                        <span class="btn btn-danger removeeventmore"><i class="lni lni-circle-minus"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var counter = 0;
        $(document).on("click",".addeventmore",function(){
            var whole_extra_item_add = $("#whole_extra_item_add").html();
            $(this).closest(".add_item").append(whole_extra_item_add);
            counter++;
        });
        $(document).on("click",".removeeventmore",function(event){
            $(this).closest("#basic_facility_section_remove").remove();
            counter -= 1
        });
    });
</script>

@endsection

