<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Candidate from '../Components/Candidate.vue'
import { onMounted, ref } from 'vue';
import Icon from '../Components/Icons/Icon.vue';

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    election: Object
});

const positions = ref([]);

onMounted(() => {
    positions.value = props.election.positions

    positions.value.forEach((pos, index) => {

        try {
            positions.value[index].candidates.sort((a, b) => b.vote_count - a.vote_count)
        } catch (error) {
            console.log(error);

        }

    })

})

const electionVoterTurnout = (election) => {
    const totalVoters = election.total_voted;
    const eligibleVoters = election.total_voters;

    if (eligibleVoters > 0) {
        return ((totalVoters / eligibleVoters) * 100).toFixed(2);
    }

    return 0;
}

const stats = ref([
    {
        icon: 'pie-chart',
        value: `${electionVoterTurnout(props.election)}%`,
        label: '% Turnout',
        classes: ''
    },
    {
        icon: 'stats-chart',
        value: props.election?.total_voted,
        label: 'Votes Cast',
        classes: ''
    },
    {
        icon: 'people',
        value: props.election?.total_voters,
        label: 'Total Voters',
        classes: 'col-span-2 md:col-span-1'
    },
])

</script>

<template>
    <Head :title="`${election.title} Results`" />

    <div
        class="relative min-h-screen bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        <div v-if="canLogin" class="relative m-4 p-6 text-right z-10 shadow rounded-3xl bg-white">
            <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
            VOTE NOW</Link>

            <template v-else>
                <Link :href="route('login')"
                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                Log in</Link>

                <Link v-if="canRegister" :href="route('register')"
                    class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                Register</Link>
            </template>
        </div>

        <div class="relative m-4 p-6 shadow bg-white rounded-3xl">
            <h2 class="text-3xl text-center md:text-left md:text-5xl font-bold uppercase" v-text="election.title"></h2>
            <h4 v-text="election.description"
                class="text-md md:text-xl text-gray-600 mb-6 md:mb-12 md:text-left text-center"></h4>
            <div class="grid grid-cols-2 md:grid-cols-3 md:gap-6 gap-3 my-3 md:my-6">
                <div class="shadow py-3 px-5 flex gap-2 items-center rounded-xl border" :class="stat.classes"
                    v-for="stat in stats">
                    <Icon :type="stat.icon" class="h-10 w-10 md:h-16 md:w-16 text-lime-600" />
                    <div class="flex flex-col">
                        <span v-text="stat.value"
                            class="whitespace-nowrap text-xl md:text-2xl font-semibold text-red-600"></span>
                        <span v-text="stat.label" class="text-xs md:text-sm whitespace-nowrap"></span>
                    </div>
                </div>
            </div>
            <div v-for="position in positions" class=" py-8 last:rounded-b-xl first:rounded-t-xl overflow-hidden">
                <h3 class="text-2xl md:text-4xl font-semibold uppercase text-center md:text-left" v-text="position.name">
                </h3>
                <hr class="my-3">
                <div class="grid gap-3 grid-cols-1 md:grid-cols-3 lg:grid-cols-4">
                    <Candidate v-for="candidate in position.candidates" :candidate="candidate"
                        :total="position.total_votes" />
                </div>
            </div>
        </div>
    </div>
</template>

