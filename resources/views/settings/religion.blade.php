<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">religions</a>
                            </li>

                        </ul>
                    </div>
                    <div class="col-auto">

                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                            <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal" data-target="#varyModal" data-whatever="@mdo">New religion<span
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
                                        <th>religion</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($religions->count() > 0)
                                        @foreach ($religions as $index => $religion)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $religion->religion }}</td>
                                                <td class="text-right">
                                                    <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                        <a href="javascript:void(0);"
                                                        class="btn btn-sm btn-primary edit-religion-btn"
                                                        data-religion-id="{{ $religion->id }}"
                                                        data-religion-name="{{ $religion->religion }}">
                                                         <span class="fe fe-edit fe-16"></span>
                                                     </a>

                                                        <form action="{{ route('religion.destroy', $religion->id) }}" method="POST"
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
                                            <td colspan="3" class="text-center">No religion found</td>
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
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="varyModalLabel">New religion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('religion.store') }}" validate>
                        @csrf

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <input type="text" class="form-control" id="validationCustom3"
                                    name="religion" required>
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


    <!-- Edit religion Modal -->
<div class="modal fade" id="editreligionModal" tabindex="-1" role="dialog" aria-labelledby="editreligionModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="editreligionModalLabel">Edit religion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form method="POST" action="" id="editreligionForm">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="col-md-12 mb-3">
                    <input type="text" class="form-control" id="editreligionName" name="religion" required>
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
document.querySelectorAll('.edit-religion-btn').forEach(button => {
button.addEventListener('click', function () {
const religionId = this.getAttribute('data-religion-id');
const religionName = this.getAttribute('data-religion-name');

// Set the form's action attribute to the route for updating the religion
document.getElementById('editreligionForm').setAttribute('action', `/religion/${religionId}`);

// Populate the religion name in the modal
document.getElementById('editreligionName').value = religionName;

// Show the modal
$('#editreligionModal').modal('show');
});
});

</script>



</x-app-layout>
