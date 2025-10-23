<x-app-layout>


                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">payments</a>
                            </li>

                        </ul>
                    </div>
                    <div class="col-auto">

                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                            <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal" data-target="#varyModal" data-whatever="@mdo">New payment<span
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
                                        <th>Payment Name</th>
                                        <th>Payment Type</th>
                                        <th>Rate Check</th>
                                        <th>Payment Rate</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($payments->count() > 0)
                                        @foreach ($payments as $index => $payment)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $payment->payment_name }}</td>
                                                <td>
                                                        {{ $payment->payment_type }}
                                                </td>
                                                <td>
                                                    @if($payment->payment_type == 'Dynamic')
                                                        <span class="badge badge-{{ $payment->rate_check ? 'success' : 'warning' }}">
                                                            {{ $payment->rate_check ? 'Yes' : 'No' }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($payment->payment_type == 'Dynamic' && $payment->rate_check && $payment->payment_rate)
                                                        {{ number_format($payment->payment_rate, 2) }}%
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $payment->status == 'Active' ? 'success' : 'danger' }}">
                                                        {{ $payment->status }}
                                                    </span>
                                                </td>
                                                <td class="text-right">
                                                    <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                        <a href="javascript:void(0);"
                                                        class="btn btn-sm btn-primary edit-payment-btn"
                                                        data-payment-id="{{ $payment->id }}"
                                                        data-payment-name="{{ $payment->payment_name }}"
                                                        data-payment-type="{{ $payment->payment_type }}"
                                                        data-rate-check="{{ $payment->rate_check ? 1 : 0 }}"
                                                        data-payment-rate="{{ $payment->payment_rate }}"
                                                        data-status="{{ $payment->status }}">
                                                         <span class="fe fe-edit fe-16"></span>
                                                     </a>

                                                        <form action="{{ route('payment.destroy', $payment->id) }}" method="POST"
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
                                            <td colspan="7" class="text-center">No payment found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div> <!-- simple table -->


        </div> <!-- .row -->



    <!-- Add Payment Modal -->
    <div class="modal fade" id="varyModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="varyModalLabel">New payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('payment.store') }}" validate>
                        @csrf

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="payment_name">Payment Name</label>
                                <input type="text" class="form-control" id="payment_name" name="payment_name" required>
                                <div class="valid-feedback">Looks good!</div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="payment_type">Payment Type</label>
                                <select class="form-control" id="payment_type" name="payment_type" required onchange="toggleRateFields('add')">
                                    <option value="Dynamic">Dynamic</option>
                                    <option value="Static">Static</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3" id="add_rate_check_div">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="add_rate_check" name="rate_check" onchange="toggleRateInput('add')">
                                    <label class="form-check-label" for="add_rate_check">
                                        Enable Rate Check
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3" id="add_payment_rate_div" style="display: none;">
                                <label for="add_payment_rate">Payment Rate (%)</label>
                                <input type="number" class="form-control" id="add_payment_rate" name="payment_rate" step="0.01" min="0" max="100">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn mb-2 btn-primary">Save and Close</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <!-- Edit payment Modal -->
    <div class="modal fade" id="editpaymentModal" tabindex="-1" role="dialog" aria-labelledby="editpaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editpaymentModalLabel">Edit payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="" id="editpaymentForm">
                        @csrf
                        @method('PUT')

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="edit_payment_name">Payment Name</label>
                                <input type="text" class="form-control" id="edit_payment_name" name="payment_name" required>
                                <div class="valid-feedback">Looks good!</div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="edit_payment_type">Payment Type</label>
                                <select class="form-control" id="edit_payment_type" name="payment_type" required onchange="toggleRateFields('edit')">
                                    <option value="Dynamic">Dynamic</option>
                                    <option value="Static">Static</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3" id="edit_rate_check_div">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="edit_rate_check" name="rate_check" onchange="toggleRateInput('edit')">
                                    <label class="form-check-label" for="edit_rate_check">
                                        Enable Rate Check
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3" id="edit_payment_rate_div" style="display: none;">
                                <label for="edit_payment_rate">Payment Rate (%)</label>
                                <input type="number" class="form-control" id="edit_payment_rate" name="payment_rate" step="0.01" min="0" max="100">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="edit_status">Status</label>
                                <select class="form-control" id="edit_status" name="status" required>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn mb-2 btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to toggle rate fields based on payment type
        function toggleRateFields(formType) {
            const paymentType = document.getElementById(formType + '_payment_type').value;
            const rateCheckDiv = document.getElementById(formType + '_rate_check_div');
            const rateInputDiv = document.getElementById(formType + '_payment_rate_div');
            const rateCheckbox = document.getElementById(formType + '_rate_check');

            if (paymentType === 'Static') {
                rateCheckDiv.style.display = 'none';
                rateInputDiv.style.display = 'none';
                rateCheckbox.checked = false;
            } else {
                rateCheckDiv.style.display = 'block';
                // Only show rate input if checkbox is checked
                if (rateCheckbox.checked) {
                    rateInputDiv.style.display = 'block';
                } else {
                    rateInputDiv.style.display = 'none';
                }
            }
        }

        // Function to toggle rate input based on checkbox
        function toggleRateInput(formType) {
            const rateCheckbox = document.getElementById(formType + '_rate_check');
            const rateInputDiv = document.getElementById(formType + '_payment_rate_div');

            if (rateCheckbox.checked) {
                rateInputDiv.style.display = 'block';
            } else {
                rateInputDiv.style.display = 'none';
            }
        }

        // Edit button click handler
        document.querySelectorAll('.edit-payment-btn').forEach(button => {
            button.addEventListener('click', function () {
                const paymentId = this.getAttribute('data-payment-id');
                const paymentName = this.getAttribute('data-payment-name');
                const paymentType = this.getAttribute('data-payment-type');
                const rateCheck = this.getAttribute('data-rate-check') === '1';
                const paymentRate = this.getAttribute('data-payment-rate');
                const status = this.getAttribute('data-status');

                // Set the form's action attribute
                document.getElementById('editpaymentForm').setAttribute('action', `/payment/${paymentId}`);

                // Populate the form fields
                document.getElementById('edit_payment_name').value = paymentName;
                document.getElementById('edit_payment_type').value = paymentType;
                document.getElementById('edit_rate_check').checked = rateCheck;
                document.getElementById('edit_payment_rate').value = paymentRate || '';
                document.getElementById('edit_status').value = status;

                // Trigger the toggle functions to show/hide appropriate fields
                toggleRateFields('edit');

                // Show the modal
                $('#editpaymentModal').modal('show');
            });
        });

        // Initialize form state on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleRateFields('add');
        });
    </script>

</x-app-layout>
