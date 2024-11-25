@extends('adminlte::page')

@section('title', 'entries')

@section('content')
<div class="content-wrapper m-auto">
    <h2>Books</h2>
    
    <div class="table-responsive bg-white">
          <!-- Button to open the modal -->
        <button class="btn btn-primary mt-2 mb-2" data-toggle="modal" data-target="#addBookLibrayModal">Add Book Libray Data</button>
        
        <!-- Books Table -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Library Name</th>
                    <th>Book Name</th>
                    <th>Available Copies</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if($bookLibraries->isNotEmpty())
                    @foreach($bookLibraries as $bookLibray)
                        <tr>
                            <td>{{ $bookLibray->library->name }}</td>
                            <td>{{ $bookLibray->book->title }}</td>
                            <td>{{ $bookLibray->available_copies }}</td>
                            <td>{{ date('Y-m-d H:i:s', strtotime($bookLibray->created_at)) }}</td>
                            <td>
                                <i class="fas fa-edit" onclick="openEditModal({{ $bookLibray }})"></i>
                                <i class="fa fa-solid fa-trash" onclick="openDeleteModal({{ $bookLibray->id }})"></i>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">No data found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
<!-- Add Book Modal -->
<div class="modal fade" id="addBookLibrayModal" tabindex="-1" role="dialog" aria-labelledby="addBookLibrayModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBookLibrayModalLabel">Add New Book</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addBookLibrayForm" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="libraies_id">Library</label>
                        <select class="form-control" id="libraryId" name="libraies_id" required>
                            @foreach($libraries as $library)
                                <option value="{{ $library->id }}">{{ $library->name }}</option>
                            @endforeach
                        </select>
                    </div>
                     <div class="form-group">
                        <label for="book_id">Book</label>
                        <select class="form-control" id="bookId" name="book_id" required>
                            @foreach($books as $book)
                                <option value="{{ $book->id }}">{{ $book->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="title">Avalilable Copies</label>
                        <input type="number" class="form-control" id="availableCopies" name="available_copies" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Book</button>
                </form>
                <div id="formResponse" class="mt-2"></div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Book Modal -->
<div class="modal fade" id="editBookLibrayModal" tabindex="-1" role="dialog" aria-labelledby="editBookLibrayModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBookLibrayModalLabel">Edit Book</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editBookLibrayForm" method="POST">
                    @csrf
                    @method('PUT')  
                    
                    <div class="form-group">
                        <label for="editLibraryId">Library</label>
                        <select class="form-control" id="editLibraryId" name="libraies_id" required>
                            @foreach($libraries as $library)
                                <option value="{{ $library->id }}">{{ $library->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editBookId">Book</label>
                        <select class="form-control" id="editBookId" name="book_id" required>
                            @foreach($books as $book)
                                <option value="{{ $book->id }}">{{ $book->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="title">Avalilable Copies</label>
                        <input type="number" class="form-control" id="editAvailableCopies" name="available_copies" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Book</button>
                </form>
                <div id="editFormResponse" class="mt-2"></div>
            </div>
        </div>
    </div>
</div>


<!-- Delete Book Modal -->
<div class="modal fade" id="deleteBookLibrayModal" tabindex="-1" role="dialog" aria-labelledby="deleteBookLibrayModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteBookLibrayModalLabel">Delete Book</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this record?</p>
                <button id="confirmDeleteBtn" class="btn btn-danger">Yes, Delete</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

@stop




<!-- JavaScript for Handling AJAX Form Submission -->
@section('js')
<script>
    $(document).ready(function() {
        // Add Book Form Submission
       $('#addBookLibrayForm').on('submit', function(event) {
    event.preventDefault();

    var formData = $(this).serialize();

    $.ajax({
        url: '{{ route('books-libray.store') }}', // Laravel route
        method: 'POST',
        data: formData,
        success: function(response) {
            console.log("Success Response:", response);
            $('#formResponse').html('<div class="alert alert-success">' + response.message + '</div>');
            setTimeout(function() {
                $('#addBookLibrayModal').modal('hide');
                location.reload();
            }, 2000);
        },
        error: function(response) {
            console.error("Error Response:", response);
            var errorMessage = response.responseJSON?.message || "An error occurred while adding the book.";
            $('#formResponse').html('<div class="alert alert-danger">' + errorMessage + '</div>');

            // Optionally, display validation errors:
            if (response.responseJSON?.errors) {
                let errorsHtml = '<ul>';
                $.each(response.responseJSON.errors, function(key, error) {
                    errorsHtml += '<li>' + error + '</li>';
                });
                errorsHtml += '</ul>';
                $('#formResponse').append('<div class="alert alert-danger">' + errorsHtml + '</div>');
            }
        }
    });
});


        // Open the Edit Modal and Populate with Book Data
        window.openEditModal = function(bookLibray) {
            $('#editBookId').val(bookLibray.book_id);
            $('#editLibraryId').val(bookLibray.libraies_id);
            $('#editAvailableCopies').val(bookLibray.available_copies);
            $('#editBookLibrayModal').modal('show');
               $('#editBookLibrayForm').on('submit', function(event) {
            event.preventDefault();
 
            let formData = $(this).serialize();
            $.ajax({
                url: '{{ route("books-libray.update", ":id") }}'.replace(':id', bookLibray.id), // Replace placeholder with actual book ID
                method: 'PUT',
                data: formData,
                success: function(response) {
                    $('#editFormResponse').html('<div class="alert alert-success">' + response.message + '</div>');
                    setTimeout(function() {
                        $('#editBookModal').modal('hide');
                        location.reload();
                    }, 2000);
                },
                error: function(response) {
                    $('#editFormResponse').html('<div class="alert alert-danger">An error occurred while updating the book.</div>');
                }
            });
        });
        };

     
  
        // Open Delete Confirmation Modal and Delete Book
        window.openDeleteModal = function(bookLibrayId) {
            
            $('#deleteBookLibrayModal').modal('show');
            $('#confirmDeleteBtn').on('click', function() {
                $.ajax({
                    url: '{{ route("books-libray.destroy", ":id") }}'.replace(':id', bookLibrayId),
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#deleteBookLibrayModal').modal('hide');
                       location.reload();
                    },
                    error: function(response) {
                        alert('An error occurred while deleting the data.');
                    }
                });
            });
            
        };
        
    });
</script>
@stop
