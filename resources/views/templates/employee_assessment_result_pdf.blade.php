<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Employee Assessment Report</title>
    <style>
        body {
            font-family: 'Arial, sans-serif';
            font-size: 11px;
        }
        .bordered-table {
        width: 100%;
        border-collapse: collapse;
        }

        .bordered-table th,
        .bordered-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
        }

        .bordered-table th {
        background-color: #f2f2f2;
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
    <div>
        <table class="bordered-table">
            <tr>
                <td colspan="2" rowspan="2">
                    <div style="text-align: center; width: 100%; vertical-align: middle;">
                        <img src="{{public_path("assets/logo/logona2.png")}}" alt="Logo" height="20px">
                    </div>
                </td>
                <td colspan="4" rowspan="2">
                    <div style="text-align: center">
                        <span style="font-weight: 500;">PERFORMANCE APPRAISSAL</span>
                    </div>
                </td>
                <td style="width: 10%;">
                    <div>
                        NO
                    </div>
                </td>
                <td style="width: 20%;">
                    <div>
                        
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div>
                        Berlaku
                    </div>
                </td>
                <td>
                    <div>
                        
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div>
                        Dept
                    </div>
                </td>
                <td colspan="5">
                    {{$employee_assessed->employee_departement}}
                </td>
                <td>
                    <div>
                        Tanggal
                    </div>
                </td>
                <td>
                    <div>
                        16-01-2000
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 10px;">
                    <div>
                        Nama
                    </div>
                </td>
                <td colspan="5">
                    <div>
                        {{$employee_assessed->employee_name}}
                    </div>
                </td>
                <td>
                    <div>
                        NIK
                    </div>
                </td>
                <td>
                    <div>
                        {{$employee_assessed->employee_nik}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div>
                        Jabatan
                    </div>
                </td>
                <td colspan="5">
                    <div>
                        {{$employee_assessed->employee_position}}
                    </div>
                </td>
                <td>
                    <div>
                        PA
                    </div>
                </td>
                <td>
                    <div>
                        {{$employee_assessed->employee_assessment->name}}
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div style="margin-top: 5px;">
        <table class="bordered-table">
            <thead>
                <th>
                    <div>
                        Aspek
                    </div>
                </th>
                <th>
                    <div>
                        Pengertian
                    </div>
                </th>
                <th>
                    <div style="text-align: center;">
                        Nilai Aktual
                    </div>
                </th>
                <th>
                    <div style="text-align: center;">
                        Bobot
                    </div>
                </th>
                <th>
                    <div style="text-align: center;">
                        Nilai Pembobotan
                    </div>
                </th>
            </thead>
            <tbody>
                @foreach ($employee_assessed_response as $key => $response)
                    <tr>
                        <td>
                            <div>
                                {{$response->aspect}}
                            </div>
                        </td>
                        <td>
                            <div>
                                {{$response->question}}
                            </div>
                        </td>
                        <td>
                            <div style="text-align: center;">
                                {{$response->option}}
                            </div>
                        </td>
                        <td>
                            <div style="text-align: center;">
                                {{$response->weight}}
                            </div>
                        </td>
                        <td>
                            <div style="text-align: center;">
                                {{$response->score}}
                            </div>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" style="text-align: right;">
                        <div style="font-weight: bold;">
                            Total
                        </div>
                    </td>
                    <td>
                        <div style="text-align: center;">
                            {{$employee_assessed_response_summary['option']}}
                        </div>
                    </td>
                    <td>
                        <div style="text-align: center;">
                            {{$employee_assessed_response_summary['weight']}}
                        </div>
                    </td>
                    <td>
                        <div style="text-align: center;">
                            {{$employee_assessed_response_summary['score']}}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div>
        <div style="margin: 8px 0px;">
            <span style="font-weight: 500; font-size: 16px;">Kesimpulan</span>
        </div>
        <div>
            <table class="bordered-table">
                <thead>
                    <tr>
                        <th>
                            <div style="text-align: center;">
                                Total Score
                            </div>
                        </th>
                        <th>
                            <div style="text-align: center;">
                                Kriteria
                            </div>
                        </th>
                        <th>
                            <div>
                                Deskripsi
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div style="text-align: center;">
                                {{$employee_assessed->score}}
                            </div>
                        </td>
                        <td>
                            <div style="text-align: center;">
                                {{$score_detail['criteria']}}
                            </div>
                        </td>
                        <td>
                            <div>
                                {{$score_detail['description']}}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div>
        <table class="bordered-table" style="margin: 20px 0px; font-weight: 500; width: 100%;">
            <thead>
                <tr>
                    <td colspan="2" style="width: 25%;">
                        <div style="text-align: center;">YANG DIEVALUASI</div>
                    </td>
                    <td colspan="2" style="width: 25%;">
                        <div style="text-align: center;">YANG MENGEVALUASI</div>
                    </td>
                    <td colspan="2" style="width: 25%;">
                        <div style="text-align: center;">DEPT/DIV HEAD</div>
                    </td>
                    <td colspan="2" style="width: 25%;">
                        <div style="text-align: center;">HUMAN CAPITAL</div>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width: 10%; text-align: center;">
                        <div>
                            NAMA
                        </div>
                    </td>
                    <td style="width: 15%;">
                        <div>
                            {{$employee_assessed->employee_name}}
                        </div>
                    </td>
                    <td style="width: 10%; text-align: center;">
                        <div>
                            NAMA
                        </div>
                    </td>
                    <td style="width: 15%;">
                        <div>
                            {{$employee_assessed->assessor_name}}
                        </div>
                    </td>
                    <td style="width: 10%; text-align: center;">
                        <div>
                            NAMA
                        </div>
                    </td>
                    <td style="width: 15%;">
                        <div>
                            {{$employee_assessed->approver_name}}
                        </div>
                    </td>
                    <td style="width: 10%; text-align: center;">
                        <div>
                            NAMA
                        </div>
                    </td>
                    <td style="width: 15%;">
                        <div>
                            
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="width: 10%; height: 50px; text-align: center;">
                        <div>
                            TTD
                        </div>
                    </td>
                    <td style="width: 15%;">
                        <div>
                            
                        </div>
                    </td>
                    <td style="width: 10%; text-align: center;">
                        <div>
                            TTD
                        </div>
                    </td>
                    <td style="width: 15%;">
                        <div>
                            
                        </div>
                    </td>
                    <td style="width: 10%; text-align: center;">
                        <div>
                            TTD
                        </div>
                    </td>
                    <td style="width: 15%;">
                        <div>
                            
                        </div>
                    </td>
                    <td style="width: 10%; text-align: center;">
                        <div>
                            TTD
                        </div>
                    </td>
                    <td style="width: 15%;">
                        <div>
                            
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    {{-- <div style="margin-top: 5px;">
        <table class="bordered-table">
            <thead>
                <tr>
                    <th style="width: 10%">
                        <div style="text-align: center;">
                            Range
                        </div>
                    </th>
                    <th style="width: 5%">
                        <div style="text-align: center;">
                            Criteria
                        </div>
                    </th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($score_description as $score)
                    <tr>
                        <td>
                            <div style="text-align: center;">
                                {{$score->min}} - {{$score->max}}
                            </div>
                        </td>
                        <td>
                            <div style="text-align: center;">
                                {{$score->criteria}}
                            </div>
                        </td>
                        <td>
                            <div class="">
                                {{$score->description}}
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div> --}}
</body>
</html>
