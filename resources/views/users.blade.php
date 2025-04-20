<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User List</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const loadMoreBtn = document.getElementById('load-more');
            const userList = document.getElementById('user-list');

            if (!loadMoreBtn) return;

            loadMoreBtn.addEventListener('click', async () => {
                const nextPage = loadMoreBtn.dataset.page;

                try {
                    const response = await fetch(`/api/users?page=${nextPage}&count=6&sort=desc`);
                    const data = await response.json();

                    if (!data.success || !data.users.length) {
                        loadMoreBtn.remove(); // Remove button if no more users
                        return;
                    }

                    data.users.forEach(user => {
                        const div = document.createElement('div');
                        div.className = 'user-card bg-gray-50 p-4 rounded shadow text-center';
                        div.innerHTML = `
                        <img src="${user.photo}" alt="${user.name}"
                             class="w-20 h-20 mx-auto rounded-full object-cover mb-2">
                        <p class="font-semibold">${user.name}</p>
                        <p class="text-sm">${user.email}</p>
                        <p class="text-sm">${user.phone}</p>
                        <p class="text-sm text-gray-500">${user.position}</p>
                    `;
                        userList.appendChild(div);
                    });

                    // Update to next page or hide button
                    if (data.page < data.total_pages) {
                        loadMoreBtn.dataset.page = parseInt(nextPage) + 1;
                    } else {
                        loadMoreBtn.remove(); // No more pages
                    }
                } catch (error) {
                    loadMoreBtn.disabled = true;
                    loadMoreBtn.textContent = 'Error loading more users';
                    console.error(error);
                }
            });
        });
    </script>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-4xl mx-auto space-y-10">

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-4 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Add User Form -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Add New User</h2>
        <form action="/create-user" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <input name="name" type="text" placeholder="Name" value="{{ old('name') }}"
                   class="w-full p-2 border rounded">
            <input name="email" type="email" placeholder="Email" value="{{ old('email') }}"
                   class="w-full p-2 border rounded">
            <input name="phone" type="text" placeholder="Phone (+380XXXXXXXXX)" value="{{ old('phone') }}"
                   class="w-full p-2 border rounded">

            <select name="position_id" class="w-full p-2 border rounded">
                @foreach ($positions as $position)
                    <option value="{{ $position['id'] }}" @if(old('position_id') == $position['id']) selected @endif>
                        {{ $position['name'] }}
                    </option>
                @endforeach
            </select>

            <input type="file" name="photo" class="w-full p-2 border rounded">

            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">Submit</button>
        </form>
    </div>

    <!-- User List -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">User List</h2>
        <div id="user-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($users as $user)
                <div class="bg-gray-50 p-4 rounded shadow text-center">
                    <img src="{{ $user['photo'] }}" alt="{{ $user['name'] }}"
                         class="w-20 h-20 mx-auto rounded-full object-cover mb-2">
                    <p class="font-semibold">{{ $user['name'] }}</p>
                    <p class="text-sm">{{ $user['email'] }}</p>
                    <p class="text-sm">{{ $user['phone'] }}</p>
                    <p class="text-sm text-gray-500">{{ $user->positions->name }}</p>
                </div>
            @endforeach
        </div>

        @if ($pagination['page'] < $pagination['total_pages'])
            <div class="mt-6 text-center">
                <button id="load-more" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                        data-page="{{ $pagination['page'] + 1 }}">
                    Show more
                </button>
            </div>
        @endif
    </div>
</div>
</body>
</html>
