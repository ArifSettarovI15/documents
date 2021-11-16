@isset($paginator)
    @if($paginator->hasPages())
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item @if($paginator->onFirstPage()) disabled @endif">
                    <a class="page-link" href="{{$paginator->previousPageUrl()}}"
                       data-page="{{$paginator->currentPage()-1}}"
                       aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                @for($i = 1; $i<=$paginator->lastPage(); $i++ )
                    <li class="page-item
                        @if($paginator->currentPage() == $i) active @endif
                        "><a class="page-link" href="{{$paginator->url($i)}}" data-page="{{$i}}">{{$i}}</a></li>
                @endfor
                <li class="page-item @if(!$paginator->hasMorePages()) disabled @endif">
                    <a class="page-link" href="{{$paginator->nextPageUrl()}}"
                       data-page="{{$paginator->currentPage()+1}}"
                       aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            </ul>
        </nav>
    @endif
@endisset
