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
                    <td>assessment_date</td>
                    <td><strong>Wajib</strong></td>
                    <td>Diisi tanggal menilai</td>
                </tr>
                <tr>
                    <td>employee_nik</td>
                    <td><strong>Wajib</strong></td>
                    <td>Pastikan nik ada di master data employee, semua data seperti nama, posisi, section, dan departement akan sama dengan master data tersebut</td>
                </tr>
                <tr>
                    <td>assessor_nik</td>
                    <td><strong>Wajib</strong></td>
                    <td>Pastikan nik ada di master data employee, semua data seperti nama, posisi, section, dan departement akan sama dengan master data tersebut</td>
                </tr>
                <tr>
                    <td>approver_nik</td>
                    <td><strong>Wajib</strong></td>
                    <td>Pastikan nik ada di master data employee, semua data seperti nama, posisi, section, dan departement akan sama dengan master data tersebut</td>
                </tr>
                <tr>
                    <td>approver_at</td>
                    <td><strong>Wajib</strong></td>
                    <td>Diisi tanggal waktu penilaian disetujui</td>
                </tr>
                <tr>
                    <td>score</td>
                    <td><strong>Wajib</strong></td>
                    <td>Skor penilaian</td>
                </tr>
                <tr>
                    <td>criteria</td>
                    <td><strong>Wajib</strong></td>
                    <td>Kriteria berdasarkan skor</td>
                </tr>
                <tr>
                    <td>description</td>
                    <td><strong>Wajib</strong></td>
                    <td>Deskripsi kriteria</td>
                </tr>
                <tr>
                    <td>job_description</td>
                    <td>Wajib</td>
                    <td>Diisi deskripsi pekerjaan karyawan</td>
                </tr>
                <tr>
                    <td>assessor_comments</td>
                    <td>Tidak Wajib</td>
                    <td>Diisi komentar dari penilai</td>
                </tr>
                <tr>
                    <td>approver_comments</td>
                    <td>Tidak Wajib</td>
                    <td>Diisi komentar dari penyetuju</td>
                </tr>
                <tr>
                    <td><strong>Mengisi Aspek</strong></td>
                    <td><strong>Wajib</strong></td>
                    <td>Silahkan isi</td>
                </tr>
            </tbody>
        </table>

        <div class="mt-3">
            <span>Contoh : </span>
            <table class="bordered-table" style="width: 50%;">
                <thead>
                    <tr>
                        <th><div class="text-left">name</div></th>
                        <th><div class="text-left">division</div></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>BUSINESS DEVELOPMENT</td>
                        <td>MARKETING & SALES</td>
                    </tr>
                    <tr>
                        <td>CAROSERIES PRODUCTION</td>
                        <td>CAROSERIES PRODUCTION</td>
                    </tr>
                    <tr>
                        <td>COMMERCIAL</td>
                        <td>STAMPING & TOOLS</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
