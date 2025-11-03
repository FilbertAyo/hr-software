<x-app-layout>


                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">Employee Leaves</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                        <a href="{{ route('leaves.create') }}" class="btn mb-2 btn-primary btn-sm">
                            Assign Leave<span class="fe fe-plus fe-16 ml-2"></span>
                        </a>
                    </div>
                </div>

                <div class="row my-2">
                    <!-- Leaves table -->

                    <div class="col-md-12">
                        <div class="card shadow-none border">
                            <div class="card-body">
                                <!-- table -->
                                <table class="table table-bordered datatables" id="dataTable-1">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Employee</th>
                                            <th>Leave Type</th>
                                            <th>Action</th>
                                            <th>From Date</th>
                                            <th>To Date</th>
                                            <th>Days</th>
                                            <th>Status</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($leaves->count() > 0)
                                            @foreach ($leaves as $index => $leave)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $leave->employee->employee_name ?? 'N/A' }}</td>
                                                    <td>{{ $leave->leaveType->leave_type_name ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge badge-{{
                                                            $leave->leave_action == 'proceed' ? 'primary' :
                                                            ($leave->leave_action == 'sold' ? 'success' :
                                                            ($leave->leave_action == 'emergency' ? 'danger' : 'info'))
                                                        }}">
                                                            {{ ucfirst($leave->leave_action) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ date('M d, Y', strtotime($leave->from_date)) }}</td>
                                                    <td>{{ date('M d, Y', strtotime($leave->to_date)) }}</td>
                                                    <td>{{ $leave->no_of_days }}</td>
                                                    <td>
                                                        <span class="badge badge-{{
                                                            $leave->status == 'Approved' ? 'success' :
                                                            ($leave->status == 'Rejected' ? 'danger' : 'warning')
                                                        }}">
                                                            {{ $leave->status }}
                                                        </span>
                                                    </td>
                                                    <td class="text-right">
                                                        <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                            <!-- View Button -->
                                                            <a href="{{ route('leaves.show', $leave->id) }}"
                                                               class="btn btn-sm btn-info" title="View">
                                                                <span class="fe fe-eye fe-16"></span>
                                                            </a>

                                                            <!-- Edit Button -->
                                                            <a href="{{ route('leaves.edit', $leave->id) }}"
                                                               class="btn btn-sm btn-primary" title="Edit">
                                                                <span class="fe fe-edit fe-16"></span>
                                                            </a>

                                                            <!-- Delete Button -->
                                                            <form action="{{ route('leaves.destroy', $leave->id) }}" method="POST"
                                                                  onsubmit="return confirm('Are you sure you want to delete this leave record?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                                    <span class="fe fe-trash-2 fe-16"></span>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="9" class="text-center">No leaves assigned yet</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- simple table -->
                </div> <!-- .row -->
            </div> <!-- .col -->
        </div> <!-- .row -->


    <script>
        function reloadPage() {
            location.reload();
        }
    </script>
</x-app-layout>
