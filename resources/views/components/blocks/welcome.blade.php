@props(['data' => []])

{{-- The same view draws the block on the website, in the visual editor and in previews. Its
     look is Tailwind's classes, right here; links go nowhere while it's being edited. --}}
@php
    $steps = array_filter(array_map('trim', explode("\n", (string) ($data['steps'] ?? ''))));
    $buttons = [
        'primary' => 'border-black bg-[#1b1b18] text-white hover:bg-black',
        'secondary' => 'border-[#19140035] text-[#1b1b18] hover:border-[#1915014a]',
    ];
@endphp

<section class="welcome-sky flex min-h-svh items-center justify-center px-4 py-10 sm:px-6">
    <div class="grid w-full max-w-5xl overflow-hidden rounded-lg shadow-[0_40px_60px_rgb(0_0_0/0.45),0_0_40px_rgb(124_58_237/0.35)] sm:grid-cols-2">
        <div class="bg-white p-5 text-[13px] leading-5 text-[#706f6c] lg:p-12">
            <h1 class="mb-1 text-lg font-medium text-[#1b1b18]" @editable('title')>{{ inline_text($data['title'] ?? '') }}</h1>

            @if (shows_field('text', $data['text'] ?? null))
                <p class="mb-4 whitespace-pre-line" @editable('text')>{{ plain_text($data['text'] ?? '') }}</p>
            @endif

            @if ($steps !== [])
                <ul class="mb-6 space-y-2">
                    @foreach ($steps as $step)
                        <li class="flex gap-3">
                            {{ svg('phosphor-check-bold', 'mt-1 size-3.5 shrink-0 text-violet-600') }}
                            <span>{{ $step }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="flex flex-wrap gap-3">
                @foreach ($buttons as $button => $classes)
                    @if (shows_field("{$button}_label", $data["{$button}_label"] ?? null))
                        <a
                            href="{{ is_editing() ? '#' : (link_href($data, $button) ?? '#') }}"
                            @if (link_target($data, $button)) target="_blank" rel="noopener" @endif
                            class="inline-block whitespace-nowrap rounded-sm border px-5 py-1.5 text-sm leading-normal transition {{ $classes }}"
                        ><span @editable("{$button}_label")>{{ plain_text($data["{$button}_label"] ?? '') }}</span></a>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="welcome-glow flex min-h-72 flex-col items-center justify-center bg-[#f5f3ff] p-10 text-center">
            <img src="{{ asset('brand-mark.svg') }}" alt="" class="mx-auto w-32 rounded-[28%] shadow-2xl shadow-violet-500/30 lg:w-44">
            <h2 class="mt-6 text-3xl font-semibold tracking-tight text-[#2e1065]" @editable('name')>{{ inline_text($data['name'] ?? '') }}</h2>

            @if (shows_field('tagline', $data['tagline'] ?? null))
                <p class="text-sm text-[#5b21b6]" @editable('tagline')>{{ plain_text($data['tagline'] ?? '') }}</p>
            @endif
        </div>
    </div>
</section>
