<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs uppercase tracking-wider rounded-xl shadow-md shadow-amber-500/20 focus:outline-none focus:ring-2 focus:ring-amber-500 active:scale-95 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
