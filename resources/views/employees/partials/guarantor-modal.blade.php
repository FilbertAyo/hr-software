<!-- Bootstrap Modal for Adding Guarantor -->
<div class="modal fade" id="addGuarantorModal" tabindex="-1" aria-labelledby="addGuarantorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGuarantorModalLabel">Add Employee Guarantor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('employee.guarantor.store', $employee->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Mobile *</label>
                            <input type="text" name="mobile" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Relationship *</label>
                            <input type="text" name="relationship" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Occupation</label>
                            <input type="text" name="occupation" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">ID Number *</label>
                            <input type="text" name="id_number" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Attachment (Optional)</label>
                            <input type="file" name="attachment" class="form-control">
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
