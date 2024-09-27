<x-filament-panels::page>
    <div class="grid-2">
        <div>
            <div class="card">
                <div class="mb-5">
                    <span class="title">Assessment Information</span>
                </div>
                <div>
                    <table class="w-full">
                        <tbody>
                            <tr class="bg-gray-50">
                                <td style="width: 100px;" class="px-6 py-4 text-sm font-medium text-gray-900">Name</td>
                                <td>:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessment->name}}</td>
                            </tr>
                            <tr class="bg-white">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Description</td>
                                <td class="">:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessment->description}}</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td style="width: 100px;" class="px-6 py-4 text-sm font-medium text-gray-900">Time Open</td>
                                <td>:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessment->getFormattedTimeStartTest().', '.$employee_assessment->getFormattedDateStartTest()}}</td>
                            </tr>
                            <tr class="bg-white">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Time Close</td>
                                <td class="">:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessment->getFormattedTimeCloseTest().', '.$employee_assessment->getFormattedDateCloseTest()}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="mb-5">
                <span class="title">Status Information</span>
            </div>
            <div>
                <div class="mb-3 overflow-y-auto">
                    <table class="border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 p-2 text-center">Status</th>
                                <th class="border border-gray-300 p-2 text-center">Deskripsi</th>
                                <th class="border border-gray-300 p-2 text-center">Jumlah</th>
                                <th class="border border-gray-300 p-2 text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-300 p-2 text-center">
                                    <div>not_assessed</div>
                                </td>
                                <td class="border border-gray-300 p-2">
                                    <div>Belum dinilai</div>
                                </td>
                                <td class="border border-gray-300 p-2 text-right">
                                    <div class="text-center">
                                        {{$assessment_data['not_assessed']}}
                                    </div>
                                </td>
                                <td class="border border-gray-300 p-2 text-right">
                                    <div class="text-center">
                                        <a href="{{route('filament.admin.pages.employee-assessment-result', ['employee-assessment' => $employee_assessment->slug, 'status' => 'not_assessed'])}}" class="text-blue-500">lihat</a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 p-2 bg-blue-100 text-center">
                                    <div>on_progress</div>
                                </td>
                                <td class="border border-gray-300 p-2">
                                    <div>Sedang dinilai</div>
                                </td>
                                <td class="border border-gray-300 p-2 text-right">
                                    <div class="text-center">
                                        {{$assessment_data['on_progress']}}
                                    </div>
                                </td>
                                <td class="border border-gray-300 p-2 text-right">
                                    <div class="text-center">
                                        <a href="{{route('filament.admin.pages.employee-assessment-result', ['employee-assessment' => $employee_assessment->slug, 'status' => 'on_progress'])}}" class="text-blue-500">lihat</a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 p-2 bg-yellow-100 text-center">
                                    <div>done</div>
                                </td>
                                <td class="border border-gray-300 p-2">
                                    <div>Penilaian selesai, menunggu approve</div>
                                </td>
                                <td class="border border-gray-300 p-2 text-right">
                                    <div class="text-center">
                                        {{$assessment_data['done']}}
                                    </div>
                                </td>
                                <td class="border border-gray-300 p-2 text-right">
                                    <div class="text-center">
                                        <a href="{{route('filament.admin.pages.employee-assessment-result', ['employee-assessment' => $employee_assessment->slug, 'status' => 'done'])}}" class="text-blue-500">lihat</a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 p-2 bg-green-100 text-center">
                                    <div>approved</div>
                                </td>
                                <td class="border border-gray-300 p-2">
                                    <div>Penilaian telah diapprove/disetujui</div>
                                </td>
                                <td class="border border-gray-300 p-2 text-right">
                                    <div class="text-center">
                                        {{$assessment_data['approved']}}
                                    </div>
                                </td>
                                <td class="border border-gray-300 p-2 text-right">
                                    <div class="text-center">
                                        <a href="{{route('filament.admin.pages.employee-assessment-result', ['employee-assessment' => $employee_assessment->slug, 'status' => 'approved'])}}" class="text-blue-500">lihat</a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 p-2 bg-red-100 text-center">
                                    <div>rejected</div>
                                </td>
                                <td class="border border-gray-300 p-2">
                                    <div>Penilaian telah direject/ditolak</div>
                                </td>
                                <td class="border border-gray-300 p-2 text-right">
                                    <div class="text-center">
                                        {{$assessment_data['rejected']}}
                                    </div>
                                </td>
                                <td class="border border-gray-300 p-2 text-right">
                                    <div class="text-center">
                                        <a href="{{route('filament.admin.pages.employee-assessment-result', ['employee-assessment' => $employee_assessment->slug, 'status' => 'rejected'])}}" class="text-blue-500">lihat</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>             
            </div>
        </div>
    </div>
    <div class="card">
        <div class="mb-3">
            <span class="title"><section class="title">Employee Assessment Status {{$status == '' ? 'not_assessed' : $status}}</section></span>
        </div>
        <div>
            {{$this->table}}
        </div>
    </div>
    <div>
        <x-filament::button color="gray" wire:click="back">
            Back
        </x-filament::button>
    </div>
</x-filament-panels::page>
