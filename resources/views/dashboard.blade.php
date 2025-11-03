<x-app-layout>



                        <div class="row align-items-center mb-3 border-bottom no-gutters">
                            <div class="col">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                            role="tab" aria-controls="home" aria-selected="true">Dashboard</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6 col-xl-3 mb-4">
                                <div class="card shadow-none">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-3 text-center">
                                                <span class="circle circle-sm bg-primary">
                                                    <i class="fe fe-16 fe-shopping-cart text-white mb-0"></i>
                                                </span>
                                            </div>
                                            <div class="col pr-0">
                                                <p class="small text-muted mb-0">Users</p>
                                                <span class="h3 mb-0">{{ $user }}</span>
                                                <span class="small text-success">{{ $user }} - Active</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3 mb-4">
                                <div class="card shadow-none border">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-3 text-center">
                                                <span class="circle circle-sm bg-primary">
                                                    <i class="fe fe-16 fe-filter text-white mb-0"></i>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <p class="small text-muted mb-0">Main Station</p>
                                                <div class="row align-items-center no-gutters">
                                                    <div class="col-auto">
                                                        <span class="h3 mr-2 mb-0">{{ $mainstation }}</span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3 mb-4">
                                <div class="card shadow-none border">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-3 text-center">
                                                <span class="circle circle-sm bg-primary">
                                                    <i class="fe fe-16 fe-activity text-white mb-0"></i>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <p class="small text-muted mb-0">Sub station</p>
                                                <span class="h3 mb-0">{{ $substation }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end section -->



                    </div> <!-- .col-12 -->
                </div> <!-- .row -->



        </x-app-layout>
