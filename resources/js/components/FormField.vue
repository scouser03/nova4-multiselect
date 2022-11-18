<template>
    <DefaultField
        :field="field"
        :errors="errors"
        :show-help-text="showHelpText"
        :full-width-content="fullWidthContent"
    >
        <template #field>
            <multiselect
                :disabled="field.readonly"
                :options="options"
                :multiple="true"
                :label="label"
                :track-by="inputId"
                v-model="value"
            >
            </multiselect>
        </template>
    </DefaultField>
</template>

<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";
import Multiselect from "vue-multiselect";

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ["resourceName", "resourceId", "field"],

    components: { Multiselect },
    data() {
        return {
            selectedItems: [],
            value: [],
            options: this.field.data,
            label: this.field.label,
            inputId: this.field.inputId,
        };
    },
    mounted() {
        console.log(this.field);
    },

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || "";
        },
        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            const ids = this.value.map((obj) => obj[this.inputId]);
            formData.append(this.field.attribute, ids || "");
        },

        onSelect() {
            console.log(this.value[0]);
            this.selectedItems.push(this.value[0]);
            this.value = [];
        },
    },
};
</script>
