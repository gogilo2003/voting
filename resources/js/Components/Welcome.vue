<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from "vue";
import PrimaryButton from "./PrimaryButton.vue";
import Swal from 'sweetalert2';

const props = defineProps({
    election: Object
})

const page = usePage();

const form = useForm({
    voter: page.props.auth.user.id,
    election: props.election.id,
    votes: []
})

const votes = ref([])
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
            Swal.fire({
                icon: 'success',
                title: 'Voting Notification',
                text: page.props.notification.success
            })

        }
    })
}

onMounted(() => {
    // form.voter = props.auth.user.id
    positions.value = props.election.positions
})

</script>

<template>
    <div class="m-6">
        <h2 v-text="election.title"></h2>
        <div class="" v-for="(position, indexP) in positions">
            <h3 v-text="position.name"></h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 cursor-pointer">
                <div v-for="candidate in position.candidates" :class="{ 'border-blue-500': candidate.selected }"
                    class="flex gap-3 items-center my-3 border" @click="selectCandidate(indexP, candidate.id)">
                    <img :src="candidate.photo" alt="" class="h-28 w-28">
                    <div>
                        <h4 v-text="candidate.name"></h4>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <form @submit.prevent="submit">
                <PrimaryButton :disabled="form.processing" :class="{ 'opacity-30': form.processing }">VOTE</PrimaryButton>
            </form>
        </div>
    </div>
</template>
