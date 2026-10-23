window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables
    // https://github.com/fiduswriter/Simple-DataTables/wiki

    const datatablesSimple = document.getElementById('datatablesSimple');
    if (!datatablesSimple || typeof simpleDatatables === 'undefined') {
        return;
    }

    const skipSearch = ['logo', 'action', 'question', 'result', 'id'];
    const skipSort = ['logo', 'action', 'question', 'result'];
    const columns = [];

    datatablesSimple.querySelectorAll('thead th').forEach(function (th, i) {
        const label = (th.textContent || '').replace(/\s+/g, ' ').trim().toLowerCase();
        const col = { select: i };
        let used = false;

        if (th.getAttribute('data-searchable') === 'false' || skipSearch.indexOf(label) !== -1) {
            col.searchable = false;
            used = true;
        }
        if (th.getAttribute('data-sortable') === 'false' || skipSort.indexOf(label) !== -1) {
            col.sortable = false;
            used = true;
        }
        if (th.getAttribute('data-hidden') === 'true' || th.classList.contains('d-none')) {
            col.hidden = true;
            used = true;
        }
        if (th.getAttribute('data-type') === 'number' || label === 'id') {
            col.type = 'number';
            used = true;
        }
        const sort = th.getAttribute('data-sort');
        if (sort === 'asc' || sort === 'desc') {
            col.sort = sort;
            used = true;
        }

        if (used) {
            columns.push(col);
        }
    });

    new simpleDatatables.DataTable(datatablesSimple, {
        searchable: true,
        sortable: true,
        paging: true,
        perPage: 10,
        perPageSelect: [10, 25, 50, 100],
        columns: columns,
        labels: {
            placeholder: 'Search...',
            perPage: '{select} entries per page',
            noRows: 'No matching records',
            info: 'Showing {start} to {end} of {rows} entries',
        },
    });
});
