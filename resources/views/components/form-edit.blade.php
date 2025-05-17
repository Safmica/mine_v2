@props(['type', 'course' => null, 'meeting' => null, 'file' => null, 'id', 'item'])

@php
    $apiEndpoints = [
        'course' => url('/api/courses/' . $course->id),
        'meeting' => isset($course->id, $meeting->id) ? url("/api/courses/{$course->id}/meetings/{$meeting->id}") : '#',
        'file' => isset($course->id, $meeting->id, $file->id) ? url("/api/courses/{$course->id}/meetings/{$meeting->id}/files/{$file->id}") : '#',
    ];

    $labels = [
        'course' => 'Course',
        'meeting' => 'Meeting',
        'file' => 'File',
    ];

    $titleField = [
        'course' => 'title',
        'meeting' => 'meeting_name',
        'file' => 'filename',
    ];

    $descField = [
        'course' => 'description',
        'meeting' => 'topic',
        'file' => null,
    ];

    $endpoint = $apiEndpoints[$type] ?? '#';
    $label = $labels[$type] ?? 'Item';
    $titleValue = old($titleField[$type], $item->{$titleField[$type]} ?? '');
    $descValue = $descField[$type] ? old($descField[$type], $item->{$descField[$type]} ?? '') : null;
@endphp

<div class="bg-white p-6 rounded-lg w-full max-w-lg mx-4">
    <h2 class="text-2xl font-semibold mb-6 text-center">Edit {{ $label }}</h2>

    <form id="form-edit-{{ $id }}">
        <div class="mb-4">
            <label for="edit-{{ $titleField[$type] }}" class="block text-sm font-medium text-gray-700">
                {{ $label === 'Meeting' ? 'Meeting Name' : ($label === 'File' ? 'Filename' : 'Title') }}
            </label>
            <input type="text" name="{{ $titleField[$type] }}" id="edit-{{ $titleField[$type] }}"
                value="{{ $titleValue }}"
                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        @if($descField[$type])
            <div class="mb-4">
                <label for="edit-{{ $descField[$type] }}" class="block text-sm font-medium text-gray-700">
                    {{ $label === 'Meeting' ? 'Topic' : 'Description' }}
                </label>
                <textarea name="{{ $descField[$type] }}" id="edit-{{ $descField[$type] }}" rows="4"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required>{{ $descValue }}</textarea>
            </div>
        @endif

        <div class="flex justify-end space-x-4 mt-6">
            <button type="button" onclick="document.getElementById('modal-edit-{{ $id }}').classList.add('hidden')"
                class="px-6 py-2 text-sm font-semibold text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition">
                Cancel
            </button>
            <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-cos-yellow rounded-md hover:bg-cos-dark-yellow transition">
                Update
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('form-edit-{{ $id }}').addEventListener('submit', async function (e) {
    e.preventDefault();

    const form = e.target;
    const title = form.querySelector('[name="{{ $titleField[$type] }}"]').value;
    const description = {{ $descField[$type] ? 'form.querySelector(\'[name="' . $descField[$type] . '"]\').value' : 'null' }};

    try {
        const res = await fetch('{{ $endpoint }}', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': 'Bearer {{ auth()->user()->api_token ?? '' }}' // jika pakai token
            },
            body: JSON.stringify({
                {{ $titleField[$type] }}: title,
                @if($descField[$type])
                    {{ $descField[$type] }}: description,
                @endif
            })
        });

        const data = await res.json();

        if (res.ok) {
            alert('Update berhasil!');
            location.reload(); // bisa diganti dengan DOM update
        } else {
            alert(data.message || 'Update gagal');
        }
    } catch (err) {
        alert('Terjadi kesalahan: ' + err.message);
    }
});
</script>
