<div class="overflow-x-hidden">
    <div class="">
        <div class="w-full mx-auto">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Dashboard</h2>
                @php use Illuminate\Support\Facades\DB; @endphp
                <div>

                    <!-- Institution Details Section -->
                    <div class="w-full">
                        <div class="flex items-center justify-center p-2 h-full">
                            <div aria-label="card" class="p-6 rounded-3xl bg-white w-full h-full">
                                <div aria-label="header" class="flex items-center space-x-2">
                                    <svg data-slot="icon" class="w-8 h-8 shrink-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#43b02a" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"></path>
                                    </svg>
                                    <div class="space-y-0.5 flex-1 cursor-pointer">
                                        <h3 class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                            Institution Details
                                        </h3>
                                        <p class="text-sm font-medium text-gray-900 leading-none">
                                            Overview of institution performance and key metrics
                                        </p>
                                    </div>
                                </div>

                                <div class="w-full flex gap-4 mt-8">
                                    <!-- Total Institutions -->
                                    <div class="w-1/4 bg-gray-100 p-1 rounded-3xl">
                                        <div class="flex items-center justify-center h-full">
                                            <div aria-label="card" class="p-6 rounded-3xl bg-white w-full h-full">
                                                <div aria-label="header" class="flex items-center space-x-2">
                                                    <svg data-slot="icon" class="w-8 h-8 shrink-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#43b02a" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"></path>
                                                    </svg>
                                                    <div class="space-y-0.5 flex-1 cursor-pointer">
                                                        <h3 class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                                            Total Institutions
                                                        </h3>
                                                        <p class="text-sm font-medium text-gray-900 leading-none">
                                                            Total number of registered institutions
                                                        </p>
                                                    </div>
                                                </div>
                                                <div aria-label="content" class="mt-9 grid gap-2.5">
                                                    <p class="mb-2 text-3xl font-extrabold">{{ $totalInstitutions }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Active Institutions -->
                                    <div class="w-1/4 bg-gray-100 p-1 rounded-3xl">
                                        <div class="flex items-center justify-center h-full">
                                            <div aria-label="card" class="p-6 rounded-3xl bg-white w-full h-full">
                                                <div aria-label="header" class="flex items-center space-x-2">
                                                    <svg data-slot="icon" class="w-8 h-8 shrink-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#43b02a" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"></path>
                                                    </svg>
                                                    <div class="space-y-0.5 flex-1 cursor-pointer">
                                                        <h3 class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                                            Active Institutions
                                                        </h3>
                                                        <p class="text-sm font-medium text-gray-900 leading-none">
                                                            Number of active institutions
                                                        </p>
                                                    </div>
                                                </div>
                                                <div aria-label="content" class="mt-9 grid gap-2.5">
                                                    <p class="mb-2 text-3xl font-extrabold">{{ $activeInstitutions }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Microfinance Institutions -->
                                    <div class="w-1/4 bg-gray-100 p-1 rounded-3xl">
                                        <div class="flex items-center justify-center h-full">
                                            <div aria-label="card" class="p-6 rounded-3xl bg-white w-full h-full">
                                                <div aria-label="header" class="flex items-center space-x-2">
                                                    <svg data-slot="icon" class="w-8 h-8 shrink-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#43b02a" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 20.25l6.75-6.75H4.5a2.25 2.25 0 0 1-2.25-2.25V6.75A2.25 2.25 0 0 1 4.5 4.5h15.75A2.25 2.25 0 0 1 22.5 6.75v8.25a2.25 2.25 0 0 1-2.25 2.25H9z"></path>
                                                    </svg>
                                                    <div class="space-y-0.5 flex-1 cursor-pointer">
                                                        <h3 class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                                            Microfinance Institutions
                                                        </h3>
                                                        <p class="text-sm font-medium text-gray-900 leading-none">
                                                            Total microfinance institutions
                                                        </p>
                                                    </div>
                                                </div>
                                                <div aria-label="content" class="mt-9 grid gap-2.5">
                                                    <p class="mb-2 text-3xl font-extrabold">{{ $microfinanceInstitutions }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SACCO Institutions -->
                                    <div class="w-1/4 bg-gray-100 p-1 rounded-3xl">
                                        <div class="flex items-center justify-center h-full">
                                            <div aria-label="card" class="p-6 rounded-3xl bg-white w-full h-full">
                                                <div aria-label="header" class="flex items-center space-x-2">
                                                    <svg data-slot="icon" class="w-8 h-8 shrink-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#43b02a" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"></path>
                                                    </svg>
                                                    <div class="space-y-0.5 flex-1 cursor-pointer">
                                                        <h3 class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                                            SACCO Institutions
                                                        </h3>
                                                        <p class="text-sm font-medium text-gray-900 leading-none">
                                                            Total SACCO institutions
                                                        </p>
                                                    </div>
                                                </div>
                                                <div aria-label="content" class="mt-9 grid gap-2.5">
                                                    <p class="mb-2 text-3xl font-extrabold">{{ $saccoInstitutions }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="w-full mt-8">
                        <div class="flex items-center justify-center p-2 h-full">
                            <div aria-label="card" class="p-6 rounded-3xl bg-white w-full h-full">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <!-- Institution Type Distribution -->
                                    <div class="bg-gray-100 p-4 rounded-3xl">
                                        <h3 class="text-lg font-medium mb-4">Institution Type Distribution</h3>
                                        <div id="institutionStatusChart"></div>
                                    </div>

                                    <!-- Active vs Inactive Comparison -->
                                    <div class="bg-gray-100 p-4 rounded-3xl">
                                        <h3 class="text-lg font-medium mb-4">Active vs Inactive Institutions</h3>
                                        <div id="activeInactiveChart"></div>
                                    </div>

                                    <!-- Detailed Status Breakdown -->
                                    <div class="bg-gray-100 p-4 rounded-3xl md:col-span-2">
                                        <h3 class="text-lg font-medium mb-4">Detailed Status Breakdown</h3>
                                        <div id="statusBreakdownChart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- dashboard 0 -->
                    <div class="w-full">

                        <div class="flex items-center justify-center p-2 h-full ">
                            <div aria-label="card" class="p-6 rounded-3xl bg-white w-full h-full  ">





                                <!-- bottom 4 cards -->
                                <div class="w-full flex gap-2">
                                    <div class="w-1/4 bg-gray-100 p-1 rounded-3xl">

                                        <div class="flex items-center justify-center h-full ">
                                            <div aria-label="card" class="p-6 rounded-3xl bg-white w-full h-full  ">

                                                <div aria-label="header" class="flex items-center space-x-2">

                                                    <svg data-slot="icon" class="w-8 h-8 shrink-0" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="#43b02a" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125">
                                                        </path>
                                                    </svg>


                                                    <div x="loadView('b')" class="space-y-0.5 flex-1 cursor-pointer">
                                                        <h3
                                                            class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                                            Loan disbursed to date
                                                        </h3>
                                                        <p class="text-sm font-medium text-gray-900 leading-none">
                                                            All loans granted to customers since inception
                                                        </p>
                                                    </div>




                                                </div>


                                                <div aria-label="content" class="mt-9 grid gap-2.5">

                                                    <p class="mb-2 text-3xl font-extrabold">
                                                        237,415
                                                    </p>

                                                </div>

                                            </div>


                                        </div>

                                    </div>

                                    <div class="w-1/4 bg-gray-100 p-1 rounded-3xl">
                                        <div class="flex items-center justify-center h-full ">
                                            <div aria-label="card" class="p-6 rounded-3xl bg-white w-full h-full ">
                                                <div aria-label="header" class="flex items-center space-x-2">
                                                    <svg data-slot="icon" class="w-8 h-8 shrink-0" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="#43b02a" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z">
                                                        </path>
                                                    </svg>


                                                    <div class="space-y-0.5 flex-1 cursor-pointer">
                                                        <h3
                                                            class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                                            Amount disbursed to date
                                                        </h3>
                                                        <p class="text-sm font-medium text-gray-900 leading-none">
                                                            Cumulative amount disbursed to borrowers (TZS)

                                                        </p>
                                                    </div>




                                                </div>



                                                <div aria-label="content" class="mt-9 grid gap-2.5">

                                                    <p class="mb-2 text-3xl font-extrabold">
                                                        7,419,112,333.00
                                                    </p>

                                                </div>


                                            </div>
                                        </div>
                                    </div>

                                    <div class="w-1/4 bg-gray-100 p-1 rounded-3xl">
                                        <div class="flex items-center justify-center h-full ">
                                            <div aria-label="card" class="p-6 rounded-3xl bg-white w-full h-full ">
                                                <div aria-label="header" class="flex items-center space-x-2">
                                                    <svg data-slot="icon" class="w-8 h-8 shrink-0" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="#43b02a" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z">
                                                        </path>
                                                    </svg>
                                                    <div class="space-y-0.5 flex-1 cursor-pointer">
                                                        <h3
                                                            class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                                            Amount repaid to date
                                                        </h3>
                                                        <p class="text-sm font-medium text-gray-900 leading-none">
                                                            Total amount successfully collected from borrowers
                                                        </p>
                                                    </div>
                                                </div>

                                                <div aria-label="content" class="mt-9 grid gap-2.5">

                                                    <p class="mb-2 text-3xl font-extrabold">
                                                        4,513,414,791.00
                                                    </p>

                                                </div>




                                            </div>
                                        </div>
                                    </div>

                                    <div class="w-1/4 bg-gray-100 p-1 rounded-3xl">
                                        <div class="flex items-center justify-center h-full ">
                                            <div aria-label="card" class="p-6 rounded-3xl bg-white w-full h-full ">
                                                <div aria-label="header" class="flex items-center space-x-2">
                                                    <svg data-slot="icon" class="w-8 h-8 shrink-0" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="#43b02a" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z">
                                                        </path>
                                                    </svg>
                                                    <div class="space-y-0.5 flex-1 cursor-pointer">
                                                        <h3
                                                            class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                                            Loans closed to date
                                                        </h3>
                                                        <p class="text-sm font-medium text-gray-900 leading-none">
                                                            Loans have been completely paid off by customers
                                                        </p>
                                                    </div>
                                                </div>

                                                <div aria-label="content" class="mt-9 grid gap-2.5">

                                                    <p class="mb-2 text-3xl font-extrabold">
                                                        148,392
                                                    </p>

                                                </div>




                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>

                        </div>
                    </div>

                    <!-- //dashboard 1 -->

                    <div class="w-full">

                        <div class="flex items-center justify-center p-2 h-full ">
                            <div aria-label="card" class="p-6 rounded-3xl bg-white w-full h-full  ">

                                <div aria-label="header" class="flex items-center space-x-2">

                                    <svg data-slot="icon" class="w-8 h-8 shrink-0" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="#43b02a" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5m.75-9 3-3 2.148 2.148A12.061 12.061 0 0 1 16.5 7.605">
                                        </path>
                                    </svg>


                                    <div x="loadView('b')" class="space-y-0.5 flex-1 cursor-pointer">
                                        <h3 class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                            Loan Disbursement & Collection Report
                                        </h3>
                                        <p class="text-sm font-medium text-gray-900 leading-none">
                                            Loan Issuance versus collection performance over time
                                        </p>
                                    </div>




                                </div>


                                <div aria-label="content" class="mt-9 w-full gap-0">
                                    <div class="w-full flex items-center">
                                        <div class="w-1/3 flex items-center justify-center">
                                            <div class="pl-2 pr-2">
                                                <div class="item">
                                                    <dt class="mb-2 text-4xl text-center font-extrabold ">
                                                        5,749,852,352.00
                                                    </dt>
                                                    <dd class="text-gray-500 dark:text-gray-400">
                                                        <h3
                                                            class="font-medium text-lg text-center tracking-tight  leading-tight">
                                                            Total Disbursement(TZS)
                                                        </h3>
                                                    </dd>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-1/3">

                                            <div id="dash1radial-bar-chart" class="overflow-hidden"
                                                style="height: 350px;"></div>

                                            <div class="w-full flex items-center justify-center">
                                                <hr class=" mx-auto bg-green-700 w-1/3" style="height: 3px;">
                                            </div>

                                        </div>
                                        <div class="w-1/3 flex items-center justify-center">
                                            <div class="pl-2 pr-2">
                                                <div class="item">
                                                    <dt class="mb-2 text-4xl text-center  font-extrabold">
                                                        2,585,974,215.00
                                                    </dt>
                                                    <dd class="text-gray-500 dark:text-gray-400">
                                                        <h3
                                                            class="font-medium text-lg text-center tracking-tightleading-tight">
                                                            Total Collection(TZS)
                                                        </h3>
                                                    </dd>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="h-12"></div>
                                    <div class="w-full flex">
                                        <div class="w-1/2">

                                            <div class="bg-white rounded-xl p-2">
                                                <div class="w-full">
                                                    <div class="flex items-center justify-center text-center w-full">
                                                        <div class="flex space-x-2">
                                                            <div class="space-y-0.5 flex-1 cursor-pointer">
                                                                <div class="flex flex-col items-center justify-center">
                                                                    <div class="bottom-section">
                                                                        <span
                                                                            class="title font-medium text-lg tracking-tight text-gray-900 leading-tight"
                                                                            style="color: #43b02a;">
                                                                            TOTAL DISBURSEMENT & TOTAL COLLECTION(TZS)
                                                                        </span>
                                                                        <div class="grid grid-cols-2 divide-x-2 mt-4">
                                                                            <div class="pl-2 pr-2">
                                                                                <div class="item">
                                                                                    <dt
                                                                                        class="mb-2 text-xl font-extrabold">
                                                                                        5,749,852,352.00</dt>
                                                                                    <dd
                                                                                        class="text-gray-500 dark:text-gray-400">
                                                                                        <h3
                                                                                            class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                                                                            Disbursement
                                                                                        </h3>
                                                                                    </dd>
                                                                                </div>
                                                                            </div>
                                                                            <div class="pl-2 pr-2">
                                                                                <div class="item">
                                                                                    <dt
                                                                                        class="mb-2 text-xl font-extrabold">
                                                                                        2,585,974,215.00</dt>
                                                                                    <dd
                                                                                        class="text-gray-500 dark:text-gray-400">
                                                                                        <h3
                                                                                            class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                                                                            Collection
                                                                                        </h3>
                                                                                    </dd>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <p
                                                                        class="text-sm font-normal text-gray-400 leading-none mt-4">
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="h-8"></div>
                                                <div id="dash1line-chart" class="overflow-hidden mt-4"
                                                    style="height: 350px;"></div>
                                            </div>

                                        </div>

                                        <div class="w-1/2">
                                            <div class="bg-white rounded-xl p-2">
                                                <div class="w-full">
                                                    <div class="flex items-center justify-center text-center w-full">
                                                        <div class="flex space-x-2">
                                                            <div class="space-y-0.5 flex-1 cursor-pointer">
                                                                <div class="flex flex-col items-center justify-center">
                                                                    <div class="bottom-section">
                                                                        <span
                                                                            class="title font-medium text-lg tracking-tight text-gray-900 leading-tight"
                                                                            style="color: #43b02a;">
                                                                            DISBURSEMENT & COLLECTION TREND(TZS)

                                                                        </span>
                                                                        <div class="grid grid-cols-2 divide-x-2 mt-4">
                                                                            <div class="pl-2 pr-2">
                                                                                <div class="item">
                                                                                    <dt
                                                                                        class="mb-2 text-xl font-extrabold">
                                                                                        5,749,852,352.00</dt>
                                                                                    <dd
                                                                                        class="text-gray-500 dark:text-gray-400">
                                                                                        <h3
                                                                                            class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                                                                            Disbursement
                                                                                        </h3>
                                                                                    </dd>
                                                                                </div>
                                                                            </div>
                                                                            <div class="pl-2 pr-2">
                                                                                <div class="item">
                                                                                    <dt
                                                                                        class="mb-2 text-xl font-extrabold">
                                                                                        2,585,974,215.00</dt>
                                                                                    <dd
                                                                                        class="text-gray-500 dark:text-gray-400">
                                                                                        <h3
                                                                                            class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                                                                            Collection
                                                                                        </h3>
                                                                                    </dd>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <p
                                                                        class="text-sm font-normal text-gray-400 leading-none mt-4">
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="h-8"></div>
                                                <div id="dash1bar-chart" class="overflow-hidden mt-4"
                                                    style="height: 350px;"></div>
                                            </div>

                                        </div>

                                    </div>





                                </div>

                            </div>


                        </div>

                    </div>

                    <!-- //dashboard 2 -->
                    <div class="w-full">

                        <div class="flex items-center justify-center p-2 h-full ">
                            <div aria-label="card" class="p-6 rounded-3xl bg-white w-full h-full  ">

                                <div aria-label="header" class="flex items-center space-x-2">

                                    <svg data-slot="icon" class="w-8 h-8 shrink-0" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="#43b02a" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z">
                                        </path>
                                    </svg>


                                    <div x="loadView('b')" class="space-y-0.5 flex-1 cursor-pointer">
                                        <h3 class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                            Loan Portfolio Breakdown
                                        </h3>
                                        <p class="text-sm font-medium text-gray-900 leading-none">
                                            Unpaid loan amounts categorized into principal, interest, and penalties
                                        </p>
                                    </div>




                                </div>


                                <div aria-label="content" class="mt-9 w-full gap-0">
                                    <div class="w-full flex items-center">
                                        <div class="w-1/3">
                                            <div id="dash2stacked-bar" class="overflow-hidden mt-4"
                                                style="height: 350px;"></div>

                                            <div class="w-full">
                                                <div class="flex items-center justify-center text-center w-full">
                                                    <div class="flex space-x-2">
                                                        <div class="space-y-0.5 flex-1 cursor-pointer">
                                                            <div class="flex flex-col items-center justify-center">
                                                                <div class="bottom-section">
                                                                    <span
                                                                        class="title font-medium text-sm tracking-tight text-gray-900 leading-tight"
                                                                        style="color: #43b02a;">
                                                                        AGING FOR OUTSTANDING LOAN BALANCES(TZS)
                                                                    </span>
                                                                    <div class="grid grid-cols-3 divide-x-2 mt-4">
                                                                        <div class="pl-2 pr-2">
                                                                            <div class="item">
                                                                                <dt class="mb-2 text-sm font-extrabold">
                                                                                    5,749,852,352.00</dt>
                                                                                <dd
                                                                                    class="text-gray-500 dark:text-gray-400">
                                                                                    <h3
                                                                                        class="font-medium text-sm tracking-tight text-gray-900 leading-tight">
                                                                                        Principal
                                                                                    </h3>
                                                                                </dd>
                                                                            </div>
                                                                        </div>
                                                                        <div class="pl-2 pr-2">
                                                                            <div class="item">
                                                                                <dt class="mb-2 text-sm font-extrabold">
                                                                                    2,585,974,215.00</dt>
                                                                                <dd
                                                                                    class="text-gray-500 dark:text-gray-400">
                                                                                    <h3
                                                                                        class="font-medium text-sm tracking-tight text-gray-900 leading-tight">
                                                                                        Interest
                                                                                    </h3>
                                                                                </dd>
                                                                            </div>
                                                                        </div>
                                                                        <div class="pl-2 pr-2">
                                                                            <div class="item">
                                                                                <dt class="mb-2 text-sm font-extrabold">
                                                                                    2,585,974,215.00</dt>
                                                                                <dd
                                                                                    class="text-gray-500 dark:text-gray-400">
                                                                                    <h3
                                                                                        class="font-medium text-sm tracking-tight text-gray-900 leading-tight">
                                                                                        Penalty
                                                                                    </h3>
                                                                                </dd>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <p
                                                                    class="text-sm font-normal text-gray-400 leading-none mt-4">
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="w-1/3">

                                            <div class="w-full">
                                                <div class="flex items-center justify-center text-center w-full">
                                                    <div class="flex space-x-2">
                                                        <div class="space-y-0.5 flex-1 cursor-pointer">
                                                            <div class="flex flex-col items-center justify-center">
                                                                <div class="bottom-section">
                                                                    <span
                                                                        class="title font-medium text-sm tracking-tight text-gray-900 leading-tight"
                                                                        style="color: #43b02a;">
                                                                        TOTAL PRINCIPAL & TOTAL INTEREST & TOTAL
                                                                        PENALTY(TZS)
                                                                    </span>
                                                                    <div class="grid grid-cols-3 divide-x-2 mt-4">
                                                                        <div class="pl-2 pr-2">
                                                                            <div class="item">
                                                                                <dt class="mb-2 text-sm font-extrabold">
                                                                                    5,749,852,352.00</dt>
                                                                                <dd
                                                                                    class="text-gray-500 dark:text-gray-400">
                                                                                    <h3
                                                                                        class="font-medium text-sm tracking-tight text-gray-900 leading-tight">
                                                                                        Principal
                                                                                    </h3>
                                                                                </dd>
                                                                            </div>
                                                                        </div>
                                                                        <div class="pl-2 pr-2">
                                                                            <div class="item">
                                                                                <dt class="mb-2 text-sm font-extrabold">
                                                                                    2,585,974,215.00</dt>
                                                                                <dd
                                                                                    class="text-gray-500 dark:text-gray-400">
                                                                                    <h3
                                                                                        class="font-medium text-sm tracking-tight text-gray-900 leading-tight">
                                                                                        Interest
                                                                                    </h3>
                                                                                </dd>
                                                                            </div>
                                                                        </div>
                                                                        <div class="pl-2 pr-2">
                                                                            <div class="item">
                                                                                <dt class="mb-2 text-sm font-extrabold">
                                                                                    2,585,974,215.00</dt>
                                                                                <dd
                                                                                    class="text-gray-500 dark:text-gray-400">
                                                                                    <h3
                                                                                        class="font-medium text-sm tracking-tight text-gray-900 leading-tight">
                                                                                        Penalty
                                                                                    </h3>
                                                                                </dd>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <p
                                                                    class="text-sm font-normal text-gray-400 leading-none mt-4">
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="dash2pie-chart" class="overflow-hidden" style="height: 350px;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-1/3">
                                            <div id="dash2vertical-bar" class="overflow-hidden" style="height: 350px;">
                                            </div>
                                            <div class="w-full">
                                                <div class="flex items-center justify-center text-center w-full">
                                                    <div class="flex space-x-2">
                                                        <div class="space-y-0.5 flex-1 cursor-pointer">
                                                            <div class="flex flex-col items-center justify-center">
                                                                <div class="bottom-section">
                                                                    <span
                                                                        class="title font-medium text-sm tracking-tight text-gray-900 leading-tight"
                                                                        style="color: #43b02a;">
                                                                        OUTSTANDING LOAN BALANCES(TZS)
                                                                    </span>
                                                                    <div class="grid grid-cols-3 divide-x-2 mt-4">
                                                                        <div class="pl-2 pr-2">
                                                                            <div class="item">
                                                                                <dt class="mb-2 text-sm font-extrabold">
                                                                                    5,749,852,352.00</dt>
                                                                                <dd
                                                                                    class="text-gray-500 dark:text-gray-400">
                                                                                    <h3
                                                                                        class="font-medium text-sm tracking-tight text-gray-900 leading-tight">
                                                                                        Principal
                                                                                    </h3>
                                                                                </dd>
                                                                            </div>
                                                                        </div>
                                                                        <div class="pl-2 pr-2">
                                                                            <div class="item">
                                                                                <dt class="mb-2 text-sm font-extrabold">
                                                                                    2,585,974,215.00</dt>
                                                                                <dd
                                                                                    class="text-gray-500 dark:text-gray-400">
                                                                                    <h3
                                                                                        class="font-medium text-sm tracking-tight text-gray-900 leading-tight">
                                                                                        Interest
                                                                                    </h3>
                                                                                </dd>
                                                                            </div>
                                                                        </div>
                                                                        <div class="pl-2 pr-2">
                                                                            <div class="item">
                                                                                <dt class="mb-2 text-sm font-extrabold">
                                                                                    2,585,974,215.00</dt>
                                                                                <dd
                                                                                    class="text-gray-500 dark:text-gray-400">
                                                                                    <h3
                                                                                        class="font-medium text-sm tracking-tight text-gray-900 leading-tight">
                                                                                        Penalty
                                                                                    </h3>
                                                                                </dd>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <p
                                                                    class="text-sm font-normal text-gray-400 leading-none mt-4">
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>







                                </div>

                            </div>


                        </div>

                    </div>


                    <!-- //dashboard 4 -->
                    <!-- top 4 cards -->
                    <div class="w-full">

                        <div class="flex items-center justify-center p-2 h-full ">
                            <div aria-label="card" class="p-6 rounded-3xl bg-white w-full h-full  ">
                                <div aria-label="header" class="flex items-center space-x-2">

                                    <svg data-slot="icon" class="w-8 h-8 shrink-0" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="#43b02a" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z">
                                        </path>
                                    </svg>


                                    <div x="loadView('b')" class="space-y-0.5 flex-1 cursor-pointer">
                                        <h3 class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                            Customer Overview
                                        </h3>
                                        <p class="text-sm font-medium text-gray-900 leading-none">
                                            Customer acquisition, retention, and repeat borrowing trends
                                        </p>
                                    </div>
                                </div>

                                <div class="w-full flex gap-4 mt-8">


                                    <div class="w-1/4 bg-gray-100 p-1 rounded-3xl">

                                        <div class="flex items-center justify-center h-full ">
                                            <div aria-label="card" class="p-6 rounded-3xl bg-white w-full h-full  ">

                                                <div aria-label="header" class="flex items-center space-x-2">

                                                    <svg data-slot="icon" class="w-8 h-8 shrink-0" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="#43b02a" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z">
                                                        </path>
                                                    </svg>


                                                    <div x="loadView('b')" class="space-y-0.5 flex-1 cursor-pointer">
                                                        <h3
                                                            class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                                            Number of customers
                                                        </h3>
                                                        <p class="text-sm font-medium text-gray-900 leading-none">
                                                            Total number of customers
                                                        </p>
                                                    </div>




                                                </div>


                                                <div aria-label="content" class="mt-9 grid gap-2.5">

                                                    <p class="mb-2 text-3xl font-extrabold">
                                                        142,000
                                                    </p>

                                                </div>

                                            </div>


                                        </div>

                                    </div>

                                    <div class="w-1/4 bg-gray-100 p-1 rounded-3xl">
                                        <div class="flex items-center justify-center h-full ">
                                            <div aria-label="card" class="p-6 rounded-3xl bg-white w-full h-full ">
                                                <div aria-label="header" class="flex items-center space-x-2">
                                                    <svg data-slot="icon" class="w-8 h-8 shrink-0" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="#43b02a" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z">
                                                        </path>
                                                    </svg>


                                                    <div class="space-y-0.5 flex-1 cursor-pointer">
                                                        <h3
                                                            class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                                            Repeat customers
                                                        </h3>
                                                        <p class="text-sm font-medium text-gray-900 leading-none">
                                                            Total number of repeat customers
                                                        </p>
                                                    </div>




                                                </div>



                                                <div aria-label="content" class="mt-9 grid gap-2.5">

                                                    <p class="mb-2 text-3xl font-extrabold">
                                                        55,004
                                                    </p>

                                                </div>


                                            </div>
                                        </div>
                                    </div>

                                    <div class="w-1/4 bg-gray-100 p-1 rounded-3xl">
                                        <div class="flex items-center justify-center h-full ">
                                            <div aria-label="card" class="p-6 rounded-3xl bg-white w-full h-full ">
                                                <div aria-label="header" class="flex items-center space-x-2">
                                                    <svg data-slot="icon" class="w-8 h-8 shrink-0" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="#43b02a" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z">
                                                        </path>
                                                    </svg>
                                                    <div class="space-y-0.5 flex-1 cursor-pointer">
                                                        <h3
                                                            class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                                            Repeat rate
                                                        </h3>
                                                        <p class="text-sm font-medium text-gray-900 leading-none">
                                                            Total repeat rate
                                                        </p>
                                                    </div>
                                                </div>

                                                <div aria-label="content" class="mt-9 grid gap-2.5">

                                                    <p class="mb-2 text-3xl font-extrabold">
                                                        47.19%
                                                    </p>

                                                </div>




                                            </div>
                                        </div>
                                    </div>

                                    <div class="w-1/4 bg-gray-100 p-1 rounded-3xl">
                                        <div class="flex items-center justify-center h-full ">
                                            <div aria-label="card" class="p-6 rounded-3xl bg-white w-full h-full ">
                                                <div aria-label="header" class="flex items-center space-x-2">
                                                    <svg data-slot="icon" class="w-8 h-8 shrink-0" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="#43b02a" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z">
                                                        </path>
                                                    </svg>
                                                    <div class="space-y-0.5 flex-1 cursor-pointer">
                                                        <h3
                                                            class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                                            Number of new customers
                                                        </h3>
                                                        <p class="text-sm font-medium text-gray-900 leading-none">
                                                            Total number of new customers
                                                        </p>
                                                    </div>
                                                </div>

                                                <div aria-label="content" class="mt-9 grid gap-2.5">

                                                    <p class="mb-2 text-3xl font-extrabold">
                                                        10,494
                                                    </p>

                                                </div>




                                            </div>
                                        </div>
                                    </div>


                                </div>

                                <!-- two bar charts -->
                                <div class="w-full flex  gap-2 mt-8">
                                    <div class="w-1/2 p-1 rounded-3xl flex flex-col gap-2">
                                        <div class="flex items-center justify-center text-center w-full">
                                            <div class="flex space-x-2">
                                                <div class="space-y-0.5 flex-1 cursor-pointer">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <div class="bottom-section">
                                                            <span
                                                                class="title font-medium text-sm tracking-tight text-gray-900 leading-tight"
                                                                style="color: #43b02a;">
                                                                TOTAL CUSTOMERS & TOTAL WAVES
                                                            </span>
                                                            <div class="grid grid-cols-2 divide-x-2 mt-4">
                                                                <div class="pl-2 pr-2">
                                                                    <div class="item">
                                                                        <dt class="mb-2 text-sm font-extrabold">237,415
                                                                        </dt>
                                                                        <dd class="text-gray-500 dark:text-gray-400">
                                                                            <h3
                                                                                class="font-medium text-sm tracking-tight text-gray-900 leading-tight">
                                                                                Customers
                                                                            </h3>
                                                                        </dd>
                                                                    </div>
                                                                </div>
                                                                <div class="pl-2 pr-2">
                                                                    <div class="item">
                                                                        <dt class="mb-2 text-sm font-extrabold">5</dt>
                                                                        <dd class="text-gray-500 dark:text-gray-400">
                                                                            <h3
                                                                                class="font-medium text-sm tracking-tight text-gray-900 leading-tight">
                                                                                Waves
                                                                            </h3>
                                                                        </dd>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <p class="text-sm font-normal text-gray-400 leading-none mt-4">
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-full h-full bg-white rounded-3xl" id="dash4bar-chart-1"></div>
                                    </div>
                                    <div class="w-1/2 p-1 rounded-3xl flex flex-col gap-2">
                                        <div class="flex items-center justify-center text-center w-full">
                                            <div class="flex space-x-2">
                                                <div class="space-y-0.5 flex-1 cursor-pointer">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <div class="bottom-section">
                                                            <span
                                                                class="title font-medium text-sm tracking-tight text-gray-900 leading-tight"
                                                                style="color: #43b02a;">
                                                                TOTAL CUSTOMERS & TOTAL WAVES
                                                            </span>
                                                            <div class="grid grid-cols-2 divide-x-2 mt-4">
                                                                <div class="pl-2 pr-2">
                                                                    <div class="item">
                                                                        <dt class="mb-2 text-sm font-extrabold">237,415
                                                                        </dt>
                                                                        <dd class="text-gray-500">
                                                                            <h3
                                                                                class="font-medium text-sm tracking-tight text-gray-900 leading-tight">
                                                                                Customers
                                                                            </h3>
                                                                        </dd>
                                                                    </div>
                                                                </div>
                                                                <div class="pl-2 pr-2">
                                                                    <div class="item">
                                                                        <dt class="mb-2 text-sm font-extrabold">5</dt>
                                                                        <dd class="text-gray-500">
                                                                            <h3
                                                                                class="font-medium text-sm tracking-tight text-gray-900 leading-tight">
                                                                                Waves
                                                                            </h3>
                                                                        </dd>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p class="text-sm font-normal text-gray-400 leading-none mt-4">
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="w-full h-full bg-white rounded-3xl" id="dash4bar-chart-2"></div>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                    <!-- //dashboard 6(remake of dashboard 5 as dashboard 2 ) -->

                    <div class="w-full">

                        <div class="flex items-center justify-center p-2 h-full ">
                            <div aria-label="card" class="p-6 rounded-3xl bg-white w-full h-full  ">

                                <div aria-label="header" class="flex items-center space-x-2">

                                    <svg data-slot="icon" class="w-8 h-8 shrink-0" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="#43b02a" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z">
                                        </path>
                                    </svg>

                                    <div x="loadView('b')" class="space-y-0.5 flex-1 cursor-pointer">
                                        <h3 class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                            Customer retention and growth over CVD waves
                                        </h3>
                                        <p class="text-sm font-medium text-gray-900 leading-none">
                                            Customer retention and growth over CVD waves
                                        </p>
                                    </div>
                                </div>

                                <div aria-label="content" class="mt-9 w-full gap-0">
                                    <div class="w-full flex items-center">
                                        <div class="w-1/3">
                                            <div id="dash6line-chart" class="overflow-hidden mt-4"
                                                style="height: 350px;"></div>

                                            <div class="w-full">
                                                <div class="flex items-center justify-center text-center w-full">
                                                    <div class="flex space-x-2">
                                                        <div class="space-y-0.5 flex-1 cursor-pointer">
                                                            <div class="flex flex-col items-center justify-center">
                                                                <div class="bottom-section">
                                                                    <span
                                                                        class="title font-medium text-sm tracking-tight text-gray-900 leading-tight"
                                                                        style="color: #43b02a;">
                                                                        REPAYMENTS(TZS)
                                                                    </span>
                                                                    <div class="grid grid-cols-3 divide-x-2 mt-4">
                                                                        <div class="pl-2 pr-2">
                                                                            <div class="item">
                                                                                <dt class="mb-2 text-sm font-extrabold">
                                                                                    5,749,852,352.00</dt>
                                                                                <dd
                                                                                    class="text-gray-500 dark:text-gray-400">
                                                                                    <h3
                                                                                        class="font-medium text-sm tracking-tight text-gray-900 leading-tight">
                                                                                        Principal
                                                                                    </h3>
                                                                                </dd>
                                                                            </div>
                                                                        </div>
                                                                        <div class="pl-2 pr-2">
                                                                            <div class="item">
                                                                                <dt class="mb-2 text-sm font-extrabold">
                                                                                    2,585,974,215.00</dt>
                                                                                <dd
                                                                                    class="text-gray-500 dark:text-gray-400">
                                                                                    <h3
                                                                                        class="font-medium text-sm tracking-tight text-gray-900 leading-tight">
                                                                                        Interest
                                                                                    </h3>
                                                                                </dd>
                                                                            </div>
                                                                        </div>
                                                                        <div class="pl-2 pr-2">
                                                                            <div class="item">
                                                                                <dt class="mb-2 text-sm font-extrabold">
                                                                                    2,585,974,215.00</dt>
                                                                                <dd
                                                                                    class="text-gray-500 dark:text-gray-400">
                                                                                    <h3
                                                                                        class="font-medium text-sm tracking-tight text-gray-900 leading-tight">
                                                                                        Penalty
                                                                                    </h3>
                                                                                </dd>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <p
                                                                    class="text-sm font-normal text-gray-400 leading-none mt-4">
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="w-1/3">

                                            <div class="w-full">
                                                <div class="flex items-center justify-center text-center w-full">
                                                    <div class="flex space-x-2">
                                                        <div class="space-y-0.5 flex-1 cursor-pointer">
                                                            <div class="flex flex-col items-center justify-center">
                                                                <div class="bottom-section">
                                                                    <span
                                                                        class="title font-medium text-sm tracking-tight text-gray-900 leading-tight"
                                                                        style="color: #43b02a;">
                                                                        COLLECTED VS DUE RATE (CVD)
                                                                    </span>
                                                                    <div class="grid grid-cols-2 divide-x-2 mt-4">

                                                                        <div class="pl-2 pr-2">
                                                                            <div class="item">
                                                                                <dt class="mb-2 text-sm font-extrabold">
                                                                                    1,713,221.00</dt>
                                                                                <dd
                                                                                    class="text-gray-500 dark:text-gray-400">
                                                                                    <h3
                                                                                        class="font-medium text-sm tracking-tight text-gray-900 leading-tight">
                                                                                        Total Repayments
                                                                                    </h3>
                                                                                </dd>
                                                                            </div>
                                                                        </div>
                                                                        <div class="pl-2 pr-2">
                                                                            <div class="item">
                                                                                <dt class="mb-2 text-sm font-extrabold">
                                                                                    7,213,417,500.00</dt>
                                                                                <dd
                                                                                    class="text-gray-500 dark:text-gray-400">
                                                                                    <h3
                                                                                        class="font-medium text-sm tracking-tight text-gray-900 leading-tight">
                                                                                        Total Amount due
                                                                                    </h3>
                                                                                </dd>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <p
                                                                    class="text-sm font-normal text-gray-400 leading-none mt-4">
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="dash6pie-chart" class="overflow-hidden" style="height: 350px;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-1/3">
                                            <div id="dash6bar-chart" class="overflow-hidden" style="height: 350px;">
                                            </div>
                                            <div class="w-full">
                                                <div class="flex items-center justify-center text-center w-full">
                                                    <div class="flex space-x-2">
                                                        <div class="space-y-0.5 flex-1 cursor-pointer">
                                                            <div class="flex flex-col items-center justify-center">
                                                                <div class="bottom-section">
                                                                    <span
                                                                        class="title font-medium text-sm tracking-tight text-gray-900 leading-tight"
                                                                        style="color: #43b02a;">
                                                                        REPAYMENT TREND(CVD)
                                                                    </span>
                                                                    <div class="grid grid-cols-3 divide-x-2 mt-4">
                                                                        <div class="pl-2 pr-2">
                                                                            <div class="item">
                                                                                <dt class="mb-2 text-sm font-extrabold">
                                                                                    5,749,852,352.00</dt>
                                                                                <dd
                                                                                    class="text-gray-500 dark:text-gray-400">
                                                                                    <h3
                                                                                        class="font-medium text-sm tracking-tight text-gray-900 leading-tight">
                                                                                        Principal
                                                                                    </h3>
                                                                                </dd>
                                                                            </div>
                                                                        </div>
                                                                        <div class="pl-2 pr-2">
                                                                            <div class="item">
                                                                                <dt class="mb-2 text-sm font-extrabold">
                                                                                    2,585,974,215.00</dt>
                                                                                <dd
                                                                                    class="text-gray-500 dark:text-gray-400">
                                                                                    <h3
                                                                                        class="font-medium text-sm tracking-tight text-gray-900 leading-tight">
                                                                                        Interest
                                                                                    </h3>
                                                                                </dd>
                                                                            </div>
                                                                        </div>
                                                                        <div class="pl-2 pr-2">
                                                                            <div class="item">
                                                                                <dt class="mb-2 text-sm font-extrabold">
                                                                                    2,585,974,215.00</dt>
                                                                                <dd
                                                                                    class="text-gray-500 dark:text-gray-400">
                                                                                    <h3
                                                                                        class="font-medium text-sm tracking-tight text-gray-900 leading-tight">
                                                                                        Penalty
                                                                                    </h3>
                                                                                </dd>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <p
                                                                    class="text-sm font-normal text-gray-400 leading-none mt-4">
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>







                                </div>

                            </div>


                        </div>

                    </div>

                </div>

                <!-- //dashboard 7(loan repayment trends) -->

                <div class="w-full">

                    <div class="flex items-center justify-center p-2 h-full ">
                        <div aria-label="card" class="p-6 rounded-3xl bg-white w-full h-full  ">

                            <div aria-label="header" class="flex items-center space-x-2">

                                <svg data-slot="icon" class="w-8 h-8 shrink-0" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="#43b02a" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z">
                                    </path>
                                </svg>
                                <div x="loadView('b')" class="space-y-0.5 flex-1 cursor-pointer">
                                    <h3 class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                        Loan Repayment trends
                                    </h3>
                                    <p class="text-sm font-medium text-gray-900 leading-none">
                                        Payment behavior and overdue amounts over time
                                    </p>
                                </div>
                            </div>
                            <div aria-label="content" class="mt-9 w-full gap-2">
                                <div class="w-full flex flex-wrap gap-2">
                                    <div class="w-full lg:w-1/2 p-1 rounded-3xl">
                                        <div id="dash7stacked-bar" class="overflow-x-auto"></div>
                                    </div>
                                    <div class="w-full lg:w-1/2 p-1 rounded-3xl">
                                        <div id="dash7grouped-bar" class="overflow-x-auto"></div>
                                    </div>
                                </div>
                                <div class="w-full flex mt-4 space-x-2 p-10">
                                    <div class="w-2/3 rounded-3xl p-4">
                                        <div id="dash7data-table"></div>
                                    </div>
                                    <div class="w-1/3 rounded-3xl p-4">
                                        <div id="dash7line-chart"></div>
                                    </div>
                                </div>

                            </div>

                        </div>


                    </div>

                </div>


                <!-- //dashboard 8 (Monthly analysis of payment performance) -->

                <div class="w-full">

                    <div class="flex items-center justify-center p-2 h-full ">
                        <div aria-label="card" class="p-6 rounded-3xl bg-white w-full h-full  ">

                            <div aria-label="header" class="flex items-center space-x-2">

                                <svg data-slot="icon" class="w-8 h-8 shrink-0" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="#43b02a" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941">
                                    </path>
                                </svg>
                                <div x="loadView('b')" class="space-y-0.5 flex-1 cursor-pointer">
                                    <h3 class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                        Monthly analysis of payment performance
                                    </h3>
                                    <p class="text-sm font-medium text-gray-900 leading-none">
                                        Trends and comparisons across different months payment performance
                                    </p>
                                </div>
                            </div>
                            <div aria-label="content" class="mt-9 w-full gap-2">
                                <div class="w-full flex mb-4 space-x-2">

                                    <div class="w-1/3 p-4 rounded-3xl">
                                        <div id="dash8chartLine" style="width: 100%; height: 350px;"></div>
                                    </div>
                                    <div class="w-1/3 p-4 rounded-3xl">

                                        <h2
                                            style="text-align: center; margin-bottom: 10px; font-weight: bold; color:#43b02a">
                                            Loan Payment Status</h2>
                                        <table border="1" cellpadding="5" cellspacing="0"
                                            style="border-collapse: collapse; width: 100%; text-align: center; border: 2px solid green;">
                                            <thead>
                                                <tr
                                                    style="background-color: #43b02a; font-weight: bold; text-align: center; color:#fff">
                                                    <th style="border: 2px solid green;">Month</th>
                                                    <th style="border: 2px solid green;">Paid (%)</th>
                                                    <th style="border: 2px solid green;">Totals (%)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="border: 2px solid green;">May</td>
                                                    <td style="border: 2px solid green;">97.20%</td>
                                                    <td style="border: 2px solid green;">88.80%</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 2px solid green;">Jun</td>
                                                    <td style="border: 2px solid green;">93.35%</td>
                                                    <td style="border: 2px solid green;">62.02%</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 2px solid green;">Jul</td>
                                                    <td style="border: 2px solid green;">94.09%</td>
                                                    <td style="border: 2px solid green;">66.31%</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 2px solid green;">Aug</td>
                                                    <td style="border: 2px solid green;">95.13%</td>
                                                    <td style="border: 2px solid green;">67.55%</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 2px solid green;">Sep</td>
                                                    <td style="border: 2px solid green;">98.83%</td>
                                                    <td style="border: 2px solid green;">72.82%</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 2px solid green;">Oct</td>
                                                    <td style="border: 2px solid green;">94.66%</td>
                                                    <td style="border: 2px solid green;">66.08%</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>



                                    <div class="w-1/3 p-4 rounded-3xl">
                                        <div id="dash8chartPie" style="width: 100%; height: 350px;"></div>
                                    </div>

                                </div>
                                <div class="w-full flex mt-4 space-x-2 p-10">
                                    <div class="w-1/2 rounded-3xl p-4">
                                        <div id="dash8chartBar" style="width: 100%; height: 350px;"></div>
                                    </div>
                                    <div class="w-1/2 rounded-3xl p-4">
                                        <div id="dash8chartArea" style="width: 100%; height: 350px;"></div>
                                    </div>
                                </div>

                            </div>

                        </div>


                    </div>

                </div>

                <!-- //dashboard 9 (CVD) -->

                <div class="w-full">

                    <div class="flex items-center justify-center p-2 h-full ">
                        <div aria-label="card" class="p-6 rounded-3xl bg-white w-full h-full  ">

                            <div aria-label="header" class="flex items-center space-x-2">

                                <svg data-slot="icon" class="w-8 h-8 shrink-0" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="#43b02a" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125">
                                    </path>
                                </svg>
                                <div x="loadView('c')" class="space-y-0.5 flex-1 cursor-pointer">
                                    <h3 class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                        Collected Vs Due (CvD) Data
                                    </h3>
                                    <p class="text-sm font-medium text-gray-900 leading-none">
                                        Customers repayment behavior over multiple borrowing cycles
                                    </p>
                                </div>
                            </div>
                            <div aria-label="content" class="mt-9 w-full gap-2">
                                <div class="w-full flex mb-4 space-x-2">

                                    <div class="w-1/3 p-4 rounded-3xl">
                                        <div id="dash9chartLine" style="width: 100%; height: 350px;"></div>
                                    </div>

                                    <div class="w-1/3 p-4 rounded-3xl">
                                        <div id="dash9chartPie" style="width: 100%; height: 350px;"></div>
                                    </div>

                                    <div class="w-1/3 rounded-3xl p-4">
                                        <div id="dash9chartArea" style="width: 100%; height: 350px;"></div>
                                    </div>

                                </div>
                                <div class="w-full flex mt-4 space-x-2 p-10">
                                    <div class="w-1/2 rounded-3xl p-4">
                                        <div id="dash9chartBar" style="width: 100%; height: 350px;"></div>
                                    </div>

                                    <div class="w-1/2 p-4 rounded-3xl">

                                        <h2
                                            style="text-align: center; margin-bottom: 10px; font-weight: bold; color:#43b02a">
                                            Wave-wise Collected Vs Due Data
                                        </h2>

                                        <table border="1" cellpadding="5" cellspacing="0"
                                            style="border-collapse: collapse; width: 100%; text-align: center; border: 2px solid green;">

                                            <thead>
                                                <tr
                                                    style="background-color: #43b02a; font-weight: bold; text-align: center; color: #fff;">
                                                    <th style="border: 2px solid green;" rowspan="2">Category</th>
                                                    <th style="border: 2px solid green;" colspan="6">Wave</th>
                                                    <th style="border: 2px solid green;" rowspan="2">Grand Total</th>
                                                </tr>
                                                <tr
                                                    style="background-color: #43b02a; font-weight: bold; text-align: center; color: #fff;">
                                                    <th style="border: 2px solid green;">Wave0</th>
                                                    <th style="border: 2px solid green;">Wave1</th>
                                                    <th style="border: 2px solid green;">Wave2</th>
                                                    <th style="border: 2px solid green;">Wave3</th>
                                                    <th style="border: 2px solid green;">Wave4</th>
                                                    <th style="border: 2px solid green;">Wave5+</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="border: 2px solid green;">Never Paid</td>
                                                    <td style="border: 2px solid green;">0%</td>
                                                    <td style="border: 2px solid green;">0%</td>
                                                    <td style="border: 2px solid green;">0%</td>
                                                    <td style="border: 2px solid green;">0%</td>
                                                    <td style="border: 2px solid green;">0%</td>
                                                    <td style="border: 2px solid green;">0%</td>
                                                    <td style="border: 2px solid green;">0%</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 2px solid green;">Paid</td>
                                                    <td style="border: 2px solid green;">94%</td>
                                                    <td style="border: 2px solid green;">94%</td>
                                                    <td style="border: 2px solid green;">95%</td>
                                                    <td style="border: 2px solid green;">97%</td>
                                                    <td style="border: 2px solid green;">98%</td>
                                                    <td style="border: 2px solid green;">100%</td>
                                                    <td style="border: 2px solid green;">95%</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 2px solid green;">Distribution (%) - Never Paid
                                                    </td>
                                                    <td style="border: 2px solid green;">34.23%</td>
                                                    <td style="border: 2px solid green;">29.98%</td>
                                                    <td style="border: 2px solid green;">30.90%</td>
                                                    <td style="border: 2px solid green;">36.22%</td>
                                                    <td style="border: 2px solid green;">39.64%</td>
                                                    <td style="border: 2px solid green;">32.09%</td>
                                                    <td style="border: 2px solid green;">33.05%</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 2px solid green;">Distribution (%) - Paid</td>
                                                    <td style="border: 2px solid green;">65.77%</td>
                                                    <td style="border: 2px solid green;">70.02%</td>
                                                    <td style="border: 2px solid green;">69.10%</td>
                                                    <td style="border: 2px solid green;">63.78%</td>
                                                    <td style="border: 2px solid green;">60.36%</td>
                                                    <td style="border: 2px solid green;">67.91%</td>
                                                    <td style="border: 2px solid green;">66.95%</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 2px solid green;">Subjects - Never Paid</td>
                                                    <td style="border: 2px solid green;">41,248</td>
                                                    <td style="border: 2px solid green;">16,921</td>
                                                    <td style="border: 2px solid green;">8,995</td>
                                                    <td style="border: 2px solid green;">5,647</td>
                                                    <td style="border: 2px solid green;">3,074</td>
                                                    <td style="border: 2px solid green;">2,569</td>
                                                    <td style="border: 2px solid green;">78,454</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 2px solid green;">Subjects - Paid</td>
                                                    <td style="border: 2px solid green;">79,246</td>
                                                    <td style="border: 2px solid green;">39,522</td>
                                                    <td style="border: 2px solid green;">20,114</td>
                                                    <td style="border: 2px solid green;">9,940</td>
                                                    <td style="border: 2px solid green;">4,680</td>
                                                    <td style="border: 2px solid green;">5,134</td>
                                                    <td style="border: 2px solid green;">158,636</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 2px solid green;">Total Average of CvD</td>
                                                    <td style="border: 2px solid green;">62%</td>
                                                    <td style="border: 2px solid green;">66%</td>
                                                    <td style="border: 2px solid green;">69%</td>
                                                    <td style="border: 2px solid green;">73%</td>
                                                    <td style="border: 2px solid green;">77%</td>
                                                    <td style="border: 2px solid green;">83%</td>
                                                    <td style="border: 2px solid green;">66%</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 2px solid green;">Total Sum of Volume</td>
                                                    <td style="border: 2px solid green;">100.00%</td>
                                                    <td style="border: 2px solid green;">100.00%</td>
                                                    <td style="border: 2px solid green;">100.00%</td>
                                                    <td style="border: 2px solid green;">100.00%</td>
                                                    <td style="border: 2px solid green;">100.00%</td>
                                                    <td style="border: 2px solid green;">100.00%</td>
                                                    <td style="border: 2px solid green;">100.00%</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 2px solid green;">Total Sum of NCustomers</td>
                                                    <td style="border: 2px solid green;">120,494</td>
                                                    <td style="border: 2px solid green;">56,443</td>
                                                    <td style="border: 2px solid green;">29,109</td>
                                                    <td style="border: 2px solid green;">15,587</td>
                                                    <td style="border: 2px solid green;">7,754</td>
                                                    <td style="border: 2px solid green;">7,703</td>
                                                    <td style="border: 2px solid green;">237,090</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                            </div>

                        </div>


                    </div>

                </div>


                <!-- //dashboard 10 (Vintage analysis) -->


                <div class="w-full">

                    <div class="flex items-center justify-center p-2 h-full ">
                        <div aria-label="card" class="p-6 rounded-3xl bg-white w-full h-full  ">

                            <div aria-label="header" class="flex items-center space-x-2">

                                <svg data-slot="icon" class="w-8 h-8 shrink-0" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="#43b02a" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m21 7.5-2.25-1.313M21 7.5v2.25m0-2.25-2.25 1.313M3 7.5l2.25-1.313M3 7.5l2.25 1.313M3 7.5v2.25m9 3 2.25-1.313M12 12.75l-2.25-1.313M12 12.75V15m0 6.75 2.25-1.313M12 21.75V19.5m0 2.25-2.25-1.313m0-16.875L12 2.25l2.25 1.313M21 14.25v2.25l-2.25 1.313m-13.5 0L3 16.5v-2.25">
                                    </path>
                                </svg>
                                <div x="loadView('c')" class="space-y-0.5 flex-1 cursor-pointer">
                                    <h3 class="font-medium text-lg tracking-tight text-gray-900 leading-tight">
                                        Vintage Analysis
                                    </h3>
                                    <p class="text-sm font-medium text-gray-900 leading-none">
                                        Loan Repayment Performance Over Time
                                    </p>
                                </div>
                            </div>
                            <div aria-label="content" class="mt-9 w-full gap-2">
                                <div class="w-full flex mb-2 space-x-2 p-10">

                                    <!-- Vintage Analysis Table -->
                                    <div class="w-full flex flex-col overflow-x-auto">
                                        <h2
                                            style="text-align: center; margin-bottom: 10px; font-weight: bold; color:#43b02a">
                                            Vintage Analysis for Bad Rate
                                        </h2>
                                        <table border="1" cellpadding="5" cellspacing="0"
                                            style="border-collapse: collapse; width: 100%; text-align: center; border: 2px solid green;">
                                            <thead>
                                                <tr
                                                    style="background-color: #43b02a; font-weight: bold; text-align: center; color: #fff;">
                                                    <th style="border: 2px solid green;">Vintage Month</th>
                                                    <th style="border: 2px solid green;">Total Loans</th>
                                                    <th style="border: 2px solid green;">Jan (1st Month)</th>
                                                    <th style="border: 2px solid green;">Feb (2nd Month)</th>
                                                    <th style="border: 2px solid green;">Mar (3rd Month)</th>
                                                    <th style="border: 2px solid green;">Apr (4th Month)</th>
                                                    <th style="border: 2px solid green;">May (5th Month)</th>
                                                    <th style="border: 2px solid green;">Jun (6th Month)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="border: 2px solid green;">Jan</td>
                                                    <td style="border: 2px solid green;">100</td>
                                                    <td style="border: 2px solid green;">0%</td>
                                                    <td style="border: 2px solid green;">1%</td>
                                                    <td style="border: 2px solid green;">3%</td>
                                                    <td style="border: 2px solid green;">5%</td>
                                                    <td style="border: 2px solid green;">6%</td>
                                                    <td style="border: 2px solid green;">7%</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 2px solid green;">Feb</td>
                                                    <td style="border: 2px solid green;">95</td>
                                                    <td style="border: 2px solid green;">0%</td>
                                                    <td style="border: 2px solid green;">2%</td>
                                                    <td style="border: 2px solid green;">3%</td>
                                                    <td style="border: 2px solid green;">4%</td>
                                                    <td style="border: 2px solid green;">5%</td>
                                                    <td style="border: 2px solid green;">N/A</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 2px solid green;">Mar</td>
                                                    <td style="border: 2px solid green;">90</td>
                                                    <td style="border: 2px solid green;">0%</td>
                                                    <td style="border: 2px solid green;">1%</td>
                                                    <td style="border: 2px solid green;">2%</td>
                                                    <td style="border: 2px solid green;">3%</td>
                                                    <td style="border: 2px solid green;">N/A</td>
                                                    <td style="border: 2px solid green;">N/A</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 2px solid green;">Apr</td>
                                                    <td style="border: 2px solid green;">105</td>
                                                    <td style="border: 2px solid green;">0%</td>
                                                    <td style="border: 2px solid green;">2%</td>
                                                    <td style="border: 2px solid green;">4%</td>
                                                    <td style="border: 2px solid green;">N/A</td>
                                                    <td style="border: 2px solid green;">N/A</td>
                                                    <td style="border: 2px solid green;">N/A</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 2px solid green;">May</td>
                                                    <td style="border: 2px solid green;">100</td>
                                                    <td style="border: 2px solid green;">0%</td>
                                                    <td style="border: 2px solid green;">1%</td>
                                                    <td style="border: 2px solid green;">N/A</td>
                                                    <td style="border: 2px solid green;">N/A</td>
                                                    <td style="border: 2px solid green;">N/A</td>
                                                    <td style="border: 2px solid green;">N/A</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 2px solid green;">Jun</td>
                                                    <td style="border: 2px solid green;">110</td>
                                                    <td style="border: 2px solid green;">0%</td>
                                                    <td style="border: 2px solid green;">N/A</td>
                                                    <td style="border: 2px solid green;">N/A</td>
                                                    <td style="border: 2px solid green;">N/A</td>
                                                    <td style="border: 2px solid green;">N/A</td>
                                                    <td style="border: 2px solid green;">N/A</td>

                                                </tr>
                                                <!-- <tr>
                                        <td style="border: 2px solid green;">Jul</td>
                                        <td style="border: 2px solid green;">95</td>
                                        <td style="border: 2px solid green;">0%</td>
                                        <td style="border: 2px solid green;">2%</td>
                                        <td style="border: 2px solid green;">3%</td>
                                        <td style="border: 2px solid green;">5%</td>
                                        <td style="border: 2px solid green;">6%</td>
                                        <td style="border: 2px solid green;">7%</td>
                                    </tr>
                                    <tr>
                                        <td style="border: 2px solid green;">Aug</td>
                                        <td style="border: 2px solid green;">98</td>
                                        <td style="border: 2px solid green;">0%</td>
                                        <td style="border: 2px solid green;">2%</td>
                                        <td style="border: 2px solid green;">4%</td>
                                        <td style="border: 2px solid green;">6%</td>
                                        <td style="border: 2px solid green;">7%</td>
                                        <td style="border: 2px solid green;">8%</td>
                                    </tr>
                                    <tr>
                                        <td style="border: 2px solid green;">Sep</td>
                                        <td style="border: 2px solid green;">102</td>
                                        <td style="border: 2px solid green;">0%</td>
                                        <td style="border: 2px solid green;">1%</td>
                                        <td style="border: 2px solid green;">2%</td>
                                        <td style="border: 2px solid green;">3%</td>
                                        <td style="border: 2px solid green;">4%</td>
                                        <td style="border: 2px solid green;">5%</td>
                                    </tr>
                                    <tr>
                                        <td style="border: 2px solid green;">Oct</td>
                                        <td style="border: 2px solid green;">100</td>
                                        <td style="border: 2px solid green;">0%</td>
                                        <td style="border: 2px solid green;">1%</td>
                                        <td style="border: 2px solid green;">2%</td>
                                        <td style="border: 2px solid green;">3%</td>
                                        <td style="border: 2px solid green;">3%</td>
                                        <td style="border: 2px solid green;">4%</td>
                                    </tr>
                                    <tr>
                                        <td style="border: 2px solid green;">Nov</td>
                                        <td style="border: 2px solid green;">93</td>
                                        <td style="border: 2px solid green;">0%</td>
                                        <td style="border: 2px solid green;">1%</td>
                                        <td style="border: 2px solid green;">2%</td>
                                        <td style="border: 2px solid green;">2%</td>
                                        <td style="border: 2px solid green;">3%</td>
                                        <td style="border: 2px solid green;">4%</td>
                                    </tr>
                                    <tr>
                                        <td style="border: 2px solid green;">Dec</td>
                                        <td style="border: 2px solid green;">97</td>
                                        <td style="border: 2px solid green;">0%</td>
                                        <td style="border: 2px solid green;">1%</td>
                                        <td style="border: 2px solid green;">1%</td>
                                        <td style="border: 2px solid green;">2%</td>
                                        <td style="border: 2px solid green;">3%</td>
                                        <td style="border: 2px solid green;">4%</td>
                                    </tr> -->
                                            </tbody>
                                        </table>

                                    </div>

                                </div>
                                <div class="w-full flex mt-4 space-x-2 p-10">
                                    <div class="w-full rounded-3xl p-4">
                                        <div id="dash10vintageChart" style="width: 100%; height: 350px;"></div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                </div>

            </div>

        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <!-- //dashboard 1 scripts -->
   {{-- <script>
        document.addEventListener("DOMContentLoaded", function () {
            // **Radial Bar Chart - Collection Rate**
            var radialBarChartOptions = {
                series: [44.97],
                chart: {
                    height: 430,
                    type: 'radialBar',
                },
                plotOptions: {
                    radialBar: {
                        dataLabels: {
                            show: true,
                            name: {
                                offsetY: 10,
                                color: '#215731',
                                fontSize: '18px',
                                fontWeight: 'bold'
                            },
                            value: {
                                color: '#43b02a',
                                fontSize: '22px',
                                fontWeight: 'bold',
                                show: true
                            }
                        },
                        hollow: {
                            size: '70%',
                        }
                    }
                },
                labels: ['Collection Rate'],
                colors: ['#43b02a'],
            };

            var radialBarChart = new ApexCharts(document.querySelector("#dash1radial-bar-chart"), radialBarChartOptions);
            radialBarChart.render();

            // **Line Chart - Collection & Disbursement Trends**
            var lineChartOptions = {
                series: [
                    {
                        name: 'Collection',
                        data: [120, 150, 130, 170, 90, 140, 110]
                    },
                    {
                        name: 'Disbursement',
                        data: [100, 130, 110, 160, 80, 120, 105]
                    }
                ],
                chart: {
                    height: 430,
                    type: 'line',
                    zoom: { enabled: false },
                    toolbar: { show: false }
                },
                dataLabels: { enabled: false },
                stroke: {
                    width: 4,
                    curve: 'smooth'
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'center'
                },
                markers: { size: 5 },
                xaxis: {
                    categories: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                    labels: { style: { colors: ['#333'] } }
                },
                yaxis: {
                    labels: {
                        formatter: function (value) { return value + " mil"; },
                        style: { colors: ['#333'] }
                    }
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function (val) { return val + " million"; }
                    }
                },
                grid: {
                    show: false,
                    borderColor: '#e0e0e0',
                    strokeDashArray: 4
                },
                colors: ['#43b02a', '#215731'],
                responsive: [{
                    breakpoint: 600,
                    options: {
                        chart: { height: 430 },
                        legend: { position: 'bottom' }
                    }
                }]
            };

            var lineChart = new ApexCharts(document.querySelector("#dash1line-chart"), lineChartOptions);
            lineChart.render();

            // **Bar Chart - Collection & Disbursement**
            var barChartOptions = {
                series: [
                    {
                        name: 'Collection',
                        data: [120, 150, 130, 170, 90, 140, 110]
                    },
                    {
                        name: 'Disbursement',
                        data: [100, 130, 110, 160, 80, 120, 105]
                    }
                ],
                chart: {
                    type: 'bar',
                    height: 430,
                    stacked: true,
                    toolbar: { show: false },
                    zoom: { enabled: true }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '50%',
                        borderRadius: 5
                    }
                },
                dataLabels: {
                    enabled: true,
                    style: { colors: ['#fff'] }
                },
                stroke: {
                    show: true,
                    width: 1,
                    colors: ['#fff']
                },
                grid: {
                    show: false,
                    borderColor: '#ccc',
                    strokeDashArray: 4
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function (val) { return val + " million"; }
                    }
                },
                xaxis: {
                    categories: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                    labels: { style: { colors: ['#333'] } }
                },
                yaxis: {
                    labels: {
                        formatter: function (value) { return value + " mil"; },
                        style: { colors: ['#333'] }
                    }
                },
                legend: {
                    position: 'top',
                    markers: { width: 12, height: 12, radius: 4 }
                },
                colors: ['#43b02a', '#215731'],
                responsive: [{
                    breakpoint: 600,
                    options: {
                        chart: { height: 430 },
                        legend: { position: 'bottom' }
                    }
                }]
            };

            var barChart = new ApexCharts(document.querySelector("#dash1bar-chart"), barChartOptions);
            barChart.render();
        });
    </script>

    <!-- //dashboard 2 scripts -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // **Horizontal Stacked Bar Chart**
            var stackedBarChart = new ApexCharts(document.querySelector("#dash2stacked-bar"), {
                series: [
                    { name: 'Principal', data: [500, 300, 200, 150] },
                    { name: 'Interest', data: [800, 500, 300, 200] },
                    { name: 'Penalty', data: [200, 150, 100, 50] }
                ],
                chart: {
                    type: 'bar', height: 430, stacked: true, toolbar: { show: false }, zoom: { enabled: true }
                },
                plotOptions: {
                    bar: { horizontal: true, barHeight: '70%', borderRadius: 5, columnWidth: '50%' }
                },
                colors: ['#43b02a', '#89D87F', '#215731'],
                xaxis: {
                    categories: ['Current', '30+ Days', '60+ Days', '90+ Days'],
                    title: { text: 'Amount (TZS)' },
                    labels: { formatter: val => val.toLocaleString() + " mil" }
                },
                legend: { position: 'top', fontSize: '10px' },
                dataLabels: { enabled: true },
                tooltip: { y: { formatter: val => "TZS " + val.toLocaleString() + " mil" } }
            });
            stackedBarChart.render();

            // **Pie Chart**
            var pieChart = new ApexCharts(document.querySelector("#dash2pie-chart"), {
                series: [500000, 120000, 30000],
                chart: { type: 'pie', height: 430 },
                labels: ['Total Principal', 'Total Interest', 'Total Penalty'],
                colors: ['#43b02a', '#89D87F', '#215731'],
                legend: { position: 'top', fontSize: '10px' },
                tooltip: { y: { formatter: val => "TZS " + val.toLocaleString() + " mil" } },
                stroke: { show: true, width: 2, colors: ['#fff'] }
            });
            pieChart.render();

            // **Vertical Stacked Bar Chart**
            var verticalBarChart = new ApexCharts(document.querySelector("#dash2vertical-bar"), {
                series: [
                    { name: 'Principal', data: [500, 520, 490, 510] },
                    { name: 'Interest', data: [120, 115, 118, 122] },
                    { name: 'Penalty', data: [30, 28, 31, 29] }
                ],
                chart: { type: 'bar', height: 430, stacked: true, toolbar: { show: false }, zoom: { enabled: true } },
                plotOptions: { bar: { horizontal: false, columnWidth: '50%', borderRadius: 5 } },
                colors: ['#43b02a', '#89D87F', '#215731'],
                xaxis: { categories: ['Branch A', 'Branch B', 'Branch C', 'Branch D'], title: { text: 'Branches' } },
                yaxis: { labels: { formatter: val => val.toLocaleString() + " mil" } },
                legend: { position: 'top', fontSize: '10px' },
                dataLabels: { enabled: true },
                tooltip: { y: { formatter: val => "TZS " + val.toLocaleString() + " mil" } }
            });
            verticalBarChart.render();
        });
    </script>

    <!-- //dashboard 4 scripts -->

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Bar Chart: Number of Customers
            var barChartOptions = {
                chart: {
                    type: "bar",
                    height: 430,
                    toolbar: { show: false },
                    zoom: { enabled: false }
                },
                colors: ['#43b02a'],
                series: [{
                    name: "Customers",
                    data: [120494, 56443, 29111, 15589, 7754, 8025]
                }],
                xaxis: {
                    categories: ["New Customer", "Wave 1", "Wave 2", "Wave 3", "Wave 4", "Wave 5+"]
                },
                yaxis: {
                    title: { text: "Number of Customers" }
                },
                plotOptions: {
                    bar: { horizontal: true, barHeight: '70%', borderRadius: 5, columnWidth: '50%' }
                },
                // title: {
                //     text: "Number of Customers",
                //     align: "center"
                // }
            };
            var barChart = new ApexCharts(document.querySelector("#dash4bar-chart-1"), barChartOptions);
            barChart.render();

            // Line Chart: Repeat Percentage
            var lineChartOptions = {
                chart: {
                    type: "line",
                    height: 430,
                    toolbar: { show: false },
                    zoom: { enabled: false }
                },
                colors: ['#43b02a'], // Adjusted to a valid color
                series: [{
                    name: "Repeat Percentage",
                    data: [62.19, 65.78, 69.38, 73.25, 76.89, 83.00]
                }],
                xaxis: {
                    categories: ["New Customer", "Wave 1", "Wave 2", "Wave 3", "Wave 4", "Wave 5+"]
                },
                yaxis: {
                    title: { text: "Percentage (%)" }
                }
            };
            var lineChart = new ApexCharts(document.querySelector("#dash4bar-chart-2"), lineChartOptions);
            lineChart.render();
        });
    </script>


    <!-- //dashboard 6 scripts -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // Line Chart (Repeat Percentage)
            var lineChartOptions = {
                chart: {
                    type: "line",
                    height: 430,
                    zoom: { enabled: false },
                    toolbar: { show: false }
                },
                colors: ['#43b02a'],
                series: [{
                    name: "Repeat Percentage",
                    data: [62.19, 65.78, 69.38, 73.25, 76.89, 83.00]
                }],
                xaxis: {
                    categories: ["New Customer", "Wave 1", "Wave 2", "Wave 3", "Wave 4", "Wave 5+"], title: { text: "Months" }
                },
                yaxis: {
                    title: { text: "Percentage (%)" }
                },

            };
            new ApexCharts(document.querySelector("#dash6line-chart"), lineChartOptions).render();

            // Pie Chart (Repayments vs Amount Due)
            var pieChartOptions = {
                series: [1713221, 7213417], // Principal & Interest
                chart: {
                    type: 'pie',
                    height: 430,
                    zoom: { enabled: false },
                    toolbar: { show: false }
                },
                labels: ['Repayments', 'Amount Due'],
                colors: ['#43b02a', '#89D87F'],
                legend: {
                    position: 'top',
                    fontSize: '10px',
                    labels: { colors: '#333' },
                    markers: { width: 12, height: 12, radius: 12 }
                },
                dataLabels: {
                    enabled: true,
                    // formatter: (val, opts) => opts.w.globals.series[opts.seriesIndex].toLocaleString(),
                    // style: { fontSize: '10px', colors: ['#fff'] }
                },
                tooltip: {
                    y: { formatter: (val) => "TZS " + val.toLocaleString() + " mil" }
                },
                stroke: { show: true, width: 2, colors: ['#fff'] },
                plotOptions: {
                    pie: {
                        expandOnClick: true,
                        donut: {
                            size: '50%',
                            labels: {
                                show: false,
                                total: {
                                    show: true,
                                    fontSize: '10px',
                                    fontWeight: 'bold',
                                    color: '#fff',
                                    label: 'Total Loans',
                                    formatter: (w) => "TZS " + w.globals.seriesTotals.reduce((a, b) => a + b, 0).toLocaleString() + "mil"
                                }
                            }
                        }
                    }
                },

            };
            new ApexCharts(document.querySelector("#dash6pie-chart"), pieChartOptions).render();

            // Bar Chart (Number of Customers)
            var barChartOptions = {
                chart: {
                    type: "bar",
                    height: 430,
                    zoom: { enabled: false },
                    toolbar: { show: false }
                },
                colors: ['#43b02a'],
                series: [{
                    name: "Customers",
                    data: [120494, 56443, 29111, 15589, 7754, 8025]
                }],
                xaxis: {
                    categories: ["New Customer", "Wave 1", "Wave 2", "Wave 3", "Wave 4", "Wave 5+"]
                },
                yaxis: {
                    title: { text: "Number of Customers" }
                },
                plotOptions: {
                    bar: { horizontal: true, barHeight: '70%', borderRadius: 5, columnWidth: '50%' }
                }
            };
            new ApexCharts(document.querySelector("#dash6bar-chart"), barChartOptions).render();

        });
    </script>



    <!-- //dashboard 7 scripts -->
    <script>

        const months = ["May", "Jun", "Jul", "Aug", "Sep", "Oct"];
        const neverPaid = [109, 22947, 26288, 13060, 10273, 0];
        const paid = [922, 54568, 62739, 26504, 12895, 1324];
        const totalAccounts = neverPaid.map((np, i) => np + paid[i]);
        const neverPaidPercentage = neverPaid.map((np, i) => ((np / totalAccounts[i]) * 100).toFixed(2));

        // Generate HTML Table
        function generateTable() {
            let tableHTML = `
  <h2 style="text-align: center; margin-bottom: 10px; font-weight: bold; color:#43b02a">Loan Payment Status</h2>
    <table border="1" cellpadding="5" cellspacing="0" 
      style="border-collapse: collapse; width: 100%; text-align: center; border: 2px solid green;">
      <thead>
        <tr style="background-color: #43b02a; font-weight: bold; text-align: center; color:#fff">
          <th style="border: 2px solid green;">Disbursement Month</th>
          <th style="border: 2px solid green;">Never Paid</th>
          <th style="border: 2px solid green;">Paid</th>
          <th style="border: 2px solid green;">Total Accounts</th>
          <th style="border: 2px solid green;">Never Paid (%)</th>
        </tr>
      </thead>
      <tbody>
  `;

            months.forEach((month, i) => {
                tableHTML += `
      <tr>
        <td style="border: 2px solid green;">${month}</td>
        <td style="border: 2px solid green;">${neverPaid[i]}</td>
        <td style="border: 2px solid green;">${paid[i]}</td>
        <td style="border: 2px solid green;">${totalAccounts[i]}</td>
        <td style="border: 2px solid green;">${neverPaidPercentage[i]}%</td>
      </tr>
    `;
            });

            tableHTML += `</tbody></table>`;  // Wrap in a template literal

            document.getElementById("dash7data-table").innerHTML = tableHTML;
        }

        generateTable();


        var stackedBarChart = new ApexCharts(document.querySelector("#dash7stacked-bar"), {
            chart: { type: "bar", stacked: true, height: 430, toolbar: { show: false }, zoom: { enabled: false } },
            colors: ['#43b02a', '#89D87F'],
            fontWeight: 'medium',
            series: [
                { name: "Never Paid", data: neverPaid },
                { name: "Paid", data: paid }
            ],
            xaxis: { categories: months, title: { text: "Months" } },
            yaxis: { title: { text: "Number of Accounts" } },
            legend: { position: "top" },
            dataLabels: { enabled: true },
            plotOptions: {
                bar: { horizontal: true, borderRadius: 5, columnWidth: '50%', barHeight: '70%' }
            },
            title: {
                text: "Loan Repayment Status",
                align: 'center',
                style: {
                    fontSize: '14px',
                    fontWeight: 'bold',
                    color: '#43b02a'
                }
            }
        });
        stackedBarChart.render();

        var groupedBarChart = new ApexCharts(document.querySelector("#dash7grouped-bar"), {
            chart: { type: "bar", stacked: true, height: 430, toolbar: { show: false }, zoom: { enabled: false } },
            colors: ['#43b02a', '#89D87F'],
            fontSize: '10px',
            fontFamily: 'Roboto',
            fontWeight: 'medium',
            series: [
                { name: "Never Paid", data: neverPaid },
                { name: "Paid", data: paid }
            ],
            xaxis: { categories: months, title: { text: "Months" } },
            yaxis: { title: { text: "Number of Accounts" } },
            legend: { position: "top" },
            dataLabels: { enabled: true },
            plotOptions: {
                bar: { horizontal: false, borderRadius: 5, columnWidth: '50%', barHeight: '70%' }
            },
            title: {
                text: "Loan Repayment Status",
                align: 'center',
                style: {
                    fontSize: '14px',
                    fontWeight: 'bold',
                    color: '#43b02a',

                }
            },
        });
        groupedBarChart.render();

        var lineChart = new ApexCharts(document.querySelector("#dash7line-chart"), {
            chart: { type: "line", height: 430, toolbar: { show: false }, zoom: { enabled: false } },
            colors: ['#43b02a'],
            fontSize: '10px',
            fontFamily: 'Roboto',
            fontWeight: 'medium',
            series: [{ name: "Never Paid %", data: neverPaidPercentage }],
            xaxis: { categories: months, title: { text: "Months" } },
            yaxis: { title: { text: "Percentage (%)" } },
            markers: { size: 5 },
            stroke: { width: 3, curve: "smooth" },
            legend: { position: "top" },
            title: {
                text: "Loan Repayment Status",
                align: 'center',
                style: {
                    fontSize: '14px',
                    fontWeight: 'bold',
                    color: '#43b02a',
                }
            }
        });
        lineChart.render();


    </script>

    <!-- //dashboard 8 scripts -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var optionsLine = {
                chart: {
                    type: "line", height: 430, toolbar: { show: false }, zoom: { enabled: false },
                    fontSize: '10px',
                    fontFamily: 'Roboto',
                    fontWeight: 'medium',
                },
                colors: ['#43b02a', '#89D87F'],
                series: [
                    { name: "Paid", data: [97.2, 93.35, 94.09, 95.13, 98.83, 94.66] },
                    { name: "Totals", data: [88.8, 62.02, 66.31, 67.55, 72.82, 66.08] }
                ],
                legend: { position: "top" },
                dataLabels: { enabled: false },
                xaxis: { categories: ["May", "Jun", "Jul", "Aug", "Sep", "Oct"], title: { text: "Months" } },
                yaxis: { title: { text: "Percentage (%)" } },
                title: {
                    text: "Paid vs Totals",
                    align: 'center',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        color: '#43b02a'
                    }
                }
            };
            new ApexCharts(document.querySelector("#dash8chartLine"), optionsLine).render();

            var optionsBar = {
                chart: {
                    type: "bar", stacked: true, height: 430, toolbar: { show: false }, zoom: { enabled: false },
                    fontSize: '10px',
                    fontFamily: 'Roboto',
                    fontWeight: 'medium',
                },
                colors: ['#43b02a', '#89D87F'],
                series: [
                    { name: "Paid", data: [97.2, 93.35, 94.09, 95.13, 98.83, 94.66] },
                    { name: "Totals", data: [88.8, 62.02, 66.31, 67.55, 72.82, 66.08] }
                ],
                legend: { position: "top" },
                dataLabels: { enabled: true },
                plotOptions: {
                    bar: { horizontal: true, borderRadius: 5, columnWidth: '50%', barHeight: '70%' }
                },
                xaxis: { categories: ["May", "Jun", "Jul", "Aug", "Sep", "Oct"], title: { text: "Months" } },
                yaxis: { title: { text: "Percentage (%)" } },
                title: {
                    text: "Paid vs Totals",
                    align: 'center',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        color: '#43b02a'
                    }
                }
            };
            new ApexCharts(document.querySelector("#dash8chartBar"), optionsBar).render();

            var optionsPie = {
                chart: {
                    type: "pie", height: 430, toolbar: { show: false }, zoom: { enabled: false },
                    fontSize: '10px',
                    fontFamily: 'Roboto',
                    fontWeight: 'medium',
                },
                colors: ['#43b02a', '#89D87F'],
                series: [94.66, 66.08],
                labels: ["Paid", "Totals"],
                legend: { position: "top" },
                dataLabels: { enabled: true },
                title: {
                    text: "Paid vs Totals",
                    align: 'center',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        color: '#43b02a'
                    }
                }
            };
            new ApexCharts(document.querySelector("#dash8chartPie"), optionsPie).render();

            var optionsArea = {
                chart: {
                    type: "area", height: 430, toolbar: { show: false }, zoom: { enabled: false },
                    fontSize: '10px',
                    fontFamily: 'Roboto',
                    fontWeight: 'medium',
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        shade: 'light', // Softer than 'dark'
                        shadeIntensity: 0.5,
                        gradientToColors: ['#43b02a', '#89D87F'], // Same as base colors
                        inverseColors: false,
                        opacityFrom: 0.9,
                        opacityTo: 0.4,
                        stops: [0, 50, 100],
                        colorStops: [
                            {
                                offset: 0,
                                color: '#43b02a',
                                opacity: 0.9
                            },
                            {
                                offset: 100,
                                color: '#43b02a',
                                opacity: 0.2
                            }
                        ]
                    },

                },
                stroke: {
                    curve: 'smooth',
                    width: 2.5,
                    lineCap: 'round'
                },
                colors: ['#43b02a', '#89D87F'],
                series: [
                    { name: "Paid", data: [97.2, 93.35, 94.09, 95.13, 98.83, 94.66] },
                    { name: "Totals", data: [88.8, 62.02, 66.31, 67.55, 72.82, 66.08] }
                ],
                xaxis: { categories: ["May", "Jun", "Jul", "Aug", "Sep", "Oct"], title: { text: "Months" } },
                yaxis: { title: { text: "Percentage (%)" } },
                title: {
                    text: "Paid vs Totals",
                    align: 'center',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        color: '#43b02a'
                    }
                },
                legend: { position: "top" },
                dataLabels: { enabled: false },
            };
            new ApexCharts(document.querySelector("#dash8chartArea"), optionsArea).render();
        });
    </script>


    <!-- //dashboard 9 scripts -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // Line Chart: Paid vs Never Paid Over Waves
            var optionsLine = {
                chart: { type: "line", height: 430, toolbar: { show: false }, zoom: { enabled: false } },
                series: [
                    {
                        name: "Paid",
                        data: [94, 94, 95, 97, 98, 100]
                    },
                    {
                        name: "Never Paid",
                        data: [0, 0, 0, 0, 0, 0]
                    }
                ],
                colors: ['#43b02a', '#89D87F'],
                fontSize: '10px',
                fontFamily: 'Roboto',
                fontWeight: 'medium',
                legend: { position: "top" },
                dataLabels: { enabled: false },
                xaxis: { categories: ["Wave0", "Wave1", "Wave2", "Wave3", "Wave4", "Wave5+"], title: { text: "Waves" } },
                yaxis: { title: { text: "Percentage (%)" } },
                title: {
                    text: "Paid vs Never Paid Over Waves",
                    align: 'center',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        color: '#43b02a'
                    }
                }
            };
            new ApexCharts(document.querySelector("#dash9chartLine"), optionsLine).render();

            // Bar Chart: Distribution of Paid vs Never Paid
            var optionsBar = {
                chart: { type: "bar", stacked: true, height: 430, toolbar: { show: false }, zoom: { enabled: false } },
                series: [
                    {
                        name: "Never Paid",
                        data: [34.23, 29.98, 30.90, 36.22, 39.64, 32.09]
                    },
                    {
                        name: "Paid",
                        data: [65.77, 70.02, 69.10, 63.78, 60.36, 67.91]
                    }
                ],
                colors: ['#43b02a', '#89D87F'],
                fontSize: '10px',
                fontFamily: 'Roboto',
                fontWeight: 'medium',
                xaxis: { categories: ["Wave0", "Wave1", "Wave2", "Wave3", "Wave4", "Wave5+"], title: { text: "Waves" } },
                yaxis: { title: { text: "Percentage (%)" } },
                legend: { position: "top" },
                dataLabels: { enabled: true },
                plotOptions: {
                    bar: { horizontal: false, borderRadius: 5, columnWidth: '50%', barHeight: '70%' }
                },
                title: {
                    text: "Distribution of Paid vs Never Paid Over Waves",
                    align: 'center',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        color: '#43b02a'
                    }
                }
            };
            new ApexCharts(document.querySelector("#dash9chartBar"), optionsBar).render();

            // Pie Chart: Distribution of Paid vs Never Paid in Grand Total
            var optionsPie = {
                chart: { type: "pie", height: 430, toolbar: { show: false }, zoom: { enabled: false } },
                series: [66.95, 33.05],  // Paid and Never Paid percentage in Grand Total
                labels: ["Paid", "Never Paid"],
                colors: ['#43b02a', '#89D87F'],
                fontSize: '10px',
                fontFamily: 'Roboto',
                fontWeight: 'medium',
                legend: { position: "top" },
                dataLabels: { enabled: true },
                title: {
                    text: "Distribution of Paid vs Never Paid (Grand Total)",
                    align: 'center',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        color: '#43b02a'
                    }
                }
            };
            new ApexCharts(document.querySelector("#dash9chartPie"), optionsPie).render();

            // Area Chart: Subjects (Paid vs Never Paid)
            var optionsArea = {
                chart: { type: "area", height: 430, toolbar: { show: false }, zoom: { enabled: false } },
                series: [
                    {
                        name: "Never Paid",
                        data: [41248, 16921, 8995, 5647, 3074, 2569]
                    },
                    {
                        name: "Paid",
                        data: [79246, 39522, 20114, 9940, 4680, 5134]
                    }
                ],
                colors: ['#43b02a', '#89D87F'],
                fontSize: '10px',
                fontFamily: 'Roboto',
                fontWeight: 'medium',
                xaxis: { categories: ["Wave0", "Wave1", "Wave2", "Wave3", "Wave4", "Wave5+"], title: { text: "Waves" } },
                yaxis: { title: { text: "Number of Accounts" } },
                legend: { position: "top" },
                dataLabels: { enabled: false },
                title: {
                    text: "Subjects (Paid vs Never Paid)",
                    align: 'center',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        color: '#43b02a'
                    }
                }
            };
            new ApexCharts(document.querySelector("#dash9chartArea"), optionsArea).render();

        });
    </script>

    <!-- //dashboard 10 scripts -->

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var options = {
                series: [
                    { name: 'Jan', data: [0, 1, 3, 5, 6, 7] },
                    { name: 'Feb', data: [0, 2, 3, 4, 5, null] },
                    { name: 'Mar', data: [0, 1, 2, 3, null, null] },
                    { name: 'Apr', data: [0, 2, 4, null, null, null] },
                    { name: 'May', data: [0, 1, null, null, null, null] },
                    { name: 'Jun', data: [0, null, null, null, null, null] },
                    // { name: 'Jul', data: [0, 2, 3, 5, 6, 7] },
                    // { name: 'Aug', data: [0, 2, 4, 6, 7, 8] },
                    // { name: 'Sep', data: [0, 1, 2, 3, 4, 5] },
                    // { name: 'Oct', data: [0, 1, 2, 3, 3, 4] },
                    // { name: 'Nov', data: [0, 1, 2, 2, 3, 4] },
                    // { name: 'Dec', data: [0, 1, 1, 2, 3, 4] }
                ],
                chart: {
                    type: 'line',
                    height: 400,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    fontFamily: 'Roboto',
                },
                colors: [
                    '#ec4899',  // Pink
                    '#14b8a6',  // Turquoise
                    '#f97316',  // Orange
                    '#22d3ee',  // Sky Blue
                    '#4ade80',  // Light Green
                    '#a855f7'   // Purple
                    // '#10b981', '#6366f1', '#f59e0b', '#ef4444',
                    // '#3b82f6', '#8b5cf6', 
                    // '#ec4899', '#14b8a6',
                    // '#f97316', '#22d3ee', '#4ade80', '#a855f7'
                ],

                stroke: {
                    curve: 'smooth',
                    width: 4
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    title: { text: "Months on Books" }
                },
                yaxis: {
                    title: { text: "Cumulative Bad Rate (%)" },
                    min: 0,
                    max: 10,
                    labels: {
                        formatter: function (value) {
                            return value + "%";
                        }
                    }
                },
                legend: {
                    position: "top"
                },
                dataLabels: {
                    enabled: false
                },
                title: {
                    text: "Vintage Analysis by Month (2024)",
                    align: 'center',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        color: '#10b981'
                    }
                }
            };

            new ApexCharts(document.querySelector("#dash10vintageChart"), options).render();
        });
    </script> --}}

   
    <script>
    
        //document.addEventListener('DOMContentLoaded', function () {
        var options = {
            chart: {
                type: 'donut',
                height: 250,
                width : '95%'
            },           

            series: [
                {{ $data['totalInstitutions'] }},
                {{ $data['activeInstitutions'] }},
                {{ $data['inactiveInstitutions'] }},                
            ],
            labels: [
                'Total',
                'Active',
                'Inactive',
            ],
            colors: [
                '#71dd37',  // Pending (Bright Green)
                '#1EA24A',  // Analysis (Dark Green)
                '#4CAF50',  // Offered (Medium Green)
                //'#81C784',  // Accepted (Light Green)
                //'#388E3C',  // Declined (Forest Green)
            ],
            title: {
                text: 'Institution Status Summary'
            }
        };

        var chart = new ApexCharts(document.querySelector("#institutionStatusChart"), options);
        chart.render();
        //});
    
</script>
   

</div>
