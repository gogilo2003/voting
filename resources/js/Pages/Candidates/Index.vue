<script setup lang="ts">
import AppLayout from '../../Layouts/AppLayout.vue';
import { cElection } from "../../types";

const props = defineProps({ elections: Array<cElection>, notification: Object })

</script>

<template>
    <AppLayout title="Candidates">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Candidates
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-for="election in elections" class="shadow border rounded-xl p-6 my-8">
                    <h1 class="text-2xl uppercase text-green-600 font-semibold mb-3 pb-3 border-b" v-text="election.title">
                    </h1>
                    <div v-for="position in election.positions" class="my-6">
                        <h2 class="text-xl font-semibold uppercase text-lime-700 border-b pb-3 mb-3" v-text="position.name">
                        </h2>
                        <div class="border rounded-xl p-8 grid grid-cols-1 md:grid-cols-4 gap-8">
                            <div v-for="candidate in position.candidates" class="relative">
                                <img :src="candidate.photo" class="rounded-3xl" />
                                <div
                                    class="rounded-full border absolute bottom-2 left-2 right-2 backdrop-blur-sm bg-gray-950/30">
                                    <h3 class="p-2 font-semibold uppercase text-center text-white" v-text="candidate.name">
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
