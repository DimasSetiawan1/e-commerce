<!-- Modal for Add/Edit Address -->
<div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addressModalLabel"></h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addressForm">
                    <input type="hidden" id="addressId" name="address_id">
                    <div class="form-outline mb-4">
                        <input type="text" id="name" name="name" class="form-control" required />
                        <label class="form-label" for="name">Nama Penerima</label>
                        <div class="invalid-feedback" id="name-error"></div>

                    </div>
                    <div class="form-outline mb-4">
                        <input type="text" id="nomor-telepon" name="phone_number" class="form-control" required />
                        <label class="form-label" for="nomor-telepon">Nomor Telepon</label>
                        <div class="invalid-feedback" id="phone-error"></div>
                    </div>


                    <div class="form-outline mb-4 d-flex flex-column">
                        <label class="form-label" for="label">Label</label>
                        <div class="btn-group">
                            <input type="radio" class="btn-check" name="label" id="label-rumah" value="Rumah"
                                autocomplete="off" required />
                            <label class="btn btn-outline-secondary" for="label-rumah"
                                data-mdb-ripple-init>Rumah</label>

                            <input type="radio" class="btn-check" name="label" id="label-kantor" value="Kantor"
                                autocomplete="off" required />
                            <label class="btn btn-outline-secondary" for="label-kantor"
                                data-mdb-ripple-init>Kantor</label>
                        </div>
                        <div class="invalid-feedback" id="label-error">Please select a label</div>
                    </div>

                    <div class="form-outline mb-4">
                        <select class="form-select select-initialized province" id="province" name="province" required>
                            <option value="">Select Province</option>
                        </select>
                        <label class="form-label select-label active" for="province">Province</label>
                        <div class="invalid-feedback" id="label-error"></div>

                    </div>
                    <div class="form-outline mb-4">
                        <select class="form-select select-initialized kota" id="city" name="city" required>
                            <option value="">Select City</option>
                        </select>
                        <label class="form-label select-label active" for="city">City</label>
                    </div>
                    <div class="form-outline mb-4">
                        <input type="text" id="postalCode" name="postal_code" class="form-control" required />
                        <label class="form-label" for="postalCode">Postal Code</label>
                    </div>
                    <div class="form-outline mb-4">
                        <textarea class="form-control" id="fullAddress" name="full_address" rows="4"
                            required></textarea>
                        <label class="form-label" for="fullAddress">Full Address</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveAddress">Save</button>
            </div>
        </div>
    </div>
</div>