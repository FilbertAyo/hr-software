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

                                        <div class="col-md-6 mb-3">
                                            <label for="department_id">Department <span class="text-danger">*</span></label>
                                            <select class="form-control" id="department_id" name="department_id" required>
                                                <option value="">Choose Department...</option>
                                                @foreach($departments as $department)
                                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                        {{ $department->department_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('department_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="general_factor_id">KPI's Group Factor <span class="text-danger">*</span></label>
                                            <select class="form-control" id="general_factor_id" name="general_factor_id" required>
                                                <option value="">Choose KPI Group...</option>
                                                @foreach($generalFactors as $generalFactor)
                                                    <option value="{{ $generalFactor->id }}" {{ old('general_factor_id') == $generalFactor->id ? 'selected' : '' }}>
                                                        {{ $generalFactor->general_factor_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('general_factor_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="rating_scale_id">Rating Scale <span class="text-danger">*</span></label>
                                            <select class="form-control" id="rating_scale_id" name="rating_scale_id" required>
                                                <option value="">Choose Rating Scale...</option>
                                                @foreach($ratingScales as $ratingScale)
                                                    <option value="{{ $ratingScale->id }}" {{ old('rating_scale_id') == $ratingScale->id ? 'selected' : '' }}>
                                                        {{ $ratingScale->scale_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('rating_scale_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="evaluation_period_start">Evaluation Period Start <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="evaluation_period_start"
                                                   name="evaluation_period_start" value="{{ old('evaluation_period_start') }}" required>
                                            @error('evaluation_period_start')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="evaluation_period_end">Evaluation Period End <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="evaluation_period_end"
                                                   name="evaluation_period_end" value="{{ old('evaluation_period_end') }}" required>
                                            @error('evaluation_period_end')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="status">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" id="status" name="status" required>
                                                <option value="Draft" {{ old('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                                <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
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

                                    <!-- Preview Section -->
                                    <div id="preview-section" style="display: none;">
                                        <hr>
                                        <h5>Evaluation Preview</h5>
                                        <div class="alert alert-info">
                                            <strong>Selected KPI Group Factors:</strong>
                                            <div id="factors-preview"></div>
                                        </div>
                                        <div class="alert alert-success">
                                            <strong>Selected Rating Scale Items:</strong>
                                            <div id="rating-scale-preview"></div>
                                        </div>
                                    </div>

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
        document.addEventListener('DOMContentLoaded', function() {
            const generalFactorSelect = document.getElementById('general_factor_id');
            const ratingScaleSelect = document.getElementById('rating_scale_id');
            const previewSection = document.getElementById('preview-section');
            const factorsPreview = document.getElementById('factors-preview');
            const ratingScalePreview = document.getElementById('rating-scale-preview');

            // Load factors when general factor is selected
            generalFactorSelect.addEventListener('change', function() {
                const generalFactorId = this.value;
                if (generalFactorId) {
                    fetch(`/api/general-factors/${generalFactorId}/factors`)
                        .then(response => response.json())
                        .then(data => {
                            let factorsHtml = '<ul class="mb-0">';
                            data.forEach(factor => {
                                factorsHtml += `<li><strong>${factor.factor_name}</strong> (Weight: ${factor.weight}%)`;
                                if (factor.description) {
                                    factorsHtml += ` - ${factor.description}`;
                                }
                                factorsHtml += '</li>';
                            });
                            factorsHtml += '</ul>';
                            factorsPreview.innerHTML = factorsHtml;
                            updatePreviewVisibility();
                        })
                        .catch(error => {
                            console.error('Error loading factors:', error);
                            factorsPreview.innerHTML = 'Error loading factors';
                        });
                } else {
                    factorsPreview.innerHTML = '';
                    updatePreviewVisibility();
                }
            });

            // Load rating scale items when rating scale is selected
            ratingScaleSelect.addEventListener('change', function() {
                const ratingScaleId = this.value;
                if (ratingScaleId) {
                    fetch(`/api/rating-scales/${ratingScaleId}/items`)
                        .then(response => response.json())
                        .then(data => {
                            let itemsHtml = '<ul class="mb-0">';
                            data.forEach(item => {
                                itemsHtml += `<li><strong>${item.name}</strong> - Score: ${item.score}`;
                                if (item.description) {
                                    itemsHtml += ` (${item.description})`;
                                }
                                itemsHtml += '</li>';
                            });
                            itemsHtml += '</ul>';
                            ratingScalePreview.innerHTML = itemsHtml;
                            updatePreviewVisibility();
                        })
                        .catch(error => {
                            console.error('Error loading rating scale items:', error);
                            ratingScalePreview.innerHTML = 'Error loading rating scale items';
                        });
                } else {
                    ratingScalePreview.innerHTML = '';
                    updatePreviewVisibility();
                }
            });

            function updatePreviewVisibility() {
                if (factorsPreview.innerHTML.trim() && ratingScalePreview.innerHTML.trim()) {
                    previewSection.style.display = 'block';
                } else {
                    previewSection.style.display = 'none';
                }
            }
        });
    </script>

</x-app-layout>
