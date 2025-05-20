<div>
<div class="min-h-screen flex bg-gray-50">
    <div class="w-full mx-auto">
        <div class="bg-white shadow-xl sm:rounded-2xl overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Dashboard</h2>
                    <p class="mt-1 text-sm text-gray-500">Overview of Institutions</p>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    {{-- Total Institutions --}}
                    <div class="bg-white rounded-lg shadow p-5 hover:shadow-md transition duration-300">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-sm text-gray-600">Total Institutions</h2>
                                <p class="text-2xl font-semibold text-gray-800">{{ $data['totalInstitutions'] }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Active Institutions --}}
                    <div class="bg-white rounded-lg shadow p-5 hover:shadow-md transition duration-300">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-sm text-gray-600">Active Institutions</h2>
                                <p class="text-2xl font-semibold text-gray-800">{{ $data['activeInstitutions'] }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Microfinance Institutions --}}
                    <div class="bg-white rounded-lg shadow p-5 hover:shadow-md transition duration-300">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-sm text-gray-600">Total Microfinances</h2>
                                <p class="text-2xl font-semibold text-gray-800">{{ $data['microfinanceInstitutions'] }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- SACCO Institutions --}}
                    <div class="bg-white rounded-lg shadow p-5 hover:shadow-md transition duration-300">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-sm text-gray-600">Total SACCOS</h2>
                                <p class="text-2xl font-semibold text-gray-800">{{ $data['saccoInstitutions'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-center text-gray-900 mb-4">Institution Type Distribution</h3>
                        <div id="institutionStatusChart" class="h-[300px]"></div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-center text-gray-900 mb-4">Active vs Inactive Institutions</h3>
                        <div id="activeInactiveChart" class="h-[300px]"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    window.addEventListener('load', () => {
        // Donut Chart
        const donutChart = new ApexCharts(document.querySelector("#institutionStatusChart"), {
            chart: { 
                type: 'donut', 
                height: '300px', 
                fontFamily: 'Inter, sans-serif', 
                toolbar: { show: false },
                parentHeightOffset: 0
            },
            series: [{{ $data['microfinanceInstitutions'] }}, {{ $data['saccoInstitutions'] }}],
            labels: ['Microfinances', 'SACCOS'],
            colors: ['#2563EB', '#DC2626'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            name: { show: true, fontSize: '20px' },
                            value: { show: true, fontSize: '24px' },
                            total: {
                                show: true,
                                label: 'Total',
                                fontSize: '16px',
                                fontWeight: 800,
                                color: '#6B7280',
                                formatter: () => '{{ $data['totalInstitutions'] }}'
                            }
                        }
                    }
                }
            },
            legend: { position: 'top', fontSize: '14px' },
            dataLabels: {
                enabled: true,
                formatter: (val) => val.toFixed(1) + "%",
                style: { fontSize: '12px', fontWeight: 600, colors: ['#fff'] }
            }
        });
        donutChart.render();

        // Bar Chart
        const barChart = new ApexCharts(document.querySelector("#activeInactiveChart"), {
            chart: {
                type: 'bar',
                height: '300px',
                stacked: true,
                stackType: '100%',
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false },
                parentHeightOffset: 0
            },
            series: [{
                name: 'Microfinances',
                data: [{{ $data['activeMicrofinanceInstitutions'] ?? 0 }}, {{ $data['inactiveMicrofinanceInstitutions'] ?? 0 }}]
            }, {
                name: 'SACCOS',
                data: [{{ $data['activeSaccoInstitutions'] ?? 0 }}, {{ $data['inactiveSaccoInstitutions'] ?? 0 }}]
            }],
            colors: ['#2563EB', '#DC2626'],
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 0,
                    dataLabels: { position: 'center' }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: (val) => val > 0 ? val.toFixed(1) + '%' : '',
                style: { fontSize: '13px', colors: ['#fff'], fontWeight: 600 }
            },
            xaxis: {
                categories: ['ACTIVE', 'INACTIVE'],
                labels: { style: { fontSize: '13px', fontWeight: 600, colors: '#6B7280' } }
            },
            legend: {
                position: 'top',
                fontSize: '14px',
                markers: { width: 12, height: 12, radius: 6 }
            },
            fill: { opacity: 1 },
            tooltip: {
                theme: 'light',
                y: { formatter: (val) => val.toFixed() }
            }
        });
        barChart.render();
    });
</script>

</div>
