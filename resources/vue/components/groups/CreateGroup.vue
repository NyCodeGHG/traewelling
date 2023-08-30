<script setup>
// @ts-check
import { useForm, useIsFormValid, useIsSubmitting } from 'vee-validate';
import { toTypedSchema } from '@vee-validate/valibot';
import { object, string, number, minLength, minValue, maxValue, optional } from 'valibot';
import { computed } from 'vue';
import { createGroup } from './group_api';
import { notyf } from '../../../js/app';

const schema = toTypedSchema(
    object({
        name: string([minLength(1, 'The group name can\'t be empty.')]),
        description: optional(string()),
        duration: number([
            minValue(1, 'The duration must be at least one hour.'),
            maxValue(48, 'The duration must be at most 48 hours.'),
        ]),
    })
);

const { values, errors, defineInputBinds, handleSubmit } = useForm({
    validationSchema: schema,
    initialValues: {
        duration: 1,
        name: '',
    },
});

const name = defineInputBinds('name');
const description = defineInputBinds('description');
const duration = defineInputBinds('duration');

const formattedDuration = computed(() => {
    const dur = duration.value.value;
    if (dur === undefined) {
        return null;
    }
    if (dur % 24 == 0) {
        return `${dur / 24} days`;
    } else {
        return `${dur} hours`;
    }
});

const isValid = useIsFormValid();
const isSubmitting = useIsSubmitting();

const onSubmit = handleSubmit(async values => {
    try {
        const group = await createGroup(values.name, values.description ?? null, values.duration)
        window.location.pathname = `/groups/${group.id}`;
    } catch (error) {
        notyf.error(`Failed to create group: ${error}`);
    }
});

</script>

<template>
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card">
                <div class="card-header">Create new Group</div>
                <div class="card-body">
                    <form class="row g-4" novalidate @submit="onSubmit">
                        <div class="col-md-12 col-lg-10">
                            <div class="form-outline">
                                <input type="text" class="form-control"
                                    :class="{ 'is-invalid': !!errors.name }" v-bind="name" id="create-group-name" required>
                                <label class="form-label" for="create-group-name">Group name*</label>
                                <div v-if="errors.name" class="invalid-feedback">{{ errors.name }}</div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-outline">
                                <input type="text" class="form-control"
                                    :class="{ 'is-invalid': !!errors.description }" v-bind="description" id="create-group-description" required>
                                <label class="form-label" for="create-group-description">Group description</label>
                                <div v-if="errors.description" class="invalid-feedback">{{ errors.description }}</div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 row">
                            <p>
                                A group automatically gets closed after a certain amount of inactivity to prevent unwanted checkins.
                                You can customize that time in hours.
                            </p>
                            <label for="create-group-duration" class="form-label">Group Duration: {{ formattedDuration }}</label>
                            <div class="col-md-6 col-lg-4">
                                <input type="number" class="form-control"
                                    :class="{ 'is-invalid': !!errors.duration }" v-bind="duration"
                                    id="create-group-duration" required min="1" max="48">
                                <div v-if="errors.duration" class="invalid-feedback">{{ errors.duration }}</div>
                            </div>
                        </div>
                        <div class="col-md-12 d-flex justify-content-center">
                            <button class="btn btn-outline-primary" :disabled="!isValid || isSubmitting">
                                Create Group
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
