<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{
            state: $wire.entangle('{{ $getStatePath() }}'),
            init() {
                if (!this.state || !Array.isArray(this.state)) {
                    this.state = [];
                }
            }
        }"
    >
        <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.5rem;">
            @foreach($groupedPermissions as $category => $options)
                @php
                    $optionIds = array_map('strval', array_keys($options));
                    $jsonIds = json_encode($optionIds);
                @endphp

                <x-filament::section class="h-full">
                    <x-slot name="heading">
                        {{ Illuminate\Support\Str::headline($category) }}
                    </x-slot>

                    <div style="display: flex; justify-content: flex-start; margin-bottom: 0.75rem; border-b: 1px solid rgba(156, 163, 175, 0.1); padding-bottom: 0.5rem;">
                        <button type="button"
                            x-on:click="
                                const ids = {{ $jsonIds }};
                                const allChecked = ids.every(id => state.includes(id));
                                if (allChecked) {
                                    // Filter out only this section's IDs from the flat master array
                                    state = state.filter(id => !ids.includes(id));
                                } else {
                                    // Merge this section's IDs into the flat master array safely
                                    state = [...new Set([...state, ...ids])];
                                }
                            "
                            class="fi-color fi-color-primary fi-text-color-600 dark:fi-text-color-300 fi-link fi-size-sm  fi-ac-link-action"
                        >
                            <span x-text="{{ $jsonIds }}.every(id => state.includes(id)) ? 'Deselect All' : 'Select All'"></span>
                        </button>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.75rem;">
                        @foreach($options as $id => $label)
                            <label class="flex items-center gap-x-3 text-sm font-medium text-gray-950 dark:text-white cursor-pointer">
                                <x-filament::input.checkbox
                                    value="{{ (string) $id }}"
                                    x-model="state"
                                />
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </x-filament::section>
            @endforeach
        </div>
    </div>
</x-dynamic-component>
