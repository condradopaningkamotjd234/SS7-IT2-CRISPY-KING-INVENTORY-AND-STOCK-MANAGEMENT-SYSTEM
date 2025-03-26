document.addEventListener('DOMContentLoaded', function() {
    // Example sales data
    const salesData = {
        today: 150,
        weekly: 1050,
        monthly: 4500,
        yearly: 54000
    };

    // Update sales summary
    const totalSales = salesData.today + salesData.weekly + salesData.monthly + salesData.yearly;
    document.getElementById('total-sales').textContent = `$${totalSales}`;

    // Update sales record
    document.getElementById('sales-today').textContent = `$${salesData.today}`;
    document.getElementById('sales-weekly').textContent = `$${salesData.weekly}`;
    document.getElementById('sales-monthly').textContent = `$${salesData.monthly}`;
    document.getElementById('sales-yearly').textContent = `$${salesData.yearly}`;

    const editButtons = document.querySelectorAll('.edit-button');

    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const cells = row.querySelectorAll('td:not(:last-child)'); // Exclude the last cell (Actions)

            cells.forEach(cell => {
                const input = document.createElement('input');
                input.type = 'text';
                input.value = cell.textContent;
                cell.textContent = '';
                cell.appendChild(input);
            });

            this.textContent = 'Save';
            this.classList.remove('edit-button');
            this.classList.add('save-button');
        });
    });

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('save-button')) {
            const row = event.target.closest('tr');
            const inputs = row.querySelectorAll('input');

            inputs.forEach(input => {
                const cell = input.parentElement;
                cell.textContent = input.value;
            });

            event.target.textContent = 'Edit';
            event.target.classList.remove('save-button');
            event.target.classList.add('edit-button');
        }
    });
});
