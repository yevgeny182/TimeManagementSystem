<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TMS</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%; 
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        table {
            width: 80%; /* Changed width for better display */
            border-collapse: collapse;
            font-size: 18px;
            text-align: left;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <h2>Time Management System</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
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

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Actions</th>
                <th>Time In</th>
                <th>Time Out</th>
            </tr>
        </thead>
        <tbody>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->first_name }}</td>
            <td>{{ $user->last_name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone_number }}</td>
            <td>
                @if(session('user_' . $user->id))
                    <button class="btn btn-danger btn-login-logout" data-user-id="{{ $user->id }}">Logout</button>
                @else
                    <button class="btn btn-success btn-login-logout" data-user-id="{{ $user->id }}">Login</button>
                @endif
            </td>
            <td class="time-in">{{ $user->login_time == NULL ? "Not In" : "$user->login_time" }}</td> 
            <td class="time-out">{{ $user->logout_time == NULL ? "Not Out" : "$user->logout_time" }}</td>
            <td>  <button class="btn btn-danger btn-delete-user" data-user-id="{{ $user->id }}">Delete User</button> </td>
        </tr>
    @endforeach
</tbody>
    </table>

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
                            <input type="file" class="form-control" id="file" name="file" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
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
                        } else {
                            button.text('Login').removeClass('btn-danger').addClass('btn-success');
                            button.closest('tr').find('.time-out').text(response.logout_time); // Update time-out directly
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
<script>
    $(document).on('click', '.btn-delete-user', function() {
        let button = $(this);
        let userId = button.data('user-id');

        if (confirm('Are you sure you want to delete this user?')) {
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
                        alert('User deleted successfully.'); // Optional: Show success message
                    } else {
                        alert('Error deleting user: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error Status:', status);
                    console.error('Error Response:', xhr.responseText);
                }
            });
        }
    });
</script>


</body>
</html>