<x-app-layout>


    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Banks</a>
                </li>
            </ul>
        </div>
        <div class="col-auto">
            <x-modal-button>
                {{ __('Add Bank') }}
            </x-modal-button>
        </div>
    </div>

    <div class="row my-2">

        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-body">
                    <table class="table table-bordered datatables" id="dataTable-1">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>bank name</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($banks->count() > 0)
                                @foreach ($banks as $index => $bank)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $bank->bank_name }}</td>
                                        <td class="text-right">
                                            <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-sm btn-primary edit-bank-btn"
                                                    data-bank-id="{{ $bank->id }}"
                                                    data-bank-name="{{ $bank->bank_name }}">
                                                    <span class="fe fe-edit fe-16"></span>
                                                </a>

                                                <form action="{{ route('bank.destroy', $bank->id) }}" method="POST"
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
                                    <td colspan="3" class="text-center">No bank found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                </div>
            </div>



            <div class="modal fade" id="varyModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="varyModalLabel">New bank</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('bank.store') }}" validate>
                                @csrf

                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <input type="text" class="form-control" id="validationCustom3"
                                            name="bank_name" required>
                                        <div class="valid-feedback"> Looks good! </div>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <x-secondary-button data-dismiss="modal">
                                        {{ __('Close') }}
                                    </x-secondary-button>
                                    <x-primary-button>
                                        {{ __('Save') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>


            <!-- Edit bank Modal -->
            <div class="modal fade" id="editbankModal" tabindex="-1" role="dialog"
                aria-labelledby="editbankModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editbankModalLabel">Edit bank</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="" id="editbankForm">
                                @csrf
                                @method('PUT')

                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <input type="text" class="form-control" id="editbankName" name="bank_name"
                                            required>
                                        <div class="valid-feedback"> Looks good! </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <x-secondary-button data-dismiss="modal">
                                        {{ __('Close') }}
                                    </x-secondary-button>
                                    <x-primary-button>
                                        {{ __('Update') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.querySelectorAll('.edit-bank-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const bankId = this.getAttribute('data-bank-id');
                        const bankName = this.getAttribute('data-bank-name');

                        // Set the form's action attribute to the route for updating the bank
                        document.getElementById('editbankForm').setAttribute('action', `/bank/${bankId}`);

                        // Populate the bank name in the modal
                        document.getElementById('editbankName').value = bankName;

                        // Show the modal
                        $('#editbankModal').modal('show');
                    });
                });
            </script>


</x-app-layout>
