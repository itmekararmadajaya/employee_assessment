<x-filament-panels::page>
    <div class="grid-2">
        <div>
            <div class="card">
                <div class="mb-5">
                    <span class="title">Assessor Information</span>
                </div>
                <div>
                    <table class="w-full">
                        <tbody>
                            <tr class="bg-gray-50">
                                <td style="width: 100px;" class="px-6 py-4 text-sm font-medium text-gray-900">Name</td>
                                <td>:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$user_assessor->name}}</td>
                            </tr>
                            <tr class="bg-white">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Position</td>
                                <td class="">:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$user_assessor->employee->position}}</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td style="width: 100px;" class="px-6 py-4 text-sm font-medium text-gray-900">Section</td>
                                <td>:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$user_assessor->employee->section->name}}</td>
                            </tr>
                            <tr class="bg-white">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Departement</td>
                                <td class="">:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$user_assessor->employee->section->departement->name}}</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Division</td>
                                <td class="">:</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{$user_assessor->employee->section->departement->division->name}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>   
    <div class="card">
        <div class="mb-5">
            <span class="title">Summary by Section</span>
        </div>
        <div class="overflow-x-auto w-full">
            <table class="bordered-table" x-cloak>
                <thead>
                    <tr>
                        <th>
                            <div class="text-left">
                                Section
                            </div>
                        </th>
                        <th>
                            <div class="text-left">
                                Assessed Position
                            </div>
                        </th>
                        <th class="w-6">
                            <div class="text-left">
                                Count
                            </div>
                        </th>
                        <th class="w-6">
                            <div class="text-left">
                                Approver
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $employee_total = 0;
                    @endphp
                    @foreach ($assessor_list as $assessor)
                    @php
                        $employee_total += $assessor->count_of_employee;
                    @endphp
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{$assessor->section->name}}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{implode(', ', $assessor->assessed)}}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                <div class="text-center">
                                    {{$assessor->count_of_employee}}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                <div class="text-center">
                                    {{$assessor->approver}}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2">
                            <div class="text-right">
                                <span class="font-bold">Total</span>
                            </div>
                        </td>
                        <td>
                            <div class="text-center">
                                <span class="font-bold">{{$employee_total}}</span>
                            </div>
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="mb-5">
            <span class="title">Must be assessed</span>
        </div>
        <div>
            {{$this->table}}
        </div>
    </div>
</x-filament-panels::page>
