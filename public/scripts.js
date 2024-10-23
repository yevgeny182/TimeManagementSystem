$(document).on('click', '.btn-login-logout', function() {
    let button = $(this);
    let userId = button.data('user-id');
    let action = button.text().toLowerCase();

    // Ensure proper route for login/logout
    let url = action === 'login' ? loginRoute : logoutRoute; // Use variables for URL
    console.log('AJAX URL:', action === 'login' ? loginRoute : logoutRoute);

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            _token: csrfToken, // Use variables for CSRF token
            user_id: userId
        },
        success: function(response) {
            if (response.status === 'success') {
                if (action === 'login') {
                    button.text('Logout').removeClass('btn-success').addClass('btn-danger');
                    button.closest('tr').find('.time-in').text(response.login_time); // Update time-in directly

                    let loggedInCount = parseInt($('#userCount').text().split(' / ')[0]);
                    $('#userCount').text((loggedInCount + 1) + ' / ' + totalUsers);

                } else {
                    button.text('Login').removeClass('btn-danger').addClass('btn-success');
                    button.closest('tr').find('.time-out').text(response.logout_time); // Update time-out directly
                    let loggedInCount = parseInt($('#userCount').text().split(' / ')[0]);
                    $('#userCount').text((loggedInCount - 1) + ' / ' + totalUsers);
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Error Status:', status); // Log the status
            console.error('Error Response:', xhr.responseText); 
        }
    });
});
