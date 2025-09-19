<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                    aria-controls="home" aria-selected="true">payperiods</a>
                            </li>

                        </ul>
                    </div>
                    <div class="col-auto">

                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                        <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal"
                            data-target="#varyModal" data-whatever="@mdo">New payperiod<span
                                class="fe fe-plus fe-16 ml-2"></span></button>
                    </div>
                </div>

                <div class="row my-2">
                    <!-- Small table -->

                    @include('elements.spinner')
                    <div class="col-md-12">
                        <div class="card shadow-none border">
                            <div class="card-body">
                                <!-- table -->
                                <table class="table table-bordered datatables" id="dataTable-1">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>payperiod</th>
                                            <th>Status</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($payperiods->count() > 0)
                                            @foreach ($payperiods as $index => $payperiod)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $payperiod->period_name }}</td>
                                                    <td>
                                                        {{ $payperiod->status }}
                                                    </td>
                                                    <td class="text-right">
                                                        <div
                                                            style="display: flex; gap: 4px; justify-content: flex-end;">
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-sm btn-primary edit-payperiod-btn"
                                                                data-payperiod-id="{{ $payperiod->id }}"
                                                                data-payperiod-name="{{ $payperiod->payperiod }}">
                                                                <span class="fe fe-edit fe-16"></span>
                                                            </a>

                                                            <form
                                                                action="{{ route('payperiod.destroy', $payperiod->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <span class="fe fe-trash-2 fe-16"></span>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3" class="text-center">No payperiod found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div> <!-- simple table -->


                </div> <!-- .row -->
            </div> <!-- .container-fluid -->


            <div class="modal fade" id="varyModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="varyModalLabel">New payperiod</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('payperiod.store') }}">
                                @csrf

                                <div class="form-row">
                                    @php
                                        $selMonth = old('month', $month ?? now()->month);
                                        $selYear = old('year', $year ?? now()->year);
                                    @endphp

                                    <div class="col-md-6">
                                        <select name="month" class="form-control" required>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}"
                                                    {{ $selMonth == $i ? 'selected' : '' }}>
                                                    {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <select name="year" class="form-control" required>
                                            @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                                                <option value="{{ $i }}" {{ $selYear == $i ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn mb-2 btn-secondary"
                                        data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn mb-2 btn-primary">Save and Close</button>
                                </div>
                            </form>


                        </div>
                    </div>
                </div>


                <!-- Edit payperiod Modal -->
                <div class="modal fade" id="editpayperiodModal" tabindex="-1" role="dialog"
                    aria-labelledby="editpayperiodModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editpayperiodModalLabel">Edit payperiod</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="" id="editpayperiodForm">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">
                                            <input type="text" class="form-control" id="editpayperiodName"
                                                name="payperiod" required>
                                            <div class="valid-feedback"> Looks good! </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn mb-2 btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn mb-2 btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    document.querySelectorAll('.edit-payperiod-btn').forEach(button => {
                        button.addEventListener('click', function() {
                            const payperiodId = this.getAttribute('data-payperiod-id');
                            const payperiodName = this.getAttribute('data-payperiod-name');

                            // Set the form's action attribute to the route for updating the payperiod
                            document.getElementById('editpayperiodForm').setAttribute('action',
                                `/payperiod/${payperiodId}`);

                            // Populate the payperiod name in the modal
                            document.getElementById('editpayperiodName').value = payperiodName;

                            // Show the modal
                            $('#editpayperiodModal').modal('show');
                        });
                    });
                </script>



</x-app-layout>
