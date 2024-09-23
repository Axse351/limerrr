@extends('staff.layouts.app')

@section('title', 'Scan Transaksi')

@push('style')
@endpush

@section('staff.content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Scan Transaksi</h1>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('staff.layouts.alert')
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h4>Scan QR Code Transaksi</h4>
                            </div>
                            <div class="card-body">
                                <div id="reader" style="width: 100%; height: 300px; border: 1px solid #ccc;"></div>
                                <button id="start-scan" class="btn btn-primary mt-3">Mulai Scan</button>
                                <button id="stop-scan" class="btn btn-danger mt-3" style="display: none;">Hentikan Scan</button>
                                <div id="transaksi-details" style="display: none;">
                                    <h5>Detail Transaksi</h5>
                                    <p id="detail-content"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <script>
        let html5QrCode;
        let scanning = false;
    
        function onScanSuccess(decodedText, decodedResult) {
            console.log(`Kode berhasil di-scan: ${decodedText}`);
            $.ajax({
                url: '{{ route("staff.transaksi.scanTransaksi") }}',
                method: 'GET',
                data: { id: decodedText },
                success: function(response) {
                    if (response.success) {
                        $('#transaksi-details').show();
                        $('#detail-content').html('Nama Konsumen: ' + response.transaksi.nm_konsumen + '<br>' +
                                                  'No HP: ' + response.transaksi.nohp + '<br>' +
                                                  'Paket ID: ' + response.transaksi.paket_id);
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    $('#transaksi-details').hide();
                    alert('Transaksi tidak ditemukan');
                }
            });
        }
    
        function onScanError(errorMessage) {
            console.error(`Kesalahan saat scan QR Code: ${errorMessage}`);
        }
    
        function startScan() {
            html5QrCode = new Html5Qrcode("reader");
            Html5QrCode.getCameras().then(cameras => {
                if (cameras && cameras.length) {
                    let cameraId = cameras[0].id;
                    html5QrCode.start(
                        { deviceId: { exact: cameraId } },
                        { fps: 10, qrbox: { width: 250, height: 250 } },
                        onScanSuccess,
                        onScanError
                    ).catch(err => {
                        console.error(`Kesalahan saat memulai kamera: ${err}`);
                    });
                } else {
                    alert('Tidak ada kamera yang tersedia.');
                }
            }).catch(err => {
                console.error(`Kesalahan saat mendapatkan kamera: ${err}`);
            });
    
            scanning = true;
            $('#start-scan').hide();
            $('#stop-scan').show();
        }
    
        function stopScan() {
            if (html5QrCode) {
                html5QrCode.stop().then(ignore => {
                    scanning = false;
                    $('#start-scan').show();
                    $('#stop-scan').hide();
                }).catch(err => {
                    console.error(`Kesalahan saat menghentikan kamera: ${err}`);
                });
            }
        }
    
        $(document).ready(function() {
            $('#start-scan').click(startScan);
            $('#stop-scan').click(stopScan);
        });

    </script>
    
@endpush
