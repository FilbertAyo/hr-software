<x-app-layout>


    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">holidays</a>
                </li>

            </ul>
        </div>
        <div class="col-auto">
            <x-modal-button>
                {{ __('Add Holiday') }}
            </x-modal-button>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-body">

                    <table class="table table-bordered datatables" id="dataTable-1">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Holiday Name</th>
                                <th>Holiday Date</th>
                                <th>Is Recurring</th>
                                <th>Description</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($holidays->count() > 0)
                                @foreach ($holidays as $index => $holiday)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $holiday->holiday_name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($holiday->holiday_date)->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            <span
                                                class="badge {{ $holiday->is_recurring ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $holiday->is_recurring ? 'Yes' : 'No' }}
                                            </span>
                                        </td>
                                        <td>{{ $holiday->description ?? '-' }}</td>
                                        <td class="text-right">
                                            <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-sm btn-primary edit-holiday-btn"
                                                    data-holiday-id="{{ $holiday->id }}"
                                                    data-holiday-name="{{ $holiday->holiday_name }}"
                                                    data-holiday-date="{{ $holiday->holiday_date }}"
                                                    data-holiday-is-recurring="{{ $holiday->is_recurring ? 1 : 0 }}"
                                                    data-holiday-description="{{ $holiday->description }}">
                                                    <span class="fe fe-edit fe-16"></span>
                                                </a>

                                                <form action="{{ route('holiday.destroy', $holiday->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <span class="fe fe-trash-2 fe-16"></span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">No holiday found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>


                </div>
            </div>
        </div> 


    </div> <!-- .row -->



    <div class="modal fade" id="varyModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="varyModalLabel">New holiday</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('holiday.store') }}" validate>
                        @csrf
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label>Holiday Name</label>
                                <input type="text" class="form-control" name="holiday_name" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Holiday Date</label>
                                <input type="date" class="form-control" name="holiday_date" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Is Recurring</label>
                                <select class="form-control" name="is_recurring">
                                    <option value="0" selected>No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Description</label>
                                <textarea class="form-control" name="description" rows="3" placeholder="Optional description"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <x-secondary-button data-dismiss="modal">
                                {{ __('Close') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Save') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>


    <!-- Edit holiday Modal -->
    <div class="modal fade" id="editholidayModal" tabindex="-1" role="dialog" aria-labelledby="editholidayModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editholidayModalLabel">Edit holiday</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="" id="editholidayForm">
                        @csrf
                        @method('PUT')

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label>Holiday Name</label>
                                <input type="text" class="form-control" id="editholidayName" name="holiday_name"
                                    required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Holiday Date</label>
                                <input type="date" class="form-control" id="editholidayDate" name="holiday_date"
                                    required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Is Recurring</label>
                                <select class="form-control" id="editholidayIsRecurring" name="is_recurring">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Description</label>
                                <textarea class="form-control" id="editholidayDescription" name="description" rows="3"
                                    placeholder="Optional description"></textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <x-secondary-button data-dismiss="modal">
                                {{ __('Close') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.edit-holiday-btn').forEach(button => {
            button.addEventListener('click', function() {
                const holidayId = this.getAttribute('data-holiday-id');
                const holidayName = this.getAttribute('data-holiday-name');
                const holidayDate = this.getAttribute('data-holiday-date');
                const holidayIsRecurring = this.getAttribute('data-holiday-is-recurring');
                const holidayDescription = this.getAttribute('data-holiday-description');

                document.getElementById('editholidayForm').setAttribute('action', `/holiday/${holidayId}`);
                document.getElementById('editholidayName').value = holidayName;
                document.getElementById('editholidayDate').value = holidayDate;
                document.getElementById('editholidayIsRecurring').value = holidayIsRecurring;
                document.getElementById('editholidayDescription').value = holidayDescription;

                $('#editholidayModal').modal('show');
            });
        });
    </script>


</x-app-layout>
