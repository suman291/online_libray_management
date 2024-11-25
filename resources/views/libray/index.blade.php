@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
<div class="content-wrapper m-auto">
    <h2>Libraries</h2>
    
    <div class="table-responsive bg-white">
        <!-- Button to open the modal -->
        <button class="btn btn-primary mt-2 mb-2" data-toggle="modal" data-target="#addLibraryModal">Add Library</button>
        
        <!-- Library Table -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Longitude</th>
                    <th>Latitude</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if($libraries->isNotEmpty())
                    @foreach($libraries as $library)
                        <tr>
                            <td>{{ $library->name }}</td>
                            <td>{{ $library->location }}</td>
                            <td>{{ $library->long }}</td>
                            <td>{{ $library->lat }}</td>
                            <td>{{ date('Y-m-d H:i:s', strtotime($library->created_at)) }}</td>
                            <td><i class="fas fa-edit" onclick="openEditModal({{ $library }})"></i>&nbsp;<i class="fa fa-solid fa-trash" onclick="openDeleteModal({{ $library->id }})"></i></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">No libraries available.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Adding Library -->
<div class="modal fade" id="addLibraryModal" tabindex="-1" role="dialog" aria-labelledby="addLibraryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLibraryModalLabel">Add New Library</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('libraries.store') }}" id="libraryForm" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Library Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" class="form-control" id="location" name="location" required>
                    </div>
                    <div class="form-group">
                        <label for="long">Longitude</label>
                        <input type="text" class="form-control" id="long" name="long" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="lat">Latitude</label>
                        <input type="text" class="form-control" id="lat" name="lat" readonly required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Library</button>
                </form>
                 <div id="formResponse" class="mt-2"></div>
            </div>
        </div>
    </div>
</div>
<!-- Edit Library Modal -->
<div class="modal fade" id="editLibraryModal" tabindex="-1" role="dialog" aria-labelledby="editLibraryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLibraryModalLabel">Edit Library</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editLibraryForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editLibraryId" name="library_id">
                    <div class="form-group">
                        <label for="editName">Library Name</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="editLocation">Location</label>
                        <input type="text" class="form-control" id="editLocation" name="location" required>
                    </div>
                    <div class="form-group">
                        <label for="editLong">Longitude</label>
                        <input type="text" class="form-control" id="editLong" name="long" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="editLat">Latitude</label>
                        <input type="text" class="form-control" id="editLat" name="lat" readonly required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Library</button>
                </form>
                <div id="editFormResponse" class="mt-2"></div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Library Modal -->
<div class="modal fade" id="deleteLibraryModal" tabindex="-1" role="dialog" aria-labelledby="deleteLibraryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteLibraryModalLabel">Delete Library</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this library?</p>
                <button id="confirmDeleteBtn" class="btn btn-danger">Yes, Delete</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

@stop



@section('js')
<script>
    $(document).ready(function(){
        $('#location').on('input', () => {
        let address="";
            address=$('#location').val();
            
                 $.ajax({
    url: "{{ route('get-address') }}",
    type: 'POST',
    data: {
        address: address,
        _token: "{{ csrf_token() }}"
    },
    success: function(result) {
     
       const data=JSON.parse(result);
       if (data.status === "success") {
            $('#long').val(data.data.Longitude)
            $('#lat').val(data.data.Latitude)

            
        } else {
            $('#long').val()
            $('#lat').val()
            alert(data.message);
        }
    }
});
        });
        $('#editLocation').on('input', () => {
        let address="";
            address=$('#editLocation').val();
            
                 $.ajax({
    url: "{{ route('get-address') }}",
    type: 'POST',
    data: {
        address: address,
        _token: "{{ csrf_token() }}"
    },
    success: function(result) {
     
       const data=JSON.parse(result);
       if (data.status === "success") {
            $('#editLong').val(data.data.Longitude)
            $('#editLat').val(data.data.Latitude)

            
        } else {
            $('#editLong').val()
            $('#editLat').val()
            alert(data.message);
        }
    }
});
        });
               $('#libraryForm').on('submit', function(event) {
        event.preventDefault(); // Prevent default form submission
        var formData = $(this).serialize(); // Serialize form data

        $.ajax({
            url: '{{ route('libraries.store') }}', // Set the route to submit the form data
            method: 'POST',
            data: formData,
            success: function(response) {
                // Show a success message
                $('#formResponse').html('<div class="alert alert-success">' + response.message + '</div>');

                // Hide the modal after 5 seconds
                setTimeout(function() {
                    $('#addLibraryModal').modal('hide');
                    $('#formResponse').html(''); 
                    $('#libraryForm')[0].reset(); 
                    location.reload()
                }, 3000);
            },
            error: function(response) {
                // Handle errors
                $('#formResponse').html('<div class="alert alert-danger">An error occurred while adding the library.</div>');
            }
        });
    });
        // Open Edit Modal and populate data
    window.openEditModal = function(library) {
        $('#editLibraryId').val(library.id);
        $('#editName').val(library.name);
        $('#editLocation').val(library.location);
        $('#editLong').val(library.long);
        $('#editLat').val(library.lat);
        $('#editLibraryModal').modal('show');
    };

    // Open Delete Modal and set library ID
    window.openDeleteModal = function(libraryId) {
        $('#confirmDeleteBtn').data('id', libraryId);
        $('#deleteLibraryModal').modal('show');
    };

    // Submit Edit Form
    $('#editLibraryForm').on('submit', function(event) {
        event.preventDefault();
        var libraryId = $('#editLibraryId').val();
        var formData = $(this).serialize();

        $.ajax({
            url: '/libraries/' + libraryId,
            method: 'PUT',
            data: formData,
            success: function(response) {
                $('#editFormResponse').html('<div class="alert alert-success">' + response.message + '</div>');
                setTimeout(function() {
                    $('#editLibraryModal').modal('hide');
                    location.reload();
                }, 3000);
            },
            error: function(response) {
                $('#editFormResponse').html('<div class="alert alert-danger">An error occurred while updating the library.</div>');
            }
        });
    });

    // Delete Library
    $('#confirmDeleteBtn').on('click', function() {
        var libraryId = $(this).data('id');

        $.ajax({
            url: '/libraries/' + libraryId,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                $('#deleteLibraryModal').modal('hide');
                location.reload();
            },
            error: function(response) {
                alert('An error occurred while deleting the library.');
            }
        });
    });
    });
</script>
@stop
