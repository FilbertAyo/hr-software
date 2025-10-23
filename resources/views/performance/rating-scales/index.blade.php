<x-app-layout>


                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">Rating Scales</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                        <a href="{{ route('rating-scales.create') }}" class="btn mb-2 btn-primary btn-sm">
                            New Rating Scale<span class="fe fe-plus fe-16 ml-2"></span>
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
                                    <a href="{{ route('rating-scales.index') }}" class="btn btn-primary btn-sm">
                                        <i class="fe fe-star"></i> Rating Scales
                                    </a>
                                    <a href="{{ route('evaluations.index') }}" class="btn btn-outline-primary btn-sm">
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
                                            <th>Scale Name</th>
                                            <th>Description</th>
                                            <th>Rating Items</th>
                                            <th>Score Range</th>
                                            <th>Status</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($ratingScales->count() > 0)
                                            @foreach ($ratingScales as $index => $ratingScale)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td><strong>{{ $ratingScale->scale_name }}</strong></td>
                                                    <td>{{ $ratingScale->description ? Str::limit($ratingScale->description, 50) : 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge badge-info">{{ $ratingScale->rating_scale_items_count }} items</span>
                                                        @if($ratingScale->ratingScaleItems->count() > 0)
                                                            <br>
                                                            <small class="text-muted">
                                                                @foreach($ratingScale->ratingScaleItems->take(2) as $item)
                                                                    {{ $item->name }} ({{ $item->score }}){{ !$loop->last ? ', ' : '' }}
                                                                @endforeach
                                                                @if($ratingScale->ratingScaleItems->count() > 2)
                                                                    ...
                                                                @endif
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($ratingScale->ratingScaleItems->count() > 0)
                                                            <span class="badge badge-success">
                                                                {{ $ratingScale->ratingScaleItems->min('score') }} - {{ $ratingScale->ratingScaleItems->max('score') }}
                                                            </span>
                                                        @else
                                                            <span class="badge badge-secondary">No items</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-{{ $ratingScale->status == 'Active' ? 'success' : 'secondary' }}">
                                                            {{ $ratingScale->status }}
                                                        </span>
                                                    </td>
                                                    <td class="text-right">
                                                        <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                            <!-- View Button -->
                                                            <a href="{{ route('rating-scales.show', $ratingScale->id) }}"
                                                               class="btn btn-sm btn-info" title="View">
                                                                <span class="fe fe-eye fe-16"></span>
                                                            </a>

                                                            <!-- Edit Button -->
                                                            <a href="{{ route('rating-scales.edit', $ratingScale->id) }}"
                                                               class="btn btn-sm btn-primary" title="Edit">
                                                                <span class="fe fe-edit fe-16"></span>
                                                            </a>

                                                            <!-- Delete Button -->
                                                            <form action="{{ route('rating-scales.destroy', $ratingScale->id) }}" method="POST"
                                                                  onsubmit="return confirm('Are you sure you want to delete this rating scale?');">
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
                                                <td colspan="7" class="text-center">No rating scales found</td>
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
