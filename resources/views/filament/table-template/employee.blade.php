{{-- Kolom tabel untuk Employee Assessed Data (Yang Belum Dinilai) --}}
<div class="w-full">
    <table class="bordered-table" style="font-size: 14px;">
        <tr>
            <td style="width: 50px;">Nama</td>
            <td>{{$getRecord()->name}}</td>
        </tr>
        <tr>
            <td>NIK</td>
            <td>{{$getRecord()->nik}}</td>
        </tr>
        <tr>
            <td>Section</td>
            <td>{{$getRecord()->section->name}}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>
                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">not_assessed</span>
            </td>
        </tr>
    </table>
    <div class="mt-2">
        @if (auth()->user()->hasRole(['assessor']))
            <a href="{{route('filament.admin.pages.assessment-detail', ['assessment' => $this->assessment->slug, 'employee' => Crypt::encrypt($getRecord()->id)])}}">
                <x-filament::button size="md" style="width: 100%;">
                    Nilai
                </x-filament::button>
            </a>
        @elseif(auth()->user()->hasRole(['admin', 'superadmin']))
            <a href="{{route('filament.admin.pages.assessment-detail', ['assessment' => $this->assessment->slug, 'employee' => Crypt::encrypt($getRecord()->id)])}}">
                <x-filament::button size="md" style="width: 100%;">
                    Nilai
                </x-filament::button>
            </a>
        @endif
    </div>
</div>