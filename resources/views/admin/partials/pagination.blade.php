@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-center my-2">
        {{-- Nút Previous (Trái) --}}
        @if ($paginator->onFirstPage())
            <span class="relative inline-flex items-center justify-center w-8 h-8 text-gray-300 bg-white border border-gray-100 rounded-full cursor-not-allowed mx-0.5">
                <i class="fas fa-chevron-left text-[10px]"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center justify-center w-8 h-8 text-gray-500 bg-white border border-gray-200 rounded-full hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition-all duration-200 mx-0.5">
                <i class="fas fa-chevron-left text-[10px]"></i>
            </a>
        @endif

        {{-- Các số trang --}}
        <div class="flex items-center flex-wrap justify-center gap-y-1 px-1">
            @foreach ($elements as $element)
                {{-- Dấu ba chấm "..." --}}
                @if (is_string($element))
                    <span class="relative inline-flex items-center justify-center w-8 h-8 text-gray-400 bg-white rounded-full cursor-default mx-0.5 text-xs font-light">
                        {{ $element }}
                    </span>
                @endif

                {{-- Mảng các số trang --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            {{-- Trang hiện tại (Active) --}}
                            <span class="relative inline-flex items-center justify-center w-8 h-8 text-white bg-indigo-600 border border-indigo-600 rounded-full shadow-sm shadow-indigo-200 cursor-default mx-0.5 font-bold text-xs">
                                {{ $page }}
                            </span>
                        @else
                            {{-- Các trang khác --}}
                            <a href="{{ $url }}" class="relative inline-flex items-center justify-center w-8 h-8 text-gray-600 bg-white border border-gray-200 rounded-full hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition-all duration-200 mx-0.5 font-medium text-xs">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Nút Next (Phải) --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center justify-center w-8 h-8 text-gray-500 bg-white border border-gray-200 rounded-full hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition-all duration-200 mx-0.5">
                <i class="fas fa-chevron-right text-[10px]"></i>
            </a>
        @else
            <span class="relative inline-flex items-center justify-center w-8 h-8 text-gray-300 bg-white border border-gray-100 rounded-full cursor-not-allowed mx-0.5">
                <i class="fas fa-chevron-right text-[10px]"></i>
            </span>
        @endif
    </nav>
@endif