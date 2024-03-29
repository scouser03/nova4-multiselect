<template>
    <div class="flex items-center">
        <Modal
            :item="deleteItem"
            :label="name"
            v-if="deleteModal"
            @close="deleteModal = false"
            @destroy="remove"
        >
        </Modal>
        <button
            v-if="
                select == false &&
                field.indexUpdateable &&
                field.readonly == false
            "
            @click="editHandler"
            class="inline-flex cursor-pointer text-70 hover:text-primary mr-2"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 fill-current"
                viewBox="0 0 20 20"
                aria-labelledby="edit"
                role="presentation"
            >
                <path
                    d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"
                ></path>
            </svg>
        </button>
        <button
            v-if="select == true"
            @click="closeHandler"
            class="inline-flex cursor-pointer text-70 hover:text-primary mr-2"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 fill-current"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6 18L18 6M6 6l12 12"
                />
            </svg>
        </button>

        <div
            v-show="!select"
            v-if="data.length"
            :style="`max-height: ${maxHeight}px; width: ${width}px;`"
            class="flex items-center flex-wrap overflow-y-auto h-56 max-w-56"
        >
            <div
                v-for="item in data"
                :key="item.id"
                class="bg-primary-500 multi-select-field-container relative w-fit px-1 py-1 rounded-lg flex items-center mr-1 mb-1"
            >
                <span
                    class="relative btn text-white dark:text-gray-800 font-xs"
                    >{{ item[name] }}</span
                >
                <div
                    v-if="field.readonly == false"
                    class="flex items-center dark:text-gray-800 font-xs font-bold"
                >
                    <svg
                        @click="removeConfirm(item)"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 ml-1 font-bold cursor-pointer hover:text-white"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
            </div>
        </div>
        <span v-else>-</span>
        <div v-if="select">
            <multiselect
                v-model="value"
                :options="options"
                :multiple="true"
                :label="name"
                :track-by="inputId"
            ></multiselect>
        </div>
        <div v-if="select" @click="updated" class="cursor-pointer">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>
        </div>
    </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import Modal from "./Modal.vue";
export default {
    props: ["resourceName", "field", "resource"],
    components: { Multiselect, Modal },
    data() {
        return {
            maxHeight: this.field.maxHeight,
            width: this.field.width,
            select: false,
            data: this.field.value,
            name: this.field.label,
            inputId: this.field.inputId,
            value: this.field.value,
            options: this.field.data,
            deleteModal: false,
            deleteItem: null,
        };
    },
    methods: {
        removeConfirm(item) {
            this.deleteItem = item;
            this.deleteModal = true;
        },
        resetConfirm() {
            this.deleteItem = null;
            this.deleteModal = false;
        },
        remove(item) {
            Nova.request()
                .post("/nova-vendor/scouser03/nova4-multiselect/delete", {
                    table: this.field.table,
                    field: item.pivot,
                    mainTable: this.field.mainTable,
                })
                .then((response) => {
                    this.data = this.data.filter((mapItem) => mapItem !== item);
                    Nova.success(`${item[this.name]} has been detached`);
                    this.resetConfirm();
                });
        },
        updated() {
            if (!this.field.indexUpdateable) {
                return;
            }
            const ids = this.value.map((obj) => obj[this.inputId]);
            Nova.request()
                .post("/nova-vendor/scouser03/nova4-multiselect/update", {
                    attribute: this.field.attribute,
                    id: this.resource.id.value,
                    ids: ids,
                    resourceName: this.resourceName,
                })
                .then((response) => {
                    this.data = this.value;
                    this.closeHandler();
                    Nova.success(`updated!`);
                });
        },
        editHandler() {
            this.select = true;
            this.removeSomeClass();
        },
        closeHandler() {
            this.select = false;
            this.addSomeClass();
        },
        removeSomeClass() {
            const tbl = document.querySelector(["table"]).parentElement;
            tbl.classList.remove("overflow-hidden");
            tbl.classList.remove("overflow-x-auto");
        },
        addSomeClass() {
            const tbl = document.querySelector(["table"]).parentElement;
            tbl.classList.add("overflow-hidden");
            tbl.classList.add("overflow-x-auto");
        },
    },
};
</script>

<style>
.multi-select-field-container {
    white-space: nowrap;
}
.multiselect__tag,
.multiselect__option--highlight {
    background: #4099de;
}
.multiselect__tag-icon:hover {
    background: #0281e2;
}
.multiselect__option--highlight:after {
    background: #4099de;
}
.multiselect__content-wrapper {
    z-index: 999999;
}
</style>
