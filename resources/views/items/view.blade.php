<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>Mars comm - hr</title>
    <!-- Simple bar CSS -->
    <link rel="stylesheet" href="{{ asset('css/simplebar.css') }}">
    <!-- Fonts CSS -->
    <link
        href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="{{ asset('css/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('css/uppy.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery.steps.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery.timepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/quill.snow.css') }}">
    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}">
    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('css/app-light.css') }}" id="lightTheme">
    <link rel="stylesheet" href="{{ asset('css/app-dark.css') }}" id="darkTheme" disabled>
</head>

<body class="vertical light">
    <div class="wrapper">

        @include('layouts.navigation')

        <aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
            <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3"
                data-toggle="toggle">
                <i class="fe fe-x"><span class="sr-only"></span></i>
            </a>
            <nav class="vertnav navbar navbar-light">
                <!-- nav bar -->
                <div class="w-100 mb-4 d-flex">
                    <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="./index.html">

                        <img src="{{ asset('images/marslogo.png') }}" class="navbar-brand-img" alt="" style="height: 60px">
                    </a>
                </div>

                <ul class="navbar-nav flex-fill w-100">
                    <li class="nav-item">
                        <a href="{{ url('/dashboard') }}" class=" nav-link">
                            <i class="fe fe-home fe-16"></i>
                            <span class="ml-3 item-text">Dashboard</span><span
                                class="badge badge-pill badge-primary">New</span>
                        </a>

                    </li>
                </ul>

                <p class="text-muted nav-heading mt-4 mb-1">
                    <span>Manage Assets</span>
                </p>


                <ul class="navbar-nav flex-fill w-100">
                    <li class="nav-item dropdown active">
                        <a href="#dashboard" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle nav-link">
                            <i class="fe fe-archive fe-16"></i>
                            <span class="ml-3 item-text">Items</span><span class="sr-only">(current)</span>
                        </a>
                        <ul class="collapse list-unstyled pl-4 w-100" id="dashboard">
                            <li class="nav-item active">
                                <a class="nav-link pl-3" href="#"><span
                                        class="ml-1 item-text">Item With
                                        Cost</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link pl-3" href="./dashboard-analytics.html"><span
                                        class="ml-1 item-text">Item Without Cost</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link pl-3" href="./dashboard-sales.html"><span
                                        class="ml-1 item-text">Inventory Assembly</span></a>
                            </li>

                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#ui-elements" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle nav-link">
                            <i class="fe fe-box fe-16"></i>
                            <span class="ml-3 item-text">Assets</span>
                        </a>
                        <ul class="collapse list-unstyled pl-4 w-100" id="ui-elements">
                            <li class="nav-item">
                                <a class="nav-link pl-3" href="./ui-color.html"><span class="ml-1 item-text">Asset
                                        Register</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link pl-3" href="./ui-typograpy.html"><span class="ml-1 item-text">Asset
                                        Tracking</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link pl-3" href="./ui-icons.html"><span class="ml-1 item-text">Asset
                                        Disposal</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link pl-3" href="./ui-buttons.html"><span
                                        class="ml-1 item-text">Disposed Asset</span></a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a href="#forms" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle nav-link">
                            <i class="fe fe-credit-card fe-16"></i>
                            <span class="ml-3 item-text">Sales</span>
                        </a>
                        <ul class="collapse list-unstyled pl-4 w-100" id="forms">
                            <li class="nav-item">
                                <a class="nav-link pl-3" href="./form_elements.html"><span
                                        class="ml-1 item-text">Customers</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link pl-3" href="./form_advanced.html"><span
                                        class="ml-1 item-text">Cash Sales</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link pl-3" href="./form_validation.html"><span
                                        class="ml-1 item-text">Invoice</span></a>
                            </li>

                        </ul>
                    </li>


                </ul>
                <p class="text-muted nav-heading mt-4 mb-1">
                    <span>Management</span>
                </p>
                <ul class="navbar-nav flex-fill w-100">

                    <li class="nav-item dropdown">
                        <a href="#contact" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle nav-link">
                            <i class="fe fe-book fe-16"></i>
                            <span class="ml-3 item-text">Loan Manager</span>
                        </a>
                        <ul class="collapse list-unstyled pl-4 w-100" id="contact">
                            <a class="nav-link pl-3" href="./contacts-list.html"><span
                                    class="ml-1">Customers</span></a>
                            <a class="nav-link pl-3" href="./contacts-grid.html"><span class="ml-1">Loan
                                    Application</span></a>
                            <a class="nav-link pl-3" href="./contacts-new.html"><span class="ml-1">Loan
                                    Approval</span></a>
                            <a class="nav-link pl-3" href="./contacts-new.html"><span class="ml-1">Second Loan
                                    Approval</span></a>
                        </ul>
                    </li>


                </ul>
                <p class="text-muted nav-heading mt-4 mb-1">
                    <span>Settings</span>
                </p>
                <ul class="navbar-nav flex-fill w-100">
                    <li class="nav-item dropdown">
                        <a href="#pages" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle nav-link">
                            <i class="fe fe-file fe-16"></i>
                            <span class="ml-3 item-text">Reports</span>
                        </a>
                        <ul class="collapse list-unstyled pl-4 w-100 w-100" id="pages">
                            <li class="nav-item">
                                <a class="nav-link pl-3" href="./page-orders.html">
                                    <span class="ml-1 item-text">Detailed Report</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link pl-3" href="./page-timeline.html">
                                    <span class="ml-1 item-text">Summarized Report</span>
                                </a>
                            </li>

                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav flex-fill w-100">
                    <li class="nav-item dropdown">
                        <a href="#settings" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle nav-link">
                            <i class="fe fe-settings fe-16"></i>
                            <span class="ml-3 item-text">Settings</span>
                        </a>
                        <ul class="collapse list-unstyled pl-4 w-100 w-100" id="settings">
                            <li class="nav-item">
                                <a class="nav-link pl-3" href="{{ route('users.index') }}">
                                    <span class="ml-1 item-text">All Users</span>
                                </a>
                            </li>

                        </ul>
                    </li>
                </ul>


            </nav>
        </aside>

        <main role="main" class="main-content">
            @include('elements.spinner')
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12">

                        <div class="row align-items-center mb-3 border-bottom no-gutters">
                            <div class="col">
                                <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                            role="tab" aria-controls="home" aria-selected="true">Item Details</a>
                                    </li>

                                </ul>
                            </div>
                            <div class="col-auto">

                                <button type="button" class="btn btn-sm" onclick="reloadPage()">
                                    <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                                </button>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="card shadow mb-4">
                                    <div class="card-header">
                                        <strong class="card-title">{{ $item->item_name }}</strong>
                                        <span class="float-right badge badge-pill badge-success text-white"
                                                >{{ $item->item_type }}</span>
                                    </div>
                                    <div class="card-body">

                                        <dl class="row mb-0">
                                            <dt class="col-sm-2 mb-3 text-muted">Barcode</dt>
                                            <dd class="col-sm-4 mb-3">{{ $item->code }}</dd>
                                            <dt class="col-sm-2 mb-3 text-muted">Category</dt>
                                            <dd class="col-sm-4 mb-3">{{ $item->category }}</dd>
                                            <dt class="col-sm-2 mb-3 text-muted">Income</dt>
                                            <dd class="col-sm-4 mb-3">
                                                <span class="badge badge-pill badge-danger">{{ $item->income }}</span>

                                            </dd>
                                            <dt class="col-sm-2 mb-3 text-muted">Account Expenses</dt>
                                            <dd class="col-sm-4 mb-3">{{ $item->expense }}</dd>
                                            <dt class="col-sm-2 mb-3 text-muted">Reorder Point</dt>
                                            <dd class="col-sm-4 mb-3">
                                                <span class="bg-warning mr-2"></span> {{ $item->unit }}
                                            </dd>
                                            <dt class="col-sm-2 mb-3 text-muted">On Hand</dt>
                                            <dd class="col-sm-4 mb-3">
                                                <span class="dot dot-md bg-warning mr-2"></span> {{ $item->reorder }}
                                            </dd>
                                            <dt class="col-sm-2 mb-3 text-muted">As of Date</dt>
                                            <dd class="col-sm-4 mb-3">{{ $item->created_at }}</dd>
                                            <dt class="col-sm-2 mb-3 text-muted">Last Update</dt>
                                            <dd class="col-sm-4 mb-3">{{ $item->updated_at }}</dd>
                                            <dt class="col-sm-2 mb-3 text-muted">Expire date</dt>
                                            <dd class="col-sm-4 mb-3">{{ $item->expire_date }}</dd>
                                            <dt class="col-sm-2 mb-3 text-muted">Last updated by</dt>
                                            <dd class="col-sm-4 mb-3">
                                                <strong>{{ $item->created_by }}</strong>
                                            </dd>

                                            <dt class="col-sm-2 text-muted">Description</dt>
                                            <dd class="col-sm-10"> {{ $item->description }} </dd>
                                        </dl>


                                    </div> <!-- .card-body -->
                                </div> <!-- .card -->

                            </div> <!-- .col-md -->

                        </div> <!-- .col-md -->




                    </div> <!-- .col-12 -->
                </div> <!-- .row -->
            </div> <!-- .container-fluid -->






        </main> <!-- main -->
    </div> <!-- .wrapper -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/simplebar.min.js') }}"></script>
    <script src='{{ asset('js/daterangepicker.js') }}'></script>
    <script src='{{ asset('js/jquery.stickOnScroll.js') }}'></script>
    <script src="{{ asset('js/tinycolor-min.js') }}"></script>
    <script src="{{ asset('js/config.js') }}"></script>
    <script src="{{ asset('js/d3.min.js') }}"></script>
    <script src="{{ asset('js/topojson.min.js') }}"></script>
    <script src="{{ asset('js/datamaps.all.min.js') }}"></script>
    <script src="{{ asset('js/datamaps-zoomto.js') }}"></script>
    <script src="{{ asset('js/datamaps.custom.js') }}"></script>
    <script src="{{ asset('js/Chart.min.js') }}"></script>
    <script>
        /* defind global options */
        Chart.defaults.global.defaultFontFamily = base.defaultFontFamily;
        Chart.defaults.global.defaultFontColor = colors.mutedColor;
    </script>
    <script src="{{ asset('js/gauge.min.js') }}"></script>
    <script src="{{ asset('js/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('js/apexcharts.custom.js') }}"></script>
    <script src='{{ asset('js/jquery.mask.min.js') }}'></script>
    <script src='{{ asset('js/select2.min.js') }}'></script>
    <script src='{{ asset('js/jquery.steps.min.js') }}'></script>
    <script src='{{ asset('js/jquery.validate.min.js') }}'></script>
    <script src='{{ asset('js/jquery.timepicker.js') }}'></script>
    <script src='{{ asset('js/dropzone.min.js') }}'></script>
    <script src='{{ asset('js/uppy.min.js') }}'></script>
    <script src='{{ asset('js/quill.min.js') }}'></script>


    {{-- table --}}
    <script src='{{ asset('js/jquery.dataTables.min.js') }}'></script>
    <script src='{{ asset('js/dataTables.bootstrap4.min.js') }}'></script>

    <script>
        $('#dataTable-1').DataTable({
            autoWidth: true,
            "lengthMenu": [
                [5, 10, 20, -1],
                [5, 10, 20, "All"]
            ]
        });
    </script>
    <script src="js/apps.js"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="{{ asset('https://www.googletagmanager.com/gtag/js?id=UA-56159088-1') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'UA-56159088-1');
    </script>
    <script>
        $('.select2').select2({
            theme: 'bootstrap4',
        });
        $('.select2-multi').select2({
            multiple: true,
            theme: 'bootstrap4',
        });
        $('.drgpicker').daterangepicker({
            singleDatePicker: true,
            timePicker: false,
            showDropdowns: true,
            locale: {
                format: 'MM/DD/YYYY'
            }
        });
        $('.time-input').timepicker({
            'scrollDefault': 'now',
            'zindex': '9999' /* fix modal open */
        });
        /** date range picker */
        if ($('.datetimes').length) {
            $('.datetimes').daterangepicker({
                timePicker: true,
                startDate: moment().startOf('hour'),
                endDate: moment().startOf('hour').add(32, 'hour'),
                locale: {
                    format: 'M/DD hh:mm A'
                }
            });
        }
        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                    'month')]
            }
        }, cb);
        cb(start, end);
        $('.input-placeholder').mask("00/00/0000", {
            placeholder: "__/__/____"
        });
        $('.input-zip').mask('00000-000', {
            placeholder: "____-___"
        });
        $('.input-money').mask("#.##0,00", {
            reverse: true
        });
        $('.input-phoneus').mask('(000) 000-0000');
        $('.input-mixed').mask('AAA 000-S0S');
        $('.input-ip').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
            translation: {
                'Z': {
                    pattern: /[0-9]/,
                    optional: true
                }
            },
            placeholder: "___.___.___.___"
        });
        // editor
        var editor = document.getElementById('editor');
        if (editor) {
            var toolbarOptions = [
                [{
                    'font': []
                }],
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{
                        'header': 1
                    },
                    {
                        'header': 2
                    }
                ],
                [{
                        'list': 'ordered'
                    },
                    {
                        'list': 'bullet'
                    }
                ],
                [{
                        'script': 'sub'
                    },
                    {
                        'script': 'super'
                    }
                ],
                [{
                        'indent': '-1'
                    },
                    {
                        'indent': '+1'
                    }
                ], // outdent/indent
                [{
                    'direction': 'rtl'
                }], // text direction
                [{
                        'color': []
                    },
                    {
                        'background': []
                    }
                ], // dropdown with defaults from theme
                [{
                    'align': []
                }],
                ['clean'] // remove formatting button
            ];
            var quill = new Quill(editor, {
                modules: {
                    toolbar: toolbarOptions
                },
                theme: 'snow'
            });
        }
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
    <script>
        var uptarg = document.getElementById('drag-drop-area');
        if (uptarg) {
            var uppy = Uppy.Core().use(Uppy.Dashboard, {
                inline: true,
                target: uptarg,
                proudlyDisplayPoweredByUppy: false,
                theme: 'dark',
                width: 770,
                height: 210,
                plugins: ['Webcam']
            }).use(Uppy.Tus, {
                endpoint: 'https://master.tus.io/files/'
            });
            uppy.on('complete', (result) => {
                console.log('Upload complete! We’ve uploaded these files:', result.successful)
            });
        }
    </script>
    <script src="js/apps.js"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-56159088-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'UA-56159088-1');
    </script>
</body>

</html>
