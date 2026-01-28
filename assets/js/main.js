document.addEventListener('DOMContentLoaded', () => {
    // Ajax status change on ticket_view.php
    const statusSelect = document.querySelector('#ticket-status');
    const statusBadge = document.querySelector('#ticket-status-badge');
    const ticketIdEl = document.querySelector('#ticket-id');

    if (statusSelect && statusBadge && ticketIdEl) {
        statusSelect.addEventListener('change', () => {
            const ticketId = ticketIdEl.value;
            const status = statusSelect.value;

            fetch('ajax_update_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + encodeURIComponent(ticketId) +
                      '&status=' + encodeURIComponent(status)
            })
            .then(res => res.json())
            .then(data => {
                if (data.ok) {
                    statusBadge.textContent = data.status;
                    statusBadge.className = 'badge ' + data.badgeClass;
                } else {
                    alert('Failed to update status');
                }
            })
            .catch(() => alert('Error updating status'));
        });
    }
});
