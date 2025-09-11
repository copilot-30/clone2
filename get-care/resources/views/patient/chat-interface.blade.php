@extends('patient_layout')

@section('content')
<div class="flex h-screen antialiased text-gray-800">
    <div class="flex flex-row h-full w-full overflow-x-hidden">
        <div class="flex flex-col  pl-6 pr-2 w-64 bg-emerald-800 flex-shrink-0">
            <div class="flex flex-col mt-8">
                <div class="flex flex-row items-center justify-between text-xs">
                    <span class="font-bold text-gray-300">Recent Conversations</span>
                    <span class="flex items-center justify-center bg-gray-600 h-4 w-4 rounded-full text-white">4</span>
                </div>
                <div class="flex flex-col space-y-1 mt-4 -mx-2 h-full overflow-y-auto">
                    <button class="flex flex-row items-center hover:bg-purple-900 rounded-xl p-2">
                        <div class="flex items-center justify-center h-8 w-8 bg-purple-200 rounded-full">
                            <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Avatar" class="h-full w-full rounded-full">
                        </div>
                        <div class="ml-2 text-sm font-semibold text-white">Art Williams</div>
                        <div class="flex items-center justify-center ml-auto text-xs text-gray-400">Now</div>
                        <span class="flex items-center justify-center bg-blue-500 h-4 w-4 rounded-full text-white ml-2">2</span>
                    </button>
                    <button class="flex flex-row items-center hover:bg-purple-900 rounded-xl p-2">
                        <div class="flex items-center justify-center h-8 w-8 bg-purple-200 rounded-full">
                            <img src="https://randomuser.me/api/portraits/men/2.jpg" alt="Avatar" class="h-full w-full rounded-full">
                        </div>
                        <div class="ml-2 text-sm font-semibold text-white">Nick Blanche</div>
                        <div class="flex items-center justify-center ml-auto text-xs text-gray-400">Today</div>
                    </button>
                    <button class="flex flex-row items-center hover:bg-purple-900 rounded-xl p-2">
                        <div class="flex items-center justify-center h-8 w-8 bg-purple-200 rounded-full">
                            <img src="https://randomuser.me/api/portraits/men/3.jpg" alt="Avatar" class="h-full w-full rounded-full">
                        </div>
                        <div class="ml-2 text-sm font-semibold text-white">Richard McMasters</div>
                        <div class="flex items-center justify-center ml-auto text-xs text-gray-400">Tuesday</div>
                    </button>
                    <button class="flex flex-row items-center hover:bg-purple-900 rounded-xl p-2">
                        <div class="flex items-center justify-center h-8 w-8 bg-purple-200 rounded-full">
                            <img src="https://avatars3.githubusercontent.com/u/2763884?s=128" alt="Avatar" class="h-full w-full rounded-full">
                        </div>
                        <div class="ml-2 text-sm font-semibold text-white">Michael Wong</div>
                        <div class="flex items-center justify-center ml-auto text-xs text-gray-400">Typing...</div>
                    </button>
                </div>
            </div>
        </div>
        <div class="flex flex-col flex-auto h-full">
            <div class="flex flex-col flex-auto rounded-2xl bg-gray-100 h-full">
                <div class="flex flex-row items-center h-16 w-full bg-white rounded-t-xl p-4">
                    <div class="flex items-center justify-center rounded-full bg-blue-100 flex-shrink-0 h-10 w-10">
                        <img src="https://avatars3.githubusercontent.com/u/2763884?s=128" alt="Avatar" class="h-full w-full rounded-full">
                    </div>
                    <div class="flex flex-col ml-3">
                        <div class="font-semibold text-sm">Michael Wong</div>
                        <div class="text-xs text-gray-500">Active</div>
                    </div>
                    <div class="ml-auto">
                        <ul class="flex flex-row items-center space-x-2">
                            <li>
                                <button class="flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded-full h-10 w-10">
                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                            </li>
                            <li>
                                <button class="flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded-full h-10 w-10">
                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                </button>
                            </li>
                            <li>
                                <button class="flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded-full h-10 w-10">
                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.135a11.042 11.042 0 005.516 5.516l1.135-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </button>
                            </li>
                            <li>
                                <button class="flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded-full h-10 w-10">
                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="chat-area flex flex-col flex-1 overflow-y-auto p-4">
                    <div class="chat-message">
                        <div class="flex items-end">
                            <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 items-start">
                                <div><span class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600">Hey Nikola, I just want to welcome you to the community.</span></div>
                            </div>
                            <img src="https://avatars3.githubusercontent.com/u/2763884?s=128" alt="My Avatar" class="w-6 h-6 rounded-full order-1">
                        </div>
                        <span class="text-xs text-gray-500 leading-none ml-12">7:28 AM</span>
                    </div>
                    <div class="chat-message">
                        <div class="flex items-end justify-end">
                            <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end">
                                <div><span class="px-4 py-2 rounded-lg inline-block rounded-br-none bg-purple-500 text-white">Thanks Mizko, I'm glad to be here.</span></div>
                            </div>
                            <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="My Avatar" class="w-6 h-6 rounded-full order-2">
                        </div>
                        <span class="text-xs text-gray-500 leading-none mr-12 text-right">7:29 AM</span>
                    </div>
                    <div class="text-center text-gray-500 my-4">Today</div>
                    <div class="chat-message">
                        <div class="flex items-end">
                            <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 items-start">
                                <div><span class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600">Hey Mizko, this is my design for this weeks UI competition.</span></div>
                                <div><img src="https://images.pexels.com/photos/20787/pexels-photo.jpg?auto=compress&cs=tinysrgb&h=350" alt="Design" class="rounded-lg"></div>
                                <div><span class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600">What do you think?</span></div>
                            </div>
                            <img src="https://avatars3.githubusercontent.com/u/2763884?s=128" alt="My Avatar" class="w-6 h-6 rounded-full order-1">
                        </div>
                        <span class="text-xs text-gray-500 leading-none ml-12">7:29 AM</span>
                    </div>
                    <div class="chat-message">
                        <div class="flex items-end justify-end">
                            <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end">
                                <div><span class="px-4 py-2 rounded-lg inline-block rounded-br-none bg-purple-500 text-white">Nice!</span></div>
                                <div><span class="px-4 py-2 rounded-lg inline-block rounded-br-none bg-purple-500 text-white">Can you send this to thedesignership.comps@gmail.com?</span></div>
                            </div>
                            <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="My Avatar" class="w-6 h-6 rounded-full order-2">
                        </div>
                        <span class="text-xs text-gray-500 leading-none mr-12 text-right">7:29 AM</span>
                    </div>
                    <div class="chat-message">
                        <div class="flex items-end">
                            <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 items-start">
                                <div><span class="px-4 py-2 rounded-lg inline-block bg-gray-300 text-gray-600">. . .</span></div>
                            </div>
                            <img src="https://avatars3.githubusercontent.com/u/2763884?s=128" alt="My Avatar" class="w-6 h-6 rounded-full order-1">
                        </div>
                        <span class="text-xs text-gray-500 leading-none ml-12">7:29 AM</span>
                    </div>
                </div>
                <div class="flex flex-row items-center h-16 bg-white w-full px-4 rounded-b-xl">
                    <button class="flex items-center justify-center text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13.5"></path></svg>
                    </button>
                    <div class="flex-grow ml-4">
                        <div class="relative w-full">
                            <input type="text" class="flex w-full border rounded-xl focus:outline-none focus:border-indigo-300 pl-4 h-10" placeholder="Type your message here...">
                            <button class="absolute flex items-center justify-center h-full w-12 right-0 top-0 text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </button>
                        </div>
                    </div>
                    <div class="ml-4">
                        <button class="flex items-center justify-center bg-purple-500 hover:bg-purple-600 rounded-xl text-white px-4 py-1 flex-shrink-0">
                            <span>Send</span>
                            <span class="ml-2">
                                <svg class="w-4 h-4 transform rotate-45 -mt-px" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection