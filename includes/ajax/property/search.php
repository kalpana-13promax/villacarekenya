<!-- Include necessary JS files -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    var table = $('#propertyTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "server_side.php", // The server-side processing script
            "type": "POST",
            "data": function(d) {
                // Append form data to the AJAX request
                d.unit_no = $('input[name="unit_no"]').val();
                d.min = $('input[name="min"]').val();
                d.max = $('input[name="max"]').val();
                d.owner_name = $('input[name="owner_name"]').val();
                d.father = $('input[name="father"]').val();
                d.phone_no = $('input[name="phone_no"]').val();
                d.min_size = $('input[name="min_size"]').val();
                d.max_size = $('input[name="max_size"]').val();
                d.measurement_unit = $('select[name="measurement_unit"]').val();
                d.available_for = $('select[name="available_for[]"]').val();
                d.property_type = $('select[name="property_type[]"]').val();
                d.category = $('select[name="category[]"]').val();
                d.furnished_status = $('select[name="furnished_status[]"]').val();
                d.status = $('select[name="status[]"]').val();
                d.project = $('select[name="project[]"]').val();
                d.city = $('select[name="city[]"]').val();
                d.property_location = $('select[name="property_location[]"]').val();
                d.sub_location = $('select[name="sub_location[]"]').val();
                d.quota = $('select[name="quota"]').val();
                d.priority = $('select[name="priority"]').val();
                d.order_by = $('select[name="order_by"]').val();
                d.sequence = $('select[name="sequence"]').val();
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "unit_no" },
            { "data": "property_title" },
            { "data": "owner_name" },
            { "data": "location" },
            { "data": "property_type" },
            { "data": "available_for" },
            { "data": "size" },
            { "data": "property_price" },
            { "data": "status" },
            { "data": "action", "orderable": false, "searchable": false }
        ],
        "order": [[1, "asc"]]
    });

    // On form submit, reload DataTable
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        table.draw();
    });
});
</script>
