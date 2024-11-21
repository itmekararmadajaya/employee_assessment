<x-filament-panels::page>
    <div class="grid-2">
        <div>
            <div class="card">
                <div class="mb-5">
                    <span class="title">Assessment Information</span>
                </div>
                <div>
                    <table class="w-full">
                        <tbody>
                            <tr class="bg-gray-50">
                                <td style="width: 100px;" class="px-6 py-4 text-sm font-medium text-gray-900">Name</td>
                                <td>:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$assessment->name}}</td>
                            </tr>
                            <tr class="bg-white">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Description</td>
                                <td class="">:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$assessment->description}}</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td style="width: 100px;" class="px-6 py-4 text-sm font-medium text-gray-900">Time Open</td>
                                <td>:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$assessment->getFormattedTimeStartTest().', '.$assessment->getFormattedDateStartTest()}}</td>
                            </tr>
                            <tr class="bg-white">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Time Close</td>
                                <td class="">:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$assessment->getFormattedTimeCloseTest().', '.$assessment->getFormattedDateCloseTest()}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>   
    <div class="card">
        {{$this->table}}
    </div> 
</x-filament-panels::page>
