<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">Edit Group Benefit</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('group-benefits.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fe fe-arrow-left fe-16 mr-2"></i>Back
                        </a>
                    </div>
                </div>

                <div class="row my-2">
                    <div class="col-md-12">
                        <div class="card shadow-none border">
                            <div class="card-body">
                                <form action="{{ route('group-benefits.update', $groupBenefit) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="earngroup_id">Select Earning Group <span class="text-danger">*</span></label>
                                                <select name="earngroup_id" id="earngroup_id" class="form-control" required>
                                                    <option value="">Select Earning Group</option>
                                                    @foreach($earngroups as $earngroup)
                                                        <option value="{{ $earngroup->id }}"
                                                                {{ $groupBenefit->earngroup_id == $earngroup->id ? 'selected' : '' }}>
                                                            {{ $earngroup->earngroup_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('earngroup_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="allowance_id">Select Benefit <span class="text-danger">*</span></label>
                                                <select name="allowance_id" id="allowance_id" class="form-control" required>
                                                    <option value="">Select Benefit</option>
                                                    @foreach($allowances as $allowance)
                                                        <option value="{{ $allowance->id }}"
                                                                {{ $groupBenefit->allowance_id == $allowance->id ? 'selected' : '' }}>
                                                            {{ $allowance->allowance_name }}
                                                            @if($allowance->allowanceDetails->count() > 0)
                                                                - {{ $allowance->allowanceDetails->first()->calculation_type == 'amount' ? number_format($allowance->allowanceDetails->first()->amount, 2) : $allowance->allowanceDetails->first()->percentage . '%' }}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('allowance_id')
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
                                                    <option value="active" {{ $groupBenefit->status == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ $groupBenefit->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                                                    <i class="fe fe-save fe-16 mr-2"></i>Update
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
</x-app-layout>
