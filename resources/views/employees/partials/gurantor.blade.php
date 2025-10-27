<div class="card-header">
    <strong>Guarantor Information</strong>
</div>
<div class="card-body">
    @if(isset($employee) && $employee && $employee->exists)
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Employee Guarantors</strong>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addGuarantorModal">
                <i class="bi bi-plus-circle"></i> Add Guarantor
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Mobile</th>
                            <th>Relationship</th>
                            <th>Occupation</th>
                            <th>ID Number</th>
                            <th>Address</th>
                            <th>Attachment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employee->guarantors as $index => $guarantor)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $guarantor->full_name }}</td>
                                <td>{{ $guarantor->mobile }}</td>
                                <td>{{ $guarantor->relationship }}</td>
                                <td>{{ $guarantor->occupation }}</td>
                                <td>{{ $guarantor->id_number }}</td>
                                <td>{{ $guarantor->address }}</td>
                                <td>
                                    @if($guarantor->attachment)
                                        <a href="{{ asset('storage/' . $guarantor->attachment) }}" target="_blank" class="btn btn-sm btn-outline-info">View</a>
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning">Edit</button>
                                    <form action="{{ route('employee.guarantor.destroy', [$employee->id, $guarantor->id]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this guarantor?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No guarantor records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Guarantors can be added after the employee is created.
    </div>
    @endif
</div>
