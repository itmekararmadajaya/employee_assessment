<x-filament-panels::page>
    <div class="grid-2">
            <div>
                <div class="card" style="background-color: #DCFCE7;" x-data="{ blinking: true }" x-init="blinking = true" :class="blinking ? 'animate-pulse' : ''">
                    <div>
                        <span class="title">Approval Information</span>
                    </div>
                    <div>
                        Ada {{$count_must_be_approve}} karyawan yang penilaiannya perlu anda approve/setujui. SIlahkan menuju ke halaman <strong><a href="{{route('filament.admin.pages.assessment-approve', ['assessment' => $assessment->slug])}}" class="text-blue-500">Approve</a></strong>
                    </div>
                </div>
            </div>
        <div>
            <div class="card">
                <div class="mb-3">
                    <span class="title">Assessment Data</span>
                </div>
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
                                        <a href="{{route('filament.admin.pages.assessment', ['assessment' => $assessment->slug, 'status' => 'not_assessed'])}}" class="text-blue-500">lihat</a>
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
                                        <a href="{{route('filament.admin.pages.assessment', ['assessment' => $assessment->slug, 'status' => 'on_progress'])}}" class="text-blue-500">lihat</a>
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
                                        <a href="{{route('filament.admin.pages.assessment', ['assessment' => $assessment->slug, 'status' => 'done'])}}" class="text-blue-500">lihat</a>
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
                                        <a href="{{route('filament.admin.pages.assessment', ['assessment' => $assessment->slug, 'status' => 'approved'])}}" class="text-blue-500">lihat</a>
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
                                        <a href="{{route('filament.admin.pages.assessment', ['assessment' => $assessment->slug, 'status' => 'rejected'])}}" class="text-blue-500">lihat</a>
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
            <section class="title">Employee Assessment Status {{$status == '' ? 'not_assessed' : $status}}</section>
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
