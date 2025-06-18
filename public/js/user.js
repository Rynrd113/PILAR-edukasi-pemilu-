/**
 * Edukasi Pemilu - Public User Frontend JavaScript
 *
 * This file contains the core JavaScript functionality for the public
 * user frontend of the Edukasi Pemilu application, implementing AJAX
 * data fetching and UI interactivity.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize sidebar video list
    loadSidebarVideoList();

    // Initialize any custom UI components that aren't handled by Alpine.js
    setupUIComponents();
});

// Register Alpine.js components
document.addEventListener('alpine:init', () => {
    // Materi Populer component
    Alpine.data('materiPopuler', () => ({
        materiPopulerList: [],
        loading: true,

        loadMateriPopuler() {
            this.loading = true;
            fetch('/api/materi?sort=views&limit=3')
                .then(res => res.json())
                .then(data => {
                    this.materiPopulerList = data.data;
                    this.loading = false;
                })
                .catch(err => {
                    console.error('Error loading materi populer:', err);
                    this.loading = false;
                    this.materiPopulerList = [];
                });
        },

        formatDate(dateString) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        }
    }));
});

/**
 * Load the latest 5 materi for the sidebar video list
 */
function loadSidebarVideoList() {
    const videoListElement = document.getElementById('video-list');
    if (!videoListElement) return;

    fetch('/api/materi?limit=5')
        .then(response => response.json())
        .then(data => {
            let html = '';
            if (data.data && data.data.length > 0) {
                data.data.forEach(materi => {
                    html += `
                        <a href="/materi/${materi.slug}" class="block group py-3 transition-colors">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded overflow-hidden">
                                    ${materi.gambar_url
                                        ? `<img src="${materi.gambar_url}" alt="${materi.judul}" class="w-full h-full object-cover">`
                                        : `<div class="w-full h-full flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                            </svg>
                                          </div>`
                                    }
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white truncate group-hover:text-primary-600 dark:group-hover:text-primary-400">
                                        ${materi.judul}
                                    </h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        ${materi.kategori.nama}
                                    </p>
                                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                        ${materi.views} views
                                    </div>
                                </div>
                            </div>
                        </a>
                    `;
                });
            } else {
                html = `<div class="py-3 text-center text-gray-500 dark:text-gray-400">Belum ada materi</div>`;
            }
            videoListElement.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading video list:', error);
            videoListElement.innerHTML = `
                <div class="py-3 text-center text-red-500">
                    <p>Gagal memuat data.</p>
                </div>
            `;
        });
}

/**
 * Set up custom UI components and interactions
 */
function setupUIComponents() {
    // Handle dark mode initialization based on localStorage or user preference
    initializeDarkMode();

    // Setup any other custom UI components that aren't handled by Alpine.js
    // ...
}

/**
 * Initialize dark mode based on saved preference or system preference
 */
function initializeDarkMode() {
    // This is handled by Alpine.js in our layout, but we can add additional functionality here
    // For example, respecting the system preference when there's no saved preference
    if (!localStorage.getItem('darkMode')) {
        const prefersDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        localStorage.setItem('darkMode', prefersDarkMode);

        // Add a listener for changes to the prefers-color-scheme media query
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            if (!localStorage.getItem('darkModeLocked')) {
                localStorage.setItem('darkMode', e.matches);
                window.dispatchEvent(new CustomEvent('dark-mode-changed'));
            }
        });
    }
}

/**
 * Format a date string to localized format
 *
 * @param {string} dateString - ISO date string
 * @returns {string} - Formatted date string
 */
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
}
