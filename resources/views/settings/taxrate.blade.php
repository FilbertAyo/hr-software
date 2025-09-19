<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                    aria-controls="home" aria-selected="true">taxrates</a>
                            </li>

                        </ul>
                    </div>
                    <div class="col-auto">

                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                        <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal"
                            data-target="#varyModal" data-whatever="@mdo">New taxrate<span
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
                                            <th>Tax Name</th>
                                            <th>Tax Rate (%)</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($taxrates->count() > 0)
                                            @foreach ($taxrates as $index => $taxrate)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $taxrate->name }}</td>
                                                    <td>{{ number_format($taxrate->tax_rate, 2) }}</td>
                                                    <td class="text-right">
                                                        <div
                                                            style="display: flex; gap: 4px; justify-content: flex-end;">
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-sm btn-primary edit-taxrate-btn"
                                                                data-taxrate-id="{{ $taxrate->id }}"
                                                                data-taxrate-name="{{ $taxrate->name }}"
                                                                data-taxrate-value="{{ $taxrate->tax_rate }}">
                                                                <span class="fe fe-edit fe-16"></span>
                                                            </a>

                                                            <form action="{{ route('taxrate.destroy', $taxrate->id) }}"
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
                                                <td colspan="4" class="text-center">No taxrate found</td>
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
                            <h5 class="modal-title" id="varyModalLabel">New taxrate</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('taxrate.store') }}" validate>
                                @csrf

                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label>Tax Name</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label>Tax Rate (%)</label>
                                        <input type="number" step="0.01" class="form-control" name="tax_rate"
                                            required>
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
            </div>


            <!-- Edit taxrate Modal -->
            <div class="modal fade" id="edittaxrateModal" tabindex="-1" role="dialog"
                aria-labelledby="edittaxrateModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="edittaxrateModalLabel">Edit taxrate</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="" id="edittaxrateForm">
                                @csrf
                                @method('PUT')

                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label>Tax Name</label>
                                        <input type="text" class="form-control" id="edittaxrateName"
                                            name="name" required>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label>Tax Rate (%)</label>
                                        <input type="number" step="0.01" class="form-control"
                                            id="edittaxrateValue" name="tax_rate" required>
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
                document.querySelectorAll('.edit-taxrate-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const taxrateId = this.getAttribute('data-taxrate-id');
                        const taxrateName = this.getAttribute('data-taxrate-name');
                        const taxrateValue = this.getAttribute('data-taxrate-value');

                        // Set form action
                        document.getElementById('edittaxrateForm')
                            .setAttribute('action', `/taxrate/${taxrateId}`);

                        // Populate fields
                        document.getElementById('edittaxrateName').value = taxrateName;
                        document.getElementById('edittaxrateValue').value = taxrateValue;

                        // Show modal
                        $('#edittaxrateModal').modal('show');
                    });
                });
            </script>



</x-app-layout>
