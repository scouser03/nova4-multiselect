<template>
    <div class="flex flex-col md:flex-row -mx-6 px-6 py-2 md:py-0 space-y-2 md:space-y-0">
    <Modal
        :item="deleteItem"
        :label="label"
        v-if="deleteModal"
        @close="deleteModal = false"
        @destroy="remove"
    >
    </Modal>
    <div class="md:w-1/4 md:py-3">
      <slot>
        <h4 class="font-bold md:font-normal">{{ field.name }}</h4>
      </slot>
    </div>
    <div class="w-3/4 py-4 break-words">
      <slot name="value">
        <p class="text-90">
            <div v-if="data.length" class="flex items-center flex-wrap">
                <div v-for="(item, index) in data" :key="item.id" class="bg-primary-500 relative group overflow-hidden px-2 py-1 rounded-lg flex items-center mr-2 mb-2">
                    <span class="relative btn text-white dark:text-gray-800 font-bold">{{  item[label] }}</span>
                    <div v-if="field.readonly == false" class="flex items-center font-bold">
                        <svg @click="removeConfirm(item)" xmlns="http://www.w3.org/2000/svg" class="scouser__svg h-4 w-4 ml-2 font-bold cursor-pointer hover:text-white dark:text-gray-800" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <span v-else>-</span>
        </p>
      </slot>
    </div>
  </div>
</template>

<script>
import Modal from './Modal.vue'
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    components:{
        Modal
    },
    data(){
        return {
            data: this.field.value,
            label: this.field.label,
            deleteModal: false,
            deleteItem: null,
        }
    },
    methods:{
        removeConfirm(item) {
            this.deleteItem = item
            this.deleteModal = true
        },
        resetConfirm() {
            this.deleteItem = null
            this.deleteModal = false
        },
        remove(item){
            Nova.request().post('/nova-vendor/scouser03/nova4-multiselect/delete', {
                table: this.field.table,
                field: item.pivot,
                mainTable: this.field.mainTable,
            }).then(response => {
                this.data = this.data.filter((mapItem) => mapItem !== item);
                Nova.success(`${item[this.label]} has been detached`);
                this.resetConfirm()
            })
        }
    }
}
</script>

<style>
.scouser__svg:hover{
    color: #fff;
}
</style>