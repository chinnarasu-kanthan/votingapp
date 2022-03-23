<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
    <x-form-card>
        <div>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('category') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-label for="category_name" :value="__('Category Name')" />

                <x-input id="category_name" class="block mt-1 w-full" type="text" name="category_name" :value="old('category_name')" required autofocus />
            </div>

            <!-- Description  -->
            <div class="mt-4">
                <x-label for="Description" :value="__('Description')" />

                <textarea  class="block mt-1 w-full" name="description" :value="old('description')"  autofocus></textarea>
            </div>

            <div class="flex items-center justify-end mt-4">
               

                <x-button class="ml-4">
                    {{ __('Submit') }}
                </x-button>
            </div>
        </form>
    </x-form-card>
    </div>
</x-app-layout>
