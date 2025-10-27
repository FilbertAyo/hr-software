<x-app-layout>

    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                        role="tab" aria-controls="home" aria-selected="true">Performance Evaluations</a>
                </li>
            </ul>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-sm" onclick="reloadPage()">
                <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
            </button>
            <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal" data-target="#evaluationModal" onclick="openCreateModal()">
                New Evaluation<span class="fe fe-plus fe-16 ml-2"></span>
            </button>
        </div>
    </div>

    <div class="row my-2">
        @include('elements.spinner')
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-body">
                    <!-- table -->
                    <table class="table table-bordered datatables" id="dataTable-1">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Evaluation Name</th>
                                <th>Period</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($evaluations->count() > 0)
                                @foreach ($evaluations as $index => $evaluation)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $evaluation->evaluation_name }}</strong></td>
                                        <td>
                                            <small>
                                                {{ optional($evaluation->start_date)->format('M d, Y') }}<br>
                                                to {{ optional($evaluation->end_date)->format('M d, Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge {{ $evaluation->status_badge_class }}">
                                                {{ $evaluation->status }}
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                <!-- View Button -->
                                                <a href="{{ route('evaluations.show', $evaluation->id) }}"
                                                   class="btn btn-sm btn-info" title="View">
                                                    <span class="fe fe-eye fe-16"></span>
                                                </a>

                                                <!-- Edit Button -->
                                                <button type="button" class="btn btn-sm btn-primary" title="Edit"
                                                        onclick="openEditModal({{ $evaluation->id }}, '{{ $evaluation->evaluation_name }}', '{{ $evaluation->start_date }}', '{{ $evaluation->end_date }}', '{{ $evaluation->status }}')">
                                                    <span class="fe fe-edit fe-16"></span>
                                                </button>

                                                <!-- Delete Button -->
                                                <form action="{{ route('evaluations.destroy', $evaluation->id) }}" method="POST"
                                                      onsubmit="return confirm('Are you sure you want to delete this evaluation?');">
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
                                    <td colspan="5" class="text-center">No evaluations found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Evaluation Modal -->
    <div class="modal fade" id="evaluationModal" tabindex="-1" role="dialog" aria-labelledby="evaluationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="POST" id="evaluationForm">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="modal-header">
                        <h5 class="modal-title" id="evaluationModalLabel">Create Evaluation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="evaluation_name">Evaluation Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="evaluation_name" name="evaluation_name" required>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">End Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="draft">Draft</option>
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="fe fe-save fe-16 mr-2"></span><span id="submitBtnText">Create</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function reloadPage() {
            location.reload();
        }

        function openCreateModal() {
            document.getElementById('evaluationModalLabel').textContent = 'Create Evaluation';
            document.getElementById('submitBtnText').textContent = 'Create';
            document.getElementById('evaluationForm').action = "{{ route('evaluations.store') }}";
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('evaluationForm').reset();
        }

        function openEditModal(id, name, startDate, endDate, status) {
            document.getElementById('evaluationModalLabel').textContent = 'Edit Evaluation';
            document.getElementById('submitBtnText').textContent = 'Update';
            document.getElementById('evaluationForm').action = `/evaluations/${id}`;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('evaluation_name').value = name;
            document.getElementById('start_date').value = startDate;
            document.getElementById('end_date').value = endDate;
            document.getElementById('status').value = status;

            $('#evaluationModal').modal('show');
        }
    </script>

</x-app-layout>
