<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'border border-primary-0 text-xs py-1.5 px-3 rounded-lg hover:text-white transition text-primary-0 flex items-center space-x-1 flex items-center space-x-1 justify-center gap-1 hover:bg-primary-0 active:bg-primary-0 font-semibold shadow-md hover:shadow-lg ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-primary-600']) }}>
    {{ $slot }}
</button>
