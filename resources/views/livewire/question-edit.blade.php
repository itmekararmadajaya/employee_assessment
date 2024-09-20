<div class="bg-gray-100 p-8 h-screen">
    <div x-data="{
        show_toast: @entangle('toast_success'),
        message: @entangle('toast_message'),
    }" x-init="">
        <div x-cloak x-show="show_toast" class="fixed top-4 right-4 space-y-4">
            <div id="toast-success" class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                    </svg>
                    <span class="sr-only">Check icon</span>
                </div>
                <div x-text="message" class="ms-3 text-sm font-normal"></div>
                <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-success" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>    
    {{-- Modal --}}
    <div x-data="{ showModal: @entangle('showModal') }">
        <div x-cloak class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-50" x-show="showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <!-- Modal Container -->
            <div class="bg-white rounded-lg shadow-lg w-1/3">
                <!-- Modal Header -->
                <div class="px-4 py-2 border-b">
                    <h2 class="text-lg font-semibold text-gray-800">Confirm Deletion</h2>
                </div>
                <!-- Modal Body -->
                <div class="p-4">
                    <p>Are you sure you want to delete this question?</p>
                </div>
                <!-- Modal Footer -->
                <div class="px-4 py-2 border-t flex justify-end">
                    <button class="px-4 py-2 mr-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300" wire:click="funcCloseModal">Cancel</button>
                    <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700" wire:click="deleteQuestion">Delete</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal --}}
    <div class="lg:grid lg:grid-cols-2 lg:gap-3">
        <div>
            <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow mb-4">
                <h5 class="mb-2 text-3xl font-bold tracking-tight text-gray-900">Update Question</h5>
                <div class="mt-5">
                    <form wire:submit="update">
                        <div class="mb-3 w-1/2">
                            <label for="level" class="block mb-1 text-sm font-medium text-gray-950 dark:text-white">Question Level<sup class="text-red-600 font-medium">*</sup></label>
                            <select wire:model="level" id="level" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5">
                                @foreach ($levels as $key => $level)
                                    <option value="{{$level->id}}">{{$level->name}}</option>
                                @endforeach
                            </select>
                            <div class="text-red-500 text-sm">
                                @error('level') <span class="error">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="mb-3 w-1/2">
                            <label for="aspect" class="block mb-2 text-sm font-medium text-gray-900">Aspect<sup class="text-red-600 font-medium">*</sup></label>
                            <input wire:model="aspect" type="text" id="aspect" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5" placeholder="" />
                            <div class="text-red-500 text-sm">
                                @error('aspect') <span class="error">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description<sup class="text-red-600 font-medium">*</sup></label>
                            <textarea wire:model="description" id="description" rows="4" class="block p-2.5 w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500" placeholder=""></textarea>
                            <div class="text-red-500 text-sm">
                                @error('description') <span class="error">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="weight" class="block mb-2 text-sm font-medium text-gray-900">Weight<sup class="text-red-600 font-medium">*</sup></label>
                            <input wire:model="weight" type="number" id="weight" class="w-14 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block p-2.5" placeholder="" />
                            <div class="text-red-500 text-sm">
                                @error('weight') <span class="error">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-5">
                            <div>
                                <span>This field <sup class="text-red-600 dark:text-danger-400 font-medium">*</sup> is required.</span>
                            </div>
                            <div class="inline-flex gap-1 rounded-md shadow-sm" role="group">
                                <a href="{{route('question')}}">
                                    <button type="button" class="inline-flex gap-1 items-center px-4 py-2 text-sm font-medium text-gray-900 bg-gray-100 border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700">
                                        Back
                                    </button>
                                </a>
                                <button wire:click="funcShowModal" type="button" class="inline-flex gap-1 items-center px-4 py-2 text-sm font-medium text-white bg-red-500 border border-white rounded-lg hover:bg-red-400">
                                    Delete
                                </button>
                                <a href="{{route('question-create')}}">
                                    <button type="button" class="inline-flex gap-1 items-center px-4 py-2 text-sm font-medium text-white bg-green-500 border border-white rounded-lg hover:bg-green-400">
                                        Create Other Question
                                    </button>
                                </a>
                                <button type="submit" class="inline-flex gap-1 items-center px-4 py-2 text-sm font-medium text-white bg-yellow-400 border border-white rounded-lg hover:bg-yellow-500">
                                    Update Question
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div>
            <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow mb-4">
                <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900">Options</h5>
                <div class="mt-5">
                    <div>
                        <table class="min-w-full border-collapse border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="border border-gray-300 px-4 py-2 w-12">Option</th>                                        
                                    <th class="border border-gray-300 px-4 py-2">Content</th>                                        
                                    <th class="border border-gray-300 px-4 py-2  w-12"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($options as $option)
                                <tr>
                                    <td class="text-center border border-gray-300 px-4 py-2"><span class="font-bold">{{$option->option}}</span></td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $option->content }}</td>
                                    <td class="text-center border border-gray-300 px-4 py-2 text-xs"><button wire:click='updateDataOption("{{$option->id}}")'><span class="bg-yellow-400 text-white px-2 py-1 rounded">Edit</span></button></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-5">
                        <div>
                            <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900">Form Update Options</h5>
                            <form wire:submit.prevent="updateOption">
                                <div>
                                    <div class="mb-3">
                                        <label for="option" class="block mb-1 text-sm font-medium text-gray-950 dark:text-white">Option<sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup></label>
                                        <input wire:model="opt_option" type="text" id="option" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-300 focus:border-gray-300 block w-12 p-2.5" required readonly />
                                        <div class="text-red-500 text-sm">
                                            @error('opt_option') <span class="error">{{ $message }}</span> @enderror 
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="mb-3">
                                            <label for="opt_content" class="block mb-2 text-sm font-medium text-gray-900">Content<sup class="text-red-600 font-medium">*</sup></label>
                                            <textarea wire:model="opt_content" id="opt_content" rows="4" class="block p-2.5 w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500" placeholder=""></textarea>
                                            <div class="text-red-500 text-sm">
                                                @error('opt_content') <span class="error">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end mt-5">
                                    <div class="inline-flex gap-1 rounded-md shadow-sm" role="group">
                                        <button type="submit" class="inline-flex gap-1 items-center px-4 py-2 text-sm font-medium text-white bg-yellow-400 border border-white rounded-lg hover:bg-yellow-500">
                                            Update Option
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>