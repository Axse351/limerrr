@extends('scan1.layouts.app')

@section('title', 'Scan QR Code')

@push('style')
    <!-- Add custom styles if needed -->
    <style>
        #qr-reader {
            width: 100%; /* Full width for responsive design */
            height: auto; /* Auto height */
            display: none; /* Initially hidden */
        }
    </style>
@endpush

@section('scan1.content')
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
                                <div id="qr-reader"></div> <!-- Responsive QR reader -->
                                <button id="start-scan-btn" class="btn btn-primary mb-3">Start Scan</button>
                                <form id="scanForm" action="{{ route('scan.process') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="qrdata" id="qrcodeInput">
                                </form>
                                <!-- Add audio element for scan success sound -->
                                <audio id="scanSuccessSound" src="{{ asset('sounds/scan-success.mp3') }}"></audio>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    // Play sound on successful scan
    function playScanSuccessSound() {
        const audio = document.getElementById('scanSuccessSound');
        audio.currentTime = 0; // Reset to the start of the sound
        audio.play(); // Play the sound
    }

    function onScanSuccess(decodedText, decodedResult) {
        console.log(`Code matched = ${decodedText}`, decodedResult);
        document.getElementById('qrcodeInput').value = decodedText;
        playScanSuccessSound(); // Play sound on successful scan
        document.getElementById('scanForm').submit();
    }

    function onScanFailure(error) {
        console.warn(`Code scan error = ${error}`);
    }

    document.getElementById('start-scan-btn').addEventListener('click', function() {
        document.getElementById('qr-reader').style.display = 'block'; // Show the QR reader
        const html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", { 
            fps: 10, 
            qrbox: 250 
        });
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    });
</script>
@endpush
