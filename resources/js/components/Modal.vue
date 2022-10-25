<template>
    <div
        class="modal fixed inset-0 z-[60] px-3 md:px-0 py-3 md:py-6 overflow-x-hidden overflow-y-auto"
    >
        <div class="relative mx-auto z-20 max-w-sm">
            <div>
                <form
                    class="mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden"
                    style="width: 460px"
                >
                    <div class="p-8">
                        <h3
                            class="text-90 uppercase tracking-wide font-bold md:text-sm border-b border-gray-100 dark:border-gray-700 py-4 px-8"
                        >
                            Detach {{ item[label] }}
                        </h3>

                        <div class="py-3 px-8">
                            <p class="leading-normal">
                                Are you sure you want to detach {{ item.id }} ?
                            </p>
                        </div>
                    </div>
                    <div class="bg-gray-100 dark:bg-gray-700 px-6 py-3 flex">
                        <div class="ml-auto">
                            <button
                                type="button"
                                @click="close"
                                class="mr-3 appearance-none bg-transparent font-bold text-gray-400 hover:text-gray-300 active:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 dark:active:text-gray-600 dark:hover:bg-gray-800 mr-3 cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center h-9 px-3 mr-3 appearance-none bg-transparent font-bold text-gray-400 hover:text-gray-300 active:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 dark:active:text-gray-600 dark:hover:bg-gray-800 mr-3"
                            >
                                Cancel
                            </button>
                            <button
                                @click="destroy"
                                type="button"
                                class="shadow relative bg-red-500 hover:bg-red-400 text-white cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center h-9 px-3 shadow relative bg-red-500 hover:bg-red-400 text-white"
                            >
                                <span class=""> Delete </span>
                                <!---->
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "modal",
    props: {
        item: {
            type: Object,
            required: true,
        },
        label: {
            type: String,
            required: true,
        },
    },
    methods: {
        close() {
            this.$emit("close");
        },
        destroy() {
            this.$emit("destroy", this.item);
        },
    },
};
</script>
