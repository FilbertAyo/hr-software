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
                        <a href="{{ route('evaluations.create') }}" class="btn mb-2 btn-primary btn-sm">
                            New Evaluation<span class="fe fe-plus fe-16 ml-2"></span>
                        </a>
                    </div>
                </div>

                <!-- Performance Management Navigation -->
                {{-- <div class="row mb-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body py-2">
                                <div class="btn-group" role="group" aria-label="Performance Management">
                                    <a href="{{ route('general-factors.index') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fe fe-layers"></i> General Factors
                                    </a>
                                    <a href="{{ route('factors.index') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fe fe-grid"></i> Factors
                                    </a>
                                    <a href="{{ route('sub-factors.index') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fe fe-list"></i> Sub Factors
                                    </a>
                                    <a href="{{ route('rating-scales.index') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fe fe-star"></i> Rating Scales
                                    </a>
                                    <a href="{{ route('evaluations.index') }}" class="btn btn-primary btn-sm">
                                        <i class="fe fe-clipboard"></i> Evaluations
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

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
                                            <th>Department</th>
                                            <th>KPI Group</th>
                                            <th>Rating Scale</th>
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
                                                    <td>
                                                        <strong>{{ $evaluation->evaluation_name }}</strong>
                                                        @if($evaluation->description)
                                                            <br><small class="text-muted">{{ Str::limit($evaluation->description, 50) }}</small>
                                                        @endif
                                                    </td>
                                                    <td>{{ $evaluation->department->department_name ?? 'N/A' }}</td>
                                                    <td>{{ $evaluation->generalFactor->general_factor_name ?? 'N/A' }}</td>
                                                    <td>{{ $evaluation->ratingScale->scale_name ?? 'N/A' }}</td>
                                                    <td>
                                                        <small>
                                                            {{ date('M d, Y', strtotime($evaluation->evaluation_period_start)) }}<br>
                                                            to {{ date('M d, Y', strtotime($evaluation->evaluation_period_end)) }}
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
                                                            <a href="{{ route('evaluations.edit', $evaluation->id) }}"
                                                               class="btn btn-sm btn-primary" title="Edit">
                                                                <span class="fe fe-edit fe-16"></span>
                                                            </a>

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
                                                <td colspan="8" class="text-center">No evaluations found</td>
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
