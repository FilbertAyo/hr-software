<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">Employee Group Benefits Add</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('group-benefits.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fe fe-arrow-left fe-16 mr-2"></i>Back
                        </a>
                    </div>
                </div>

                <div class="row my-2">
                    <div class="col-md-12">
                        <div class="card shadow-none border">
                            <div class="card-body">
                                <form action="{{ route('group-benefits.store') }}" method="POST">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="earngroup_ids">Select Earning Groups <span class="text-danger">*</span></label>
                                                <select name="earngroup_ids[]" id="earngroup_ids" class="form-control select2" multiple required>
                                                    @foreach($earngroups as $earngroup)
                                                        <option value="{{ $earngroup->id }}">{{ $earngroup->earngroup_name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('earngroup_ids')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="allowance_ids">Select Benefits <span class="text-danger">*</span></label>
                                                <select name="allowance_ids[]" id="allowance_ids" class="form-control select2" multiple required>
                                                    @foreach($allowances as $allowance)
                                                        <option value="{{ $allowance->id }}">
                                                            {{ $allowance->allowance_name }}
                                                            @if($allowance->allowanceDetails->count() > 0)
                                                                - {{ $allowance->allowanceDetails->first()->calculation_type == 'amount' ? number_format($allowance->allowanceDetails->first()->amount, 2) : $allowance->allowanceDetails->first()->percentage . '%' }}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('allowance_ids')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status">Status <span class="text-danger">*</span></label>
                                                <select name="status" id="status" class="form-control" required>
                                                    <option value="">Select Status</option>
                                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                                @error('status')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group text-right">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fe fe-save fe-16 mr-2"></i>Save
                                                </button>
                                                <a href="{{ route('group-benefits.index') }}" class="btn btn-secondary ml-2">
                                                    Cancel
                                                </a>
                                            </div>
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
        $(document).ready(function() {
            // Initialize Select2 for multiple selection
            $('.select2').select2({
                placeholder: 'Select options',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
</x-app-layout>
