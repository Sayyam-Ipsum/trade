<!-- jQuery CDN - Slim version (=without AJAX) -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="{{asset('assets/site/js/jquery-1.12.4.min.js')}}"></script>
<!-- Popper.JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
<!-- Jquery Validate -->
<script src="{{asset('assets/site/js/jquery.validate.min.js')}}"></script>
<!-- Sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<!-- Datatables JS -->
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<!-- jQuery Custom Scroller CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(".dropdown-menu").click(function(e){
        e.stopPropagation();
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    $(document).ready(function () {
        $("#sidebar").mCustomScrollbar({
            theme: "minimal"
        });

        $('#dismiss, .overlay').on('click', function () {
            $('#sidebar').removeClass('active');
            $('.overlay').removeClass('active');
        });

        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').addClass('active');
            $('.overlay').addClass('active');
            $('.collapse.in').toggleClass('in');
            $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        });
    });

    function toast(title, type) {
        let bg = "#10253c";
        if (type == "success")  bg = "#5cb85c";
        if (type == "error")    bg = "#d9534f";
        if (type == "info")     bg = "#5bc0de";
        if (type == "warning")  bg = "#f0ad4e";
        Swal.fire({
            toast: true,
            title: title,
            text: '',
            type: type,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            background: bg,
            color: '#f7f7f7'
        });
    }

    @include('partials.response')
</script>
@yield('scripts');
</body>

</html>
