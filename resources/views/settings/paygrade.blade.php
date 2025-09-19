<x-app-layout>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12">

                        <div class="row align-items-center mb-3 border-bottom no-gutters">
                            <div class="col">
                                <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                            role="tab" aria-controls="home" aria-selected="true">paygrades</a>
                                    </li>

                                </ul>
                            </div>
                            <div class="col-auto">

                                <button type="button" class="btn btn-sm" onclick="reloadPage()">
                                    <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                                </button>
                                    <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal" data-target="#varyModal" data-whatever="@mdo">New paygrade<span
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
                                                <th>pay grade</th>
                                                <th>Currency</th>
                                                <th>Initial Amount</th>
                                                <th>Optimal Amount</th>
                                                <th>Step Increase</th>
                                                <th class="text-right">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($paygrades->count() > 0)
                                                @foreach ($paygrades as $index => $paygrade)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $paygrade->grade }}</td>
                                                        <td>{{ $paygrade->currency }}</td>
                                                        <td>{{ $paygrade->initial_amount }}</td>
                                                        <td>{{ $paygrade->optimal_amount }}</td>
                                                        <td>{{ $paygrade->step_increase }}</td>
                                                        <td class="text-right">
                                                            <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                                <a href="javascript:void(0);"
                                                                class="btn btn-sm btn-primary edit-paygrade-btn"
                                                                data-paygrade-id="{{ $paygrade->id }}"
                                                                data-paygrade-name="{{ $paygrade->paygrade_name }}">
                                                                 <span class="fe fe-edit fe-16"></span>
                                                             </a>

                                                                <form action="{{ route('pay_grade.destroy', $paygrade->id) }}" method="POST"
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
                                                    <td colspan="7" class="text-center">No paygrade found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div> <!-- simple table -->


                </div> <!-- .row -->
            </div> <!-- .container-fluid -->


            <div class="modal fade" id="varyModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="varyModalLabel">New paygrade</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('pay_grade.store') }}" validate>
                                @csrf

                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label for="">Pay Grade</label>
                                        <input type="text" class="form-control" id="validationCustom3"
                                            name="grade" required>
                                        <div class="valid-feedback"> Looks good! </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Currency</label>
                                        <input type="text" class="form-control" id="validationCustom3"
                                            name="currency" required>
                                        <div class="valid-feedback"> Looks good! </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label for="">Description</label>
                                        <textarea type="text" class="form-control" id="validationCustom3"
                                            name="description" ></textarea>
                                    </div>

                                </div>

                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label for="">Initial Amount</label>
                                        <input type="text" class="form-control" id="validationCustom3"
                                            name="initial_amount" required>
                                        <div class="valid-feedback"> Looks good! </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Optimal Amount</label>
                                        <input type="text" class="form-control" id="validationCustom3"
                                            name="optimal_amount" required>
                                        <div class="valid-feedback"> Looks good! </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label for="">Step Increase</label>
                                        <input type="text" class="form-control" id="validationCustom3"
                                            name="step_increase" required>
                                        <div class="valid-feedback"> Looks good! </div>
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


            <!-- Edit paygrade Modal -->
<div class="modal fade" id="editpaygradeModal" tabindex="-1" role="dialog" aria-labelledby="editpaygradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editpaygradeModalLabel">Edit paygrade</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="editpaygradeForm">
                    @csrf
                    @method('PUT')

                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <input type="text" class="form-control" id="editpaygradeName" name="paygrade_name" required>
                            <div class="valid-feedback"> Looks good! </div>
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
   document.querySelectorAll('.edit-paygrade-btn').forEach(button => {
    button.addEventListener('click', function () {
        const paygradeId = this.getAttribute('data-paygrade-id');
        const paygradeName = this.getAttribute('data-paygrade-name');

        // Set the form's action attribute to the route for updating the paygrade
        document.getElementById('editpaygradeForm').setAttribute('action', `/paygrade/${paygradeId}`);

        // Populate the paygrade name in the modal
        document.getElementById('editpaygradeName').value = paygradeName;

        // Show the modal
        $('#editpaygradeModal').modal('show');
    });
});

</script>


</x-app-layout>
