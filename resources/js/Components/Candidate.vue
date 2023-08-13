<script setup lang="ts">
defineProps({
    candidate: Object,
    total: Number
})
</script>
<template>
    <div
        class="relative pb-6 flex flex-col gap-3 items-center shadow border border-stone-300 bg-white rounded-xl min-h-[20rem] overflow-hidden">
        <div class="relative bg-gray-700 w-full md:w-full h-40 mb-24 rounded-b-xl flex-none ">
            <img :src="candidate.photo" alt=""
                class="h-40 w-40 rounded-full absolute md:relative bottom-0 translate-y-[50%] left-[50%] -translate-x-[50%]">
        </div>
        <div class="flex-1 px-3 flex flex-col gap-2 pr-8 w-full items-center">
            <h4 v-text="candidate.name"
                class="whitespace-nowrap absolute top-4 text-center left-[50%] -translate-x-[50%] py-2 text-xl text-gray-100 font-semibold uppercase white">
            </h4>
            <p class="text-sm flex gap-2 font-semibold uppercase">
                <span class="text-red-500" v-text="candidate.vote_count ?? 0"></span>
                <span>Votes</span>
            </p>
            <div class="relative border rounded-full w-full h-8 flex items-center p-1">
                <span v-text="`${parseFloat(candidate.vote_count / total * 100).toFixed(2)}%`"
                    class="absolute top-0 left-5 h-full flex items-center text-gray-200 font-semibold z-10 text-sm"></span>
                <div class="relative h-full rounded-full bg-lime-600 z-0"
                    :style="`width: ${parseFloat(candidate.vote_count / total * 100).toFixed(2)}%;`"></div>
            </div>
        </div>
    </div>
</template>
