<!-- Bootstrap Modal for Adding Family -->
<div class="modal fade" id="addFamilyModal" tabindex="-1" aria-labelledby="addFamilyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFamilyModalLabel">Add Employee Relative</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('employee.family.store', $employee->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">First Name *</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="middle_name" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Last Name *</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Relationship *</label>
                            <select name="relationship" class="form-control" required>
                                <option value="">Select Relationship</option>
                                @foreach ($relationships as $relation)
                                    <option value="{{ $relation->id }}">
                                        {{ $relation->relation_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Mobile *</label>
                            <input type="text" name="mobile" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Home Mobile</label>
                            <input type="text" name="home_mobile" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Age</label>
                            <input type="number" name="age" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Postal Address</label>
                            <input type="text" name="postal_address" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">District</label>
                            <input type="text" name="district" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Ward</label>
                            <input type="text" name="ward" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Division</label>
                            <input type="text" name="division" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Region</label>
                            <input type="text" name="region" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tribe</label>
                            <input type="text" name="tribe" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Religion</label>
                            <input type="text" name="religion" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Attachment</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="form-check mt-4">
                                <input type="checkbox" class="form-check-input" id="dependant" name="is_dependant">
                                <label class="form-check-label" for="dependant">Dependant</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
