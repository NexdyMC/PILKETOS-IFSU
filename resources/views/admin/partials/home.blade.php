<div>
    <h2 class="mb-4 text-2xl font-bold" data-aos="fade-up">Home</h2>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="p-4 bg-white shadow rounded-xl" data-aos="fade-up" data-aos-delay="100">
            <p class="text-sm text-gray-400">Total User</p>
            <p class="text-2xl font-bold">1,204</p>
        </div>
        <div class="p-4 bg-white shadow rounded-xl" data-aos="fade-up" data-aos-delay="200">
            <p class="text-sm text-gray-400">Total Order</p>
            <p class="text-2xl font-bold">89</p>
        </div>
        <div class="p-4 bg-white shadow rounded-xl" data-aos="fade-up" data-aos-delay="300">
            <p class="text-sm text-gray-400">Revenue</p>
            <p class="text-2xl font-bold">Rp 12.4jt</p>
        </div>
    </div>

    <div class="p-4 bg-white shadow rounded-xl" data-aos="fade-up">
        <canvas id="revenueChart" height="100"></canvas>
    </div>

    <button id="btn-test-alert" class="px-4 py-2 mt-4 text-white bg-red-500 rounded-lg hover:bg-red-600">
        Test SweetAlert
    </button>

    {{-- Script khusus tab ini --}}
    <script>
        (function () {
            // Re-init AOS setiap partial baru masuk
            if (typeof AOS !== 'undefined') AOS.refresh();

            // Hancurkan chart lama kalau ada (biar nggak duplikat pas balik ke tab ini lagi)
            if (window.revenueChartInstance) {
                window.revenueChartInstance.destroy();
            }

            const ctx = document.getElementById('revenueChart');
            window.revenueChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [{
                        label: 'Revenue',
                        data: [4, 6, 5, 8, 7, 9],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,0.1)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: { responsive: true }
            });

            $('#btn-test-alert').off('click').on('click', function () {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Ini contoh SweetAlert dari tab Home.',
                    icon: 'success'
                });
            });
        })();
    </script>
</div>