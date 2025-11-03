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
            <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal" data-target="#ratingScaleModal" onclick="openCreateModal()">
                New Rating Scale<span class="fe fe-plus fe-16 ml-2"></span>
            </button>
        </div>
    </div>

    <div class="row my-2">

        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-body">
                    <!-- table -->
                    <table class="table table-bordered datatables" id="dataTable-1">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Scale Name</th>
                                <th>Rating Items</th>
                                <th>Score Range</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($ratingScales->count() > 0)
                                @foreach ($ratingScales as $index => $ratingScale)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $ratingScale->scale_name }}</strong></td>
                                        <td>
                                            <span class="badge badge-info">{{ $ratingScale->rating_scale_items_count }} items</span>
                                            @if($ratingScale->ratingScaleItems->count() > 0)
                                                <br>
                                                <small class="text-muted">
                                                    @foreach($ratingScale->ratingScaleItems->take(3) as $item)
                                                        {{ $item->item_name }} ({{ $item->score }}){{ !$loop->last ? ', ' : '' }}
                                                    @endforeach
                                                    @if($ratingScale->ratingScaleItems->count() > 3)
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
                                        <td class="text-right">
                                            <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                <!-- Edit Button -->
                                                <button type="button" class="btn btn-sm btn-primary" title="Edit"
                                                        onclick="openEditModal({{ $ratingScale->id }}, '{{ $ratingScale->scale_name }}', {{ $ratingScale->ratingScaleItems->toJson() }})">
                                                    <span class="fe fe-edit fe-16"></span>
                                                </button>

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
                                    <td colspan="5" class="text-center">No rating scales found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Scale Modal -->
    <div class="modal fade" id="ratingScaleModal" tabindex="-1" role="dialog" aria-labelledby="ratingScaleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="POST" id="ratingScaleForm">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="rating_scale_id" id="ratingScaleId">

                    <div class="modal-header">
                        <h5 class="modal-title" id="ratingScaleModalLabel">Create Rating Scale</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="scale_name">Scale Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="scale_name" name="scale_name" required>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <h6>Rating Scale Items</h6>
                            <p class="text-muted small">Add rating items with their corresponding scores</p>
                        </div>

                        <div id="rating-items-container">
                            <div class="rating-item-row mb-2" data-index="0">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Rating Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="items[0][item_name]"
                                               placeholder="e.g., Excellent" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Score <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" class="form-control" name="items[0][score]"
                                               placeholder="5.00" required>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm remove-item-btn w-100" disabled>
                                            <i class="fe fe-trash-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-success btn-sm mt-2" id="add-item-btn">
                            <i class="fe fe-plus"></i> Add Rating Item
                        </button>
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
        let itemIndex = 1;
        let isEditMode = false;

        function reloadPage() {
            location.reload();
        }

        function openCreateModal() {
            isEditMode = false;
            document.getElementById('ratingScaleModalLabel').textContent = 'Create Rating Scale';
            document.getElementById('submitBtnText').textContent = 'Create';
            document.getElementById('ratingScaleForm').action = "{{ route('rating-scales.store') }}";
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('ratingScaleForm').reset();

            // Reset items container
            const container = document.getElementById('rating-items-container');
            container.innerHTML = `
                <div class="rating-item-row mb-2" data-index="0">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Rating Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="items[0][item_name]"
                                   placeholder="e.g., Excellent" required>
                        </div>
                        <div class="col-md-4">
                            <label>Score <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" name="items[0][score]"
                                   placeholder="5.00" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-item-btn w-100" disabled>
                                <i class="fe fe-trash-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            itemIndex = 1;
        }

        function openEditModal(id, scaleName, items) {
            isEditMode = true;
            document.getElementById('ratingScaleModalLabel').textContent = 'Edit Rating Scale';
            document.getElementById('submitBtnText').textContent = 'Update';
            document.getElementById('ratingScaleForm').action = `/rating-scales/${id}`;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('ratingScaleId').value = id;
            document.getElementById('scale_name').value = scaleName;

            // Populate items
            const container = document.getElementById('rating-items-container');
            container.innerHTML = '';

            items.forEach((item, index) => {
                const newRow = document.createElement('div');
                newRow.className = 'rating-item-row mb-2';
                newRow.setAttribute('data-index', index);

                newRow.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <label>Rating Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="items[${index}][item_name]"
                                   value="${item.item_name}" placeholder="e.g., Excellent" required>
                        </div>
                        <div class="col-md-4">
                            <label>Score <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" name="items[${index}][score]"
                                   value="${item.score}" placeholder="5.00" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-item-btn w-100">
                                <i class="fe fe-trash-2"></i>
                            </button>
                        </div>
                    </div>
                `;

                container.appendChild(newRow);
            });

            itemIndex = items.length;
            updateRemoveButtons();

            $('#ratingScaleModal').modal('show');
        }

        document.getElementById('add-item-btn').addEventListener('click', function() {
            const container = document.getElementById('rating-items-container');
            const newRow = document.createElement('div');
            newRow.className = 'rating-item-row mb-2';
            newRow.setAttribute('data-index', itemIndex);

            newRow.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <label>Rating Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="items[${itemIndex}][item_name]"
                               placeholder="e.g., Good" required>
                    </div>
                    <div class="col-md-4">
                        <label>Score <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="items[${itemIndex}][score]"
                               placeholder="4.00" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-item-btn w-100">
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
