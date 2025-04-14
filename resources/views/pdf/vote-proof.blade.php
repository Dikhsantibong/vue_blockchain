<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vote Proof #{{ $vote->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #4f46e5;
        }
        .title {
            font-size: 24px;
            color: #4f46e5;
            margin-bottom: 10px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 18px;
            color: #4f46e5;
            margin-bottom: 10px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 150px auto;
            gap: 10px;
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
            color: #666;
        }
        .value {
            color: #333;
        }
        .blockchain-info {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .qr-code {
            text-align: center;
            margin-top: 30px;
        }
        .qr-code img {
            width: 200px;
            height: 200px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Digital Vote Proof</div>
        <div>Generated on {{ $timestamp }}</div>
    </div>

    <div class="section">
        <div class="section-title">Election Information</div>
        <div class="info-grid">
            <div class="label">Election Title:</div>
            <div class="value">{{ $vote->election->title }}</div>
            
            <div class="label">Start Date:</div>
            <div class="value">{{ $vote->election->start_date }}</div>
            
            <div class="label">End Date:</div>
            <div class="value">{{ $vote->election->end_date }}</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Vote Details</div>
        <div class="info-grid">
            <div class="label">Voter Name:</div>
            <div class="value">{{ $vote->voter->name }}</div>
            
            <div class="label">Candidate:</div>
            <div class="value">{{ $vote->candidate->name }}</div>
            
            <div class="label">Vote Time:</div>
            <div class="value">{{ $vote->timestamp }}</div>
        </div>
    </div>

    <div class="section blockchain-info">
        <div class="section-title">Blockchain Verification</div>
        <div class="info-grid">
            <div class="label">Block Hash:</div>
            <div class="value" style="word-break: break-all;">{{ $vote->hash }}</div>
            
            <div class="label">Previous Hash:</div>
            <div class="value" style="word-break: break-all;">{{ $vote->previous_hash }}</div>
            
            <div class="label">Nonce:</div>
            <div class="value">{{ $vote->nonce }}</div>
        </div>
    </div>

    <div class="qr-code">
        <img src="{{ $qr_path }}" alt="Verification QR Code">
        <p>Scan to verify vote on blockchain</p>
    </div>

    <div class="footer">
        <p>This is an official digital proof of your vote. The authenticity of this document can be verified using the blockchain information provided above.</p>
    </div>
</body>
</html> 