$(document).ready(function() {
    // Initialize DataTables
    if ($('.data-table').length) {
        $('.data-table').DataTable({
            responsive: true,
            pageLength: 10
        });
    }

    // Confirm delete actions
    $('.delete-btn').on('click', function(e) {
        if (!confirm('Are you sure you want to delete this item?')) {
            e.preventDefault();
        }
    });

    // Calculate total price for booking
    $('#start_date, #end_date').on('change', function() {
        const startDate = new Date($('#start_date').val());
        const endDate = new Date($('#end_date').val());
        const pricePerDay = parseFloat($('#price_per_day').val());

        if (startDate && endDate && pricePerDay && endDate > startDate) {
            const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
            const total = days * pricePerDay;
            $('#total_price').text('₹' + total.toFixed(2));
            $('#days').text(days);
        } else {
            $('#total_price').text('₹0.00');
            $('#days').text('-');
        }
    });
});
