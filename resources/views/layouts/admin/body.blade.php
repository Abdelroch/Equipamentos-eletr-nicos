
<!doctype html>
<html lang="pt-pt">

@include('layouts.admin.head')

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    @include('layouts.admin.menu')
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      @include('layouts.admin.header')
      <!--  Header End -->
      @yield('conteudo')
    </div>
  </div>
  @yield('style')


 <!-- jQuery + DataTables JS -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

 <script>
     $(document).ready(function() {
         $('#myTable').DataTable();
     });
 </script>

  @include('layouts.admin.scripts')


  @yield('scripts')
</body>

</html>





