<div class="odontogram-display">
    @php
        $odontogram = json_decode($odontogramData ?? '{}', true);
    @endphp

    @if(empty($odontogram))
        <p class="text-center text-gray-500 py-4">No odontogram data available</p>
    @else
        <!-- Adult Teeth -->
        <div class="jaw">
            @for($i = 18; $i <= 28; $i++)
                <div class="tooth {{ $odontogram[$i]['condition'] ?? 'healthy' }}" 
                     title="Tooth {{ $i }}: {{ $odontogram[$i]['notes'] ?? '' }}">
                    {{ $i }}
                </div>
            @endfor
        </div>
        <div class="jaw">
            @for($i = 48; $i >= 38; $i--)
                <div class="tooth {{ $odontogram[$i]['condition'] ?? 'healthy' }}" 
                     title="Tooth {{ $i }}: {{ $odontogram[$i]['notes'] ?? '' }}">
                    {{ $i }}
                </div>
            @endfor
        </div>

        <!-- Child Teeth -->
        <div class="jaw mt-8">
            <h3 class="text-center mb-2">Child Teeth</h3>
            @for($i = 55; $i <= 65; $i++)
                <div class="tooth {{ $odontogram[$i]['condition'] ?? 'healthy' }}" 
                     title="Tooth {{ $i }}: {{ $odontogram[$i]['notes'] ?? '' }}">
                    {{ $i }}
                </div>
            @endfor
        </div>
        <div class="jaw">
            @for($i = 85; $i >= 75; $i--)
                <div class="tooth {{ $odontogram[$i]['condition'] ?? 'healthy' }}" 
                     title="Tooth {{ $i }}: {{ $odontogram[$i]['notes'] ?? '' }}">
                    {{ $i }}
                </div>
            @endfor
        </div>
    @endif
</div>