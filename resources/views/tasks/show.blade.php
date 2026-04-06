<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Task Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(request()->query('edit'))
                        <a href="{{ route('tasks.show', $task) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white mb-6">{{ __('Back to task') }}</a>
                    @else
                        <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white mb-6">{{ __('Back to Tasks') }}</a>                        
                    @endif

                    @if(session('success'))
                        <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-700 rounded alert-message">{{ session('success') }}</div>
                    @endif

                    @if(request()->query('edit'))
                        <form method="POST" action="{{ route('tasks.update', $task) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Title') }}</label>
                                <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full ">
                                @error('title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-4">
                                <label for="deadline" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deadline</label>
                                <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" type="date" name="deadline" id="deadline" value="{{ old('deadline', $task->deadline ? $task->deadline->format('Y-m-d') : '') }}">
                                @error('deadline') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description') }}</label>
                                <textarea name="description" id="description" rows="4" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full">{{ old('description', $task->description) }}</textarea>
                                @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
                                <select name="status" id="status" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full2">
                                    <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                    <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>{{ __('In Progress') }}</option>
                                    <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                                </select>
                                @error('status') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex items-center gap-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-sky-600 text-white rounded">{{ __('Save') }}</button>
                                <a href="{{ route('tasks.show', $task) }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded">{{ __('Cancel') }}</a>
                            </div>
                        </form>
                    @else
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-2xl font-bold">{{ $task->title }}</h3>
                            <a href="{{ route('tasks.show', [$task, 'edit' => '1']) }}" class="inline-flex items-center px-4 py-2 bg-sky-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700">{{ __('Edit') }}</a>
                        </div>
                        
                        <p>Deadline: {{ $task->deadline ?? 'No deadline' }}</p>

                        <p class="mb-4">{{ $task->description ?: __('No description yet.') }}</p>

                        <p class="mb-4"><strong>{{ __('Status') }}:</strong>
                            @if($task->status === 'pending')
                                <span class="bg-orange-500 text-white px-2 py-1 rounded-md text-xs font-semibold">{{ __('Pending') }}</span>
                            @elseif($task->status === 'in_progress')
                                <span class="bg-blue-500 text-white px-2 py-1 rounded-md text-xs font-semibold">{{ __('In Progress') }}</span>
                            @else
                                <span class="bg-green-500 text-white px-2 py-1 rounded-md text-xs font-semibold">{{ __('Completed') }}</span>
                            @endif
                        </p>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert-message');
                alerts.forEach(function(alert) {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                });
            }, 30000); // 30 seconds
        });
    </script>
</x-app-layout>