<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Ticket {{ $transaction->booking_code }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; line-height: 1.5; font-size: 14px; }
        .header { width: 100%; border-bottom: 2px solid #0d6efd; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { font-size: 28px; font-weight: 900; color: #0d6efd; letter-spacing: 1px; }
        .title { font-size: 20px; font-weight: bold; text-align: right; color: #555; text-transform: uppercase; }
        .box { border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 8px; background-color: #fafafa; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; }
        .border-table th, .border-table td { border: 1px solid #ddd; }
        .bg-light { background-color: #e9ecef; }
        .text-center { text-align: center; }
        .badge-success { background-color: #198754; color: white; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .text-primary { color: #0d6efd; }
    </style>
</head>
<body>

    {{-- HEADER KOP SURAT --}}
    <table class="header">
        <tr>
            <td class="logo">✈️ FLIGHT HUB</td>
            <td class="title">Boarding Pass / E-Tiket</td>
        </tr>
    </table>

    {{-- INFO BOOKING --}}
    <table style="margin-bottom: 20px;">
        <tr>
            <td>
                <span style="color: #777; font-size: 12px;">KODE BOOKING</span><br>
                <span style="font-size: 26px; color: #0d6efd; font-weight: 900; letter-spacing: 2px;">{{ $transaction->booking_code }}</span>
            </td>
            <td style="text-align: right;">
                <span style="color: #777; font-size: 12px;">STATUS PEMBAYARAN</span><br>
                <span class="badge-success">LUNAS</span><br>
                <span style="font-size: 12px; color: #777; display: block; margin-top: 5px;">Dicetak: {{ now()->format('d M Y, H:i') }}</span>
            </td>
        </tr>
    </table>

    {{-- DETAIL PENERBANGAN --}}
    <div class="box">
        <h3 style="margin-top: 0; color: #333; border-bottom: 1px solid #ccc; padding-bottom: 5px;">✈️ Informasi Penerbangan</h3>
        <table>
            <tr style="color: #555; font-size: 12px;">
                <th>MASKAPAI</th>
                <th>KEBERANGKATAN</th>
                <th>TUJUAN</th>
            </tr>
            <tr>
                <td>
                    <strong style="font-size: 16px;">{{ $transaction->flight->airline->name }}</strong><br>
                    <span>Kelas Ekonomi</span>
                </td>
                <td>
                    <strong style="font-size: 16px;">{{ $transaction->flight->departureAirport->city }} ({{ $transaction->flight->departureAirport->iata_code ?? '' }})</strong><br>
                    <span style="font-size: 12px;">{{ $transaction->flight->departureAirport->name }}</span>
                </td>
                <td>
                    <strong style="font-size: 16px;">{{ $transaction->flight->arrivalAirport->city }} ({{ $transaction->flight->arrivalAirport->iata_code ?? '' }})</strong><br>
                    <span style="font-size: 12px;">{{ $transaction->flight->arrivalAirport->name }}</span>
                </td>
            </tr>
        </table>
    </div>

    {{-- DAFTAR PENUMPANG --}}
    <div class="box" style="background-color: white;">
        <h3 style="margin-top: 0; color: #333;">👥 Daftar Penumpang</h3>
        <table class="border-table">
            <tr class="bg-light">
                <th class="text-center" style="width: 10%;">No.</th>
                <th style="width: 45%;">Nama Penumpang</th>
                <th style="width: 20%;">Tipe</th>
                <th class="text-center" style="width: 25%;">Nomor Kursi</th>
            </tr>
            @foreach($transaction->passengers as $index => $pax)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $pax->title ?? '' }} {{ $pax->name }}</strong></td>
                <td>{{ $pax->type }}</td>
                <td class="text-center" style="font-size: 18px; font-weight: bold; color: #0d6efd;">
                    {{ $pax->seat_number ?? 'Bebas' }}
                </td>
            </tr>
            @endforeach
        </table>
    </div>

    {{-- INFO PEMESAN & SYARAT KETENTUAN --}}
    <table style="margin-top: 30px; width: 100%;">
        <tr>
            <td style="width: 40%; vertical-align: top;">
                <strong>Detail Kontak:</strong><br>
                <span style="font-size: 12px;">
                    Nama: {{ $transaction->contact_name }}<br>
                    Email: {{ $transaction->contact_email }}<br>
                    Telp: {{ $transaction->contact_phone }}
                </span>
            </td>
            <td style="width: 60%; vertical-align: top; font-size: 11px; color: #555; background-color: #fcfcfc; padding: 10px; border: 1px dashed #ccc;">
                <strong>Penting:</strong>
                <ul style="padding-left: 15px; margin-bottom: 0;">
                    <li>Harap tiba di bandara 90 menit sebelum jadwal.</li>
                    <li>Tunjukkan E-Tiket ini (bisa dari HP) dan KTP asli saat Check-in.</li>
                    <li>Bagasi kabin maksimal 7kg.</li>
                    <li>Tiket yang dibatalkan tunduk pada kebijakan perusahaan.</li>
                </ul>
            </td>
        </tr>
    </table>

</body>
</html>