<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>

  @yield('cdn')
</head>
<body class="bg-slate-100">
	<!-- Navigation  : Desktop Top -->
	<header class="sticky top-0 z-40 border-b bg-navy-950/90 backdrop-blur-md border-slate-800">
		<div class="flex items-center justify-between max-w-6xl px-6 py-4 mx-auto">

		<!-- Navigation : Logo & Brand -->
		<div class="z-10 flex items-center gap-3">
			<div class="flex items-center justify-center w-12 h-12 text-lg font-bold bg-yellow-400 shadow-md rounded-xl text-navy-950">
			<i data-lucide="vote" class="w-8 h-8"></i>
			</div>
			<div>
			<h1 class="text-xl font-bold tracking-wide text-white uppercase">E-Vote <span class="text-brand-yellow">OSIS</span></h1>
			<p class="hidden text-xs text-slate-400 md:block">SMK Informatika Sumedang</p>
			<p class="block text-xs uppercase text-slate-400 md:hidden">{{ session('nama') ?? 'User' }}</p>
			</div>
		</div>
		
		<!-- Navigation : Navigation desktop & mobile top -->
		<div class="absolute left-0 right-0 items-center justify-center hidden gap-4 md:flex">

			<a href="{{ route('siswa.voting') }}" 
			class="inline-flex items-center gap-1.5 px-4 py-2 text-md font-bold transition-all 
			{{ request()->routeIs('siswa.voting') ? 'text-white bg-gradient-to-br to-amber-500 from-amber-300 rounded-lg shadow-md' : 'text-slate-300 hover:text-brand-yellow' }}">
				<i class="text-[16px] fa-solid fa-check-to-slot"></i>Voting
			</a>

			<a href="{{ route('siswa.hasil') }}" 
			class="inline-flex items-center gap-1.5 px-4 py-2 text-md font-bold transition-all 
			{{ request()->routeIs('siswa.hasil') ? 'text-white bg-gradient-to-br to-amber-500 from-amber-300 rounded-lg shadow-md' : 'text-slate-300 hover:text-brand-yellow' }}">
				<i class="fa-solid fa-chart-line"></i>Hasil
			</a>
		</div>

		<!-- Navigation : User Info & Logout -->
		<div class="z-10 flex items-center gap-3">
			<div class="hidden text-right md:block">
			<p class="text-sm font-semibold text-white uppercase">{{session('nama') ?? "Users"}}</p>
			<p class="text-xs uppercase text-slate-400">{{session('kelas') ?? "Kelas"}}</p>
			</div>

			<button type="button" onclick="logout()"
			class="flex items-center p-2 text-sm font-semibold text-white transition-colors bg-red-500 rounded-lg hover:bg-red-600 md:p-3 md:text-base">
			<i class="fa-solid fa-right-from-bracket"></i>
			<span class="hidden ml-2 sm:block">Logout</span>
			</button>
		</div>

		</div>
	</header>

	<!-- Navigation : Mobile Bottom -->
	<nav class="fixed bottom-0 left-0 right-0 z-40 px-2 py-2 border-t md:hidden bg-navy-950/90 backdrop-blur-md border-slate-800">
		<div class="flex items-center justify-center w-full gap-2">
			<a href="{{ route('siswa.voting') }}" class="inline-flex items-center gap-1.5 px-4 py-4 text-md w-1/2 justify-center font-bold transition-all {{ request()->routeIs('siswa.voting') ? 'text-white bg-gradient-to-br to-amber-500 from-amber-300 rounded-lg shadow-md' : 'text-slate-300 hover:text-brand-yellow' }}">
				<i class="text-[16px] fa-solid fa-check-to-slot"></i>Voting
			</a>
			<a href="{{ route('siswa.hasil') }}" class="inline-flex items-center gap-1.5 px-4 py-4 text-md w-1/2 justify-center font-bold transition-all {{ request()->routeIs('siswa.hasil') ? 'text-white bg-gradient-to-br to-amber-500 from-amber-300 rounded-lg shadow-md' : 'text-slate-300 hover:text-brand-yellow' }}">
				<i class="fa-solid fa-chart-line"></i>Hasil
			</a>
		</div>
	</nav>

	<main class="flex flex-col justify-center flex-1 w-full max-w-6xl px-6 py-8 mx-auto space-y-3 sm:px-6 ">
		@yield('content')
	</main>

  	<!-- popup : detail visi & misi kandidat -->
	<div id="popupModal" role="dialog" aria-modal="true" aria-labelledby="modalTitle"
		class="fixed inset-0 z-50 items-center justify-center hidden p-6 bg-slate-900/80 backdrop-blur-xs">
		<div class="relative flex flex-col w-full h-auto max-w-lg overflow-y-auto bg-white border-2 shadow-2xl md:scale-95 lg:scale-90 rounded-3xl border-slate-100">

			<!-- Close Button -->
			<button id="closeBtn" type="button" aria-label="Tutup detail kandidat"
				class="absolute z-10 flex items-center justify-center border-blue-500 rounded-full shadow top-4 right-4 w-9 h-9 text-slate-500 bg-white/90 hover:text-slate-700 hover:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
				<i class="text-[20px] fa-solid fa-xmark"></i>
			</button>

			<!-- Image Preview -->
			<div class="relative flex items-center justify-center overflow-hidden border-b aspect-video bg-slate-50 border-slate-100">
				<img id="modalImg" src="" alt="" class="object-cover w-full h-full">
			</div>

			<div class="p-4 sm:p-6">
				<!-- Title -->
				<h3 id="modalTitle" class="mb-4 text-3xl font-bold text-center text-slate-900"></h3>
				
				<!-- Visi & Misi -->
				<div class="h-48 space-y-4 overflow-y-auto text-slate-600">
					<div class="p-4 border-2 border-yellow-200 bg-yellow-50 rounded-xl">
						<h4 class="flex items-center gap-1 mb-1 text-lg font-bold uppercase text-slate-900">
							<i class="text-[20px] text-yellow-600 fa-solid fa-compass"></i> Visi
						</h4>
						<div id="modalVisi" class="text-sm leading-relaxed text-slate-600"></div>
					</div>

					<div class="p-4 border-2 border-blue-200 bg-blue-50 rounded-xl">
						<h4 class="flex items-center gap-1 mb-1 text-lg font-bold uppercase text-slate-900">
							<i class="text-[20px] text-blue-600 fa-solid fa-bullseye"></i> Misi
						</h4>
						<div id="modalMisi" class="text-sm leading-relaxed text-slate-600"></div>
					</div>
				</div>

				<!-- Actions -->
				<div class="grid items-center grid-rows-1 gap-3 pt-4 border-t border-slate-100">
					<button id="modalVoteBtn" type="button" class="flex items-center justify-center w-full gap-2 px-5 py-3 text-sm font-semibold text-blue-600 transition-colors bg-white border-2 border-blue-600 btn-vote rounded-xl hover:bg-blue-600 hover:text-white">
						<i class="text-[16px] fa-solid fa-check-to-slot"></i> Pilih Kandidat Ini
					</button>
				</div>
			</div>

		</div>
	</div>


  	<footer class="py-6 text-sm text-center border-t md:py-4 bg-slate-950 border-slate-900 text-slate-500">
		&copy; {{ date('Y') }} Febri Pratama — All rights reserved.
	</footer>

	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$(function () {
			"use strict";

			// ================== VOTING ==================
			$('#scroll-container').on('click', '.btn-vote', function (e) {
				e.stopPropagation(); // supaya klik tombol vote tidak ikut membuka popup
				const idKandidat = $(this).data('id');
				const name = $(this).data('name');
				selectKandidat(idKandidat, name);
			});

			// ================== POPUP VISI & MISI ==================
			const $popupModal   = $('#popupModal');
			const $modalTitle   = $('#modalTitle');
			const $modalImg     = $('#modalImg');
			const $modalVisi    = $('#modalVisi');
			const $modalMisi    = $('#modalMisi');
			const $modalVoteBtn = $('#modalVoteBtn');

			function openKandidatModal($card) {
				const nama       = $card.data('nama');
				const image      = $card.data('image');
				const idKandidat = $card.data('id');

				$modalTitle.text(nama);
				$modalImg.attr('src', image).attr('alt', 'Kandidat ' + nama);
				$modalVisi.html($card.find('.kandidat-visi').html() || '-');
				$modalMisi.html($card.find('.kandidat-misi').html() || '-');
				$modalVoteBtn.data('id', idKandidat).data('name', nama);

				$popupModal.removeClass('hidden').addClass('flex');
				$('body').addClass('overflow-hidden');
			}

			function closeKandidatModal() {
				$popupModal.addClass('hidden').removeClass('flex');
				$('body').removeClass('overflow-hidden');
			}

			// klik di kartu (bukan tombol vote) -> buka popup
			$(document).on('click', '.kandidat-item', function (e) {
				if ($(e.target).closest('.btn-vote').length) return;
				openKandidatModal($(this));
			});

			// aksesibilitas: buka popup dengan Enter/Space saat kartu di-fokus (bukan tombol di dalamnya)
			$(document).on('keydown', '.kandidat-item', function (e) {
				if (e.target !== this) return;
				if (e.key === 'Enter' || e.key === ' ') {
					e.preventDefault();
					openKandidatModal($(this));
				}
			});

			$(document).on('click', '.btn-kandidat-item', function (e) {
				if ($(e.target).closest('.btn-vote').length) return;
				openKandidatModal($(this));
			})

			// tombol vote di dalam popup
			$modalVoteBtn.on('click', function () {
				const idKandidat = $(this).data('id');
				const name = $(this).data('name');
				closeKandidatModal();
				selectKandidat(idKandidat, name);
			});

			$('#closeBtn, #closeModalAction').on('click', closeKandidatModal);

			$popupModal.on('click', function (e) {
				if ($(e.target).is('#popupModal')) closeKandidatModal();
			});

			$(document).on('keydown', function (e) {
				if (e.key === 'Escape' && $popupModal.is(':visible')) closeKandidatModal();
			});
		});

		function selectKandidat(idKandidat, name) {
			// 1. Tampilkan konfirmasi pakai SweetAlert2
			Swal.fire({
				title: `Pilih ${name}?`,
				text: "Pilihan tidak dapat diubah setelah dikonfirmasi.",
				icon: "question",
				showCancelButton: true,
				confirmButtonColor: "#2563eb",
				cancelButtonColor: "#414141",
				confirmButtonText: "Ya, Pilih!",
				cancelButtonText: "Batal"
			}).then(function (result) {
				if (!result.isConfirmed) return;

				// 2. Kirim vote pakai jQuery AJAX
				$.ajax({
					url: "../api/siswa.php",
					method: "POST",
					contentType: "application/json",
					data: JSON.stringify({
						type: "voting",
						id_kandidat: idKandidat,
						csrf_token: CSRF_TOKEN
					}),
					dataType: "json"
				})
				.done(function () {
					let timerInterval;

					Swal.fire({
						title: "Berhasil!",
						html: `Vote untuk ${name} berhasil disimpan.<br><br>Halaman akan dialihkan dalam <b>5</b> detik.`,
						icon: "success",
						timer: 5000,
						timerProgressBar: true,
						showConfirmButton: false,
						didOpen: () => {
							const timerElement = Swal.getHtmlContainer().querySelector("b");
							timerInterval = setInterval(() => {
								timerElement.textContent = Math.ceil(Swal.getTimerLeft() / 1000);
							}, 100);
						},
						willClose: () => {
							clearInterval(timerInterval);
						}
					}).then((result) => {
						if (result.dismiss === Swal.DismissReason.timer) {
							location.href = "../";
						}
					});
				})
				.fail(function (jqXHR) {
					console.error(jqXHR.responseText || jqXHR.statusText);
					Swal.fire({
						title: "Gagal!",
						text: "Terjadi kesalahan saat memproses voting.",
						icon: "error"
					});
				});
			});
		}
		
		function logout() {
			window.location.href = "logout.php";
		}
	</script>
</body>
</html>