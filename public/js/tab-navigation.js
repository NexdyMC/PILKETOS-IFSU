$(function () {
    highlightActiveTab();
    initAOS();

    $(document).on('click', '.tab-link', function (e) {
        e.preventDefault();
        loadTab($(this).attr('href'), $(this).data('tab'));
    });

    $(window).on('popstate', function () {
        const params = new URLSearchParams(window.location.search);
        const tab = params.get('tab') || 'home';
        loadTab(`/admin/dashboard?tab=${tab}`, tab, false);
    });

    function loadTab(url, tab, pushState = true) {
        $('#loading-bar').css('width', '30%');

        $.ajax({
            url: url,
            type: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (html) {
                $('#loading-bar').css('width', '100%');

                $('#tab-content').fadeOut(100, function () {
                    $(this).html(html).fadeIn(150);
                });

                $('#page-title').text(capitalize(tab));
                if (pushState) history.pushState({}, '', url);
                highlightActiveTab(tab);

                setTimeout(() => $('#loading-bar').css('width', '0'), 300);
            },
            error: function () {
                $('#loading-bar').css('width', '0');
                Swal.fire('Oops!', 'Gagal memuat halaman.', 'error');
            }
        });
    }

    function highlightActiveTab(tab = null) {
        if (!tab) {
            const params = new URLSearchParams(window.location.search);
            tab = params.get('tab') || 'home';
        }
        $('.tab-link').removeClass('bg-slate-700');
        $(`.tab-link[data-tab="${tab}"]`).addClass('bg-slate-700');
    }

    function initAOS() {
        if (typeof AOS !== 'undefined') {
            AOS.init({ duration: 600, once: true });
        }
    }

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
});