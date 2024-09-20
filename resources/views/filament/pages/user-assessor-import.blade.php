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
                    <td>nik</td>
                    <td><strong>Wajib</strong></td>
                    <td>NIK harus sudah ada didalam master data employee</td>
                </tr>
                <tr>
                    <td>email</td>
                    <td><strong>Wajib</strong></td>
                    <td>Wajib berformat email. Contoh : fajaraji.prayoga@gmail.com, fajar.aji.prayoga@newarmada.co.id</td>
                </tr>
                <tr>
                    <td>password</td>
                    <td><strong>Tidak Wajib</strong></td>
                    <td>Password minimal 6 digit. Jika di kosongkan maka password akan tergenerate otomatis</td>
                </tr>              
            </tbody>
        </table>

        <div class="mt-3">
            <span>Contoh : </span>
            <table class="bordered-table" style="width: 500px;">
                <thead>
                    <tr>
                        <th>nik</th>
                        <th>email</th>
                        <th>password</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>F201</td>  
                        <td>fajaraji.prayoga@gmail.com</td>  
                        <td>fa746</td>
                    </tr>    
                    <tr>
                        <td>K012</td>    
                        <td>kurnia@gmail.com</td>
                        <td></td>
                    </tr>    
                    <tr>
                        <td>I112</td>   
                        <td>intan@gmail.com</td>
                        <td>int64</td>
                    </tr>    
                    <tr>
                        <td>H089</td>   
                        <td>hasan@gmail.com</td> 
                        <td></td>
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
        <x-filament::button wire:click="importDiscQuestion" style="margin-right: 5px;">
            Import
        </x-filament::button>
        <a href="{{route('filament.admin.resources.users.index')}}">
            <x-filament::button color="gray">
                Back
            </x-filament::button>
        </a>
    </div>
</x-filament-panels::page>