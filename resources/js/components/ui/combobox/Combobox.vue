<script setup lang="ts">
import { computed, ref, onMounted, onUnmounted } from "vue"
import { Check, ChevronsUpDown, Search } from "@lucide/vue"
import { cn } from "@/lib/utils"

interface ComboboxItem {
    value: string | number
    label: string
}

const props = withDefaults(
    defineProps<{
        modelValue?: string | number | null
        items: ComboboxItem[]
        placeholder?: string
        searchPlaceholder?: string
        emptyMessage?: string
        disabled?: boolean
    }>(),
    {
        placeholder: "Select an option",
        searchPlaceholder: "Search...",
        emptyMessage: "No results found.",
        disabled: false,
    },
)

const emit = defineEmits<{
    "update:modelValue": [value: string | number | null]
}>()

const open = ref(false)
const searchTerm = ref("")
const containerRef = ref<HTMLElement | null>(null)
const highlightedIndex = ref(-1)

const selectedLabel = computed(() => {
    if (props.modelValue === null || props.modelValue === undefined) return ""
    const item = props.items.find((i) => i.value === props.modelValue)
    return item?.label ?? ""
})

const filteredItems = computed(() => {
    if (!searchTerm.value) return props.items
    const term = searchTerm.value.toLowerCase()
    return props.items.filter(
        (item) =>
            item.label.toLowerCase().includes(term) ||
            String(item.value).toLowerCase().includes(term),
    )
})

function handleSelect(value: string | number) {
    emit("update:modelValue", value)
    open.value = false
    searchTerm.value = ""
    highlightedIndex.value = -1
}

function toggleDropdown() {
    if (!props.disabled) {
        open.value = !open.value
        if (open.value) {
            searchTerm.value = ""
            highlightedIndex.value = -1
        }
    }
}

function handleKeydown(e: KeyboardEvent) {
    if (!open.value) return
    if (e.key === "Escape") {
        open.value = false; highlightedIndex.value = -1; return
    }
    if (e.key === "ArrowDown") {
        e.preventDefault()
        highlightedIndex.value = Math.min(highlightedIndex.value + 1, filteredItems.value.length - 1)
        return
    }
    if (e.key === "ArrowUp") {
        e.preventDefault()
        highlightedIndex.value = Math.max(highlightedIndex.value - 1, 0)
        return
    }
    if (e.key === "Enter" && highlightedIndex.value >= 0) {
        e.preventDefault()
        const item = filteredItems.value[highlightedIndex.value]
        if (item) handleSelect(item.value)
    }
}

function handleClickOutside(e: MouseEvent) {
    if (containerRef.value && !containerRef.value.contains(e.target as Node)) {
        open.value = false; highlightedIndex.value = -1
    }
}

onMounted(() => document.addEventListener("click", handleClickOutside))
onUnmounted(() => document.removeEventListener("click", handleClickOutside))
</script>

<template>
    <div ref="containerRef" class="relative w-full">
        <button
            type="button"
            role="combobox"
            :aria-expanded="open"
            :disabled="disabled"
            class="border-input data-[placeholder]:text-muted-foreground focus-visible:border-ring focus-visible:ring-ring/50 dark:bg-input/30 dark:hover:bg-input/50 flex w-full items-center justify-between gap-2 rounded-md border bg-transparent px-3 py-2 text-sm whitespace-nowrap shadow-xs transition-[color,box-shadow] outline-none focus-visible:ring-[3px] disabled:cursor-not-allowed disabled:opacity-50 h-9"
            :class="[!modelValue && 'text-muted-foreground']"
            @click="toggleDropdown"
            @keydown="handleKeydown"
        >
            <span class="line-clamp-1 flex items-center gap-2">
                {{ selectedLabel || placeholder }}
            </span>
            <ChevronsUpDown class="size-4 shrink-0 opacity-50" />
        </button>

        <div
            v-if="open"
            class="bg-popover text-popover-foreground absolute z-50 mt-1 max-h-72 min-w-[8rem] w-full overflow-hidden rounded-md border shadow-md"
            @keydown="handleKeydown"
        >
            <div class="flex items-center border-b px-3">
                <Search class="mr-2 size-4 shrink-0 opacity-50" />
                <input
                    v-model="searchTerm"
                    :placeholder="searchPlaceholder"
                    class="flex h-9 w-full bg-transparent py-1 text-sm outline-hidden placeholder:text-muted-foreground"
                />
            </div>

            <div class="max-h-60 overflow-y-auto p-1">
                <div v-if="filteredItems.length === 0" class="py-6 text-center text-sm text-muted-foreground">
                    {{ emptyMessage }}
                </div>

                <button
                    v-for="(item, index) in filteredItems"
                    :key="item.value"
                    type="button"
                    :class="cn(
                        'relative flex w-full cursor-default items-center gap-2 rounded-sm py-1.5 pr-8 pl-2 text-sm outline-hidden select-none',
                        modelValue === item.value || highlightedIndex === index ? 'bg-accent text-accent-foreground' : '',
                    )"
                    @click="handleSelect(item.value)"
                    @mouseenter="highlightedIndex = index"
                >
                    <span class="flex-1 text-left">{{ item.label }}</span>
                    <span v-if="modelValue === item.value" class="absolute right-2 flex size-3.5 items-center justify-center">
                        <Check class="size-4" />
                    </span>
                </button>
            </div>
        </div>
    </div>
</template>
