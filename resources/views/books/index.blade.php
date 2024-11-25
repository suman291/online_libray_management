@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
<div class="content-wrapper m-auto">
    <h2>Books</h2>
    
    <div class="table-responsive bg-white">
          <!-- Button to open the modal -->
        <button class="btn btn-primary mt-2 mb-2" data-toggle="modal" data-target="#addBookModal">Add Book</button>
        
        <!-- Books Table -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    {{-- <th>Library Name</th> --}}
                    <th>Title</th>
                    <th>Author</th>
                    <th>Genre</th>
                    <th>Publication</th>
                    <th>Year</th>
                    <th>Availability</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if($books->isNotEmpty())
                    @foreach($books as $book)
                        <tr>
                            {{-- <td>{{ $book->libraries->name ?? "" }}</td> --}}
                            <td>{{ $book->title }}</td>
                            <td>{{ $book->author }}</td>
                            <td>{{ $book->genre }}</td>
                            <td>{{ $book->publication }}</td>
                            <td>{{ $book->year }}</td>
                            <td>{{ ($book->availability=="yes") ? 'Yes' : 'No' }}</td>
                            <td>{{ date('Y-m-d H:i:s', strtotime($book->created_at)) }}</td>
                            <td>
                                <i class="fas fa-edit" onclick="openEditModal({{ $book }})"></i>
                                <i class="fa fa-solid fa-trash" onclick="openDeleteModal({{ $book->id }})"></i>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9" class="text-center">No books available.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
<!-- Add Book Modal -->
<div class="modal fade" id="addBookModal" tabindex="-1" role="dialog" aria-labelledby="addBookModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBookModalLabel">Add New Book</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addBookForm" method="POST">
                    @csrf
                    {{-- <div class="form-group">
                        <label for="library_id">Library</label>
                        <select class="form-control" id="library_id_fk" name="library_id_fk" required>
                            @foreach($libraries as $library)
                                <option value="{{ $library->id }}">{{ $library->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="title">Author</label>
                        <input type="text" class="form-control" id="author" name="author" required>
                    </div>
                    <div class="form-group">
                        <label for="title">Genre</label>
                        <input type="text" class="form-control" id="genre" name="genre" required>
                    </div>
                    <div class="form-group">
                        <label for="title">Published By</label>
                        <input type="text" class="form-control" id="publication" name="publication" required>
                    </div>
                    <div class="form-group">
                        <label for="title">Published Year</label>
                        <input type="date" class="form-control" id="year" name="year" required>
                    </div>
                    <!-- Additional Fields for Author, Genre, etc. -->
                    <button type="submit" class="btn btn-primary">Save Book</button>
                </form>
                <div id="formResponse" class="mt-2"></div>
            </div>
        </div>
    </div>
</div>
<!-- Modal for Adding Books -->
{{-- <div class="modal fade" id="addBookModal" tabindex="-1" role="dialog" aria-labelledby="addLibraryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLibraryModalLabel">Add New Book</h5>
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
</div> --}}
<!-- Edit Book Modal -->
<div class="modal fade" id="editBookModal" tabindex="-1" role="dialog" aria-labelledby="editBookModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBookModalLabel">Edit Book</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editBookForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editBookId" name="id">
                    
                    <!-- Populate these fields with the selected book's data -->
                    {{-- <div class="form-group">
                        <label for="editLibraryId">Library</label>
                        <select class="form-control" id="editLibraryId" name="library_id_fk" required>
                            @foreach($libraries as $library)
                                <option value="{{ $library->id }}">{{ $library->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="form-group">
                        <label for="editTitle">Title</label>
                        <input type="text" class="form-control" id="editTitle" name="title" required>
                    </div>
                     <div class="form-group">
                        <label for="editAuthor">Author</label>
                        <input type="text" class="form-control" id="editAuthor" name="author" required>
                    </div>
                    <div class="form-group">
                        <label for="editGenre">Genre</label>
                        <input type="text" class="form-control" id="editGenre" name="genre" required>
                    </div>
                     <div class="form-group">
                        <label for="editPublication">Publication Name</label>
                        <input type="text" class="form-control" id="editPublication" name="publication" required>
                    </div>
                    <div class="form-group">
                        <label for="editYear">Publiched Year</label>
                        <input type="date" class="form-control" id="editYear" name="year" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Book</button>
                </form>
                <div id="editFormResponse" class="mt-2"></div>
            </div>
        </div>
    </div>
</div>


<!-- Delete Book Modal -->
<div class="modal fade" id="deleteBookModal" tabindex="-1" role="dialog" aria-labelledby="deleteBookModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteBookModalLabel">Delete Book</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this book?</p>
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
        $('#addBookForm').on('submit', function(event) {
            event.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: '{{ route('books.store') }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    $('#formResponse').html('<div class="alert alert-success">' + response.message + '</div>');
                    setTimeout(function() {
                        $('#addBookModal').modal('hide');
                        location.reload();
                    }, 2000);
                },
                error: function(response) {
                    $('#formResponse').html('<div class="alert alert-danger">An error occurred while adding the book.</div>');
                }
            });
        });

        // Open the Edit Modal and Populate with Book Data
        window.openEditModal = function(book) {
            $('#editBookId').val(book.id);
            //$('#editLibraryId').val(book.library_id_fk);
            $('#editTitle').val(book.title);
            $('#editAuthor').val(book.author);
            $('#editGenre').val(book.genre);
            $('#editPublication').val(book.publication);
            $('#editYear').val(book.year);
            $('#editBookModal').modal('show');
        };

        // Edit Book Form Submission
        $('#editBookForm').on('submit', function(event) {
            event.preventDefault();
            var bookId = $('#editBookId').val(); // Get the book ID
            var formData = $(this).serialize();

            // Ensure the URL is correctly formed with the book ID
            $.ajax({
                url: '{{ route("books.update", ":book") }}'.replace(':book', bookId), // Replace placeholder with actual book ID
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
        // let bookId="";
        // Open Delete Confirmation Modal and Delete Book
        window.openDeleteModal = function(bookId) {
            
            $('#deleteBookModal').modal('show');
            $('#confirmDeleteBtn').on('click', function() {
                // Ensure the URL is correctly formed with the book ID
                $.ajax({
                    url: '{{ route("books.destroy", ":book") }}'.replace(':book', bookId), // Replace placeholder with actual book ID
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#deleteBookModal').modal('hide');
                        location.reload();
                    },
                    error: function(response) {
                        alert('An error occurred while deleting the book.');
                    }
                });
            });
            
        };
        
    });
</script>
@stop
