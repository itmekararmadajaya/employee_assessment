<x-filament-panels::page>
    @if ($employee_assessed->status == 'on_progress')
        <div class="card">
            Karyawan ini belum dinilai. Mohon untuk segera melakukan penilaian
        </div>
    @elseif(($employee_assessed->status == 'done'))
    <div class="grid-2">
        <div>
            <div class="card">
                <div class="mb-5">
                    <span class="title">Employee Information</span>
                </div>
                <div>
                    <table class="w-full">
                        <tbody>
                            <tr class="bg-gray-50">
                                <td style="width: 100px;" class="px-6 py-4 text-sm font-medium text-gray-900">NIK</td>
                                <td>:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessed->employee_nik}}</td>
                            </tr>
                            <tr class="bg-white">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Name</td>
                                <td class="">:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessed->employee_name}}</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Position</td>
                                <td class="">:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessed->employee_position}}</td>
                            </tr>
                            <tr class="bg-white">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Section</td>
                                <td class="">:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessed->employee_section}}</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Departement</td>
                                <td class="">:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessed->employee_departement}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div>
            <div class="card">
                <div class="mb-5">
                    <span class="title">Assessor Information</span>
                </div>
                <div>
                    <table class="w-full">
                        <tbody>
                            <tr class="bg-gray-50">
                                <td style="width: 180px;" class="px-6 py-4 text-sm font-medium text-gray-900">NIK</td>
                                <td>:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessed->assessor_nik}}</td>
                            </tr>
                            <tr class="bg-white">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Name</td>
                                <td class="">:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessed->assessor_name}}</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Position</td>
                                <td class="">:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessed->assessor_position}}</td>
                            </tr>
                            <tr class="bg-white">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Section</td>
                                <td class="">:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessed->assessor_section}}</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Departement</td>
                                <td class="">:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessed->assessor_departement}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div>
            <div class="card">
                <div class="mb-5">
                    <span class="title">Assessment Information</span>
                </div>
                <div>
                    <table class="w-full">
                        <tbody>
                            <tr class="bg-gray-50">
                                <td style="width: 180px;" class="px-6 py-4 text-sm font-medium text-gray-900">Name</td>
                                <td>:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessed->employee_assessment->name}}</td>
                            </tr>
                            <tr class="bg-white">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Date</td>
                                <td>:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessed->assessment_date}}</td>
                            </tr>
                            <tr class="bg-white">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Score</td>
                                <td>:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessed->score}}</td>
                            </tr>
                            <tr class="bg-white">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Approve</td>
                                <td class="">:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @if ($employee_assessed->approved_by != "")
                                        <x-filament::icon-button
                                            icon="heroicon-o-check-circle"
                                            color="success"
                                            label="New label"
                                        />
                                    @else
                                        <x-filament::icon-button
                                            icon="heroicon-o-x-circle"
                                            color="danger"
                                            label="New label"
                                        />
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div>
            <div class="card">
                <div class="mb-5">
                    <span class="title">Score Detail</span>
                </div>
                <div>
                    <table class="w-full">
                        <tbody>
                            <tr class="bg-gray-50">
                                <td style="width: 180px;" class="px-6 py-4 text-sm font-medium text-gray-900">Criteria</td>
                                <td>:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$score_detail['criteria']}}</td>
                            </tr>
                            <tr class="bg-white">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Description</td>
                                <td class="">:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$score_detail['description']}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="mb-5">
            <span class="title">Assessment Result</span>
        </div>
        <table class="bordered-table">
            <thead>
                <tr>
                    <th style="width: 15%">
                        <div class="text-center">
                            Aspect
                        </div>
                    </th>
                    <th style="width: 75%">
                        <div>
                            Question
                        </div>
                    </th>
                    <th style="width: 5%">
                        <div class="text-center">
                            Value
                        </div>
                    </th>
                    <th style="width: 5%">
                        <div class="text-center">
                            Score
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employee_assessed_response as $key => $response)
                    <tr>
                        <td>
                            <div class="text-center">
                                {{$response->aspect}}
                            </div>
                        </td>
                        <td>
                            <div>
                                {{$response->question}}
                            </div>
                        </td>
                        <td>
                            <div class="text-center">
                                {{$response->option}}
                            </div>
                        </td>
                        <td>
                            <div class="text-center">
                                {{$response->score}}
                            </div>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2">
                        <div class="text-right font-bold">
                            Total
                        </div>
                    </td>
                    <td>
                        <div class="text-center">
                            {{$employee_assessed_response_summary['option']}}
                        </div>
                    </td>
                    <td>
                        <div class="text-center">
                            {{$employee_assessed_response_summary['score']}}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="card">
        <div class="mb-5">
            <span class="title">Score Detail</span>
        </div>
        <table class="bordered-table">
            <thead>
                <tr>
                    <th style="width: 10%">
                        <div class="text-center">
                            Range
                        </div>
                    </th>
                    <th>
                        <div class="text-center">
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
                            <div class="text-center">
                                {{$score->min}} - {{$score->max}}
                            </div>
                        </td>
                        <td>
                            <div class="text-center">
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
    </div>
    @endif
</x-filament-panels::page>
