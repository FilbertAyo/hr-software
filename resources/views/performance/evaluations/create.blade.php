<x-app-layout>


                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">Create Evaluation</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('evaluations.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fe fe-16 fe-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="row my-2">
                    <div class="col-md-12">
                        <div class="card shadow-none border">
                            <div class="card-body">
                                <form method="POST" action="{{ route('evaluations.store') }}" id="evaluationForm">
                                    @csrf

                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="evaluation_name">Evaluation Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="evaluation_name" name="evaluation_name"
                                                   value="{{ old('evaluation_name') }}" required>
                                            @error('evaluation_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="start_date"
                                                   name="start_date" value="{{ old('start_date') }}" required>
                                            @error('start_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="end_date">End Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="end_date"
                                                   name="end_date" value="{{ old('end_date') }}" required>
                                            @error('end_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="status">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" id="status" name="status" required>
                                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                            @error('status')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="4"
                                                      placeholder="Enter evaluation description...">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Removed advanced preview for simplified schema -->

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary">
                                                <span class="fe fe-save fe-16 mr-2"></span>Create Evaluation
                                            </button>
                                            <a href="{{ route('evaluations.index') }}" class="btn btn-secondary ml-2">
                                                Cancel
                                            </a>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // No additional JS needed for simplified evaluation form
    </script>

</x-app-layout>
