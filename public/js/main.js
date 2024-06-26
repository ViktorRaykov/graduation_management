$(document).ready(function() {
    $('#studentsTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/Bulgarian.json"
        },
        "columnDefs": [
            { "orderable": true, "targets": [2, 3] }
        ]
    });
});
