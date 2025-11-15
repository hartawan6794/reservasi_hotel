@extends('admin.admin_dashboard')
@section('admin') 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Add Gallery </div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Add Gallery</li>
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
                
       <form  class="row g-3" action="{{ route('store.gallery') }}" method="post" enctype="multipart/form-data">
         @csrf

                 
    
    <div class="col-md-6">
        <label for="input1" class="form-label">Gallery Image </label>
        <input type="file" name="photo_name[]" class="form-control" id="multiImg" multiple  >
        <div class="row" id="preview_img"></div>
    </div>
 
                 
                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">Save Changes </button>
                            
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


           
 
         <!--------===Show MultiImage ========------->
<script>
    $(document).ready(function(){
        var selectedFiles = []; // Array to store selected files
        var fileIdCounter = 0; // Counter for unique file IDs
        
        $('#multiImg').on('change', function(){ //on file input change
            if (window.File && window.FileReader && window.FileList && window.Blob) //check File API supported browser
            {
                var data = $(this)[0].files; //this file data
                
                // Add new files to selectedFiles array
                $.each(data, function(index, file){
                    if(/(\.|\/)(gif|jpe?g|png|webp)$/i.test(file.type)){ //check supported file type
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
                alert("Your browser doesn't support File API!"); //if File API is absent
            }
        });
        
        function displayPreview(file) {
            var fRead = new FileReader(); //new filereader
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
            fRead.readAsDataURL(file); //URL representing the file's data.
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


@endsection