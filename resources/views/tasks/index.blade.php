<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <nav class="flex flex-wrap dark:bg-gray-800 items-center gap-2 p-4 bg-gray-50 rounded-xl">
                    @php
                        $currentFilter = request('filter');
                        // Базові стилі для посилань
                        $baseClasses = "px-4 py-2 text-sm font-medium rounded-full transition-all duration-200 ease-in-out border";
                        // Стилі для активного стану
                        $activeClasses = "bg-indigo-600 text-white border-indigo-600 shadow-md";
                        // Стилі для неактивного стану
                        $inactiveClasses = "bg-white text-gray-600 border-gray-300 hover:bg-gray-100 hover:border-gray-400";
                    @endphp

                    <a href="{{ route('tasks.index') }}" 
                    class="{{ $baseClasses }} {{ !$currentFilter ? $activeClasses : $inactiveClasses }}">
                    All
                    </a>

                    <a href="{{ route('tasks.index', ['filter' => 'completed']) }}" 
                    class="{{ $baseClasses }} {{ $currentFilter === 'completed' ? $activeClasses : $inactiveClasses }}">
                    Pending
                    </a>

                    <a href="{{ route('tasks.index', ['filter' => 'pending']) }}" 
                    class="{{ $baseClasses }} {{ $currentFilter === 'pending' ? $activeClasses : $inactiveClasses }}">
                    Completed
                    </a>

                    <a href="{{ route('tasks.index', ['filter' => 'overdue']) }}" 
                    class="{{ $baseClasses }} {{ $currentFilter === 'overdue' ? 'bg-red-600 text-white border-red-600 shadow-md' : $inactiveClasses }}">
                    Overdue
                    </a>
                </nav>
    
                <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col">
                    <a class="inline-flex items-center px-4 py-2 bg-sky-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700 focus:bg-sky-700 active:bg-sky-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 ml-auto" href="{{ route('tasks.create') }}">Create Task</a>
                    @foreach($tasks as $task)


                    <div class="p-4 0 bg-gray-500 hover:bg-gray-700 rounded-md mt-4">
                        @switch($task->status)
                            @case('pending')
                                <span class="bg-orange-500 text-white px-2 py-1 rounded-md text-xs font-semibold">Pending</span>
                            @break

                            @case('in_progress')
                                <span class="bg-blue-500 text-white px-2 py-1 rounded-md text-xs font-semibold">In Progress</span>
                            @break

                            @default
                                <span class="bg-green-500 text-white px-2 py-1 rounded-md text-xs font-semibold ">Completed</span>
                        @endswitch
                        <p>Deadline: {{ $task->deadline ?? 'No deadline' }}</p>


                        <div class="mt-2">
                            <h3 class="text-lg font-bold"><a href="{{ route('tasks.show', $task) }}" class="hover:text-sky-500">{{ $task->title }}</a></h3>
                            <p class="text-gray-100 dark:text-gray-200 overflow-hidden" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; white-space: normal;">{{ $task->description }}</p>
                        </div>
                            
                        
                        <a href="{{ route('tasks.show', [$task, 'edit' => 1]) }}" class="inline-flex items-center px-3 py-1 bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">{{ __('Edit') }}</a>
               
                        @if($task->status == 'pending')
                            <form method="POST" action="{{ route('tasks.start', $task) }}" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Start
                                </button>
                            </form>
                        @elseif($task->status == 'in_progress')
                            <form method="POST" action="{{ route('tasks.complete', $task) }}" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Complete
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('tasks.reset', $task) }}" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Reset
                                </button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Delete
                            </button>
                        </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</x-app-layout>