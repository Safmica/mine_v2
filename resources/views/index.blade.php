@extends('layouts.index')

@section('title', 'Index')

@section('content')
<div class="p-4 text-2xl font-bold bg-white" title="{{ optional(Auth::user())->name }}">
    <h1>{{ optional(Auth::user())->name ?? 'Guest' }} > Courses</h1>
</div>

<div id="courses-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 rounded-lg bg-cos-yellow p-4">
    <!-- Course cards will be injected here -->
</div>

<div id="empty-state" class="hidden flex justify-center items-center h-96 w-full col-span-full pt-10">
    <img src="{{ asset('assets/404_course.png') }}" alt="No courses" class="opacity-20 w-[400px]">
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: '/api/courses',
        method: 'GET',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function (data) {
            const container = $('#courses-container');
            const emptyState = $('#empty-state');

            if (data.length === 0) {
                emptyState.removeClass('hidden');
                return;
            }

            data.forEach(course => {
                const card = $(`
                    <div class="bg-white rounded-lg shadow-lg p-4 flex flex-row items-center h-12">
                        <a href="#" class="w-full group cursor-pointer relative">
                            <h3 class="text-lg font-semibold text-center mb-2 truncate w-full">${course.title}</h3>
                            <span class="absolute top-full left-1/2 transform -translate-x-1/2 mb-2 w-max px-2 py-1 text-sm text-white bg-black rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                ${course.description}
                            </span>
                        </a>

                        <div class="relative menu-container">
                            <button class="text-gray-500 hover:text-black focus:outline-none menu-toggle" data-id="${course.id}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12h12M6 6h12M6 18h12" />
                                </svg>
                            </button>

                            <div id="menu-${course.id}" class="hidden absolute right-0 mt-2 w-32 bg-white rounded-lg shadow-md z-50">
                                <ul>
                                    <li class="hover:bg-gray-100">
                                        <button type="button" class="w-full text-left px-4 py-2 text-black edit-button" data-id="${course.id}">
                                            Edit
                                        </button>
                                    </li>
                                    <li class="hover:bg-gray-100">
                                        <button type="button" class="w-full text-left px-4 py-2 text-red-500 delete-button" data-id="${course.id}">
                                            Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                `);

                container.append(card);
            });

            attachDropdownAndModalEvents(csrfToken);
        },
        error: function (err) {
            console.error('Error fetching courses:', err);
        }
    });

    function attachDropdownAndModalEvents(csrfToken) {
        // Toggle menu
        $(document).on('click', '.menu-toggle', function (e) {
            e.stopPropagation();
            const id = $(this).data('id');
            $(`#menu-${id}`).toggleClass('hidden');
        });

        // Click outside to hide menu
        $(document).click(function (e) {
            if (!$(e.target).closest('.menu-container').length) {
                $('[id^=menu-]').addClass('hidden');
            }
        });

        // Delete button
        $(document).on('click', '.delete-button', function () {
            const id = $(this).data('id');
            if (!confirm('Are you sure you want to delete this course?')) return;

            $.ajax({
                url: `/api/courses/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function () {
                    location.reload();
                },
                error: function (xhr) {
                    alert('Gagal menghapus course!');
                    console.error(xhr.responseText);
                }
            });
        });

        // ESC to close modals (if any)
        $(document).on('keydown', function (e) {
            if (e.key === 'Escape') {
                $('[id^=modal-edit-]').addClass('hidden');
            }
        });
    }
});
</script>
@endsection
