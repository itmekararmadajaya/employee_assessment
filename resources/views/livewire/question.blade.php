<div class="bg-gray-100 lg:p-8 p-2">
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
    
    <div class="lg:grid lg:grid-cols-2 lg:gap-2">
        <div>
            <div class="block p-3 bg-white border border-gray-200 rounded-lg shadow mb-4">
                <h5 class="mb-2 text-3xl font-bold tracking-tight text-gray-900">Questions</h5>
                
                <div class="lg:flex lg:justify-between lg:items-end">
                    <div>
                        <div class="inline-flex rounded-md shadow-sm" role="group">
                            <a href="{{route('filament.admin.resources.question-levels.index')}}">
                                <button type="button" class="inline-flex gap-1 items-center px-4 py-2 text-sm font-medium text-gray-900 bg-gray-100 border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700">
                                    Dashboard
                                  </button>
                            </a>
                            <a href="{{route('question-create')}}">
                                <button type="button" class="inline-flex gap-1 items-center px-4 py-2 text-sm font-medium text-gray-900 bg-gray-100 border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700">
                                Create Question
                                </button>
                            </a>
                        </div>
                    </div>
                    <div>
                        <form action="{{route('question')}}" method="get">
                            <label for="level" class="block mb-2 text-sm font-medium text-gray-900">Select an Question Level</label>
                            <div class="flex gap-1">
                                <select id="level" name="level" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-72 p-2.5" required>
                                        @if ($level_selected != "")
                                            <option value="{{$level_selected->id}}">{{$level_selected->name}}</option>
                                        @else
                                            <option value="">Choose a type</option>
                                        @endif    
                                        @foreach ($levels as $level)
                                            <option value="{{$level->id}}">{{$level->name}}</option>
                                        @endforeach
                                </select>
                                <button type="submit" class="text-sm font-medium text-gray-900 bg-gray-100 border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 px-3.5">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="block max-w-full p-3 bg-white border border-gray-200 rounded-lg shadow h-screen overflow-y-auto">
                <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900">List Question {{$level_selected != "" ? "Level $level_selected->name" : ''}}</h5>
                <div class="overflow-y-auto">
                    @foreach ($questions as $key => $question)
                    <div class="bg-gray-50 border bordered mb-2">
                        <div class="py-3 px-3 lg:px-5 flex justify-between items-center">
                            <div>
                                <span>Aspek : </span> <span class="font-bold">{{$question->aspect}}</span>
                            </div>
                            <div>
                                <a href="{{route('question-edit', $question->id)}}" class="text-sm bg-yellow-400 text-white px-2 py-1 rounded">Edit</a>
                            </div>
                        </div>
                        <div class="px-3 lg:px-5 bg-white">
                            <div class="py-5">
                                {{$question->question}}
                            </div>                            
                            <div>
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
                                                <td class="py-3 font-medium text-gray-900 text-center border"><input wire:model="" id="" type="radio" value="A" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300"></td>
                                                <td class="py-3 text-center border">{{$option->option}}</td>
                                                <td class="px-3 py-3 border">{{$option->content}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        
    </script>
</div>