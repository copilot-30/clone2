@if ($errors->any())
        <div class="fixed top-20 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <div class="flex items-center justify-between bg-red-500 text-white px-4 py-2 rounded shadow-md mb-2">
                <div>
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
                <button class="text-gray-100 hover:text-white bg-gray-600 border border-gray-300 px-2 py-1 mx-2" onclick="this.parentElement.remove()">
                    <i class="fa fa-times"></i> Hide
                </button>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="fixed top-20 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <div class="flex items-center justify-between bg-green-500 text-white px-4 py-2 rounded shadow-md mb-2">
                <div>
                    {{ session('success') }}
                </div>
                <button class="text-gray-100 hover:text-white bg-gray-600 border border-gray-300 px-2 py-1 mx-2" onclick="this.parentElement.remove()">
                    <i class="fa fa-times"></i> Hide
                </button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="fixed top-20 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <div class="flex items-center justify-between bg-red-500 text-white px-4 py-2 rounded shadow-md mb-2">
                <div>
                    {{ session('error') }}
                </div>
                <button class="text-gray-100 hover:text-white bg-gray-600 border border-gray-300 px-2 py-1 mx-2" onclick="this.parentElement.remove()">
                    <i class="fa fa-times"></i> Hide
                </button>
            </div>
        </div>
    @endif

    @if (session('status'))
        <div class="fixed top-20 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <div class="flex items-center justify-between bg-blue-500 text-white px-4 py-2 rounded shadow-md mb-2">
                <div>
                    {{ session('status') }}
                </div>
                <button class="text-gray-100 hover:text-white bg-gray-600 border border-gray-300 px-2 py-1 mx-2" onclick="this.parentElement.remove()">
                    <i class="fa fa-times"></i> Hide
                </button>
            </div>
        </div>
    @endif