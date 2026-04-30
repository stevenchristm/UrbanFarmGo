<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Binus Digital Score - Exercise</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; transition: all 0.3s ease; }
        body { 
            font-family: 'Poppins', sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            margin: 0; padding: 40px; min-height: 100vh;
            display: flex; flex-direction: column; align-items: center; color: #2d3436;
        }

        /* Branding Header */
        .brand-header { width: 100%; max-width: 1000px; display: flex; align-items: center; gap: 20px; margin-bottom: 40px; color: white; }
        .logo-box { background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); padding: 10px 20px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.3); }
        .logo-box b { font-size: 24px; letter-spacing: 2px; }
        h1 { font-size: 60px; margin: 0; font-weight: 800; opacity: 0.9; }

        .container { display: flex; gap: 30px; flex-wrap: wrap; justify-content: center; width: 100%; max-width: 1100px; }

        /* Estetika Card */
        .card { 
            background: rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(15px); 
            border-radius: 30px; 
            width: 450px; 
            box-shadow: 0 20px 50px rgba(0,0,0,0.2); 
            overflow: hidden; 
            border: 1px solid rgba(255,255,255,0.4);
        }
        .card:hover { transform: translateY(-10px); box-shadow: 0 30px 60px rgba(0,0,0,0.3); }
        .card-header { background: #30336b; color: white; padding: 25px; text-align: center; font-size: 20px; font-weight: 600; letter-spacing: 1px; }

        /* Form Styling */
        .card-body { padding: 35px; }
        .input-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        label { font-weight: 600; color: #4834d4; font-size: 16px; }
        input { 
            width: 100px; padding: 12px; border: 2px solid #dff9fb; border-radius: 15px; 
            text-align: center; font-size: 16px; font-weight: 600; background: #f9f9f9;
        }
        input:focus { outline: none; border-color: #4834d4; background: white; box-shadow: 0 0 15px rgba(72,52,212,0.2); }

        .btn-group { display: flex; gap: 15px; margin-top: 30px; }
        button { 
            flex: 1; padding: 15px; border-radius: 15px; border: none; font-weight: 700; cursor: pointer; font-size: 16px;
        }
        .btn-primary { background: #4834d4; color: white; box-shadow: 0 10px 20px rgba(72,52,212,0.3); }
        .btn-primary:hover { background: #686de0; }
        .btn-reset { background: #f1f2f6; color: #535c68; }

        /* Tabel Modern */
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 15px; color: #95afc0; font-size: 13px; text-transform: uppercase; }
        td { padding: 15px; border-top: 1px solid #f0f0f0; font-weight: 600; color: #30336b; }
        .badge { 
            padding: 6px 15px; border-radius: 10px; font-size: 14px; 
            background: #ebf0ff; color: #4834d4; 
        }
        .row-avg { background: #f6f8ff; border-top: 2px solid #4834d4; }

        .footer { margin-top: 60px; color: rgba(255,255,255,0.6); font-weight: 600; letter-spacing: 3px; font-size: 12px; }
    </style>
</head>
<body>

    <div class="brand-header">
        <div class="logo-box"><b>BINUS</b> UNIVERSITY</div>
        <h1>Exercise</h1>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">INPUT NILAI SISWA</div>
            <div class="card-body">
                <form action="/hitung" method="POST">
                    @csrf
                    @php $subjects = ['n1'=>'Matematika','n2'=>'Biologi','n3'=>'Fisika','n4'=>'Bhs Indonesia','n5'=>'Bhs Inggris']; @endphp
                    @foreach($subjects as $id => $name)
                    <div class="input-row">
                        <label>{{ $name }}</label>
                        <input type="number" name="{{ $id }}" min="0" max="100" value="{{ old($id) }}" required placeholder="0-100">
                    </div>
                    @endforeach
                    <div class="btn-group">
                        <button type="submit" class="btn-primary">HITUNG SKOR</button>
                        <button type="reset" class="btn-reset">RESET</button>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($hasil))
        <div class="card">
            <div class="card-header">HASIL EVALUASI</div>
            <div class="card-body" style="padding: 0;">
                <table>
                    <thead>
                        <tr>
                            <th>Mata Pelajaran</th>
                            <th style="text-align:center;">Skor</th>
                            <th style="text-align:center;">Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hasil as $h)
                        <tr>
                            <td>{{ $h['nama'] }}</td>
                            <td style="text-align:center;">{{ $h['angka'] }}</td>
                            <td style="text-align:center;"><span class="badge">{{ $h['huruf'] }}</span></td>
                        </tr>
                        @endforeach
                        <tr class="row-avg">
                            <td>RATA-RATA</td>
                            <td style="text-align:center;">{{ round($rata_rata, 1) }}</td>
                            <td style="text-align:center;"><span class="badge" style="background:#4834d4; color:white;">{{ $huruf_rata }}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <div class="footer">PEOPLE • INNOVATION • EXCELLENCE</div>

</body>
</html>