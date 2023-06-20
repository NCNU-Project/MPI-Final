<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="pt-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">
                        Upload Video
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        {{ __("Update your video in order to transoding.") }}
                    </p>
                </header>
                <form method="post" action="{{ route('upload.create') }}" class='space-y-6' enctype="multipart/form-data" >
                    @csrf
                    @method('post')

                    <div>
                        <x-input-label for="video" value="video" />
                        <x-text-input id="video" name="video" type="file" class="mt-1 block w-full border p-2" />

                        @error('video')
                        <x-input-error messages='{{ $message }}' class="mt-2" />
                        @enderror
                    </div>

                    <div>
                        <x-primary-button class="ml-auto">
                            {{ __('upload video') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($uploads->count())
    <div class="pt-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">
                        My Upload Videos
                    </h2>
                </header>

                <table class="table-auto w-full">
                  <thead class="border-b">
                    <tr>
                      <th class="px-6 py-4 text-left">id</th>
                      <th class="px-6 py-4 text-left">uploaded files</th>
                      <th class="px-6 py-4 text-left">status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($uploads as $upload)
                    <tr class="border-b">
                        <td class="px-6 py-4">#{{ $upload->id }}</td>
                        <td class="px-6 py-4 text-left text-blue-600 dark:text-blue-500 hover:underline">
                            <a href={{ '/storage/' . $upload->upload_path }} target='_blank'> 
                                {{ $upload->filename }}
                            </a>
                        </td>
                      <td class="px-6 py-4">{{ $upload->file_status->status }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</x-app-layout>
