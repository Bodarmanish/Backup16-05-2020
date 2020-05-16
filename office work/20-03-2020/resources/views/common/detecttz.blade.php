<script>
    var lt = "{{ session('local_timezone') }}";
    var lto = "{{ session('local_timezone_offset') }}";
    $(document).ready(function(){
        detectClientTZ(lt,lto);
    });
</script>