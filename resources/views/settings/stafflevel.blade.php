<x-app-layout>


                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true"> Staff Levels</a>
                            </li>

                        </ul>
                    </div>
                    <div class="col-auto">

                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                            <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal" data-target="#varyModal" data-whatever="@mdo">New level_name<span
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
                                        <th>Level Name</th>
                                        <th>Order</th>
                                        <th>Description</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($level_names->count() > 0)
                                        @foreach ($level_names as $index => $level_name)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $level_name->level_name }}</td>
                                                <td>{{ $level_name->level_order ?? 0 }}</td>
                                                <td>{{ $level_name->description ?? '-' }}</td>
                                                <td class="text-right">
                                                    <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                        <a href="javascript:void(0);"
                                                        class="btn btn-sm btn-primary edit-level_name-btn"
                                                        data-level_name-id="{{ $level_name->id }}"
                                                        data-level_name-name="{{ $level_name->level_name }}"
                                                        data-level_order="{{ $level_name->level_order }}"
                                                        data-level_description="{{ $level_name->description }}">
                                                         <span class="fe fe-edit fe-16"></span>
                                                     </a>

                                                        <form action="{{ route('stafflevel.destroy', $level_name->id) }}" method="POST"
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
                                            <td colspan="5" class="text-center">No staff levels found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div> 

                </div> <!-- simple table -->

    <div class="modal fade" id="varyModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="varyModalLabel">New level_name</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('stafflevel.store') }}" validate>
                        @csrf

                        <div class="form-row">
                            <div class="col-md-9 mb-3">
                                <label for="level_name">Level Name *</label>
                                <input type="text" class="form-control" id="level_name"
                                    name="level_name" required>
                                <div class="valid-feedback"> Looks good! </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="level_order">Order</label>
                                <input type="number" class="form-control" id="level_order"
                                    name="level_order" min="0" value="0">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="description">Description</label>
                                <input type="text" class="form-control" id="description"
                                    name="description">
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


    <!-- Edit level_name Modal -->
<div class="modal fade" id="editlevel_nameModal" tabindex="-1" role="dialog" aria-labelledby="editlevel_nameModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="editlevel_nameModalLabel">Edit level_name</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form method="POST" action="" id="editlevel_nameForm">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="col-md-9 mb-3">
                    <label for="editlevel_nameName">Level Name *</label>
                    <input type="text" class="form-control" id="editlevel_nameName" name="level_name" required>
                    <div class="valid-feedback"> Looks good! </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="editlevel_order">Order</label>
                    <input type="number" class="form-control" id="editlevel_order" name="level_order" min="0">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="editlevel_description">Description</label>
                    <input type="text" class="form-control" id="editlevel_description" name="description">
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
document.querySelectorAll('.edit-level_name-btn').forEach(button => {
button.addEventListener('click', function () {
const level_nameId = this.getAttribute('data-level_name-id');
const level_nameName = this.getAttribute('data-level_name-name');
const levelOrder = this.getAttribute('data-level_order') || '0';
const levelDescription = this.getAttribute('data-level_description') || '';

// Set the form's action attribute to the route for updating the staff level
document.getElementById('editlevel_nameForm').setAttribute('action', `/stafflevel/${level_nameId}`);

// Populate the form fields in the modal
document.getElementById('editlevel_nameName').value = level_nameName;
document.getElementById('editlevel_order').value = levelOrder;
document.getElementById('editlevel_description').value = levelDescription;

// Show the modal
$('#editlevel_nameModal').modal('show');
});
});

</script>



</x-app-layout>
