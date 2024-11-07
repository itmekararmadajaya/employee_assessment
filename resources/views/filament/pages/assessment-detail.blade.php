<x-filament-panels::page>
    @if ($employee_assessed->status == 'on_progress' || $employee_assessed->status == 'not_assessed')
        <div class="card">
            Karyawan ini belum dinilai. Mohon untuk segera melakukan penilaian
        </div>
    @elseif($employee_assessed->status == 'done' || $employee_assessed->status == 'approved' || $employee_assessed->status == 'rejected')
    <div class="grid-2">
        <div class="mb-2">
            <div class="card">
                <div class="mb-5">
                    <span class="title">Informasi Karyawan</span>
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
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Nama</td>
                                <td class="">:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessed->employee_name}}</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Posisi</td>
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
        <div class="mb-2">
            <div class="card">
                <div class="mb-5">
                    <span class="title">Informasi Penilai (Assessor)</span>
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
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Nama</td>
                                <td class="">:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessed->assessor_name}}</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Posisi</td>
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
        <div class="mb-2">
            <div class="card">
                <div class="mb-5">
                    <span class="title">Periode Penilaian</span>
                </div>
                <div>
                    <table class="w-full">
                        <tbody>
                            <tr class="bg-gray-50">
                                <td style="width: 100px;" class="px-6 py-4 text-sm font-medium text-gray-900">Nama</td>
                                <td>:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessed->employee_assessment->name}}</td>
                            </tr>
                            <tr class="bg-white">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Tanggal</td>
                                <td>:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessed->assessment_date}}</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Status</td>
                                <td class="">:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @if ($employee_assessed->status == "approved")
                                        <x-filament::icon-button
                                            icon="heroicon-o-check-circle"
                                            color="success"
                                            label="Approved"
                                        />
                                        Approved by {{$employee_assessed->approver_name}} at {{$employee_assessed->approved_at}}
                                    @elseif ($employee_assessed->status == "rejected")
                                        <x-filament::icon-button
                                            icon="heroicon-o-x-circle"
                                            color="danger"
                                            label="Rejected"
                                        />
                                        Rejected by {{$employee_assessed->approver_name}} at {{$employee_assessed->approved_at}}
                                    @else
                                        
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
                    <span class="title">Skor Penilaian</span>
                </div>
                <div>
                    <table class="w-full">
                        <tbody>
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Skor</td>
                                <td>:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessed->score}}</td>
                            </tr>
                            <tr class="bg-white">
                                <td style="width: 100px;" class="px-6 py-4 text-sm font-medium text-gray-900">Kriteria</td>
                                <td>:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessed['criteria']}}</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Deskripsi</td>
                                <td class="">:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$employee_assessed['description']}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="mt-5">
                        @if ($employee_assessed->status == 'rejected')
                            <div class="bg-red-500 p-3 text-white text-sm mb-3">
                                Penilaian direject / ditolak oleh {{$employee_assessed->approver_name}}. Dengan remark <strong>{{$employee_assessed->rejected_msg}}</strong>. Silahkan untuk segera perbarui Penilaian.
                            </div>
                        @endif
                        @if ($employee_assessed->status != 'approved')
                            <x-filament::button color="warning" wire:click="openModalReassess">
                                Perbarui Nilai
                            </x-filament::button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="mb-5">
            <span class="title">Hasil Penilaian</span>
        </div>
        <div class="overflow-x-auto w-full">
            <table class="bordered-table">
                <thead>
                    <tr>
                        <th style="width: 15%">
                            <div>
                                Aspek
                            </div>
                        </th>
                        <th style="width: 75%">
                            <div>
                                Pernyataan
                            </div>
                        </th>
                        <th style="width: 5%">
                            <div class="text-center">
                                Nilai
                            </div>
                        </th>
                        <th style="width: 5%">
                            <div class="text-center">
                                Bobot
                            </div>
                        </th>
                        <th style="width: 5%">
                            <div class="text-center">
                                Skor
                            </div>
                        </th>
                    </tr>
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
                                <div class="text-center">
                                    {{$response->option}}
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    {{$response->weight}}
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
                                {{$employee_assessed_response_summary['weight']}}
                            </div>
                        </td>
                        <td>
                            <div class="text-center">
                                {{$employee_assessed_response_summary['score']}}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Deskripsi Pekerjaan
                        </td>
                        <td colspan="4">
                            {{$employee_assessed->job_description}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Komentar Penilai
                        </td>
                        <td colspan="4">
                            {{$employee_assessed->assessor_comments}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Komentar Penyetuju
                        </td>
                        <td colspan="4">
                            {{$employee_assessed->approver_comments}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="mb-5">
            <span class="title">Deskripsi Skor</span>
        </div>
        <div class="overflow-x-auto w-full">
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
                                Kriteria
                            </div>
                        </th>
                        <th>Deskripsi</th>
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
    </div>
    <div>
        <x-filament::button color="gray" wire:click="back">
            Kembali
        </x-filament::button>
    </div>
    @endif
    {{-- Modal --}}
    <div x-data="{ showModalReassess: @entangle('showModalReassess') }">
        <div x-show="showModalReassess" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50" x-cloak style="z-index: 20;">
            <div wire:click.away="closeModal()" class="bg-white p-6 rounded shadow-lg lg:w-1/3">
                <h2 class="text-xl font-bold mb-4">Perbarui Nilai</h2>
                <div class="mb-4">
                    Konfirmasi: Anda akan memperbarui nilai, nilai sebelumnya akan terhapus. Apakah Anda yakin?
                </div>
                <div>                        
                </div>
                <div class="flex justify-end gap-1">
                    <button wire:click="closeModalReassess()" class="bg-white text-gray-500 border border-gray-400 px-4 py-2 rounded mt-4">Tutup</button>
                    <button wire:click="reassess()" class="bg-green-500 text-white px-4 py-2 rounded mt-4">Perbarui Nilai</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal --}}
</x-filament-panels::page>
