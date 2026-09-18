
@props(["isActive" => false])

<a {{ $attributes->class([
    "px-3 min-h-[4vh] md:py-4 font-bold text-sm",
    "border md:w-full bg-gray-400 md:bg-gray-400" => $isActive,
    "border md:w-full hover:bg-gray-200 <md:hover:bg-gray-200></md:hover:bg-gray-200> transtion duration-300" => !$isActive
]) }}>{{ $slot }}</a>
