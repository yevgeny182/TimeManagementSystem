<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Management System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('externalscripts.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h2>Time Management System</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="button-container mb-3">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
            Add Users
        </button>

        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#importUserModal">
            Import Users
        </button>

    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <!-- Search and Count -->
        <div class="row align-items-center mb-3">
            
        <div class="col">
                <h5> <span style="color: #DC3545;">Total Users Logged: <span id="userCount">{{ $loggedUsersCount }} / {{ $totalUsers }}</span></span> </h5>
        </div>
       
<div class="col-auto">
    <div class="input-group mb-3">
        <label for="recordsPerPage" class="form-label  mb-2">Records per page:</label>
        <select id="recordsPerPage" class="form-select ms-2" aria-label="Records per page">
            <option value="5">5</option>
            <option value="10" selected>10</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
    </div>
</div>
<div class="col-auto">
        <div class="input-group mb-3"> 
        <span class="input-group-text" id="search-addon">
        <i class="fas fa-search"></i>
        </span>
        <input type="text" id="searchUserInput" class="form-control" placeholder="Search user..." aria-label="Search user" aria-describedby="search-addon">
    </div>  
   
</div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <table class="table table-bordered" id="userTable" class="table">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Actions</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>
                    Time Logged 
                    <button id="refreshTimeLogged" class="btn btn-link btn-sm" title="Refresh">
                        <i class="fas fa-sync-alt"></i> <!-- Font Awesome reload icon -->
                    </button>
                </th>
                <th> Time Remaining </th>
            </tr>
        </thead>
                    <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="user-first-name">{{ $user->first_name }}</td>
                        <td class="user-last-name">{{ $user->last_name }}</td>
                        <td class="user-email">{{ $user->email }}</td>
                        <td class="user-phone">{{ $user->phone_number }}</td>
                        <td>
                            @if(session('user_' . $user->id))
                                <button class="btn btn-danger btn-login-logout" data-user-id="{{ $user->id }}">Logout</button>
                            @else
                                <button class="btn btn-success btn-login-logout" data-user-id="{{ $user->id }}">Login</button>
                            @endif
                            <button class="btn btn-warning btn-assign-hours" data-target="#assignHours" data-toggle="modal" 
                            data-first-name="{{ $user->first_name }}" data-last-name="{{ $user->last_name }}" data-user-id="{{ $user->id }}"
                            > Assign Hours </button>
                        </td>
                        <td class="time-in">{{ $user->login_time == NULL ? "Not In" : "$user->login_time" }}</td>
                        <td class="time-out">{{ $user->logout_time == NULL ? "Not Out" : "$user->logout_time" }}</td>
                        <td class="time-logged" data-user-id="{{ $user->id }}">
                            @if(session('user_' . $user->id))
                                <!-- If the user is currently logged in, display N/A or leave empty -->
                                N/A
                            @else
                                @if($user->login_time && $user->logout_time)
                                    <?php
                                        $timeIn = \Carbon\Carbon::parse($user->login_time);
                                        $timeOut = \Carbon\Carbon::parse($user->logout_time);
                                        $diff = $timeIn->diff($timeOut);
                                    ?>
                                    {{ $diff->h }} hour(s) {{ $diff->i }} minutes
                                @else
                                N/A
                                @endif
                            @endif
                        </td>
                        <td> {{$user->time_remaining == NULL ? "No hours assigned" : "$user->time_remaining"}} </td>
                        <td>
                            <button class="btn btn-danger btn-delete-user" data-user-id="{{ $user->id }}" data-first-name="{{ $user->first_name }}" data-last-name="{{ $user->last_name }}" data-bs-target="#deleteUserModal" data-bs-toggle="modal">Delete User</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        <tfoot>
    </table>

            <footer class="text-center mt-4">
            <p>© Yevgeny A.</p>
            </footer>

    <!-- Add Users Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm" action="{{ route('add.user') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="first_name" placeholder="Enter First Name" required>
                        </div>

                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="last_name" placeholder="Enter Last Name" required>
                        </div>
                      
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
                        </div>
                        <div class="form-group">
                            <label for="phoneNumber">Phone Number</label>
                            <input type="tel" class="form-control" id="phoneNumber" name="phone_number" placeholder="Enter Phone Number" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

  <!-- Assign Hours Modal -->
        <div class="modal fade" id="assignHours" tabindex="-1" role="dialog" aria-labelledby="assignHoursLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignHoursLabel">Assign Hours </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                            <div class="modal-body">
                    Input Custom Hours:
                        <input type="text" id="customHRS" class="form-control" placeholder="Hours" aria-label="Custom Hours" aria-describedby="search-addon">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveHours">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

    <!-- Import Users Modal -->
    <div class="modal fade" id="importUserModal" tabindex="-1" aria-labelledby="importUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importUserModalLabel">Import Users</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form action="{{ route('import.users') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file">Select Excel File</label>
                            <input type="file" class="form-control" id="import_file" name="import_file" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

            <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
            </div>
        </div>
        </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
          $(document).on('click', '.btn-login-logout', function() {
            let button = $(this);
            let userId = button.data('user-id');
            let action = button.text().toLowerCase();
            
            // Ensure proper route for login/logout
            let url = action === 'login' ? '{{ route('user.login') }}' : '{{ route('user.logout') }}';
            console.log('AJAX URL:', action === 'login' ? '{{ route('user.login') }}' : '{{ route('user.logout') }}');

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    user_id: userId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        if (action === 'login') {
                            button.text('Logout').removeClass('btn-success').addClass('btn-danger');
                            button.closest('tr').find('.time-in').text(response.login_time); // Update time-in directly

                            let loggedInCount = parseInt($('#userCount').text().split(' / ')[0]);
                            $('#userCount').text((loggedInCount + 1) + ' / {{ $totalUsers }}');

                        } else {
                            button.text('Login').removeClass('btn-danger').addClass('btn-success');
                            button.closest('tr').find('.time-out').text(response.logout_time); // Update time-out directly
                            let loggedInCount = parseInt($('#userCount').text().split(' / ')[0]);
                            $('#userCount').text((loggedInCount - 1) + ' / {{ $totalUsers }}');
                        }

                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error Status:', status); // Log the status
                    console.error('Error Response:', xhr.responseText); 
                }
            });
        });

    </script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).on('click', '.btn-delete-user', function() {
        let button = $(this);
        let userId = button.data('user-id');

        // Show the modal
        $('#deleteUserModal').modal('show');

        // Handle the deletion when the user clicks "Delete" in the modal
        $('#confirmDelete').off('click').on('click', function() {
            // Define the correct URL using Laravel's route helper
            let url = '{{ route('user.delete', '') }}/' + userId; // Correct usage

            $.ajax({
                url: url, // Use the constructed URL
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}', // Include CSRF token
                },
                success: function(response) {
                    if (response.status === 'success') {
                        button.closest('tr').remove(); // Remove the user row from the table
                        $('#userCount').text(response.loggedUsersCount + ' / ' + response.totalUsers);
                        
                        // Optional: Show a success toast or alert message
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            title: 'User deleted successfully',
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });
                    } else {
                        Swal.fire({
                            toast: true,
                            icon: 'error',
                            title: 'Error deleting user: ' + response.message,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });
                    }
                    // Close the modal after the AJAX request completes
                    $('#deleteUserModal').modal('hide');
                },
                error: function(xhr, status, error) {
                    console.error('Error Status:', status);
                    console.error('Error Response:', xhr.responseText);
                    
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        title: 'Error occurred during deletion.',
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                    // Close the modal on error
                    $('#deleteUserModal').modal('hide');
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.btn-login-logout').click(function() {
            const userId = $(this).data('user-id');

            // Send an AJAX request to log the user out
            $.ajax({
                url: `/logout/${userId}`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}' // Include CSRF token
                },
                success: function(response) {
                    // Update the UI with the new logout time and time logged
                    $(`.time-out[data-user-id="${userId}"]`).text(response.logout_time);
                    $(`.time-logged[data-user-id="${userId}"]`).text(response.time_logged);
                },
                error: function(xhr) {
                    console.error(xhr);
                }
            });
        });
    });
</script>
        <script>
 $(document).ready(function () {
    // Event listener for the search input
    $('#searchUserInput').on('input', function () {
        const searchQuery = $(this).val().toLowerCase(); // Get the search input value and convert to lowercase
        // Filter the user data
        filterUsers(searchQuery);
    });

    function filterUsers(query) {
       
        
        // Check if the query is empty
        if (query === '') {
            $('#userTable tbody tr').show(); // Show all rows if the search query is empty
            return; // Exit the function early
        }

        $('#userTable tbody tr').each(function () {
            const row = $(this);
            const firstName = row.find('td:nth-child(1)').text().toLowerCase(); // Get first name
            const lastName = row.find('td:nth-child(2)').text().toLowerCase(); // Get last name
            const email = row.find('td:nth-child(3)').text().toLowerCase(); // Get email
            const phone = row.find('td:nth-child(4)').text().toLowerCase(); // Get phone number

            // Exact match checks for the search query
            const isMatch =
                firstName === query || 
                lastName === query || 
                email === query || 
                phone === query;

            if (isMatch) {
                row.show(); // Show the row if it matches or if the user is logged in
            } else {
                row.hide(); // Hide the row if it doesn't match
            }
        });


    }
});
    </script>

        <script>
            $(document).ready(function() {
                $('.btn-login-logout').click(function() {
                    const userId = $(this).data('user-id');

                    // Send an AJAX request to log the user out
                    $.ajax({
                        url: `/logout/${userId}`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}' // Include CSRF token
                        },
                        success: function(response) {
                            // Update the UI with the new logout time and time logged
                            $(`.time-out[data-user-id="${userId}"]`).text(response.logout_time);
                            $(`.time-logged[data-user-id="${userId}"]`).text(response.time_logged);
                        },
                        error: function(xhr) {
                            console.error('Logout Error:', xhr);
                        }
                    });
                });

                // Refresh button functionality
                $('#refreshTimeLogged').click(function() {
                    // Loop through each user row to get their ID
                    $('#userTable tbody tr').each(function() {
                        const userId = $(this).find('.time-logged').data('user-id');

                        // Send an AJAX request to get updated time logged for each user
                        $.ajax({
                            url: `/get-time-logged/${userId}`,
                            type: 'GET',
                            success: function(response) {
                                if (response.logged_in) {
                                    // If the user is logged in, set Time Logged to N/A
                                    $(`.time-logged[data-user-id="${userId}"]`).text('N/A');
                                } else {
     
                                    $(`.time-logged[data-user-id="${userId}"]`).text(response.time_logged);
                                }
                            },
                            error: function(xhr) {
                                console.error('Time Logged Fetch Error:', xhr);
                            }
                        });
                    });
                });
            });
        </script>

    <script>
        $(document).ready(function() {
            // Store the total number of logged-in users
            const totalRows = $('#userTable tbody tr').length;
            // Event listener for the records per page dropdown
            $('#recordsPerPage').change(function() {
                const recordsPerPage = parseInt($(this).val());
                // Hide all rows first
                $('#userTable tbody tr').hide();
                // Show the selected number of rows
                $('#userTable tbody tr').slice(0, recordsPerPage).show();
            });
            // Trigger the change event on page load to show the initial records
            $('#recordsPerPage').change();
        });
    </script>

<script>
    $(document).ready(function() {
        // When the modal is about to be shown
        $('#assignHours').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget); // Button that triggered the modal
            const firstName = button.data('first-name'); // Extract info from data-* attributes
            const lastName = button.data('last-name');

            // Update the modal's title
            const modal = $(this);
            modal.find('.modal-title').text(`Assign Hours to: [${firstName} ${lastName}]`);
        });
   

    $('#deleteUserModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget); // Button that triggered the modal
        const firstName = button.data('first-name'); // Extract first name from data-* attribute
        const lastName = button.data('last-name');   // Extract last name from data-* attribute

        // Update the modal's title
        const modal = $(this);

        modal.find('.modal-title').html(`<span style="color: orange;">⚠️</span> Delete User? <span style="color: red;">[${firstName} ${lastName}]</span>`);
        modal.find('.modal-body').html(`Are you sure you want to remove user: <span style="color: red;">[${firstName} ${lastName}]?</span>`);
        });
    });

</script>

<script>
        $(document).ready(function() {
            function adjustLayout() {
                const container = $('.container');
                let width = window.innerWidth;
                
                // Adjust layout based on screen size
                if (width <= 576) {
                    // Extra small devices
                    container.removeClass('container').addClass('container-fluid');
                } else if (width <= 768) {
                    // Small devices
                    container.removeClass('container-fluid').addClass('container');
                } else {
                    // Medium and up devices
                    container.addClass('container');
                }
            }

            // Initial adjustment
            adjustLayout();

            // Resize event listener
            $(window).resize(function() {
                adjustLayout();
            });
        });
    </script>

</body>
</html>