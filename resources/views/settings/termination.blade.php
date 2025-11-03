<x-app-layout>


                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">Terminations</a>
                            </li>

                        </ul>
                    </div>
                    <div class="col-auto">

                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                              <x-modal-button>
                {{ __('Add termination') }}
            </x-modal-button>
                    </div>
                </div>

            <div class="row my-2">
                <!-- Small table -->


                <div class="col-md-12">
                    <div class="card shadow-none border">
                        <div class="card-body">
                            <!-- table -->
                            <table class="table table-bordered datatables" id="dataTable-1">
                                 <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Termination Type</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($terminations->count() > 0)
                                        @foreach ($terminations as $index => $termination)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $termination->termination_type }}</td>
                                                <td class="text-right">
                                                    <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                        <a href="javascript:void(0);"
                                                        class="btn btn-sm btn-primary edit-termination-btn"
                                                        data-termination-id="{{ $termination->id }}"
                                                        data-termination-name="{{ $termination->termination_type }}">
                                                         <span class="fe fe-edit fe-16"></span>
                                                     </a>

                                                        <form action="{{ route('termination.destroy', $termination->id) }}" method="POST"
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
                                            <td colspan="3" class="text-center">No termination found</td>
                                        </tr>
                                    @endif
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
                    <h5 class="modal-title" id="varyModalLabel">New termination</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('termination.store') }}" validate>
                        @csrf

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <input type="text" class="form-control" id="validationCustom3"
                                    name="termination_type" required>
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


    <!-- Edit termination Modal -->
<div class="modal fade" id="editterminationModal" tabindex="-1" role="dialog" aria-labelledby="editterminationModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="editterminationModalLabel">Edit termination</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form method="POST" action="" id="editterminationForm">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="col-md-12 mb-3">
                    <input type="text" class="form-control" id="editterminationName" name="termination_type" required>
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
document.querySelectorAll('.edit-termination-btn').forEach(button => {
button.addEventListener('click', function () {
const terminationId = this.getAttribute('data-termination-id');
const terminationName = this.getAttribute('data-termination-name');

// Set the form's action attribute to the route for updating the termination
document.getElementById('editterminationForm').setAttribute('action', `/termination/${terminationId}`);

// Populate the termination name in the modal
document.getElementById('editterminationName').value = terminationName;

// Show the modal
$('#editterminationModal').modal('show');
});
});

</script>



</x-app-layout>
