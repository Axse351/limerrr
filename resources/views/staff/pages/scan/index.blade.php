@extends('staff.layouts.app')

@section('title', 'Scan QR Code')

@push('style')
    <style>
        #qr-reader {
            width: 100%;
            height: auto;
            display: none;
        }
    </style>
@endpush

@section('staff.content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Scan QR Code</h1>
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
                                <h4>Scan QR Code</h4>
                            </div>
                            <div class="card-body">
                                <div id="qr-reader"></div>
                                <button id="start-scan-btn" class="btn btn-primary mb-3">Start Scan</button>
                                <input type="file" id="qr-upload" accept="image/*" class="btn btn-secondary mb-3">
                                <audio id="scanSuccessSound" src="{{ asset('sounds/scan-success.mp3') }}"></audio>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal for displaying scan result and dropdown -->
    <div class="modal fade" id="scanResultModal" tabindex="-1" role="dialog" aria-labelledby="scanResultModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scanResultModalLabel">Scan Result</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="scanResultText"></p>
                    <!-- Dropdown for selecting action -->
                    <div class="form-group">
                        <label for="actionSelect">Choose Action:</label>
                        <select class="form-control" id="actionSelect">
                            <option value="wahana">Pengurangan Wahana</option>
                            <option value="porsi">Pengurangan Porsi</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="confirmActionBtn">Confirm</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            // Function to play sound on successful scan
            function playScanSuccessSound() {
                const audio = document.getElementById('scanSuccessSound');
                audio.currentTime = 0;
                audio.play();
            }

            function showScanResult(decodedText) {
                document.getElementById('scanResultText').textContent = decodedText;
                $('#scanResultModal').modal('show');
            }

            function onScanSuccess(decodedText) {
                playScanSuccessSound();
                showScanResult(decodedText);
                document.getElementById('qrcodeInput').value = decodedText;
            }

            function onScanFailure(error) {
                console.warn(`Code scan error = ${error}`);
            }

            document.getElementById('start-scan-btn').addEventListener('click', function() {
                document.getElementById('qr-reader').style.display = 'block';
                const html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", {
                    fps: 10,
                    qrbox: 250
                });
                html5QrcodeScanner.render(onScanSuccess, onScanFailure);
            });

            document.getElementById('qr-upload').addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const html5QrCode = new Html5Qrcode("qr-reader");
                    html5QrCode.scanFile(file, true)
                        .then(onScanSuccess)
                        .catch(onScanFailure);
                }
            });

            document.getElementById('confirmActionBtn').addEventListener('click', function() {
                const selectedAction = document.getElementById('actionSelect').value;
                const scanResultText = document.getElementById('scanResultText').textContent;

                if (!scanResultText) {
                    alert('Scan result is required.');
                    return;
                }

                const formData = new FormData();
                formData.append('jenis_transaksi', selectedAction);
                formData.append('tanggal', new Date().toISOString().split('T')[0]);
                formData.append('jam', new Date().toTimeString().split(' ')[0]);
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('qty', '1'); // Default quantity

                let qr = scanResultText.split(",");
                let kodee = qr['4'].split(' ');
                let idTransaksi = kodee[3].substring(0, 1);
                console.log(idTransaksi)

                formData.append('transaksi_id', idTransaksi);

                // for (let [key, value] of formData.entries()) {
                //     console.log(`${key}: ${value}`);
                //     // formData.append($ {
                //     //     key
                //     // }, $ {
                //     //     value
                //     // });
                // }


                console.log("Form action URL:", '{{ route('staff.histories.store') }}');

                $.ajax({
                    url: '{{ route('staff.histories.store') }}',
                    _method: 'POST',
                    _token: '{{ csrf_token() }}',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        $('#scanResultModal').modal('hide');
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('An error occurred. Please try again.');
                    }
                });


            });

            function createHiddenInput(name, value) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = value;
                return input;
            }
        });
    </script>
@endpush
