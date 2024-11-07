<div>
    {{-- Error Notification --}}
    <div x-data="{
        show_toast: @entangle('toast_error'),
        message: @entangle('toast_message'),
    }" x-init="">
        <div x-show="show_toast" class="fixed top-4 right-4 space-y-4">
            <div id="toast-danger" class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert" x-cloak>
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                    </svg>
                    <span class="sr-only">Error icon</span>
                </div>
                <div class="ms-3 text-sm font-normal"><span x-text="message"></span></div>
                <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-danger" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    {{-- Error Notification --}}
    {{-- Modal --}}
    <div x-data="{ showModal: @entangle('showModal') }">
        <div x-show="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
            <div wire:click.away="closeModal()" class="bg-white p-6 rounded shadow-lg lg:w-1/3">
                <h2 class="text-xl font-bold mb-4">Selesai Sesi Penilaian</h2>
                <div class="mb-4">
                    Konfirmasi: Anda akan menyelesaikan sesi penilaian ini. Apakah Anda yakin?
                </div>
                <div>                        
                </div>
                <div class="flex justify-end gap-1">
                    <button wire:click="closeModal()" class="bg-white text-gray-500 border border-gray-400 px-4 py-2 rounded mt-4">Tutup</button>
                    <button wire:click="finishTest()" class="bg-green-500 text-white px-4 py-2 rounded mt-4">Selesai Penilaian</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal --}}

    <div class="flex justify-center items-center h-screen w-full">
        <div class="bg-white p-5 lg:p-8 rounded-lg shadow-lg w-full lg:w-1/2 max-h-screen overflow-y-auto">
            <div class="flex justify-center mb-3">
                <div class="text-center">
                    <span class="text-2xl font-bold">PENILAIAN KARYAWAN</span>
                    <br>
                    <span>FAJAR AJI PRAYOGA | F201 | IT</span>
                </div>
            </div>
            <div class="bg-gray-50 border bordered mb-2">
                <div class="py-3 px-5 flex justify-between items-center">
                    <div>
                        <span wire:ignore>ASPEK : <strong>KETEPATAN</strong></span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">1 dari 10 pernyataan terjawab</span>
                    </div>
                </div>
                <div class="py-3 px-5 bg-white">
                    <div>
                        {{$question->question}}
                    </div>
                    <div>
                        <div class="py-3">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                                <thead>
                                    <tr class="border">
                                        <th class="py-3 w-10 border"></th>
                                        <th class="py-3 w-12 text-center border">Nilai</th>
                                        <th class="px-3 py-3 border">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($question->question_options as $option)
                                        <tr class="bg-white border">
                                            <td class="py-3 font-medium text-gray-900 text-center border"><input wire:model="" id="" type="radio" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300"></td>
                                            <td class="py-3 text-center border">{{$option->option}}</td>
                                            <td class="px-3 py-3 border">{{$option->content}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="">
                <div class="flex justify-between items-center">
                    <button wire:click="buttonPrevious('{{$question->id}}')" type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">Sebelumnya</button>
                    <button wire:click="buttonNext('{{$question->id}}')" type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">Selanjutnya</button>
                </div>
                <div class="flex flex-wrap justify-center gap-1 max-h-40 overflow-y-auto" wire:ignore>
                    @foreach ($all_question as $question)
                    <div>
                        <button wire:click="updateQuestion('{{$question['id']}}')" type="button" class="flex items-center justify-center w-11 h-11 leading-tight {{!empty($question->selected_option) ? 'bg-blue-500 text-white hover:bg-blue-400' : 'bg-red-500 text-white hover:bg-red-400 hover:text-white'}} border border-gray-300">{{$question['question_number']}}</button>
                    </div>
                    @endforeach
                </div>
                <div class="w-full mt-3">
                    <button wire:click="openModal()" type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 w-full">
                        SELESAI PENILAIAN</button>
                </div>
            </div>
        </div>
    </div>
</div>