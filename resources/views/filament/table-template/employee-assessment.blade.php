{{-- Kolom tabel untuk Employee Assessed Data (Yang Sudah Dinilai) --}}
<div class="w-full">
    <table class="bordered-table" style="font-size: 14px;">
        <tr>
            <td style="width: 50px;">Nama</td>
            <td>{{$getRecord()->employee_name}}</td>
        </tr>
        <tr>
            <td>NIK</td>
            <td>{{$getRecord()->employee_nik}}</td>
        </tr>
        <tr>
            <td>Nilai</td>
            <td>{{$getRecord()->score}}</td>
        </tr>
        <tr>
            <td>Kriteria</td>
            <td>{{$getRecord()->criteria}}</td>
        </tr>
        <tr>
            <td>Section</td>
            <td>{{$getRecord()->employee_section}}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>
                    @if ($getRecord()->status == 'not_assessed')
                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">{{$getRecord()->status}}</span>
                    @elseif($getRecord()->status == 'on_progress')
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">{{$getRecord()->status}}</span>
                    @elseif($getRecord()->status == 'done')
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">{{$getRecord()->status}}</span>
                    @elseif($getRecord()->status == 'rejected')
                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">{{$getRecord()->status}}</span>
                    @elseif($getRecord()->status == 'approved')
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">{{$getRecord()->status}}</span>
                    @endif
            </td>
        </tr>
    </table>
    <div class="mt-2">
        @if ($this->page == 'Assessment')
            <a href="{{route('filament.admin.pages.assessment-detail', ['assessment' => $getRecord()->employee_assessment->slug, 'employee' => Crypt::encrypt($getRecord()->employee_id)])}}">
                <x-filament::button size="md" style="width: 100%;">
                    Detail
                </x-filament::button>
            </a>
        @elseif($this->page == 'Admin')
            <a href="{{route('filament.admin.pages.employee-assessment-result-detail', ['employee-assessed' => Crypt::encrypt($getRecord()->id)])}}">
                <x-filament::button size="md" style="width: 100%;">
                    Detail
                </x-filament::button>
            </a>
        @else
            <div class="flex gap-1">
                @if ($getRecord()->status != 'approved')
                <div class="w-1/2">
                    <a href="{{route('filament.admin.pages.assessment-approve-detail', ['employee-assessed' => Crypt::encrypt($getRecord()->id)])}}">
                        <x-filament::button size="md" style="width: 100%;">
                            Detail
                        </x-filament::button>
                    </a>
                </div>
                    <div class="w-1/2">
                        <x-filament::button size="md" style="width: 100%;" color="success" wire:click="approveConfirmation({{$getRecord()->id}})">
                            Approve
                        </x-filament::button>
                    </div>
                @else
                <div class="w-full">
                    <a href="{{route('filament.admin.pages.assessment-approve-detail', ['employee-assessed' => Crypt::encrypt($getRecord()->id)])}}">
                        <x-filament::button size="md" style="width: 100%;">
                            Detail
                        </x-filament::button>
                    </a>
                </div>
                @endif
            </div>
        @endif
    </div>
</div>