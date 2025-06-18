/**
 * Custom Admin Panel Scripts
 */

document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('[role="alert"]:not([data-permanent])');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert) {
                alert.classList.add('animate-fade-out');
                setTimeout(() => alert.remove(), 300);
            }
        }, 5000);
    });

    // Initialize any datepickers
    const datepickers = document.querySelectorAll('input[type="date"], input[type="datetime-local"]');
    datepickers.forEach(picker => {
        picker.addEventListener('focus', (e) => {
            e.target.showPicker && e.target.showPicker();
        });
    });

    // Confirm actions that use data-confirm attribute
    const confirmActions = document.querySelectorAll('[data-confirm]');
    confirmActions.forEach(element => {
        element.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm') || 'Apakah Anda yakin?';
            if (!confirm(message)) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });

    // Image preview functionality for file inputs
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    imageInputs.forEach(input => {
        if (!input.hasAttribute('data-no-preview')) {
            input.addEventListener('change', function() {
                const file = this.files[0];
                const previewId = this.getAttribute('data-preview') || 'image-preview';
                const previewElement = document.getElementById(previewId);

                if (file && previewElement) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewElement.src = e.target.result;
                        previewElement.parentElement.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });

    // Toggle functionality for elements with data-toggle attribute
    const toggles = document.querySelectorAll('[data-toggle]');
    toggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const targetId = this.getAttribute('data-toggle');
            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                const isHidden = targetElement.classList.contains('hidden');

                if (isHidden) {
                    targetElement.classList.remove('hidden');
                    targetElement.classList.add('animate-fade-in');
                } else {
                    targetElement.classList.add('hidden');
                }
            }
        });
    });

    // Slug generation functionality
    const slugSources = document.querySelectorAll('[data-slug-source]');
    slugSources.forEach(source => {
        const targetId = source.getAttribute('data-slug-source');
        const target = document.getElementById(targetId);

        if (target) {
            source.addEventListener('keyup', function() {
                if (!target.value || target.getAttribute('data-slug-synced') === 'true') {
                    target.value = slugify(this.value);
                }
            });

            // If user manually edits the slug, stop auto-syncing
            target.addEventListener('input', function() {
                this.setAttribute('data-slug-synced', 'false');
            });
        }
    });

    // Initialize dashboard charts if they exist
    initDashboardCharts();

    // Helper function to generate slugs
    function slugify(text) {
        return text
            .toString()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .trim()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .substring(0, 60);
    }

    /**
     * Initialize charts on the admin dashboard
     */
    function initDashboardCharts() {
        const viewsChartCanvas = document.getElementById('viewsChart');

        if (viewsChartCanvas) {
            // Get chart data from the backend (assumed to be in a global variable)
            const chartData = window.dashboardData && window.dashboardData.viewsChart ?
                window.dashboardData.viewsChart : [];

            // Format data for Chart.js
            const labels = chartData.map(item => item.date);
            const data = chartData.map(item => item.views);

            // Create the chart
            new Chart(viewsChartCanvas, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Views',
                        data: data,
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                color: 'rgba(107, 114, 128, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    }
});
