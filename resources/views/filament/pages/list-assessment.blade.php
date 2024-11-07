<x-filament-panels::page>
    <div class="card">
        Information
    </div>
    <div class="lg:flex flex-wrap" style="gap: 5px;">
        @foreach ($employee_assessments as $assessment)
            <div class="card card-width-32 bg-white shadow-lg rounded-lg overflow-hidden mb-2">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $assessment->name }}</h2>
                </div>
                <div class="p-4 space-y-4">
                    <p class="text-gray-600 text-sm">{{ $assessment->description }}</p>

                    <table class="text-gray-500">
                        <tbody>
                            <tr>
                                <td>
                                    <strong>Open</strong>
                                </td>
                                <td class="px-1">
                                    :
                                </td>
                                <td>
                                    {{$assessment->getFormattedTimeStartTest()}}, {{ $assessment->getFormattedDateStartTest() }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Close</strong>
                                </td>
                                <td class="px-1">
                                    :
                                </td>
                                <td>
                                    {{$assessment->getFormattedTimeCloseTest()}}, {{ $assessment->getFormattedDateCloseTest() }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>status</strong>
                                </td>
                                <td class="px-1">
                                    :
                                </td>
                                <td>
                                    @if ($assessment->status == 'open')
                                        <div class="w-14">
                                            <x-filament::badge color="warning" size="sm">
                                                open
                                            </x-filament::badge>
                                        </div>
                                    @elseif($assessment->status == 'done')
                                        <div class="w-14">
                                            <x-filament::badge color="success" size="sm">
                                                done
                                            </x-filament::badge>
                                        </div>
                                    @else
                                        <div class="w-14">
                                            <x-filament::badge color="dabger" size="sm">
                                                close
                                            </x-filament::badge>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-2">
                    @if ($assessment->status == 'open')
                        <div class="flex gap-3">
                            <a class="w-full" href="{{route('filament.admin.pages.assessment', ['assessment' => $assessment->slug])}}">
                                <x-filament::button class="w-full">
                                    Mulai Penilaian
                                </x-filament::button>
                            </a>
                            {{-- <a class="w-1/2" href="{{route('filament.admin.pages.assessment', ['assessment' => $assessment->slug])}}">
                                <x-filament::button class="w-full" color="info">
                                    Approve
                                </x-filament::button>
                            </a> --}}
                        </div>
                    @elseif($assessment->status == 'done')
                        {{-- <x-filament::button color="success" class="w-full">
                            Result
                        </x-filament::button> --}}
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
