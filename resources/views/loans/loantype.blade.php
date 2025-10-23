<x-app-layout>


                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">loantypes</a>
                            </li>

                        </ul>
                    </div>
                    <div class="col-auto">

                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                            <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal" data-target="#varyModal" data-whatever="@mdo">New loantype<span
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
                                        <th>loantype</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @foreach ($loantypes as $index => $loantype)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $loantype->loan_type_name }}</td>
                                                <td class="text-right">
                                                    <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                        <a href="javascript:void(0);"
                                                        class="btn btn-sm btn-primary edit-loantype-btn"
                                                        data-loantype-id="{{ $loantype->id }}"
                                                        data-loantype-name="{{ $loantype->loan_type_name }}">
                                                         <span class="fe fe-edit fe-16"></span>
                                                     </a>

                                                        <form action="{{ route('loantype.destroy', $loantype->id) }}" method="POST"
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

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div> <!-- simple table -->
        </div> <!-- .row -->



    <div class="modal fade" id="varyModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="varyModalLabel">New loantype</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('loantype.store') }}" validate>
                        @csrf

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <input type="text" class="form-control" id="validationCustom3"
                                    name="loan_type_name" required>
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


    <!-- Edit loantype Modal -->
<div class="modal fade" id="editloantypeModal" tabindex="-1" role="dialog" aria-labelledby="editloantypeModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="editloantypeModalLabel">Edit loantype</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form method="POST" action="" id="editloantypeForm">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="col-md-12 mb-3">
                    <input type="text" class="form-control" id="editloantypeName" name="loan_type_name" required>
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
document.querySelectorAll('.edit-loantype-btn').forEach(button => {
button.addEventListener('click', function () {
const loantypeId = this.getAttribute('data-loantype-id');
const loantypeName = this.getAttribute('data-loantype-name');

// Set the form's action attribute to the route for updating the loantype
document.getElementById('editloantypeForm').setAttribute('action', `/loantype/${loantypeId}`);

// Populate the loantype name in the modal
document.getElementById('editloantypeName').value = loantypeName;

// Show the modal
$('#editloantypeModal').modal('show');
});
});

</script>



</x-app-layout>
