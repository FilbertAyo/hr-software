<x-app-layout>


                        <div class="row align-items-center mb-3 border-bottom no-gutters">
                            <div class="col">
                                <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                            role="tab" aria-controls="home" aria-selected="true">Main Station</a>
                                    </li>

                                </ul>
                            </div>
                            <div class="col-auto">

                                <button type="button" class="btn btn-sm" onclick="reloadPage()">
                                    <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                                </button>
                                      <x-modal-button>
                {{ __('Add mainstation') }}
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
                                                <th>main station</th>
                                                <th>sub station</th>
                                                <th class="text-right">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                @foreach ($mainstations as $index => $mainstation)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>

                                                        <td>{{ $mainstation->station_name }}</td>
                                                        <td>{{ $mainstation->substations_count }}</td>
                                                        <td class="text-right">
                                                            <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                                <a href="javascript:void(0);"
                                                                class="btn btn-sm btn-primary edit-mainstation-btn"
                                                                data-mainstation-id="{{ $mainstation->id }}"
                                                                data-mainstation-name="{{ $mainstation->station_name }}">
                                                                 <span class="fe fe-edit fe-16"></span>
                                                             </a>

                                                                <form action="{{ route('mainstation.destroy', $mainstation->id) }}" method="POST"
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
                            <h5 class="modal-title" id="varyModalLabel">New mainstation</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('mainstation.store') }}" validate>
                                @csrf

                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label for="">Main station Name</label>
                                        <input type="text" class="form-control" id="validationCustom3"
                                            name="station_name" required>
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


            <!-- Edit mainstation Modal -->
<div class="modal fade" id="editmainstationModal" tabindex="-1" role="dialog" aria-labelledby="editmainstationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editmainstationModalLabel">Edit mainstation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="editmainstationForm">
                    @csrf
                    @method('PUT')

                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <input type="text" class="form-control" id="editmainstationName" name="station_name" required>
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
   document.querySelectorAll('.edit-mainstation-btn').forEach(button => {
    button.addEventListener('click', function () {
        const mainstationId = this.getAttribute('data-mainstation-id');
        const mainstationName = this.getAttribute('data-mainstation-name');

        // Set the form's action attribute to the route for updating the mainstation
        document.getElementById('editmainstationForm').setAttribute('action', `/mainstation/${mainstationId}`);

        // Populate the mainstation name in the modal
        document.getElementById('editmainstationName').value = mainstationName;

        // Show the modal
        $('#editmainstationModal').modal('show');
    });
});

</script>




</x-app-layout>
