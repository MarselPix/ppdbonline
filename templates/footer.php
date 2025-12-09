</main>

<footer class="bg-white text-center text-lg-start mt-5">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
        © 2024 PPDB Online SMP Negeri 1 Bawang
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Fungsi untuk mengatur toggle lihat/sembunyikan password
    function setupPasswordToggle(toggleId, passwordId) {
        const toggleButton = document.getElementById(toggleId);
        const passwordInput = document.getElementById(passwordId);

        if (toggleButton && passwordInput) {
            toggleButton.addEventListener('click', function() {
                // Ubah tipe input password
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Ubah ikon mata (this merujuk ke toggleButton)
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });
        }
    }

    // Panggil fungsi ini setelah halaman dimuat untuk setiap kolom password
    document.addEventListener('DOMContentLoaded', function() {
        setupPasswordToggle('togglePassword', 'password');
        setupPasswordToggle('togglePasswordConfirm', 'password_confirm');
    });
</script>

</body>
</html>
