<!--
*
*  INSPINIA - Responsive Admin Theme
*  version 2.7
*
-->

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>INSPINIA | Dashboard</title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('font-awesome/css/font-awesome.css') }}">

    <!-- Toastr style -->
    <link rel="stylesheet" href="{{ asset('css/plugins/toastr/toastr.min.css') }}">

    <!-- Gritter -->
    <link rel="stylesheet" href="{{ asset('js/plugins/gritter/jquery.gritter.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('css/plugins/chosen/bootstrap-chosen.css') }}">

    <link rel="stylesheet" href="{{ asset('css/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <style>
        .loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url("{{ asset('/img/3.gif')}}") 50% 50% no-repeat rgb(249,249,249) ;
            opacity: .8;
            background-size:180px 160px;
        }
    </style>
</head>

<body>
    <div id = "myDiv" style="display:none;" class="loader"></div>
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element"> 
                            {{-- <span>
                            <img alt="image" class="img-circle" src="img/profile_small.jpg" />
                             </span> --}}
                            {{-- <a data-toggle="dropdown" class="dropdown-toggle" href="#"> --}}
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{ auth()->user()->name }}</strong>
                        </div>
                    </li>
                    @if (auth()->user()->position != "Plant Analyst")
                        <li class="">
                            <a href="{{url('/home')}}"><i class="fa fa-th-large"></i> <span class="nav-label">Complete Monitoring</span></a>
                        </li>
                        <li>
                            <a href="index.html"><i class="fa fa-th-large"></i> <span class="nav-label">Summary</span> <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li class="active"><a href="{{url('/cott_summary')}}">Cottonii Summary And Charts</a></li>
                                <li class="active"><a href="{{url('/spi_summary')}}">Spinosum Summary And Charts</a></li>
                                <li class="active"><a href="{{url('/summary_suppliers')}}">Suppliers Summary Setup</a></li>
                            </ul>
                        </li>
                    @endif
                    <li>
                        <a href="{{url('/quality')}}"><i class="fa fa-th-large"></i> <span class="nav-label">Quality</span></a>
                    </li>
                </ul>

            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <a href="#" onclick="redirectToSystem1()">
                                <span class="m-r-sm text-muted welcome-message">Menu</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}">
                                <i class="fa fa-sign-out"></i> Log out
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            @yield('content')
        </div>
    </div>
    
    <!-- Mainly scripts -->
    <script src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset('js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

    <!-- Flot -->
    <script src="{{ asset('js/plugins/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('js/plugins/flot/jquery.flot.tooltip.min.js') }}"></script>
    <script src="{{ asset('js/plugins/flot/jquery.flot.spline.js') }}"></script>
    <script src="{{ asset('js/plugins/flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('js/plugins/flot/jquery.flot.pie.js') }}"></script>

    <!-- Peity -->
    <script src="{{ asset('js/plugins/peity/jquery.peity.min.js') }}"></script>
    <script src="{{ asset('js/demo/peity-demo.js') }}"></script>

    <!-- Custom and plugin javascript -->
    <script src="{{ asset('js/inspinia.js') }}"></script>
    <script src="{{ asset('js/plugins/pace/pace.min.js') }}"></script>
    
    <!-- jQuery UI -->
    <script src="{{ asset('js/plugins/jquery-ui/jquery-ui.min.js') }}"></script>

    <!-- GITTER -->
    <script src="{{ asset('js/plugins/gritter/jquery.gritter.min.js') }}"></script>

    <!-- Sparkline -->
    <script src="{{ asset('js/plugins/sparkline/jquery.sparkline.min.js') }}"></script>

    <!-- Sparkline demo data  -->
    <script src="{{ asset('js/demo/sparkline-demo.js') }}"></script>

    <!-- ChartJS-->
    <script src="{{ asset('js/plugins/chartJs/Chart.min.js') }}"></script>

    <!-- Toastr -->
    <script src="{{ asset('js/plugins/toastr/toastr.min.js') }}"></script>

    <script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>

    <script src="{{ asset('js/plugins/chosen/chosen.jquery.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>


    <style>
        div.dataTables_wrapper div.dataTables_length label {
            font-weight: normal;
            text-align: left;
            white-space: nowrap;
        }
        div.dataTables_wrapper div.dataTables_length select {
            width: 75px;
            display: inline-block;
        }
        div.dataTables_wrapper div.dataTables_filter {
            text-align: right;
        }
        div.dataTables_wrapper div.dataTables_filter label {
            font-weight: normal;
            white-space: nowrap;
            text-align: left;
        }
        div.dataTables_wrapper div.dataTables_filter input {
            margin-left: 0.5em;
            display: inline-block;
            width: auto;
            vertical-align: middle;
        }
        div.dataTables_wrapper div.dataTables_paginate {
            margin: 0;
            white-space: nowrap;
            text-align: right;
        }
        div.dataTables_wrapper div.dataTables_paginate ul.pagination {
            margin: 2px 0;
            white-space: nowrap;
        }
        table.dataTable {
            clear: both;
            margin-top: 6px !important;
            margin-bottom: 6px !important;
            max-width: none !important;
            border-collapse: separate !important;
        }
        .dataTables_empty {
            text-align: center;
        }
        .dataTables_wrapper {
            padding-bottom: 0px;
        }
        .mb-10 {
            margin-bottom: 10px;
        }
    </style>
    <script>
         function show() {
            document.getElementById("myDiv").style.display="block";
        }
        window.addEventListener('load', function () {
            document.getElementById("myDiv").style.display = "none";
        });

        window.addEventListener('pageshow', function (event) {
            document.getElementById("myDiv").style.display = "none";
        });

        $(document).ready(function(){
            $('.datatables-sample').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    {extend: 'csv', title: 'User List'},
                    {extend: 'excel', title: 'User List'},
                    {extend: 'pdf', title: 'User List'},
                ]
            });

                $('.year-picker').datepicker({
                minViewMode: 2,
                format: 'yyyy'
            });
        });

        $('.chosen-select').chosen({width: "100%"});

        function redirectToSystem1() {
            // const token = sessionStorage.getItem('api_token');
            const token = "{{ session('api_token') }}";
            
            if (token) {
                window.location.href = `https://sourcing-plan.wsystem.online/go-to-menu?token=${token}`;
            } else {
                window.location.href = `https://sourcing-plan.wsystem.online/login`;
            }
        }
        // function redirectToSystem1() {
        //     const token = sessionStorage.getItem('api_token');
            
        //     if (token) {
        //         window.location.href = `http://localhost/sourcing_plan/public/go-to-menu`;
        //     } else {
        //         window.location.href = `http://localhost/sourcing_plan/public/login`;
        //     }
        // }
    </script>
</body>
</html>
