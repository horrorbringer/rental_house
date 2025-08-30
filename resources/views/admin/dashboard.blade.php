<!-- Chosen Palette: Warm Neutrals with Subtle Accents -->
<!-- Application Structure Plan: The SPA is designed as a centralized dashboard. The core structure consists of a fixed sidebar for primary navigation and a main content area that dynamically updates based on user selection. The main content is split into four logical sections: an 'Overview' dashboard for high-level stats, a 'Buildings & Rooms' section for property management, a 'Tenants' section for tenant data, and an 'Invoices & Payments' section for financial tracking. This structure was chosen to provide a clear, single-page experience that avoids traditional page reloads and organizes complex data into manageable, task-oriented views, prioritizing user efficiency and clarity. -->
<!-- Visualization & Content Choices: Dashboard Stats -> Goal: Inform -> Viz/Presentation Method: Statistical Cards & Progress Indicators (HTML/CSS) -> Interaction: Static display. Justification: Provides a quick, scannable summary of key metrics. -> Library/Method: Vanilla JS to populate.
Building Status -> Goal: Compare -> Viz/Presentation Method: Horizontal Bar Chart (Chart.js) -> Interaction: Hover for tooltips. Justification: Effectively compares the rental status across different buildings at a glance. -> Library/Method: Chart.js (Canvas).
Invoice Status -> Goal: Inform/Compare -> Viz/Presentation Method: Pie Chart (Chart.js) -> Interaction: Hover for tooltips. Justification: Clearly visualizes the proportion of paid vs. unpaid invoices. -> Library/Method: Chart.js (Canvas).
Tenants List -> Goal: Organize/Search -> Viz/Presentation Method: Filterable/Searchable Table (HTML/CSS) -> Interaction: Text input for filtering. Justification: Allows users to quickly find specific tenant information. -> Library/Method: Vanilla JS for filtering logic.
Invoice Tracking -> Goal: Organize/Track -> Viz/Presentation Method: Interactive List/Table (HTML/CSS) -> Interaction: Button click to mark as paid, filter buttons. Justification: Provides a clear way to manage and track financial transactions. -> Library/Method: Vanilla JS to update the UI.
CONFIRMATION: NO SVG graphics used. NO Mermaid JS used. -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Management Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f3e9;
        }
        .chart-container {
            position: relative;
            width: 100%;
            max-width: 800px;
            height: 300px;
            max-height: 400px;
        }
        @media (min-width: 768px) {
            .chart-container {
                height: 350px;
            }
        }
    </style>
</head>
<body class="flex bg-gray-100 min-h-screen">

    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-white shadow-xl flex flex-col items-center py-8">
        <h1 class="text-3xl font-bold text-gray-800 tracking-tight mb-8">
            <span class="text-indigo-600">🏠</span> RM System
        </h1>
        <nav class="w-full px-4">
            <a href="#" data-section="overview" class="nav-link block text-lg font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 py-3 px-4 rounded-xl transition duration-150 ease-in-out mb-2 active:bg-indigo-100">
                <span class="mr-3">📋</span> Dashboard
            </a>
            <a href="#" data-section="buildings" class="nav-link block text-lg font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 py-3 px-4 rounded-xl transition duration-150 ease-in-out mb-2">
                <span class="mr-3">🏢</span> Buildings
            </a>
            <a href="#" data-section="tenants" class="nav-link block text-lg font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 py-3 px-4 rounded-xl transition duration-150 ease-in-out mb-2">
                <span class="mr-3">👤</span> Tenants
            </a>
            <a href="#" data-section="invoices" class="nav-link block text-lg font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 py-3 px-4 rounded-xl transition duration-150 ease-in-out mb-2">
                <span class="mr-3">🧾</span> Invoices
            </a>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 p-8 overflow-y-auto">
        <header class="flex items-center justify-between mb-8">
            <h2 id="page-title" class="text-4xl font-extrabold text-gray-900 tracking-tight">Dashboard Overview</h2>
        </header>

        <div id="content-area" class="space-y-12">
            <!-- Content will be injected here -->
        </div>

    </main>

    <div id="modal-container" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50">
        <div id="modal-content" class="relative bg-white p-8 rounded-xl shadow-lg w-full max-w-2xl mx-4 my-8">
        </div>
    </div>

<script>
    const data = {
        buildings: [
            { id: 1, name: 'Riverside Apartments', address: '123 Main St', rooms: [
                { id: 1, number: '101', status: 'rented' }, { id: 2, number: '102', status: 'rented' },
                { id: 3, number: '103', status: 'available' }, { id: 4, number: '104', status: 'rented' }
            ]},
            { id: 2, name: 'Sunrise Towers', address: '456 Oak Ave', rooms: [
                { id: 5, number: '201', status: 'rented' }, { id: 6, number: '202', status: 'available' },
                { id: 7, number: '203', status: 'rented' }
            ]},
            { id: 3, name: 'Garden Villas', address: '789 Pine Blvd', rooms: [
                { id: 8, number: 'A1', status: 'rented' }, { id: 9, number: 'A2', status: 'rented' },
                { id: 10, number: 'A3', status: 'rented' }, { id: 11, number: 'A4', status: 'available' },
                { id: 12, number: 'B1', status: 'rented' }, { id: 13, number: 'B2', status: 'available' }
            ]}
        ],
        tenants: [
            { id: 1, name: 'John Doe', phone: '123-456-7890', email: 'john.doe@example.com' },
            { id: 2, name: 'Jane Smith', phone: '987-654-3210', email: 'jane.smith@example.com' },
            { id: 3, name: 'Sok Mean', phone: '098-765-4321', email: 'sok.mean@example.com' },
            { id: 4, name: 'Lim Sopheak', phone: '012-345-6789', email: 'lim.sopheak@example.com' },
        ],
        invoices: [
            { id: 1, rentalId: 1, tenantName: 'John Doe', total: 550, status: 'unpaid', date: '2024-08-01' },
            { id: 2, rentalId: 2, tenantName: 'Jane Smith', total: 725, status: 'paid', date: '2024-08-01' },
            { id: 3, rentalId: 5, tenantName: 'Sok Mean', total: 600, status: 'unpaid', date: '2024-08-01' },
            { id: 4, rentalId: 8, tenantName: 'Lim Sopheak', total: 850, status: 'paid', date: '2024-08-01' }
        ]
    };

    let charts = {};

    document.addEventListener('DOMContentLoaded', () => {
        loadSection('overview');
        document.querySelector('.nav-link[data-section="overview"]').classList.add('bg-indigo-100', 'text-indigo-600');

        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                document.querySelectorAll('.nav-link').forEach(nav => {
                    nav.classList.remove('bg-indigo-100', 'text-indigo-600');
                });
                link.classList.add('bg-indigo-100', 'text-indigo-600');
                loadSection(e.target.dataset.section);
            });
        });
    });

    function loadSection(section) {
        const contentArea = document.getElementById('content-area');
        const pageTitle = document.getElementById('page-title');
        contentArea.innerHTML = '';
        Object.values(charts).forEach(chart => chart.destroy());
        charts = {};

        switch(section) {
            case 'overview':
                pageTitle.textContent = 'Dashboard Overview';
                renderOverview(contentArea);
                break;
            case 'buildings':
                pageTitle.textContent = 'Buildings & Rooms';
                renderBuildings(contentArea);
                break;
            case 'tenants':
                pageTitle.textContent = 'Tenants Management';
                renderTenants(contentArea);
                break;
            case 'invoices':
                pageTitle.textContent = 'Invoices & Payments';
                renderInvoices(contentArea);
                break;
        }
    }

    function renderOverview(container) {
        const totalBuildings = data.buildings.length;
        const totalRooms = data.buildings.reduce((sum, b) => sum + b.rooms.length, 0);
        const rentedRooms = data.buildings.reduce((sum, b) => sum + b.rooms.filter(r => r.status === 'rented').length, 0);
        const unpaidInvoices = data.invoices.filter(i => i.status === 'unpaid').length;

        container.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-md flex flex-col items-center justify-center">
                    <div class="text-4xl font-bold text-indigo-600">${totalBuildings}</div>
                    <p class="text-gray-500 font-medium mt-2">Total Buildings</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md flex flex-col items-center justify-center">
                    <div class="text-4xl font-bold text-indigo-600">${totalRooms}</div>
                    <p class="text-gray-500 font-medium mt-2">Total Rooms</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md flex flex-col items-center justify-center">
                    <div class="text-4xl font-bold text-indigo-600">${rentedRooms} / ${totalRooms}</div>
                    <p class="text-gray-500 font-medium mt-2">Rented Rooms</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md flex flex-col items-center justify-center">
                    <div class="text-4xl font-bold text-red-500">${unpaidInvoices}</div>
                    <p class="text-gray-500 font-medium mt-2">Unpaid Invoices</p>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-10">
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <h3 class="text-xl font-semibold mb-4">Rental Status by Building</h3>
                    <div class="chart-container mx-auto">
                        <canvas id="buildingStatusChart"></canvas>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <h3 class="text-xl font-semibold mb-4">Invoice Status</h3>
                    <div class="chart-container mx-auto">
                        <canvas id="invoiceStatusChart"></canvas>
                    </div>
                </div>
            </div>
        `;
        renderBuildingStatusChart();
        renderInvoiceStatusChart();
    }

    function renderBuildingStatusChart() {
        const buildingLabels = data.buildings.map(b => b.name);
        const rentedData = data.buildings.map(b => b.rooms.filter(r => r.status === 'rented').length);
        const availableData = data.buildings.map(b => b.rooms.filter(r => r.status === 'available').length);

        const ctx = document.getElementById('buildingStatusChart').getContext('2d');
        charts.buildingStatus = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: buildingLabels,
                datasets: [
                    {
                        label: 'Rented Rooms',
                        data: rentedData,
                        backgroundColor: '#4C51BF',
                        borderColor: '#4C51BF',
                        borderWidth: 1,
                        borderRadius: 8
                    },
                    {
                        label: 'Available Rooms',
                        data: availableData,
                        backgroundColor: '#A0AEC0',
                        borderColor: '#A0AEC0',
                        borderWidth: 1,
                        borderRadius: 8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true
                    }
                }
            }
        });
    }

    function renderInvoiceStatusChart() {
        const paidCount = data.invoices.filter(i => i.status === 'paid').length;
        const unpaidCount = data.invoices.filter(i => i.status === 'unpaid').length;

        const ctx = document.getElementById('invoiceStatusChart').getContext('2d');
        charts.invoiceStatus = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Paid', 'Unpaid'],
                datasets: [{
                    data: [paidCount, unpaidCount],
                    backgroundColor: ['#10B981', '#F56565'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    }

    function renderBuildings(container) {
        container.innerHTML = `
            <div class="space-y-8">
                ${data.buildings.map(building => `
                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <h3 class="text-xl font-bold text-gray-800">${building.name}</h3>
                        <p class="text-gray-500 text-sm mb-4">${building.address}</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            ${building.rooms.map(room => `
                                <div class="p-4 rounded-xl text-center font-semibold border-2 ${room.status === 'rented' ? 'bg-red-50 border-red-200' : 'bg-green-50 border-green-200'}">
                                    <span class="text-sm text-gray-500">Room</span>
                                    <p class="text-lg text-gray-800">${room.number}</p>
                                    <p class="text-xs font-medium uppercase mt-1 ${room.status === 'rented' ? 'text-red-600' : 'text-green-600'}">${room.status}</p>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }

    function renderTenants(container) {
        container.innerHTML = `
            <div class="bg-white p-6 rounded-xl shadow-md">
                <div class="flex flex-col sm:flex-row items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 sm:mb-0">Tenant List</h3>
                    <div class="flex items-center space-x-4 w-full sm:w-auto">
                        <input type="text" id="tenant-search" placeholder="Search tenants..." class="w-full sm:w-auto flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <button id="add-tenant-btn" class="bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition duration-150 ease-in-out">
                            + Add Tenant
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tenants-table-body" class="bg-white divide-y divide-gray-200">
                        </tbody>
                    </table>
                </div>
            </div>
        `;
        renderTenantTable();

        document.getElementById('tenant-search').addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const filteredTenants = data.tenants.filter(tenant =>
                tenant.name.toLowerCase().includes(searchTerm) ||
                tenant.phone.toLowerCase().includes(searchTerm)
            );
            renderTenantTable(filteredTenants);
        });

        document.getElementById('add-tenant-btn').addEventListener('click', () => {
            showModal('addTenant');
        });
    }

    function renderTenantTable(tenantsToRender = data.tenants) {
        const tableBody = document.getElementById('tenants-table-body');
        tableBody.innerHTML = tenantsToRender.map(tenant => `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${tenant.name}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${tenant.phone}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${tenant.email || 'N/A'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button class="text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out">View</button>
                </td>
            </tr>
        `).join('');
    }

    function renderInvoices(container) {
        container.innerHTML = `
            <div class="bg-white p-6 rounded-xl shadow-md">
                <div class="flex flex-col sm:flex-row items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 sm:mb-0">Invoices</h3>
                    <div class="flex items-center space-x-4">
                        <button id="filter-all" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-xl font-medium hover:bg-gray-300 transition duration-150 ease-in-out">All</button>
                        <button id="filter-unpaid" class="bg-red-500 text-white px-4 py-2 rounded-xl font-medium hover:bg-red-600 transition duration-150 ease-in-out">Unpaid</button>
                        <button id="filter-paid" class="bg-green-500 text-white px-4 py-2 rounded-xl font-medium hover:bg-green-600 transition duration-150 ease-in-out">Paid</button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="invoices-table-body" class="bg-white divide-y divide-gray-200">
                        </tbody>
                    </table>
                </div>
            </div>
        `;
        renderInvoiceTable();

        document.getElementById('filter-all').addEventListener('click', () => renderInvoiceTable(data.invoices));
        document.getElementById('filter-unpaid').addEventListener('click', () => renderInvoiceTable(data.invoices.filter(i => i.status === 'unpaid')));
        document.getElementById('filter-paid').addEventListener('click', () => renderInvoiceTable(data.invoices.filter(i => i.status === 'paid')));
    }

    function renderInvoiceTable(invoicesToRender = data.invoices) {
        const tableBody = document.getElementById('invoices-table-body');
        tableBody.innerHTML = invoicesToRender.map(invoice => `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#${invoice.id}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${invoice.tenantName}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$${invoice.total.toFixed(2)}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${invoice.status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                        ${invoice.status}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                    ${invoice.status === 'unpaid' ? `<button onclick="markAsPaid(${invoice.id})" class="text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out">Mark as Paid</button>` : ''}
                </td>
            </tr>
        `).join('');
    }

    function markAsPaid(invoiceId) {
        const invoice = data.invoices.find(i => i.id === invoiceId);
        if (invoice) {
            invoice.status = 'paid';
            loadSection('invoices');
        }
    }

    function showModal(type) {
        const modalContainer = document.getElementById('modal-container');
        const modalContent = document.getElementById('modal-content');
        modalContent.innerHTML = '';

        switch(type) {
            case 'addTenant':
                modalContent.innerHTML = `
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800">Add New Tenant</h3>
                        <button onclick="hideModal()" class="text-gray-500 hover:text-gray-700 transition-colors">&times;</button>
                    </div>
                    <form id="add-tenant-form" class="space-y-6">
                        <div>
                            <label for="new-tenant-name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" id="new-tenant-name" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="new-tenant-phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" id="new-tenant-phone" name="phone" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="new-tenant-email" class="block text-sm font-medium text-gray-700">Email (Optional)</label>
                            <input type="email" id="new-tenant-email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Identity Card Images</p>
                            <div class="mt-1 space-y-2">
                                <p class="text-xs text-gray-500 italic">This is for demonstration purposes. In a real app, you would handle file uploads to your backend.</p>
                                <div class="w-full h-24 bg-gray-100 rounded-md flex items-center justify-center border-2 border-dashed border-gray-300">
                                    <span class="text-gray-400">ID Card Front Placeholder</span>
                                </div>
                                <div class="w-full h-24 bg-gray-100 rounded-md flex items-center justify-center border-2 border-dashed border-gray-300">
                                    <span class="text-gray-400">ID Card Back Placeholder</span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-indigo-600 text-white py-3 px-4 rounded-xl hover:bg-indigo-700 transition duration-150 ease-in-out">
                            Save Tenant
                        </button>
                    </form>
                `;
                document.getElementById('add-tenant-form').addEventListener('submit', (e) => {
                    e.preventDefault();
                    const newTenant = {
                        id: data.tenants.length + 1,
                        name: document.getElementById('new-tenant-name').value,
                        phone: document.getElementById('new-tenant-phone').value,
                        email: document.getElementById('new-tenant-email').value,
                    };
                    data.tenants.push(newTenant);
                    hideModal();
                    loadSection('tenants');
                });
                break;
        }
        modalContainer.classList.remove('hidden');
    }

    function hideModal() {
        document.getElementById('modal-container').classList.add('hidden');
    }
</script>

</body>
</html>
