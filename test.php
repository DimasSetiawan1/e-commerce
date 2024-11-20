<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tab with Reset Feature</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .tab-content {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-top: none;
        }

        .list-group-item {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <!-- Input -->
        <div class="input-group mb-3">
            <input type="text" id="location-input" class="form-control" placeholder="Lokasi yang dipilih" readonly>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs" id="locationTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="provinsi-tab" data-toggle="tab" href="#provinsi" role="tab"
                    aria-controls="provinsi" aria-selected="true">Provinsi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="kota-tab" data-toggle="tab" href="#kota" role="tab" aria-controls="kota"
                    aria-selected="false">Kota</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="kecamatan-tab" data-toggle="tab" href="#kecamatan" role="tab"
                    aria-controls="kecamatan" aria-selected="false">Kecamatan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="kodepos-tab" data-toggle="tab" href="#kodepos" role="tab"
                    aria-controls="kodepos" aria-selected="false">Kode Pos</a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="locationTabContent">
            <div class="tab-pane fade show active" id="provinsi" role="tabpanel" aria-labelledby="provinsi-tab">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" data-next-tab="#kota">Bali</li>
                    <li class="list-group-item" data-next-tab="#kota">Jawa Barat</li>
                    <li class="list-group-item" data-next-tab="#kota">Sumatera Selatan</li>
                </ul>
            </div>
            <div class="tab-pane fade" id="kota" role="tabpanel" aria-labelledby="kota-tab">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" data-next-tab="#kecamatan">Kab. Badung</li>
                    <li class="list-group-item" data-next-tab="#kecamatan">Kab. Gianyar</li>
                    <li class="list-group-item" data-next-tab="#kecamatan">Kab. Denpasar</li>
                </ul>
            </div>
            <div class="tab-pane fade" id="kecamatan" role="tabpanel" aria-labelledby="kecamatan-tab">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" data-next-tab="#kodepos">Kec. Kuta</li>
                    <li class="list-group-item" data-next-tab="#kodepos">Kec. Ubud</li>
                    <li class="list-group-item" data-next-tab="#kodepos">Kec. Denpasar Barat</li>
                </ul>
            </div>
            <div class="tab-pane fade" id="kodepos" role="tabpanel" aria-labelledby="kodepos-tab">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" data-next-tab="done">80361</li>
                    <li class="list-group-item" data-next-tab="done">80571</li>
                    <li class="list-group-item" data-next-tab="done">80112</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Data untuk input
            const inputData = {
                provinsi: "",
                kota: "",
                kecamatan: "",
                kodepos: "",
            };

            // Klik item dalam list
            $(".list-group-item").on("click", function () {
                const value = $(this).text();
                const nextTab = $(this).data("next-tab");
                const activeTab = $(".nav-tabs .active").attr("id");

                // Simpan data sesuai tab aktif
                if (activeTab.includes("provinsi")) inputData.provinsi = value;
                if (activeTab.includes("kota")) inputData.kota = value;
                if (activeTab.includes("kecamatan")) inputData.kecamatan = value;
                if (activeTab.includes("kodepos")) inputData.kodepos = value;

                // Update input
                const inputValue = [
                    inputData.provinsi,
                    inputData.kota,
                    inputData.kecamatan,
                    inputData.kodepos,
                ]
                    .filter((item) => item !== "") // Hanya masukkan data yang ada
                    .join(", ");
                $("#location-input").val(inputValue);

                // Pindah ke tab berikutnya
                if (nextTab && nextTab !== "done") {
                    $(`a[href="${nextTab}"]`).tab("show");
                }
            });

            // Reset data saat pindah tab mundur
            $("a[data-toggle='tab']").on("show.bs.tab", function (e) {
                const targetTab = $(e.target).attr("href");
                if (targetTab === "#provinsi") {
                    inputData.kota = "";
                    inputData.kecamatan = "";
                    inputData.kodepos = "";
                } else if (targetTab === "#kota") {
                    inputData.kecamatan = "";
                    inputData.kodepos = "";
                } else if (targetTab === "#kecamatan") {
                    inputData.kodepos = "";
                }

                // Update input
                const inputValue = [
                    inputData.provinsi,
                    inputData.kota,
                    inputData.kecamatan,
                    inputData.kodepos,
                ]
                    .filter((item) => item !== "")
                    .join(", ");
                $("#location-input").val(inputValue);
            });
        });
    </script>
</body>

</html>



