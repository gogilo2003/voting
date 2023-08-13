<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import { onMounted, ref } from "vue";
import PrimaryButton from "../Components/PrimaryButton.vue";
import Swal from 'sweetalert2';
import AppLayout from '../Layouts/AppLayout.vue';

const props = defineProps({ election: Object, notification: Object, auth: Object, elections: Array })

const form = useForm({
    voter: props.auth.user.id,
    election: props.election?.id,
    votes: []
})

const positions = ref([])

const selectCandidate = (indexP, id) => {

    positions.value[indexP].candidates.forEach((candidate, c) => {
        let selected = id === candidate.id
        if (selected) {
            form.votes[indexP] = {
                position: positions.value[indexP].id,
                candidate: candidate.id
            }
        }

        positions.value[indexP].candidates[c].selected = selected

    });

}

const submit = () => {
    form.post(route('dashboard-vote'), {
        onSuccess: () => {
            if (props.notification.danger) {
                Swal.fire({
                    icon: 'error',
                    title: 'Voting Notification',
                    html: props.notification.danger
                })
            }
            if (props.notification.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Voting Notification',
                    html: props.notification.success
                })
            }
            if (props.notification.warning) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Voting Notification',
                    html: props.notification.warning
                })
            }

        }
    })
}

onMounted(() => {
    // form.voter = props.auth.user.id
    positions.value = props.election?.positions
})
</script>

<template>
    <AppLayout title="Voting">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Voting
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="election" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h2 v-text="election?.title" class="text-5xl uppercase font-semibold"></h2>
                    <hr class="my-3">
                    <div class="group odd:bg-gray-600/40 even:bg-gray-600/5 my-6 shadow-lg rounded-lg p-6 border"
                        v-for="(position, indexP) in positions">
                        <h3 v-text="position.name" class="text-2xl font-semibold uppercase text-red-600"></h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 cursor-pointer">
                            <div v-for="candidate in position.candidates" :class="{ 'border-lime-500': candidate.selected }"
                                class="flex gap-3 items-center my-3 border-4 rounded-xl overflow-hidden bg-white"
                                @click="selectCandidate(indexP, candidate.id)">
                                <img :src="candidate.photo" alt="" class="h-28 w-28">
                                <div>
                                    <h4 v-text="candidate.name" class="uppercase text-lg font-semibold"></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <form @submit.prevent="submit">
                            <PrimaryButton :disabled="form.processing" :class="{ 'opacity-30': form.processing }">VOTE
                            </PrimaryButton>
                        </form>
                    </div>
                </div>
                <div v-else class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <p>While there are no ongoing elections, you can explore past election results and stay tuned for
                        upcoming opportunities to cast your vote.</p>
                    <div v-if="elections.length" class="mt-6">
                        <Link :href="route('results', item.id)" v-for="item in elections">
                        <h4 v-text="item.title"></h4>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
