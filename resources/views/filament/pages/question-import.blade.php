<x-filament-panels::page>
    <div class="card">
        <span class="title">Instruksi Import</span>
        <div style="margin: 12px 0px">
            <ul class="list-disc list-inside">
                <li>Pastikan file yang diimport sesuai dengan <strong>template (.xlsx)</strong>. Template dapat diundun <button wire:click="downloadTemplate" style="color: blue">disini</button>.</li>
                <li>Harap mengisi template tanpa mengubah/menghapus baris pertama</li>
                <li>Jika ada data yang duplikat, data terakhir yang akan masuk ke database</li>
                <li>Keterangan tiap kolom excel :</li>
            </ul>
        </div>
        <table class="bordered-table">
            <thead>
                <th>Nama Kolom</th>
                <th>Status</th>
                <th>Keterangan</th>
            </thead>
            <tbody>
                <tr>
                    <td>level</td>
                    <td><strong>Wajib</strong></td>
                    <td>Hanya bisa diisi {{$level}} <strong>(PASTIKAN BESAR KECIL HURUF SAMA)</strong></td>
                </tr>
                <tr>
                    <td>aspect</td>
                    <td><strong>Wajib</strong></td>
                    <td>Diisi dengan aspek penilaian</td>
                </tr>
                <tr>
                    <td>question</td>
                    <td><strong>Wajib</strong></td>
                    <td>Diisi denga deskripsi/pengertian</td>
                </tr>
                <tr>
                    <td>weight</td>
                    <td><strong>Wajib</strong></td>
                    <td>Bobot nilai</td>
                </tr>              
                <tr>
                    <td>1</td>
                    <td><strong>Wajib</strong></td>
                    <td>Keterangan Standar Nilai 1</td>
                </tr>              
                <tr>
                    <td>2</td>
                    <td><strong>Wajib</strong></td>
                    <td>Keterangan Standar Nilai 2</td>
                </tr>              
                <tr>
                    <td>3</td>
                    <td><strong>Wajib</strong></td>
                    <td>Keterangan Standar Nilai 3</td>
                </tr>              
                <tr>
                    <td>4</td>
                    <td><strong>Wajib</strong></td>
                    <td>Keterangan Standar Nilai 4</td>
                </tr>              
                <tr>
                    <td>5</td>
                    <td><strong>Wajib</strong></td>
                    <td>Keterangan Standar Nilai 5</td>
                </tr>              
            </tbody>
        </table>

        <div class="mt-3">
            <span>Contoh : </span>
            <table class="bordered-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th><div class="text-center">level</div></th>
                        <th><div class="text-center">aspect</div></th>
                        <th><div class="text-center">question</div></th>
                        <th><div class="text-center">weight</div></th>
                        <th><div class="text-center">1</div></th>
                        <th><div class="text-center">2</div></th>
                        <th><div class="text-center">3</div></th>
                        <th><div class="text-center">4</div></th>
                        <th><div class="text-center">5</div></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>STAFF</td>
                        <td>
                            <div  class="text-center">
                                Ketepatan
                            </div>
                        </td>  
                        <td>
                            <div class="text-left">
                                Hadir tepat pada waktu yang sudah ditetapkan
                            </div>    
                        </td>  
                        <td>
                            <div class="text-center">
                                5
                            </div>
                        </td>
                        <td>
                            <div>
                                Sering terlambat, lebih dari 5 kali dalam 1 bulan
                            </div>
                        </td>
                        <td>
                            <div>
                                Beberapa kali terlambat, 3 s/d 4 kali dalam 1 bulan
                            </div>
                        </td>
                        <td>
                            <div>
                                Pernah terlambat, 1 s/d 2 kali dalam 1 bulan
                            </div>
                        </td>
                        <td>
                            <div>
                                Selalu hadir tepat waktu sesuai jam kerja yang berlaku
                            </div>
                        </td>
                        <td>
                            <div>
                                Selalu hadir sebelum waktu masuk
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>LEADER</td>
                        <td>
                            <div  class="text-center">
                                Komitmen
                            </div>
                        </td>  
                        <td>
                            <div class="text-left">
                                Rasa tanggung jawab terhadap pekerjaan, tingkat pengutamaan kepentingan pekerjaan diatas kepentingan lainnya
                            </div>    
                        </td>  
                        <td>
                            <div class="text-center">
                                10
                            </div>
                        </td>
                        <td>
                            <div>
                                Tanggung jawab kurang, sering lalai sekalipun dalam tugas rutin
                            </div>
                        </td>
                        <td>
                            <div>
                                Cukup bertanggung jawab, cukup mengutamakan pekerjaan walau sesekali lalai untuk tugas-tugas tertentu
                            </div>
                        </td>
                        <td>
                            <div>
                                Bertanggung jawab, sering mengutamakan kepentingan pekerjaan baik untuk tugas  rutin maupun non rutin
                            </div>
                        </td>
                        <td>
                            <div>
                                Sangat bertanggung jawab, selalu mengutama-kan kepentingan pekerjaan di atas kepentingan lainnya
                            </div>
                        </td>
                        <td>
                            <div>
                                Mampu menciptakan komitment
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="grid-2">
        <div class="card">
            <div class="">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload file</label>
                <input wire:model="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="file_input_help" id="file_input" type="file">
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">Upload file sesuai template (.xlsx)</p>
                @error('file') <span class="fi-fo-field-wrp-error-message text-sm text-danger-600">{{ $message }}</span> @enderror
            </div> 
        </div>
        <div>
            @if ($success_imports != "")
                <div class="card">
                    <strong style="color: green" class="text-success-600">{{$success_imports}}</strong>
                </div>
            @endif
            @if ($error_imports != "")
            <div class="card">
                <span class="title">Report Error</span>
                <table class="bordered-table">
                    <thead>
                        <th>Row</th>
                        <th>Column</th>
                        <th>Error</th>
                    </thead>
                    <tbody>
                        @foreach ($error_imports as $error)
                            <tr>
                                <td>{{$error['row']}}</td>
                                <td>{{$error['attribute']}}</td>
                                <td>
                                    <div>
                                        @foreach ($error['errors'] as $msg)
                                            <ul>
                                                <li>{{$msg}}</li>
                                            </ul>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div>
                    <strong>Silahkan perbaiki data tersebut, kemudian upload ulang.</strong>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="">
        <x-filament::button wire:click="importDiscNormaStandard" style="margin-right: 5px;">
            Import
        </x-filament::button>
        <a href="{{route('question')}}">
            <x-filament::button color="gray">
                Back
            </x-filament::button>
        </a>
    </div>
</x-filament-panels::page>