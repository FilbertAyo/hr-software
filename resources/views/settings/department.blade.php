<x-app-layout>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12">

                        <div class="row align-items-center mb-3 border-bottom no-gutters">
                            <div class="col">
                                <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                            role="tab" aria-controls="home" aria-selected="true">departments</a>
                                    </li>

                                </ul>
                            </div>
                            <div class="col-auto">

                                <button type="button" class="btn btn-sm" onclick="reloadPage()">
                                    <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                                </button>
                                    <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal" data-target="#varyModal" data-whatever="@mdo">New department<span
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
                                                <th>department</th>
                                                <th class="text-right">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($departments->count() > 0)
                                                @foreach ($departments as $index => $department)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $department->department_name }}</td>
                                                        <td class="text-right">
                                                            <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                                <a href="javascript:void(0);"
                                                                class="btn btn-sm btn-primary edit-department-btn"
                                                                data-department-id="{{ $department->id }}"
                                                                data-department-name="{{ $department->department_name }}">
                                                                 <span class="fe fe-edit fe-16"></span>
                                                             </a>

                                                                <form action="{{ route('department.destroy', $department->id) }}" method="POST"
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
                                                    <td colspan="3" class="text-center">No department found</td>
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
                            <h5 class="modal-title" id="varyModalLabel">New department</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('department.store') }}" validate>
                                @csrf

                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <input type="text" class="form-control" id="validationCustom3"
                                            name="department_name" required>
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


            <!-- Edit department Modal -->
<div class="modal fade" id="editdepartmentModal" tabindex="-1" role="dialog" aria-labelledby="editdepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editdepartmentModalLabel">Edit department</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="editdepartmentForm">
                    @csrf
                    @method('PUT')

                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <input type="text" class="form-control" id="editdepartmentName" name="department_name" required>
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
   document.querySelectorAll('.edit-department-btn').forEach(button => {
    button.addEventListener('click', function () {
        const departmentId = this.getAttribute('data-department-id');
        const departmentName = this.getAttribute('data-department-name');

        // Set the form's action attribute to the route for updating the department
        document.getElementById('editdepartmentForm').setAttribute('action', `/department/${departmentId}`);

        // Populate the department name in the modal
        document.getElementById('editdepartmentName').value = departmentName;

        // Show the modal
        $('#editdepartmentModal').modal('show');
    });
});

</script>



</x-app-layout>
