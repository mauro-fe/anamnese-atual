<footer class="footer py-5">
    <div class="container">

        <div class="row">
            <div class="col-8 mx-auto text-center mt-1">
                <p class="mb-0">
                    Todos os direitos reservados © <script>
                    document.write(new Date().getFullYear())
                    </script><br> Desenvolvido por <b>Mauro Felix</b>
                </p>
            </div>
        </div>
    </div>
</footer>
<!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
<!--   Core JS Files   -->
<script src="<?= BASE_URL ?>assets/js/core/popper.min.js"></script>
<script src="<?= BASE_URL ?>assets/js/core/bootstrap.min.js"></script>
<script src="<?= BASE_URL ?>assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="<?= BASE_URL ?>assets/js/plugins/smooth-scrollbar.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- jQuery (se já não tiver) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


<script>
var win = navigator.platform.indexOf('Win') > -1;
if (win && document.querySelector('#sidenav-scrollbar')) {
    var options = {
        damping: '0.5'
    }
    Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
}
</script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>

<!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
<script src="<?= BASE_URL ?>assets/js/soft-ui-dashboard.min.js?v=1.1.0"></script>
<!-- <script src="<?= BASE_URL ?>script.js"></script> -->

</body>

</html>