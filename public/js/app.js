document.addEventListener('DOMContentLoaded', function() {
    // Live Clock
    const clockElement = document.getElementById('liveClock');
    if (clockElement) {
        setInterval(() => {
            const now = new Date();
            const dateOpts = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
            const timeOpts = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
            clockElement.innerHTML = now.toLocaleDateString('en-US', dateOpts) + ' | ' + now.toLocaleTimeString('en-US', timeOpts);
        }, 1000);
    }

    // Sidebar Toggle
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const sidebar = document.getElementById('sidebar');
    if (sidebarCollapse && sidebar) {
        // Initially show on desktop, hide on mobile
        if(window.innerWidth > 768) {
            sidebar.classList.remove('active');
        }

        sidebarCollapse.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }

    // Table Row Filtering (Search)
    const searchInput = document.getElementById('tableSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();
            const table = document.querySelector('.table-glass');
            if(table) {
                const trs = table.getElementsByTagName('tr');
                for (let i = 1; i < trs.length; i++) { // Skip header
                    const tds = trs[i].getElementsByTagName('td');
                    let show = false;
                    for(let j=0; j<tds.length; j++) {
                        if (tds[j].textContent.toLowerCase().indexOf(filter) > -1) {
                            show = true;
                            break;
                        }
                    }
                    trs[i].style.display = show ? '' : 'none';
                }
            }
        });
    }

    // Confirmation Dialogs
    const confirmLinks = document.querySelectorAll('.confirm-action');
    confirmLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm(this.dataset.confirmMsg || 'Are you sure you want to perform this action?')) {
                e.preventDefault();
            }
        });
    });
    
    const confirmForms = document.querySelectorAll('.confirm-submit');
    confirmForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm(this.dataset.confirmMsg || 'Are you sure you want to perform this action?')) {
                e.preventDefault();
            }
        });
    });

    // Auto-dismiss Flash Messages
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
