<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    You're logged in!
                </div>
            </div>
            <div class="pd-3 my-5 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
            <a href='{{ route("goForm") }}'><button style="background-color: #1C3942; color: white; border: 1px solid grey;" type="button"><h4 class="p-1">MAKE THIS FORM</h4></button></a>
            </div>
        </div>
    </div>
</x-app-layout>
