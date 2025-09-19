<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                    aria-controls="home" aria-selected="true">Direct Allowance details</a>
                            </li>

                        </ul>
                    </div>
                    <div class="col-auto">

                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                        <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal"
                            data-target="#varyModal" data-whatever="@mdo">Direct Allowance Add<span
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
                                            <th>Allowance</th>
                                            <th>Amount</th>
                                            <th>Taxable</th>
                                            <th>Status</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($details->count() > 0)
                                            @foreach ($details as $index => $detail)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $detail->allowance->name ?? 'N/A' }}</td>
                                                    <td>{{ $detail->amount ?? '-' }}</td>
                                                    <td>{{ $detail->taxable ? 'Yes' : 'No' }}</td>
                                                    <td>{{ ucfirst($detail->status) }}</td>
                                                    <td class="text-right">
                                                        <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                            <a href="javascript:void(0);"
                                                               class="btn btn-sm btn-primary edit-detail-btn"
                                                               data-detail-id="{{ $detail->id }}"
                                                               data-allowance-id="{{ $detail->allowance_id }}"
                                                               data-amount="{{ $detail->amount }}"
                                                               data-taxable="{{ $detail->taxable }}"
                                                               data-status="{{ $detail->status }}">
                                                                <span class="fe fe-edit fe-16"></span>
                                                            </a>

                                                            <form action="{{ route('direct.destroy', $detail->id) }}" method="POST"
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
                                                <td colspan="6" class="text-center">No detail found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>


                </div>
            </div>


            <!-- Add Modal -->
            <div class="modal fade" id="varyModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="varyModalLabel">New detail</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('direct.store') }}">
                                @csrf

                                <div class="form-group">
                                    <label>Allowance</label>
                                    <select name="allowance_id" class="form-control" required>
                                        <option value="">-- Select Allowance --</option>
                                        @foreach($allowances as $allowance)
                                            <option value="{{ $allowance->id }}">{{ $allowance->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="number" name="amount" class="form-control" step="0.01">
                                </div>

                                <div class="form-group">
                                    <label>Taxable</label>
                                    <select name="taxable" class="form-control">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="active" selected>Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>

                        </div>

                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editdetailModal" tabindex="-1" role="dialog"
            aria-labelledby="editdetailModalLabel" aria-hidden="true">
           <div class="modal-dialog" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                       <h5 class="modal-title" id="editdetailModalLabel">Edit Allowance Detail</h5>
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                       </button>
                   </div>
                   <div class="modal-body">
                       <form method="POST" action="" id="editdetailForm">
                           @csrf
                           @method('PUT')

                           <div class="form-group">
                               <label>Allowance</label>
                               <select class="form-control" id="editAllowanceId" name="allowance_id" required>
                                   <option value="">-- Select Allowance --</option>
                                   @foreach($allowances as $allowance)
                                       <option value="{{ $allowance->id }}">{{ $allowance->name }}</option>
                                   @endforeach
                               </select>
                           </div>

                           <div class="form-group">
                               <label>Amount</label>
                               <input type="number" class="form-control" id="editAmount" name="amount" step="0.01">
                           </div>

                           <div class="form-group">
                               <label>Taxable</label>
                               <select class="form-control" id="editTaxable" name="taxable">
                                   <option value="0">No</option>
                                   <option value="1">Yes</option>
                               </select>
                           </div>

                           <div class="form-group">
                               <label>Status</label>
                               <select class="form-control" id="editStatus" name="status">
                                   <option value="active">Active</option>
                                   <option value="inactive">Inactive</option>
                               </select>
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
        document.querySelectorAll('.edit-detail-btn').forEach(button => {
            button.addEventListener('click', function() {
                const detailId = this.getAttribute('data-detail-id');
                const allowanceId = this.getAttribute('data-allowance-id');
                const amount = this.getAttribute('data-amount');
                const taxable = this.getAttribute('data-taxable');
                const status = this.getAttribute('data-status');

                // Set form action - CORRECTED
                document.getElementById('editdetailForm').setAttribute('action', `{{ url('allowance_details') }}/${detailId}`);

                // Populate fields
                document.getElementById('editAllowanceId').value = allowanceId;
                document.getElementById('editAmount').value = amount ?? '';
                document.getElementById('editTaxable').value = taxable;
                document.getElementById('editStatus').value = status;

                // Show modal
                $('#editdetailModal').modal('show');
            });
        });
    </script>

</x-app-layout>
