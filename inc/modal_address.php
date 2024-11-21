<!-- Modal -->
<div class="modal fade" id="addressModal" data-focus="false" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog mx-0 mx-sm-auto">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="addressModalLabel">Add New Address</h5>
                <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn-close text-white"
                    data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form class="px-4" id="addressForm" action="./utils/profileHandler.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" value="add" name="action" />
                    <div class="form-outline mb-4">
                        <input type="text" id="nama_lengkap" name="nama" class="form-control" required />
                        <label class="form-label" for="nama_lengkap">Nama Lengkap</label>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-text" id="phoneNumber">+62</div>
                        <input type="number" class="form-control" name="nomor_telepon" placeholder="Nomor Telepon"
                            aria-label="Input group example" aria-describedby="btnGroupAddon" required />
                    </div>

                    <div class="h-100 mb-3">
                        <label for="provinsi">Provinsi</label>
                        <select class="form-control" id="provinsi" name="provinsi" required>
                        </select>
                    </div>
                    <div class="h-100 mb-4">
                        <label for="Kota">Kota</label>
                        <select class="form-control" id="kota" name="kota" required>
                        </select>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="number" id="kode_pos" name="kode_pos" class="form-control" required />
                        <label class="form-label" for="kode_pos">Kode Pos</label>
                    </div>

                    <div class="form-outline mb-3">
                        <textarea class="form-control" id="fullAddress" name="alamat_lengkap"
                            placeholder="Nama Jalan, Gedung, No Rumah" rows="2" required></textarea>
                        <label class="form-label" for="fullAddress">Alamat Lengkap</label>
                    </div>
                    <div class="form-outline mb-3">
                        <textarea class="form-control" id="detail_alamat" name="detail_alamat"
                            placeholder="Detail Lainnya (Cth: Blok / Unit No., Patokan)" rows="2"></textarea>
                    </div>

                    <label for="label">Label</label>
                    <div class="btn-toolbar align-content-start" id="label" role="toolbar">
                        <div class="btn-group me-3" shadow-0 role="group" aria-label="First group">
                            <input type="radio" class="btn-check" name="label" id="rumah" value="Rumah"
                                autocomplete="off" checked>
                            <label class="btn btn-secondary" for="rumah">Rumah</label>
                        </div>
                        <div class="btn-group" shadow-0 role="group" aria-label="two group">
                            <input type="radio" class="btn-check" name="label" id="kantor" value="Kantor"
                                autocomplete="off">
                            <label class="btn btn-secondary" for="kantor">Kantor</label>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-primary"
                        data-mdb-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" name="submit" data-mdb-button-init class="btn btn-primary">Save
                        Address</button>
                </div>
            </form>

        </div>
    </div>
</div>