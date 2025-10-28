<div class="card-header">
    <strong>Family Relationships</strong>
</div>

<div class="card-body">
    @if(isset($employee) && $employee && $employee->exists)
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Family Members</strong>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addFamilyModal">
                <i class="bi bi-plus-circle"></i> Add Family Member
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Relationship</th>
                            <th>Mobile</th>
                            <th>Date of Birth</th>
                            <th>Dependant</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employee->family as $index => $member)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $member->first_name }}</td>
                                <td>{{ $member->last_name }}</td>
                                <td>{{ $member->relationship ? $member->relationship->relation_name : 'N/A' }}</td>
                                <td>{{ $member->mobile }}</td>
                                <td>{{ $member->date_of_birth ? $member->date_of_birth->format('Y-m-d') : 'N/A' }}</td>
                                <td>{{ $member->is_dependant ? 'Yes' : 'No' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning">Edit</button>
                                    <form action="{{ route('employee.family.destroy', [$employee->id, $member->id]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this family member?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No family records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Family members can be added after the employee is created.
    </div>
    @endif
</div>
