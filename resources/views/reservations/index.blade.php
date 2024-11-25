@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
<div class="content-wrapper m-auto">
    <h2>Book Reservations</h2>
 @if (auth()->check() && auth()->user()->role=="users")
    
    <div class="table-responsive bg-white">
        <!-- Button to open the modal -->
        <button class="btn btn-primary mt-2 mb-2" data-toggle="modal" data-target="#addReserveModal">Reserve a Book</button>
        
        <!-- Library Table -->
     

        <table class="table table-bordered table-striped">
     
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Book Name</th>
                    <th>Reserved Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @if($getreservations->isNotEmpty())
                    @foreach($getreservations as $getreservation)
                        <tr>
                            <td>{{ $getreservation->userDetails->name }}</td>
                            <td>{{ $getreservation->bookName->title }}</td>
                            <td>{{ date('Y-m-d H:i:s', strtotime($getreservation->created_at)) }}</td>
                            <td>{{ ($getreservation->status == "reserved") ? "Reserved" : "Returned"}}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="text-center">No data available.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>   
@endif
@if (auth()->check() && auth()->user()->role=="admin")
    
    <div class="table-responsive bg-white">
        
        <!-- Library Table -->
     

        <table class="table table-bordered table-striped">
     
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Book Name</th>
                    <th>Libray Name</th>
                    <th>Reserved Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if($getreservations->isNotEmpty())
                    @foreach($getreservations as $getreservation)
                        <tr>
                            <td>{{ $getreservation->userDetails->name }}</td>
                            <td>{{ $getreservation->bookName->title }}</td>
                            <td>{{ $getreservation->libraryDetails->name }}</td>
                            <td>{{ date('Y-m-d H:i:s', strtotime($getreservation->created_at)) }}</td>
                            <td>{{ ($getreservation->status == "reserved") ? "Reserved" : "Returned"}}</td>
                             <td><i class="fas fa-edit" data-toggle="modal" data-target="#editReserveModal" onclick="openEditModal({{ $getreservation }})"></i></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">No libraries available.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>   
@endif
<!--Reservation Add Modal-->
<div class="modal fade" id="addReserveModal" tabindex="-1" role="dialog" aria-labelledby="addReserveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addReserveModalLabel">Reserve a Book</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('reservation.store') }}" id="reserveForm" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id" class="form-control" value="{{auth()->user()->id}}">
                    <div class="form-group">
                        <label for="book_id">Books</label>
                        <select name="book_id" id="book_id" class="form-control form-select">
                            <option value="">Select Book</option>
                            @foreach($books as $book)
                              <option value="{{$book->id}}">{{ ucfirst($book->title) }}</option>  
                            @endforeach 
                        </select>
                    </div>
                      <div class="form-group">
                        <label for="libraies_id">Libraries</label>
                        <select name="libraies_id" id="libraies_id" class="form-control form-select">
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Reserve</button>
                </form>
                 <div id="formResponse" class="mt-2"></div>
            </div>
        </div>
    </div>
</div>
<!--Reservation Edit Modal-->
<div class="modal fade" id="editReserveModal" tabindex="-1" role="dialog" aria-labelledby="editReserveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editReserveModalLabel">Edit Reservation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" id="editreserveForm" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="reservationId">
                    <input type="hidden" name="user_id" id="edituser_id" class="form-control" value="">
                    <div class="form-group">
                        <label for="editbook_id"> Books</label>
                        <select name="book_id" id="editbook_id" class="form-control form-select">
                              
                        </select>
                    </div>
                      <div class="form-group">
                        <label for="editlibraies_id">Libraries</label>
                        <select name="libraies_id" id="editlibraies_id" class="form-control form-select">
                          
                        </select>
                    </div>
                     <div class="form-group">
                        <label for="editStatus">Status</label>
                        <select name="status" id="editStatus" class="form-control form-select">
                             <option value="reserved">Reserved</option>
                            <option value="returned">Returned</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Reserve</button>
                </form>
                 <div id="editFormResponse" class="mt-2"></div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    $(document).ready(()=>{
     window.openEditModal = function (reservationDetails) {
        // Populate the modal fields
        console.log(reservationDetails);
        $('#reservationId').val()
        $('#edituser_id').val('');
        $('#editbook_id').html('');
        $('#editlibraies_id').html('');
        $('#reservationId').val(reservationDetails.id)
        $('#edituser_id').val(reservationDetails.user_id);
        $('#editbook_id').html(`<option value="${reservationDetails.book_name.id}">${reservationDetails.book_name.title}</option>`);
        $('#editlibraies_id').html(`<option value="${reservationDetails.library_details.id}">${reservationDetails.library_details.name}</option>`);
        $('#editStatus').val(reservationDetails.status);
    };

    // Submit Edit Form
 $('#editreserveForm').on('submit', (e) => {
    e.preventDefault();
    const reservationId = $('#reservationId').val();
    const formData = $('#editreserveForm').serialize();

    $.ajax({
        url: `{{ route('reservation.update', ':id') }}`.replace(':id', reservationId),
        method: 'PUT',
        data: formData,
        success: function (response) {
           
            setTimeout(() =>{
                 $('#editFormResponse').html('<div class="alert alert-success">Reservation updated successfully!</div>');
                 location.reload();
                }, 1000);
        },
        error: function (response) {
            const message = response.responseJSON.message || 'An error occurred';
            $('#editFormResponse').html(`<div class="alert alert-danger">${message}</div>`);
        }
    });
});

       $('#book_id').on('change', () => {
    $('#libraies_id').empty();
    $.ajax({
        url: "{{ route('books-libray.show', ':id') }}".replace(':id', $('#book_id').val()),
        method: 'GET',
        success: function(response) {
            
            if (response.libraries && response.libraries.length > 0) {
                let librayOption = `<option value="">Select a library</option>`;

                for (const element of response.libraries) {
                    librayOption += `<option value="${element.id}">${element.name}</option>`;
                }

                $('#libraies_id').append(librayOption);
            } else {
                $('#libraies_id').append('<option value="">No libraries available</option>');
            }
        },
        error: function(response) {
            console.error("Error:", response);
            $('#libraies_id').append('<option value="">Error fetching libraries</option>');
        }
    });
});
   $('#reserveForm').on('submit', (e) => {
    e.preventDefault();

    const formData = $('#reserveForm').serialize(); // Serialize form data

    $.ajax({
        url: "{{ route('reservation.store') }}", // Replace with your endpoint
        method: 'POST',
        data: formData,
        success: function(response) {
            setTimeout(()=>{
            $('#formResponse').html('<div class="alert alert-success">Reservation successful!</div>');
                location.reload();
            },1000)
        },
        error: function(response) {
            console.error('Error:', response);
            $('#formResponse').html('<div class="alert alert-danger">'+response.responseJSON.message+'</div>');
        }
    });
});

    })
</script>
@endsection