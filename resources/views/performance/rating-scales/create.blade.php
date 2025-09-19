<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">Create Rating Scale</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('rating-scales.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fe fe-16 fe-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="row my-2">
                    <div class="col-md-12">
                        <div class="card shadow-none border">
                            <div class="card-body">
                                <form method="POST" action="{{ route('rating-scales.store') }}" id="ratingScaleForm">
                                    @csrf

                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="scale_name">Scale Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="scale_name" name="scale_name"
                                                   value="{{ old('scale_name') }}" required>
                                            @error('scale_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="status">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" id="status" name="status" required>
                                                <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
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
                                            <textarea class="form-control" id="description" name="description" rows="3"
                                                      placeholder="Enter description...">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="form-row mb-3">
                                        <div class="col-md-12">
                                            <h5>Rating Scale Items</h5>
                                            <p class="text-muted">Add rating items with their corresponding scores</p>
                                        </div>
                                    </div>

                                    <div id="rating-items-container">
                                        <div class="rating-item-row" data-index="0">
                                            <div class="form-row">
                                                <div class="col-md-4 mb-3">
                                                    <label>Rating Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="items[0][name]"
                                                           placeholder="e.g., Excellent" required>
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <label>Score <span class="text-danger">*</span></label>
                                                    <input type="number" step="0.01" class="form-control" name="items[0][score]"
                                                           placeholder="5.00" required>
                                                </div>
                                                <div class="col-md-5 mb-3">
                                                    <label>Description</label>
                                                    <input type="text" class="form-control" name="items[0][description]"
                                                           placeholder="Optional description">
                                                </div>
                                                <div class="col-md-1 mb-3 d-flex align-items-end">
                                                    <button type="button" class="btn btn-danger btn-sm remove-item-btn" disabled>
                                                        <i class="fe fe-trash-2"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">
                                            <button type="button" class="btn btn-success btn-sm" id="add-item-btn">
                                                <i class="fe fe-plus"></i> Add Rating Item
                                            </button>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary">
                                                <span class="fe fe-save fe-16 mr-2"></span>Create Rating Scale
                                            </button>
                                            <a href="{{ route('rating-scales.index') }}" class="btn btn-secondary ml-2">
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
        let itemIndex = 1;

        document.getElementById('add-item-btn').addEventListener('click', function() {
            const container = document.getElementById('rating-items-container');
            const newRow = document.createElement('div');
            newRow.className = 'rating-item-row';
            newRow.setAttribute('data-index', itemIndex);

            newRow.innerHTML = `
                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label>Rating Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="items[${itemIndex}][name]"
                               placeholder="e.g., Good" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>Score <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="items[${itemIndex}][score]"
                               placeholder="4.00" required>
                    </div>
                    <div class="col-md-5 mb-3">
                        <label>Description</label>
                        <input type="text" class="form-control" name="items[${itemIndex}][description]"
                               placeholder="Optional description">
                    </div>
                    <div class="col-md-1 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-item-btn">
                            <i class="fe fe-trash-2"></i>
                        </button>
                    </div>
                </div>
            `;

            container.appendChild(newRow);
            itemIndex++;
            updateRemoveButtons();
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-item-btn')) {
                const row = e.target.closest('.rating-item-row');
                row.remove();
                updateRemoveButtons();
            }
        });

        function updateRemoveButtons() {
            const rows = document.querySelectorAll('.rating-item-row');
            rows.forEach((row, index) => {
                const removeBtn = row.querySelector('.remove-item-btn');
                removeBtn.disabled = rows.length === 1;
            });
        }
    </script>

</x-app-layout>
