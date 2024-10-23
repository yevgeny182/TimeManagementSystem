$(document).ready(function() {
    // Attach the click event to the button for saving hours
    $('#saveHours').click(function() {
        // Get the user ID from the button that triggered the modal
        const userId = $('#assignHours').data('user-id'); // Updated to fetch from the modal
        const hours = $('#customHRS').val();

        // Validate the hours input
        if (!hours || isNaN(hours)) {
            alert('Please enter a valid number of hours.');
            return;
        }

        console.log('Sending data:', { user_id: userId, hours: hours }); // Debugging log

        // Make the AJAX request to assign hours
        $.ajax({
            url: `/assign-hours`, // Ensure this matches the route in web.php
            type: 'POST',
            data: {
                user_id: userId, // Ensure user_id is correctly set
                hours: hours,
                _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
            },
            success: function(response) {
                $('#assignHours').modal('hide'); // Hide the modal
                alert('Hours assigned successfully!'); // Notify success
                // Optionally update the UI to reflect changes
            },
            error: function(xhr) {
                console.error('Error:', xhr); // Log the error for debugging
                alert('Failed to assign hours. Please try again.'); // Notify error
            }
        });
    });

    // Update user_id when the modal is shown
    $('#assignHours').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget); // Button that triggered the modal
        const userId = button.data('user-id'); // Extract user ID from data attribute
        $(this).data('user-id', userId); // Set user ID in the modal
    });
});
